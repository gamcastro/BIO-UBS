#!/usr/bin/env bash
# Instala PHPMailer via Composer e move para includes/vendor
set -e
echo "Instalando PHPMailer..."
composer install
echo "Copiando vendor para ../bio-ubs-login-seguro/includes/vendor..."
rm -rf ../bio-ubs-login-seguro/includes/vendor || true
cp -R vendor ../bio-ubs-login-seguro/includes/vendor
echo "Concluído!"
