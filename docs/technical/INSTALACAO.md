# 🔧 Instalação e Configuração - BioUBS

Guia completo de instalação do sistema BioUBS em ambiente de desenvolvimento e produção.

---

## 📋 Requisitos do Sistema

### Software Necessário

#### Obrigatórios
- **PHP**: 8.2 ou superior
- **MySQL/MariaDB**: MySQL 5.7+ ou MariaDB 10.4+
- **Apache**: 2.4 ou superior
- **Composer**: 2.0 ou superior

#### Extensões PHP Necessárias
```ini
extension=mysqli
extension=pdo_mysql
extension=mbstring
extension=openssl
extension=json
extension=session
```

### Hardware Recomendado

#### Desenvolvimento
- **CPU**: 2 cores
- **RAM**: 4 GB
- **Disco**: 10 GB livres

#### Produção
- **CPU**: 4 cores
- **RAM**: 8 GB
- **Disco**: 50 GB livres (SSD recomendado)

---

## 🚀 Instalação em Desenvolvimento (Windows + XAMPP)

### 1. Instalar XAMPP

1. Baixe o XAMPP do [site oficial](https://www.apachefriends.org/)
2. Execute o instalador
3. Instale em `C:\xampp`
4. Inicie o **Apache** e **MySQL** no painel de controle

### 2. Clonar o Projeto

```powershell
# Navegue até a pasta htdocs
cd C:\xampp\htdocs

# Clone o repositório
git clone https://github.com/gamcastro/BIO-UBS.git

# Entre na pasta
cd BIO-UBS
```

### 3. Instalar Dependências

```powershell
# Instale o Composer se ainda não tiver
# Baixe em: https://getcomposer.org/download/

# Instale as dependências
composer install
```

### 4. Configurar o Banco de Dados

```powershell
# Acesse o phpMyAdmin
# URL: http://localhost/phpmyadmin

# Ou via linha de comando:
mysql -u root -p

# No MySQL, execute:
CREATE DATABASE bio_ubs CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE bio_ubs;
SOURCE C:/xampp/htdocs/BIO-UBS/migrations/bio_ubs v_5.sql;
```

### 5. Configurar Conexão

Edite o arquivo `class/Conexao.php`:

```php
<?php
namespace BioUBS;

class Conexao {
    private static $host = 'localhost';
    private static $db   = 'bio_ubs';
    private static $user = 'root';
    private static $pass = '';  // Senha do MySQL (vazia no XAMPP por padrão)
    // ... resto do código
}
```

### 6. Configurar o Virtual Host (Opcional)

Edite `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    ServerName bioubs.local
    DocumentRoot "C:/xampp/htdocs/BIO-UBS"
    <Directory "C:/xampp/htdocs/BIO-UBS">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Edite `C:\Windows\System32\drivers\etc\hosts` (como Administrador):

```
127.0.0.1 bioubs.local
```

Reinicie o Apache e acesse: `http://bioubs.local`

### 7. Acessar o Sistema

```
URL: http://localhost/BIO-UBS/login.php

Credenciais padrão (após importar SQL):
Usuário: admin@bioubs.com.br
Senha: admin123
```

---

## 🐧 Instalação em Produção (Linux + Ubuntu)

### 1. Atualizar Sistema

```bash
sudo apt update
sudo apt upgrade -y
```

### 2. Instalar LAMP Stack

```bash
# Apache
sudo apt install apache2 -y

# MySQL
sudo apt install mysql-server -y

# PHP e extensões
sudo apt install php php-mysql php-mbstring php-json php-cli php-curl -y

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 3. Configurar MySQL

```bash
# Executar script de segurança
sudo mysql_secure_installation

# Criar banco de dados
sudo mysql -u root -p
```

```sql
CREATE DATABASE bio_ubs CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
CREATE USER 'bioubs_user'@'localhost' IDENTIFIED BY 'senha_forte_aqui';
GRANT ALL PRIVILEGES ON bio_ubs.* TO 'bioubs_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. Clonar e Configurar Projeto

```bash
# Clonar para /var/www/html
cd /var/www/html
sudo git clone https://github.com/gamcastro/BIO-UBS.git
cd BIO-UBS

# Instalar dependências
sudo composer install

# Ajustar permissões
sudo chown -R www-data:www-data /var/www/html/BIO-UBS
sudo chmod -R 755 /var/www/html/BIO-UBS
```

### 5. Importar Banco de Dados

```bash
mysql -u bioubs_user -p bio_ubs < migrations/bio_ubs_v5.sql
```

### 6. Configurar Apache

```bash
sudo nano /etc/apache2/sites-available/bioubs.conf
```

```apache
<VirtualHost *:80>
    ServerName bioubs.exemplo.com.br
    ServerAdmin admin@exemplo.com.br
    DocumentRoot /var/www/html/BIO-UBS

    <Directory /var/www/html/BIO-UBS>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/bioubs_error.log
    CustomLog ${APACHE_LOG_DIR}/bioubs_access.log combined
</VirtualHost>
```

```bash
# Ativar site e módulos
sudo a2ensite bioubs.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 7. Configurar SSL (Certbot - Let's Encrypt)

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-apache -y

# Gerar certificado
sudo certbot --apache -d bioubs.exemplo.com.br
```

---

## 🔒 Configurações de Segurança

### 1. Configurar PHP

Edite `/etc/php/8.2/apache2/php.ini`:

```ini
; Desabilitar exposição de versão
expose_php = Off

; Limites de upload
upload_max_filesize = 10M
post_max_size = 10M

; Sessões
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1

; Desabilitar funções perigosas
disable_functions = exec,passthru,shell_exec,system,proc_open,popen
```

### 2. Configurar MySQL

```sql
-- Criar usuário com privilégios mínimos
CREATE USER 'bioubs_app'@'localhost' IDENTIFIED BY 'senha_forte';
GRANT SELECT, INSERT, UPDATE, DELETE ON bio_ubs.* TO 'bioubs_app'@'localhost';
FLUSH PRIVILEGES;
```

### 3. Firewall

```bash
# UFW (Ubuntu)
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

---

## 🔍 Verificação da Instalação

### Checklist

- [ ] Apache rodando: `sudo systemctl status apache2`
- [ ] MySQL rodando: `sudo systemctl status mysql`
- [ ] PHP funcionando: `php -v`
- [ ] Banco criado: `mysql -u root -p -e "SHOW DATABASES;"`
- [ ] Tabelas criadas: `mysql -u root -p bio_ubs -e "SHOW TABLES;"`
- [ ] Site acessível: Abra no navegador
- [ ] Login funcionando: Teste credenciais
- [ ] Módulos carregando: Acesse Recepção, Pacientes

### Teste de Funcionalidades

```bash
# 1. Teste de conexão ao banco
php -r "new PDO('mysql:host=localhost;dbname=bio_ubs', 'root', ''); echo 'OK';"

# 2. Teste de escrita de arquivos
touch /var/www/html/BIO-UBS/test.txt && rm /var/www/html/BIO-UBS/test.txt

# 3. Teste de sessões PHP
php -r "session_start(); echo 'Sessão: ' . session_id();"
```

---

## 🐛 Troubleshooting

### Erro: "Access denied for user"
```bash
# Verifique credenciais em class/Conexao.php
# Recrie o usuário no MySQL
```

### Erro: "404 Not Found"
```bash
# Verifique se o Apache está rodando
sudo systemctl status apache2

# Verifique permissões
ls -la /var/www/html/BIO-UBS
```

### Erro: "Call to undefined function mb_strlen()"
```bash
# Instale a extensão mbstring
sudo apt install php-mbstring
sudo systemctl restart apache2
```

### Erro: "Session failed to start"
```bash
# Verifique permissões da pasta de sessões
sudo chmod 1733 /var/lib/php/sessions
```

---

## 📊 Monitoramento

### Logs do Apache
```bash
# Erros
tail -f /var/log/apache2/bioubs_error.log

# Acessos
tail -f /var/log/apache2/bioubs_access.log
```

### Logs do MySQL
```bash
tail -f /var/log/mysql/error.log
```

### Logs do PHP
```bash
tail -f /var/log/php8.2-fpm.log
```

---

## 🔄 Atualização do Sistema

```bash
# 1. Backup do banco
mysqldump -u root -p bio_ubs > backup_$(date +%Y%m%d).sql

# 2. Pull das atualizações
cd /var/www/html/BIO-UBS
git pull origin main

# 3. Atualizar dependências
composer update

# 4. Executar migrações (se houver)
mysql -u root -p bio_ubs < migrations/nova_migracao.sql

# 5. Limpar cache do navegador
```

---

## 📞 Suporte

**Problemas na instalação?**
- Abra uma issue: [GitHub Issues](https://github.com/gamcastro/BIO-UBS/issues)
- Verifique a documentação: `docs/README.md`

---

[← Voltar para documentação técnica](README.md)
