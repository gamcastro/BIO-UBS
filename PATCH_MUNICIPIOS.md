# Refatoração: Município como ID em vez de texto

## Modais de Edição - Profissional

### modal/edicao/modalEdCadastroDeProfissional.php

**Linha ~44** - Alterar busca de município:
```php
// ANTES:
$municipio = $rowsId['MUNICIPIO'];

// DEPOIS:
$id_municipio = $rowsId['MUNICIPIO'];
$municipio = '';
if ($id_municipio) {
    $pdo = BioUBS\Conexao::getConn();
    $stmtMun = $pdo->prepare('SELECT MUNICIPIO FROM ibge_municipios WHERE CD_MUNICIPIO = :id LIMIT 1');
    $stmtMun->bindValue(':id', $id_municipio, PDO::PARAM_INT);
    $stmtMun->execute();
    $municipio = $stmtMun->fetchColumn() ?: '';
}
```

**Linha ~218** - Alterar input município:
```php
// ANTES:
<input class="form-control" type="text" id="MUNICIPIO" name="MUNICIPIO" value="<?= htmlspecialchars($municipio ?? '') ?>" data-titlecase="true">

// DEPOIS:
<input class="form-control" type="text" id="MUNICIPIO" name="MUNICIPIO" value="<?=$id_municipio?>" data-pref-label="<?=$municipio?>" data-titlecase="true">
```

---

## Modais de Edição - Unidade

### modal/edicao/modalEdCadastroDeUnidades.php

**Linha ~27** - Alterar busca de município:
```php
// ANTES:
$municipio = $rowsId['MUNICIPIO'];

// DEPOIS:
$id_municipio = $rowsId['MUNICIPIO'];
$municipio = '';
if ($id_municipio) {
    $pdo = BioUBS\Conexao::getConn();
    $stmtMun = $pdo->prepare('SELECT MUNICIPIO FROM ibge_municipios WHERE CD_MUNICIPIO = :id LIMIT 1');
    $stmtMun->bindValue(':id', $id_municipio, PDO::PARAM_INT);
    $stmtMun->execute();
    $municipio = $stmtMun->fetchColumn() ?: '';
}
```

**Linha ~102** - Alterar input município:
```php
// ANTES:
<input class="form-control" type="text" id="municipio" name="municipio" data-titlecase="true" value="<?= htmlspecialchars($municipio ?? '') ?>">

// DEPOIS:
<input class="form-control" type="text" id="municipio" name="municipio" data-titlecase="true" value="<?=$id_municipio?>" data-pref-label="<?=$municipio?>">
```

---

## Backend - Insert Profissional

### querys/inserts/insertProfissionais.php

**Linha ~28 (após captura do POST)** - Converter município para int:
```php
// Adicionar APÓS a coleta dos dados do formulário, antes do array $dados:
if (isset($_POST['MUNICIPIO']) && $_POST['MUNICIPIO'] !== '') {
    $_POST['MUNICIPIO'] = (int)$_POST['MUNICIPIO'];
}
```

---

## Backend - Update Profissional

### querys/updates/updateProfissionais.php

**Verificar se há captura de $municipio e converter:**
```php
$municipio = isset($_POST['MUNICIPIO']) && $_POST['MUNICIPIO'] !== '' ? (int)$_POST['MUNICIPIO'] : null;
```

---

## Backend - Insert Unidade

### querys/inserts/insertUnidades.php

**Linha ~28** - Converter município para int:
```php
// ANTES:
$municipio = $_POST['municipio'];

// DEPOIS:
$municipio = isset($_POST['municipio']) && $_POST['municipio'] !== '' ? (int)$_POST['municipio'] : null;
```

---

## Backend - Update Unidade

### querys/updates/updateUnidades.php (se existir)

**Converter município para int da mesma forma que insert.**

---

## Nota Importante

Caso as tabelas `cadastro_profissional` e `cadastro_unidade` ainda tenham a coluna `MUNICIPIO` como VARCHAR e não como INT, será necessário executar ALTER TABLE:

```sql
-- Para cadastro_profissional
ALTER TABLE cadastro_profissional 
CHANGE COLUMN MUNICIPIO MUNICIPIO INT(7) NULL;

-- Para cadastro_unidade
ALTER TABLE cadastro_unidade 
CHANGE COLUMN MUNICIPIO MUNICIPIO INT(7) NULL;
```

Ou renomear para ID_MUNICIPIO se preferir:

```sql
ALTER TABLE cadastro_profissional 
CHANGE COLUMN MUNICIPIO ID_MUNICIPIO INT(7) NULL;

ALTER TABLE cadastro_unidade 
CHANGE COLUMN MUNICIPIO ID_MUNICIPIO INT(7) NULL;
```

Então ajustar os scripts PHP correspondentes.
