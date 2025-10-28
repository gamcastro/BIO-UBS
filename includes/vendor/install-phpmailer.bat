@echo off
REM Instala PHPMailer via Composer e move para includes/vendor
echo Instalando PHPMailer...
composer install
if %errorlevel% neq 0 (
  echo ERRO: Composer nao encontrado ou falha na instalacao.
  pause
  exit /b 1
)
echo Copiando vendor para ..\bio-ubs-login-seguro\includes\vendor...
rmdir /S /Q ..\bio-ubs-login-seguro\includes\vendor 2>nul
xcopy /E /I /Y vendor ..\bio-ubs-login-seguro\includes\vendor
echo Concluido!
pause
