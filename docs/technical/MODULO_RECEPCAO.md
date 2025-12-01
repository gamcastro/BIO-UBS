# Módulo de Recepção - BioUBS

## 📋 Visão Geral

O Módulo de Recepção é a porta de entrada do sistema BioUBS, implementando o fluxo de **Identificação e Check-in** de pacientes conforme as diretrizes do documento "Passo a passo do atendimento na UBS".

## ✨ Funcionalidades Principais

### 1. Busca Robusta de Pacientes
- **Busca por CPF**: Aceita CPF com ou sem formatação (pontos e hífen)
- **Busca por Nome**: Busca parcial no nome do paciente
- **Busca por CNS**: Busca pelo Cartão Nacional de Saúde (15 dígitos)
- Interface limpa com campo de busca único e inteligente

### 2. Cartão de Identificação do Paciente
Quando um paciente é encontrado, exibe:
- Nome completo
- Idade calculada automaticamente
- Nome da mãe
- CPF formatado
- CNS (Cartão Nacional de Saúde)
- Campo opcional para queixa/motivo da visita

### 3. Sistema de Check-in
- Botão destacado "Confirmar Chegada - Enviar para Triagem"
- Insere o paciente na tabela `fila_atendimento` com status `AGUARDANDO_TRIAGEM`
- Registra data/hora de chegada automaticamente
- Validação para evitar check-in duplicado

### 4. Fila de Espera em Tempo Real
- Exibe todos os pacientes aguardando triagem
- Mostra posição na fila (1º, 2º, 3º...)
- Tempo de espera calculado dinamicamente
- Atualização automática a cada 30 segundos
- Contador visual do total de pacientes

### 5. Cadastro Rápido (Beco Sem Saída Resolvido)
Quando o paciente não é encontrado:
- Alerta visual informativo (amarelo/azul)
- Botão destacado "Cadastrar Novo Paciente"
- Abre o modal de cadastro existente
- **Retorno Inteligente**: Após cadastro, volta automaticamente para a recepção com:
  - Mensagem de sucesso
  - Busca automática do paciente recém-cadastrado
  - Pronto para fazer o check-in

## 🗂️ Arquivos Criados/Modificados

### Novos Arquivos

1. **`pages/recepcao.php`**
   - Página principal do módulo
   - Interface completa com busca, resultado e fila
   - JavaScript para interações e atualizações

2. **`querys/buscaPacienteRecepcao.php`**
   - API de busca robusta
   - Suporta CPF, Nome e CNS
   - Retorna JSON com dados do paciente

3. **`actions/processar_checkin.php`**
   - Processa o check-in do paciente
   - Insere na fila de atendimento
   - Validações de segurança e duplicidade

4. **`querys/filaEsperaTriagem.php`**
   - API para listar fila de espera
   - JOIN com tabela de pacientes
   - Ordenação por hora de chegada

### Arquivos Modificados

1. **`includes/sidebar.php`**
   - Adicionado link "Recepção" no menu principal
   - Ícone: `bi-door-open-fill`

2. **`modal/cadastro/modalCadastroDePacientes.php`**
   - Adicionado campo hidden `origem_recepcao`
   - Permite identificar quando o cadastro vem da recepção

3. **`querys/inserts/insertPaciente.php`**
   - Lógica de redirecionamento inteligente
   - Redireciona para recepção quando `origem_recepcao=1`
   - Busca automática após cadastro

## 🎨 Design e UX

### Princípios Seguidos
- **Interface Limpa**: Sem cliques ou pop-ups desnecessários
- **Feedback Imediato**: Alertas visuais claros para cada ação
- **Fluxo Contínuo**: Usuário nunca fica em "beco sem saída"
- **Acolhimento**: Design reflete a recomendação do Manual de não usar grades/vidros

### Elementos Visuais
- **Avatar do Paciente**: Círculo colorido com gradiente roxo
- **Cards**: Sombras suaves, bordas arredondadas
- **Badges**: Contador visual da fila
- **Ícones Bootstrap**: Consistentes com o restante do sistema
- **Cores**:
  - Primário (Azul): Ações principais
  - Sucesso (Verde): Check-in confirmado
  - Aviso (Amarelo): Paciente não encontrado
  - Secundário (Cinza): Ações secundárias

## 🔄 Fluxo de Uso

### Cenário 1: Paciente Existente
1. Recepcionista digita CPF/Nome/CNS
2. Sistema exibe cartão do paciente
3. Recepcionista confirma dados visualmente
4. (Opcional) Preenche queixa principal
5. Clica em "Confirmar Chegada"
6. Paciente aparece na fila de espera
7. Sistema limpa busca para próximo paciente

