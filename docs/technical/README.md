# 💻 Documentação Técnica - BioUBS

Documentação para desenvolvedores e equipe técnica.

---

## 🏗️ Arquitetura do Sistema

### Módulos Implementados
- [📋 Módulo de Recepção](MODULO_RECEPCAO.md) - Completo ✅
- 🏥 Módulo de Triagem - Em desenvolvimento
- 👨‍⚕️ Módulo de Atendimento - Em desenvolvimento

---

## 📊 Banco de Dados

### Estrutura
- **Schema**: `bio_ubs`
- **Engine**: MySQL/MariaDB
- **Charset**: utf8mb4

### Principais Tabelas
- `cadastro_paciente` - Cadastro de pacientes
- `fila_atendimento` - Gestão de filas
- `cadastro_profissional` - Usuários do sistema
- `cadastro_unidade` - Unidades de saúde
- `triagens` - Registro de triagens

**Arquivo de migração**: `migrations/bio_ubs v_5.sql`

---

## 🔧 Tecnologias Utilizadas

- **Backend**: PHP 8.2+
- **Frontend**: Bootstrap 5.3, jQuery 3.3.1
- **Banco**: MySQL/MariaDB
- **Servidor**: Apache (XAMPP)
- **Autoloader**: Composer

---

## 🚀 Instalação

### Requisitos
- PHP 8.2 ou superior
- MySQL 5.7 ou superior (ou MariaDB 10.4+)
- Apache 2.4
- Extensões PHP: mysqli, pdo, mbstring, openssl

### Passos
```bash
# 1. Clone o repositório
git clone https://github.com/gamcastro/BIO-UBS.git

# 2. Instale dependências
cd BIO-UBS
composer install

# 3. Configure o banco de dados
mysql -u root -p < migrations/bio_ubs_v5.sql

# 4. Configure conexão
# Edite class/Conexao.php com suas credenciais

# 5. Inicie o servidor
# XAMPP: Inicie Apache e MySQL
```

---

## 📁 Estrutura de Diretórios

```
BIO-UBS/
├── actions/          # Processamento de ações (check-in, cadastros)
├── ajax/            # Requisições assíncronas
├── class/           # Classes PHP (Conexao, UbsCrudAll, etc)
├── css/             # Estilos customizados
├── docs/            # Documentação
│   ├── user/        # Guias de usuário
│   └── technical/   # Documentação técnica
├── includes/        # Componentes reutilizáveis (header, sidebar, footer)
├── js/              # JavaScript customizado
├── migrations/      # SQL de migração
├── modal/           # Modais do sistema
│   ├── cadastro/
│   ├── edicao/
│   └── exclusao/
├── pages/           # Páginas principais
├── querys/          # Consultas ao banco
│   ├── inserts/
│   ├── updates/
│   └── deletes/
└── vendor/          # Dependências do Composer
```

---

## 🔌 API Endpoints

### Recepção
- **POST** `querys/buscaPacienteRecepcao.php` - Buscar paciente
  - Params: `termo_busca` (CPF, nome ou CNS)
  - Response: JSON com dados do paciente

- **POST** `actions/processar_checkin.php` - Fazer check-in
  - Params: `id_paciente`, `queixa_principal`
  - Response: JSON com status

- **GET** `querys/filaEsperaTriagem.php` - Listar fila
  - Response: JSON com array de pacientes

### Pacientes
- **POST** `querys/inserts/insertPaciente.php` - Cadastrar paciente
- **POST** `querys/updates/updatePaciente.php` - Atualizar paciente
- **POST** `querys/deletes/deletePaciente.php` - Excluir paciente

---

## 🧪 Testes

### Testes Manuais
Acesse as páginas e teste as funcionalidades:
- `/pages/recepcao.php` - Recepção
- `/pages/cadastroDePacientes.php` - Cadastro

### Testes SQL
```bash
mysql -u root -p bio_ubs
# Execute consultas para validar dados
```

---

## 🤝 Contribuindo

1. Fork o projeto
2. Crie uma branch (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -m 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

---

## 📝 Padrões de Código

### PHP
- **Namespace**: `BioUBS`
- **Classes**: PascalCase
- **Métodos**: camelCase
- **Arquivos**: snake_case
- **Indentação**: 2 ou 4 espaços

### SQL
- **Tabelas**: snake_case
- **Colunas**: UPPERCASE
- **Constraints**: snake_case com prefixo (fk_, uk_, etc)

### JavaScript
- **Variáveis**: camelCase
- **Constantes**: UPPERCASE
- **Funções**: camelCase

### Commits
Siga o padrão Conventional Commits:
```
feat: adiciona módulo de recepção
fix: corrige bug na busca de pacientes
docs: atualiza documentação técnica
```

---

## 🔒 Segurança

- ✓ Prepared Statements (PDO) para evitar SQL Injection
- ✓ Validação de sessão em todas as páginas
- ✓ Sanitização de inputs do usuário
- ✓ CSRF tokens (implementado via `includes/csrf.php`)
- ✓ Senhas hasheadas com `password_hash()`

---

## 📈 Roadmap

- [x] Módulo de Recepção
- [ ] Módulo de Triagem
- [ ] Módulo de Atendimento Médico
- [ ] Módulo de Prescrições
- [ ] Módulo de Agendamento
- [ ] Relatórios e Dashboard
- [ ] API REST completa
- [ ] Aplicativo mobile

---

[← Voltar para documentação principal](../README.md)
