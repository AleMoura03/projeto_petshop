<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Pet;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendamentoController extends Controller
{
    public function create()
    {
        $pets = Auth::user()->pets;
        $servicos = Servico::all();

        return view('agendamentos.create', compact('pets', 'servicos'));
    }

    public function index(Request $request)
    {
        $query = Agendamento::where('user_id', Auth::id())
            ->with('pet', 'servico');

        if ($request->filled('pet_id')) {
            $query->where('pet_id', $request->pet_id);
        }

        if ($request->filled('servico_id')) {
            $query->where('servico_id', $request->servico_id);
        }

        $agendamentos = $query->orderBy('data', 'desc')->get();
        
        $pets = Pet::where('user_id', Auth::id())->get();
        
        $servicos = Servico::orderBy('nome')->get()->map(function($s) {
            $s->nome_limpo = trim(preg_replace('/ \(.*\)/', '', $s->nome));
            return $s;
        })->unique(function ($item) {
            return $item->nome_limpo . '-' . $item->especie;
        });

        return view('agendamentos.index', compact('agendamentos', 'pets', 'servicos'));
    }

    public function relatorioGastos(Request $request)
    {
        $query = Agendamento::where('user_id', Auth::id())
            ->with('pet', 'servico');

        // Se o usuário submeteu filtros de pesquisa, aplicamos com base na validação obrigatória
        if ($request->anyFilled(['data_inicio', 'data_fim', 'especie', 'servico_nome'])) {
            $request->validate([
                'data_inicio'  => 'required|date',
                'data_fim'     => 'required|date|after_or_equal:data_inicio',
                'especie'      => 'required|string|in:cachorro,gato,todos',
                'servico_nome' => 'required|string',
            ]);

            $query->whereDate('data', '>=', $request->data_inicio)
                  ->whereDate('data', '<=', $request->data_fim);

            // Filtragem pelo nome base/limpo do serviço (agrupamento da vacina etc) se não for 'todos'
            if ($request->servico_nome !== 'todos') {
                $nomeLimpo = $request->servico_nome;
                $servicoIds = Servico::all()->filter(function($s) use ($nomeLimpo) {
                    return trim(preg_replace('/ \(.*\)/', '', $s->nome)) === $nomeLimpo;
                })->pluck('id');
                $query->whereIn('servico_id', $servicoIds);
            }

            // Filtragem pela espécie do animal se não for 'todos'
            if ($request->especie !== 'todos') {
                $query->whereHas('pet', function($q) use ($request) {
                    $q->where('species', $request->especie);
                });
            }
        }

        $agendamentos = $query->orderBy('data', 'desc')->get();
        
        // Faturamento total gasto do cliente (apenas aprovados e efetuados!)
        $totalGasto = $agendamentos->whereIn('status', ['aprovado', 'efetuado'])->sum('preco');

        $pets = Pet::where('user_id', Auth::id())->get();

        // Mapeia todos os serviços limpos sem as marcas dos fabricantes para desduplicação no select
        // Agrupamos também por espécie para que banhos de cães e gatos apareçam individualmente!
        $servicos = Servico::orderBy('nome')->get()->map(function($s) {
            $s->nome_limpo = trim(preg_replace('/ \(.*\)/', '', $s->nome));
            return $s;
        })->unique(function ($item) {
            return $item->nome_limpo . '-' . $item->especie;
        });

        return view('agendamentos.relatorios', compact('agendamentos', 'totalGasto', 'pets', 'servicos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'servico_ids' => 'required|array|min:1',
            'servico_ids.*' => 'exists:servicos,id',
            'data' => 'required|date|after_or_equal:today',
            'hora' => 'required'
        ]);

        // Validação de Horário Comercial
        $dayOfWeek = date('N', strtotime($request->data)); // 1 (Segunda) a 7 (Domingo)
        $horaFormatted = date('H:i', strtotime($request->hora));

        if ($dayOfWeek == 7) {
            return back()->withInput()->with('error', 'O petshop está fechado aos domingos! Por favor, selecione outro dia.');
        }

        if ($dayOfWeek == 6) { // Sábado
            if ($horaFormatted < '08:00' || $horaFormatted > '12:00') {
                return back()->withInput()->with('error', 'Aos sábados, o petshop funciona das 08:00 às 12:00. Escolha outro horário.');
            }
        } else { // Segunda a Sexta
            if ($horaFormatted < '08:00' || $horaFormatted > '18:00') {
                return back()->withInput()->with('error', 'De segunda a sexta, o petshop funciona das 08:00 às 18:00. Escolha outro horário.');
            }
        }

        $pet = Pet::findOrFail($request->pet_id);
        $porte = strtolower(trim($pet->porte));
        $createdCount = 0;

        foreach ($request->servico_ids as $servico_id) {
            $servico = Servico::findOrFail($servico_id);

            $preco = match ($porte) {
                'mini' => $servico->preco_mini,
                'pequeno' => $servico->preco_pequeno,
                'medio' => $servico->preco_medio,
                'grande' => $servico->preco_grande,
                'gigante' => $servico->preco_gigante,
                default => 0
            };

            Agendamento::create([
                'user_id' => Auth::id(),
                'pet_id' => $request->pet_id,
                'servico_id' => $servico_id,
                'data' => $request->data,
                'hora' => $request->hora,
                'preco' => $preco,
                'status' => 'pendente'
            ]);
            $createdCount++;
        }

        return redirect()->route('agendamentos.index')->with('success', "{$createdCount} agendamento(s) realizado(s) com sucesso!");
    }

    public function edit($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        if ($agendamento->user_id != Auth::id()) {
            abort(403);
        }

        if ($agendamento->status !== 'pendente') {
            return redirect()->route('agendamentos.index')->with('error', 'Não é possível editar um agendamento que já foi processado.');
        }

        $pets = Pet::where('user_id', Auth::id())->get();
        $servicos = Servico::all();

        return view('agendamentos.edit', compact('agendamento', 'pets', 'servicos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'servico_id' => 'required|exists:servicos,id',
            'data' => 'required|date|after_or_equal:today',
            'hora' => 'required'
        ]);

        // Validação de Horário Comercial
        $dayOfWeek = date('N', strtotime($request->data)); // 1 (Segunda) a 7 (Domingo)
        $horaFormatted = date('H:i', strtotime($request->hora));

        if ($dayOfWeek == 7) {
            return back()->withInput()->with('error', 'O petshop está fechado aos domingos! Por favor, selecione outro dia.');
        }

        if ($dayOfWeek == 6) { // Sábado
            if ($horaFormatted < '08:00' || $horaFormatted > '12:00') {
                return back()->withInput()->with('error', 'Aos sábados, o petshop funciona das 08:00 às 12:00. Escolha outro horário.');
            }
        } else { // Segunda a Sexta
            if ($horaFormatted < '08:00' || $horaFormatted > '18:00') {
                return back()->withInput()->with('error', 'De segunda a sexta, o petshop funciona das 08:00 às 18:00. Escolha outro horário.');
            }
        }

        $agendamento = Agendamento::findOrFail($id);

        if ($agendamento->user_id != Auth::id()) {
            abort(403);
        }

        if ($agendamento->status !== 'pendente') {
            return redirect()->route('agendamentos.index')->with('error', 'Não é possível editar um agendamento que já foi processado.');
        }

        $pet = Pet::findOrFail($request->pet_id);

        if ($pet->user_id != Auth::id()) {
            abort(403);
        }

        $servico = Servico::findOrFail($request->servico_id);

        $porte = strtolower(trim($pet->porte));

        $preco = match ($porte) {
            'mini' => $servico->preco_mini,
            'pequeno' => $servico->preco_pequeno,
            'medio' => $servico->preco_medio,
            'grande' => $servico->preco_grande,
            'gigante' => $servico->preco_gigante,
            default => 0
        };


        $agendamento->pet_id = $request->pet_id;
        $agendamento->servico_id = $request->servico_id;
        $agendamento->data = $request->data;
        $agendamento->hora = $request->hora;
        $agendamento->preco = $preco;

        $agendamento->save();

        return redirect()->route('agendamentos.index');
    }

    public function cancelar($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        if ($agendamento->user_id != Auth::id()) {
            abort(403);
        }

        $agendamento->status = 'cancelado_cliente';
        $agendamento->save();

        return redirect()->route('agendamentos.index')
            ->with('success', 'Serviço cancelado com sucesso.');
    }

    public function adminIndex()
    {
        $agendamentos = Agendamento::with('pet', 'servico', 'user')
            ->get();

        return view('admin.agendamentos.index', compact('agendamentos'));
    }

    public function aprovar($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        $agendamento->status = 'aprovado';
        $agendamento->save();

        $whatsappUrl = $this->getWhatsappUrl($agendamento, 'aprovado');

        if ($whatsappUrl) {
            return back()->with('success', 'Agendamento aprovado!')->with('whatsapp_url', $whatsappUrl);
        }

        return back()->with('success', 'Agendamento aprovado!');
    }

    public function recusar($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        $agendamento->status = 'recusado';
        $agendamento->save();

        $whatsappUrl = $this->getWhatsappUrl($agendamento, 'recusado');

        if ($whatsappUrl) {
            return back()->with('success', 'Agendamento recusado!')->with('whatsapp_url', $whatsappUrl);
        }

        return back()->with('success', 'Agendamento recusado!');
    }

    public function efetuar($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        $agendamento->status = 'efetuado';
        $agendamento->save();

        return back()->with('success', 'Serviço marcado como concluído (efetuado)!');
    }

    public function enviarLembrete($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        $whatsappUrl = $this->getWhatsappUrl($agendamento, 'lembrete');

        if ($whatsappUrl) {
            return back()->with('success', 'Lembrete preparado!')->with('whatsapp_url', $whatsappUrl);
        }

        return back()->with('error', 'O cliente não possui WhatsApp cadastrado.');
    }

    private function getWhatsappUrl($agendamento, $tipo)
    {
        $user = $agendamento->user;
        if (!$user || !$user->whatsapp) {
            return null;
        }

        // Sanitizar número do telefone
        $phone = preg_replace('/[^0-9]/', '', $user->whatsapp);
        if (strpos($phone, '0') === 0) {
            $phone = substr($phone, 1);
        }
        if (strlen($phone) <= 11) {
            $phone = '55' . $phone;
        }

        $petName = $agendamento->pet->name ?? 'seu pet';
        $servicoNome = $agendamento->servico->nome ?? 'serviço';
        $dataFormatted = \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y');
        $hora = $agendamento->hora;

        if ($tipo === 'aprovado') {
            $text = "Olá, {$user->name}! Tudo bem? Passando para informar que o seu agendamento para o serviço de *{$servicoNome}* para o seu pet *{$petName}* no dia *{$dataFormatted}* às *{$hora}* foi *APROVADO* com sucesso! estamos preparando tudo para receber seu amiguinho com muito carinho. Caso precise alterar algo, por favor nos avise. Obrigado! 🐾";
        } elseif ($tipo === 'recusado') {
            $text = "Olá, {$user->name}! Esperamos que esteja bem. Gostaríamos de avisar que, devido a conflitos de horários em nossa agenda, o agendamento de *{$servicoNome}* para o seu pet *{$petName}* no dia *{$dataFormatted}* às *{$hora}* precisou ser *RECUSADO*. Poderia, por gentileza, entrar em contato conosco para escolhermos outro horário disponível? Agradecemos a compreensão! 🐾";
        } elseif ($tipo === 'lembrete') {
            $text = "Olá, {$user->name}! Tudo bem? Este é um lembrete amigável do agendamento de *{$servicoNome}* para o seu pet *{$petName}* que está confirmado para o dia *{$dataFormatted}* às *{$hora}*. Estamos ansiosos para recebê-los! Caso tenha algum imprevisto, nos avise com antecedência. Até breve! 🧼🐶🐱";
        } else {
            return null;
        }

        return "https://api.whatsapp.com/send?phone={$phone}&text=" . rawurlencode($text);
    }
}