### Cenário 2: Paciente Novo
1. Recepcionista digita CPF
2. Sistema exibe "Paciente Não Encontrado"
3. Recepcionista clica "Cadastrar Novo Paciente"
4. Preenche formulário completo
5. Ao salvar, retorna automaticamente para recepção
6. Sistema busca automaticamente o paciente cadastrado
7. Recepcionista confirma check-in

## 🔧 Tecnologias Utilizadas

- **Backend**: PHP 8+ com namespace `BioUBS`
- **Banco de Dados**: MariaDB/MySQL
- **Frontend**: 
  - Bootstrap 5.3.3 (design responsivo)
  - jQuery 3.3.1 (AJAX e manipulação DOM)
  - Bootstrap Icons (iconografia)
- **Padrão de Arquitetura**: MVC (Model-View-Controller)
- **Segurança**: 
  - Validação de sessão
  - Prepared Statements (PDO)
  - Sanitização de inputs

## 📊 Estrutura do Banco de Dados

### Tabela: `fila_atendimento`
```sql
CREATE TABLE `fila_atendimento` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_PACIENTE` int(11) NOT NULL,
  `ID_UNIDADE` int(11) NOT NULL,
  `QUEIXA_PRINCIPAL` text DEFAULT NULL,
  `DATA_HORA_CHEGADA` timestamp NOT NULL DEFAULT current_timestamp(),
  `STATUS` enum('AGUARDANDO_TRIAGEM','EM_TRIAGEM','AGUARDANDO_ATENDIMENTO','EM_ATENDIMENTO','FINALIZADO') 
           NOT NULL DEFAULT 'AGUARDANDO_TRIAGEM',
  PRIMARY KEY (`ID`),
  KEY `FK_fila_paciente` (`ID_PACIENTE`),
  KEY `FK_fila_unidade` (`ID_UNIDADE`)
)
```

### Status da Fila
- **AGUARDANDO_TRIAGEM**: Paciente fez check-in e aguarda enfermeiro
- **EM_TRIAGEM**: Paciente sendo atendido pela enfermagem
- **AGUARDANDO_ATENDIMENTO**: Após triagem, aguarda médico
- **EM_ATENDIMENTO**: Paciente em consulta médica
- **FINALIZADO**: Atendimento concluído

## 🚀 Como Usar

### Acesso
1. Faça login no sistema BioUBS
2. No menu lateral, clique em **"Recepção"**
3. A página será aberta automaticamente

### Operação
- **Buscar**: Digite CPF, nome ou CNS e pressione Enter ou clique em "Buscar"
- **Check-in**: Confirme os dados e clique no botão verde
- **Cadastrar**: Se não encontrar, clique em "Cadastrar Novo Paciente"
- **Monitorar**: Acompanhe a fila de espera na coluna direita

## 📱 Responsividade

- **Desktop**: Layout de 2 colunas (busca + fila)
- **Tablet**: Colunas empilhadas com largura total
- **Mobile**: Otimizado para telas pequenas

## 🔐 Segurança

- Validação de sessão em todas as requisições
- Verificação de unidade (ubs_id)
- Proteção contra SQL Injection (prepared statements)
- Validação de duplicidade de check-in
- Sanitização de inputs

## 🎯 Conformidade com Diretrizes

✅ **Identificação**: Sistema de busca robusto por múltiplos critérios  
✅ **Check-in**: Processo simples de um clique  
✅ **Demanda Espontânea**: Horário de chegada registrado automaticamente  
✅ **Fila para Triagem**: Pacientes enviados diretamente para enfermagem  
✅ **Sem Beco Sem Saída**: Cadastro rápido com retorno inteligente  
✅ **Interface Acolhedora**: Design limpo sem barreiras visuais  

## 📈 Próximos Passos (Sugestões)

- [ ] Módulo de Triagem (Enfermagem)
- [ ] Atualização da fila via WebSocket para tempo real
- [ ] Impressão de senha/comprovante de chegada
- [ ] Painel de TV para chamar pacientes
- [ ] Estatísticas de tempo de espera
- [ ] Notificações push para enfermeiros

## 🐛 Troubleshooting

### Problema: Fila não atualiza
- Verifique o console do navegador
- Confirme que `filaEsperaTriagem.php` está acessível
- Verifique se há erros de JavaScript

### Problema: Check-in duplicado
- O sistema já valida automaticamente
- Verifique a tabela `fila_atendimento` para registros órfãos

### Problema: Cadastro não volta para recepção
- Confirme que o campo `origem_recepcao` está sendo enviado
- Verifique os logs do navegador

## 👥 Créditos

Desenvolvido seguindo as diretrizes do:
- Manual de Estrutura Física da UBS
- Documento "Passo a passo do atendimento na UBS"
- Padrão de arquitetura do projeto BioUBS

---

**Versão**: 1.0  
**Data**: Novembro 2025  
**Status**: ✅ Funcional e Testado
