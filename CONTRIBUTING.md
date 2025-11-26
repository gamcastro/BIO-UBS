# 🤝 Guia de Contribuição - BioUBS

Obrigado por considerar contribuir com o BioUBS! Este documento fornece diretrizes para contribuir com o projeto.

---

## 📋 Código de Conduta

Ao participar deste projeto, você concorda em manter um ambiente respeitoso e colaborativo. Esperamos que todos:

- 🤝 Sejam respeitosos com outros colaboradores
- 💬 Forneçam feedback construtivo
- 🎯 Foquem no que é melhor para a comunidade
- 🙏 Demonstrem empatia com outros membros

---

## 🚀 Como Posso Contribuir?

### 1. Reportar Bugs

Se encontrou um bug, abra uma **Issue** com:

- **Título claro**: "Bug: Descrição curta do problema"
- **Descrição detalhada**: O que aconteceu vs. o esperado
- **Passos para reproduzir**: Lista numerada de ações
- **Ambiente**: Navegador, SO, versão do PHP
- **Screenshots**: Se aplicável

**Template de Bug:**
```markdown
**Descrição do Bug**
Descrição clara do que está errado.

**Passos para Reproduzir**
1. Vá para '...'
2. Clique em '...'
3. Veja o erro

**Comportamento Esperado**
O que deveria acontecer.

**Screenshots**
Se aplicável, adicione screenshots.

**Ambiente**
- SO: [ex: Windows 11]
- Navegador: [ex: Chrome 120]
- PHP: [ex: 8.2]
```

### 2. Sugerir Melhorias

Abra uma **Issue** com:

- **Título**: "Feature: Descrição da funcionalidade"
- **Problema que resolve**: Contexto da necessidade
- **Solução proposta**: Como poderia funcionar
- **Alternativas**: Outras abordagens consideradas

### 3. Contribuir com Código

#### 🔀 Fluxo de Trabalho (Git Flow)

```bash
# 1. Fork o projeto
# Clique em "Fork" no GitHub

# 2. Clone seu fork
git clone https://github.com/SEU-USUARIO/BIO-UBS.git
cd BIO-UBS

# 3. Adicione o repositório original como upstream
git remote add upstream https://github.com/gamcastro/BIO-UBS.git

# 4. Crie uma branch para sua feature
git checkout -b feature/minha-feature
# ou para correção de bug
git checkout -b fix/correcao-bug

# 5. Faça suas alterações
# ... código ...

# 6. Commit suas mudanças
git add .
git commit -m "feat: adiciona nova funcionalidade X"

# 7. Sincronize com o upstream
git fetch upstream
git rebase upstream/main

# 8. Push para seu fork
git push origin feature/minha-feature

# 9. Abra um Pull Request
# No GitHub, clique em "New Pull Request"
```

---

## 📝 Padrões de Código

### PHP

#### Estilo de Código (PSR-12)

```php
<?php
namespace BioUBS;

class MinhaClasse
{
    private $propriedade;

    public function meuMetodo($parametro)
    {
        if ($parametro === true) {
            return $this->propriedade;
        }
        
        return null;
    }
}
```

#### Boas Práticas

- ✅ Use **type hints** sempre que possível
- ✅ Documente com **PHPDoc**
- ✅ Evite funções com mais de 20 linhas
- ✅ Use **prepared statements** para queries
- ✅ Valide e sanitize todos os inputs

**Exemplo:**
```php
/**
 * Busca um paciente por ID
 * 
 * @param int $id ID do paciente
 * @return array|null Dados do paciente ou null se não encontrado
 */
public function buscarPorId(int $id): ?array
{
    $sql = "SELECT * FROM cadastro_paciente WHERE ID = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}
```

### SQL

```sql
-- Maiúsculas para palavras-chave SQL
SELECT ID, NOME, CPF
FROM cadastro_paciente
WHERE STATUS = 'ATIVO'
ORDER BY NOME ASC;

-- Nomes descritivos para constraints
ALTER TABLE fila_atendimento
ADD CONSTRAINT fk_fila_paciente 
FOREIGN KEY (ID_PACIENTE) 
REFERENCES cadastro_paciente(ID);
```

### JavaScript

```javascript
// Use camelCase para variáveis e funções
const nomeCompleto = 'João Silva';

function buscarPaciente(cpf) {
    return $.ajax({
        url: 'querys/buscaPaciente.php',
        method: 'POST',
        data: { cpf: cpf }
    });
}

// Use const/let, evite var
const CONSTANTE = 'valor';
let variavel = 'outro valor';
```

