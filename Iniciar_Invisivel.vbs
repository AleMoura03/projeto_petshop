Set WshShell = CreateObject("WScript.Shell")

' Iniciar o php artisan serve 100% invisivel (o 0 oculta a janela)
WshShell.Run "cmd /c php artisan serve --port=8000", 0, False

' Aguardar 2 segundos para o Laravel subir
WScript.Sleep 2000

' Tentar abrir diretamente no Google Chrome
On Error Resume Next
WshShell.Run "chrome.exe http://localhost:8000"

' Se falhar (por nao ter Chrome), abre no navegador padrao do Windows
If Err.Number <> 0 Then
    WshShell.Run "cmd /c start http://localhost:8000", 0
End If
On Error GoTo 0
