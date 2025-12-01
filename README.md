# 🏥 BioUBS - Sistema de Gestão para Unidades Básicas de Saúde

Sistema completo para gerenciamento de atendimento em Unidades Básicas de Saúde (UBS), desenvolvido em PHP com foco em usabilidade e conformidade com as diretrizes do Sistema Único de Saúde (SUS).

---

## 📋 Sobre o Projeto

O **BioUBS** é um sistema web desenvolvido para otimizar o fluxo de atendimento em UBS, desde a recepção até o atendimento médico, seguindo as diretrizes do Manual de Estrutura Física da UBS e o documento "Passo a passo do atendimento na UBS".

### ✨ Principais Funcionalidades

- ✅ **Recepção**: Check-in de pacientes com busca inteligente (CPF, Nome, CNS)
- 🏥 **Triagem**: Classificação de risco e aferição de sinais vitais *(em desenvolvimento)*
- 👨‍⚕️ **Atendimento Médico**: Consultas e prescrições *(em desenvolvimento)*
- 📊 **Gestão de Filas**: Controle em tempo real da fila de espera
- 👥 **Cadastro de Pacientes**: Gerenciamento completo de informações
- 🔐 **Controle de Acesso**: Sistema de autenticação por perfil

---

## 🚀 Quick Start

### Requisitos
- PHP 8.2+
- MySQL 5.7+ ou MariaDB 10.4+
- Apache 2.4
- Composer

### Instalação Rápida (XAMPP - Windows)

```bash
# 1. Clone o repositório
cd C:\xampp\htdocs
git clone https://github.com/gamcastro/BIO-UBS.git
cd BIO-UBS

# 2. Instale as dependências
composer install

# 3. Importe o banco de dados
# Acesse http://localhost/phpmyadmin
# Crie o banco 'bio_ubs' e importe: migrations/bio_ubs v_5.sql

# 4. Configure a conexão
# Edite: class/Conexao.php

# 5. Acesse o sistema
# URL: http://localhost/BIO-UBS/login.php
```

**📖 [Guia Completo de Instalação](docs/technical/INSTALACAO.md)**

---

## 📚 Documentação

### Para Usuários
- [📋 Guia Rápido - Recepção](docs/user/GUIA_RAPIDO_RECEPCAO.md)
- 🏥 Guia Rápido - Triagem *(em breve)*
- 👨‍⚕️ Guia Rápido - Atendimento *(em breve)*

### Para Desenvolvedores
- [🔧 Módulo de Recepção - Documentação Técnica](docs/technical/MODULO_RECEPCAO.md)
- [⚙️ Instalação e Configuração](docs/technical/INSTALACAO.md)
- 📊 Modelo de Banco de Dados *(em breve)*
- 🔌 API e Endpoints *(em breve)*

**📖 [Ver toda a documentação](docs/README.md)**

---

## 🏗️ Arquitetura

```
BIO-UBS/
├── actions/          # Processamento de ações (check-in, autenticação)
├── ajax/            # Requisições AJAX
├── class/           # Classes PHP (Conexão, CRUD, Utilidades)
├── css/             # Estilos customizados
├── docs/            # 📚 Documentação completa
│   ├── user/        # Guias para usuários
│   └── technical/   # Documentação técnica
├── includes/        # Componentes reutilizáveis (header, sidebar, footer)
├── js/              # JavaScript customizado
├── migrations/      # Scripts SQL de migração
├── modal/           # Modais do sistema
├── pages/           # Páginas principais (recepção, cadastros)
├── querys/          # Consultas e operações no banco
└── vendor/          # Dependências do Composer
```

---

## 🎯 Módulos

### ✅ Implementados

#### 📋 Módulo de Recepção
- Busca inteligente de pacientes (CPF, Nome, CNS)
- Check-in com um clique
- Cadastro rápido de novos pacientes
- Visualização da fila de espera em tempo real
- Atualização automática a cada 30 segundos

### 🚧 Em Desenvolvimento

- 🏥 **Módulo de Triagem**: Classificação de risco, sinais vitais
- 👨‍⚕️ **Módulo de Atendimento**: Consultas, prescrições, exames
- 📅 **Módulo de Agendamento**: Agendamento de consultas
- 📊 **Dashboard**: Indicadores e relatórios gerenciais

---

## 🛠️ Tecnologias

- **Backend**: PHP 8.2+ com Namespaces
- **Frontend**: Bootstrap 5.3, jQuery 3.3
- **Banco de Dados**: MySQL/MariaDB
- **Servidor Web**: Apache
- **Gerenciador de Dependências**: Composer
- **Controle de Versão**: Git

---

## 🔒 Segurança

- ✓ Prepared Statements (PDO) contra SQL Injection
- ✓ Validação de sessão em todas as páginas protegidas
- ✓ Sanitização de inputs do usuário
- ✓ CSRF Tokens implementados
- ✓ Senhas hasheadas com `password_hash()`
- ✓ HTTPS recomendado para produção

---

## 👥 Perfis de Usuário

| Perfil | Acessos |
|--------|---------|
| **Recepcionista** | Recepção, Cadastro de Pacientes |
| **Enfermeiro(a)** | Triagem, Sinais Vitais |
| **Médico(a)** | Atendimento, Prescrições |
| **Coordenador UBS** | Todos os módulos + Relatórios |
| **Administrador** | Acesso total + Configurações |

---

## 🤝 Como Contribuir

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona MinhaFeature'`)
4. Push para a branch (`git push origin feature/MinhaFeature`)
5. Abra um Pull Request

**📝 [Guia de Contribuição](CONTRIBUTING.md)** *(em breve)*

---

## 📈 Roadmap

- [x] Sistema de login e autenticação
- [x] Cadastro completo de pacientes
- [x] Módulo de Recepção com check-in
- [x] Gestão de fila de espera
- [ ] Módulo de Triagem (Enfermagem)
- [ ] Módulo de Atendimento Médico
- [ ] Sistema de Prescrições
- [ ] Agendamento de consultas
- [ ] Prontuário eletrônico
- [ ] Relatórios gerenciais
- [ ] API REST completa
- [ ] Aplicativo mobile

---

## 📄 Licença

Este projeto está sob a licença [MIT](LICENSE).

---

## 📞 Suporte

- **Documentação**: [docs/README.md](docs/README.md)
- **Issues**: [GitHub Issues](https://github.com/gamcastro/BIO-UBS/issues)
- **Discussões**: [GitHub Discussions](https://github.com/gamcastro/BIO-UBS/discussions)

---

## 👨‍💻 Autor

**Projeto BioUBS**  
Desenvolvido com ❤️ para melhorar o atendimento nas Unidades Básicas de Saúde

---

## 🌟 Agradecimentos

Este projeto segue as diretrizes e recomendações de:
- Manual de Estrutura Física da Unidade Básica de Saúde (Ministério da Saúde)
- Documento "Passo a passo do atendimento na UBS"
- Política Nacional de Atenção Básica (PNAB)

---

**Versão**: 1.0  
**Status**: 🚀 Em Desenvolvimento Ativo  
**Última atualização**: Novembro 2025
