# 🚀 Guia Rápido - Módulo de Recepção

## Acesso Rápido
1. Login no BioUBS
2. Clique em **"Recepção"** no menu lateral
3. Pronto para usar! ✅

---

## 📝 Operações Principais

### 🔍 BUSCAR PACIENTE

**Opção 1: Buscar por CPF**
```
Digite: 123.456.789-00
ou apenas: 12345678900
```

**Opção 2: Buscar por Nome**
```
Digite: Maria da Silva
ou parcial: Maria
```

**Opção 3: Buscar por CNS**
```
Digite: 123456789012345 (15 dígitos)
```

**Atalho**: Pressione `Enter` após digitar

---

### ✅ FAZER CHECK-IN

1. **Busque** o paciente
2. **Confira** os dados exibidos no card:
   - Nome
   - Idade  
   - Nome da mãe
   - CPF e CNS
3. (Opcional) **Preencha** o campo "Queixa/Motivo da Visita"
4. **Clique** em "Confirmar Chegada - Enviar para Triagem"
5. ✓ **Pronto!** Paciente aparece na fila

---

### ➕ CADASTRAR PACIENTE NOVO

**Quando buscar e não encontrar:**

1. Sistema exibe: **"Paciente Não Encontrado"**
2. **Clique** em "Cadastrar Novo Paciente"
3. **Preencha** o formulário completo
4. **Salve**
5. ✓ Sistema **volta automaticamente** para recepção
6. ✓ Paciente já aparece **buscado e pronto** para check-in

---

## 📊 Fila de Espera

**O que você vê:**
- 👥 Total de pacientes aguardando
- 🔢 Posição na fila (1º, 2º, 3º...)
- ⏱️ Tempo de espera de cada um
- 💬 Queixa principal (se preenchida)

**Atualização**: Automática a cada 30 segundos

---

## ⚡ Dicas Rápidas

✅ **Campo de busca aceita**:
- CPF com ou sem pontuação
- Nome completo ou parcial
- CNS (15 dígitos)

✅ **Queixa principal**:
- Campo **opcional**
- Pode ser preenchido depois na triagem

✅ **Paciente duplicado**:
- Sistema **bloqueia automaticamente**
- Não é possível fazer check-in duas vezes

---

## 🎯 Fluxo Ideal

```
┌─────────────────┐
│   1. BUSCAR     │
│   (CPF/Nome)    │
└────────┬────────┘
         │
    ┌────▼────┐
    │Encontrou?│
    └────┬────┘
         │
    ┌────▼────────────────────┐
    │ SIM              NÃO    │
    │                         │
┌───▼────┐          ┌────────▼─────┐
│2. CARD │          │ 3. CADASTRAR │
│PACIENTE│          │   NOVO       │
└───┬────┘          └──────┬───────┘
    │                      │
    │                 ┌────▼────┐
    │                 │ RETORNA │
    │                 │AUTOMÁTICO│
    │                 └────┬────┘
    │                      │
    └──────────┬───────────┘
               │
        ┌──────▼──────┐
        │ 4. CHECK-IN │
        └──────┬──────┘
               │
        ┌──────▼──────┐
        │ 5. NA FILA  │
        │  TRIAGEM    │
        └─────────────┘
```

---

## 🔧 Solução Rápida de Problemas

### ❌ Não encontra paciente
- ✓ Verifique se o CPF está correto
- ✓ Tente buscar pelo nome
- ✓ Se realmente não existe, cadastre

### ❌ Check-in não funciona
- ✓ Verifique se está logado
- ✓ Confirme que selecionou o paciente
- ✓ Veja se não há mensagem de erro

### ❌ Fila não aparece
- ✓ Aguarde 30 segundos (atualização automática)
- ✓ Recarregue a página (F5)
- ✓ Verifique sua conexão

---

## 📱 Atalhos de Teclado

| Ação | Atalho |
|------|--------|
| Buscar | `Enter` |
| Limpar busca | `Esc` (após fechar card) |
| Recarregar página | `F5` |

---

## 💡 Boas Práticas

✅ **Sempre confirme**:
- Nome do paciente
- Idade aproximada
- Nome da mãe

✅ **Pergunte ao paciente**:
- "Qual o motivo da sua visita hoje?"
- Anote brevemente no campo de queixa

✅ **Oriente o paciente**:
- "Aguarde na sala de espera"
- "A enfermagem vai chamá-lo(a) em breve"

---

## 📞 Suporte

**Dúvidas?** Contate o administrador do sistema.

**Bug?** Reporte com:
- O que tentou fazer
- Mensagem de erro (se houver)
- Navegador utilizado

---

**Versão**: 1.0  
**Atualizado em**: Novembro 2025