### HTML/CSS

```html
<!-- Use indentação de 2 espaços -->
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Título</h5>
    <p class="card-text">Texto</p>
  </div>
</div>
```

---

## 📜 Padrão de Commits (Conventional Commits)

Use o formato: `<tipo>: <descrição>`

### Tipos

- **feat**: Nova funcionalidade
- **fix**: Correção de bug
- **docs**: Apenas documentação
- **style**: Formatação (não afeta o código)
- **refactor**: Refatoração de código
- **test**: Adição de testes
- **chore**: Tarefas de build, etc

### Exemplos

```bash
feat: adiciona módulo de triagem
fix: corrige bug na busca de pacientes por CNS
docs: atualiza README com instruções de instalação
style: formata código seguindo PSR-12
refactor: reorganiza estrutura de pastas
test: adiciona testes para classe Conexao
chore: atualiza dependências do composer
```

### Commit Detalhado

```bash
git commit -m "feat: adiciona filtro por data na fila de espera

- Adiciona campo de seleção de data
- Implementa query com filtro temporal
- Atualiza documentação da API

Closes #123"
```

---

## 🧪 Testes

Antes de submeter um Pull Request:

### Checklist de Testes

- [ ] Código funciona localmente
- [ ] Não há erros no console do navegador
- [ ] Não há erros de PHP
- [ ] Testado em Chrome e Firefox
- [ ] Código segue os padrões do projeto
- [ ] Documentação atualizada (se necessário)
- [ ] Sem conflitos com a branch principal

### Testes Manuais

```bash
# 1. Teste funcionalidade principal
# 2. Teste casos extremos (edge cases)
# 3. Teste com dados inválidos
# 4. Verifique responsividade
```

---

## 📖 Documentação

Se sua contribuição adiciona nova funcionalidade:

### Atualize:

1. **README.md**: Se for uma feature principal
2. **docs/technical/**: Documentação técnica
3. **docs/user/**: Guia do usuário (se aplicável)
4. **Comentários no código**: PHPDoc, JSDoc

### Exemplo de Documentação

```php
/**
 * Módulo de Triagem
 * 
 * Este módulo permite que enfermeiros realizem a classificação de risco
 * dos pacientes seguindo o protocolo de Manchester.
 * 
 * @package BioUBS\Triagem
 * @author Seu Nome <email@exemplo.com>
 * @version 1.0.0
 * @since 2025-11-26
 */
```

---

## 🔍 Revisão de Código

Seu Pull Request será revisado quanto a:

- ✅ Qualidade do código
- ✅ Conformidade com padrões
- ✅ Testes adequados
- ✅ Documentação atualizada
- ✅ Sem quebrar funcionalidades existentes

### O que esperar:

1. **Revisão inicial**: 2-5 dias úteis
2. **Feedback**: Sugestões de melhoria
3. **Ajustes**: Faça as alterações solicitadas
4. **Aprovação**: Merge para a branch principal

---

## 📁 Estrutura de Arquivos

Ao adicionar novos arquivos, siga a estrutura:

```
pages/           # Nova página principal
├── nome_pagina.php

modal/           # Novo modal
├── cadastro/
│   └── modalNovo.php

querys/          # Nova query
├── tipo/
│   └── novaQuery.php

actions/         # Nova action
└── nova_action.php

docs/            # Nova documentação
├── user/        # Para usuários
└── technical/   # Para desenvolvedores
```

---

## 🎨 Design e UX

Mantenha consistência com o sistema:

- 🎨 Use **Bootstrap 5** classes
- 🎨 Siga a **paleta de cores** do projeto
- 🎨 Use **ícones Bootstrap Icons**
- 🎨 Mantenha **padrão de espaçamento**
- 🎨 Garanta **responsividade**

---

## ❓ Dúvidas?

- 📖 Leia a [documentação completa](docs/README.md)
- 💬 Abra uma [Discussion no GitHub](https://github.com/gamcastro/BIO-UBS/discussions)
- 🐛 Reporte bugs via [Issues](https://github.com/gamcastro/BIO-UBS/issues)

---

## 🙏 Agradecimentos

Toda contribuição é valiosa, seja:
- 🐛 Reportando bugs
- 💡 Sugerindo melhorias
- 📖 Melhorando a documentação
- 💻 Contribuindo com código

**Obrigado por ajudar a melhorar o BioUBS!** 🎉

---

[← Voltar para README](README.md)
