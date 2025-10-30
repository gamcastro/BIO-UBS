<?php
namespace BioUBS; // <-- ADICIONAR ISTO

// Adicionar 'use' para cada classe nativa ou classe do mesmo namespace
use PDO; 
use PDOException;
use InvalidArgumentException;
use RuntimeException;


class UbsCrudAll {
    private $UbsPDO;
    private $tabela;
    protected array $colunasPermitidas = [];

    
    public function __construct(string $tabela, array $permitidas = []) {
        $this->UbsPDO = Conexao::getConn();
        $this->tabela = $tabela;
        if ($permitidas) $this->colunasPermitidas = $permitidas;
    }

    //------------------(opcional) Setters práticos
    public function setTabela(string $t): void { $this->tabela = $t; }
    public function setColunasPermitidas(array $c): void { $this->colunasPermitidas = $c; }


        public function criarTabela($tabela, $colunas) {
                    
                $sql = "CREATE TABLE {$tabela} ({$colunas})";
                $this->UbsPDO->exec($sql);
                  
        }

        
    public function inserir(array $dados, ?array $tipos = null): string
        {
            if (empty($dados)) {
                throw new InvalidArgumentException('Dados vazios para INSERT.');
            }

            //--------Validando nome da tabela (identificador não pode ser bound)
            if (!isset($this->tabela) || !preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $this->tabela)) {
                throw new RuntimeException('Nome de tabela inválido.');
            }

            //-----Declarando colunas $this->colunasPermitidas na classe)
            if (isset($this->colunasPermitidas) && is_array($this->colunasPermitidas) && !empty($this->colunasPermitidas)) {
                $dados = array_intersect_key($dados, array_flip($this->colunasPermitidas));
            }
            if (empty($dados)) {
                throw new InvalidArgumentException('Nenhuma coluna permitida foi informada.');
            }

            //-------------Validando cada coluna e prepara placeholders
            $colunas = [];
            $placeholders = [];
            foreach (array_keys($dados) as $col) {
                if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $col)) {
                    throw new InvalidArgumentException("Nome de coluna inválido: {$col}");
                }
                $colunas[] = "`{$col}`";
                $placeholders[] = ":{$col}";
            }

            $colsSql  = implode(', ', $colunas);
            $valsSql  = implode(', ', $placeholders);
            $sql = "INSERT INTO `{$this->tabela}` ({$colsSql}) VALUES ({$valsSql})";

            try {
                $stmt = $this->UbsPDO->prepare($sql);

                //========Bind tipado (auto-inferência, com override via $tipos)
                foreach ($dados as $col => $valor) {
                    $tipo = $tipos[$col] ?? (
                        is_int($valor)  ? \PDO::PARAM_INT  :
                        (is_bool($valor) ? \PDO::PARAM_BOOL :
                        (is_null($valor) ? \PDO::PARAM_NULL : \PDO::PARAM_STR))
                    );
                    $stmt->bindValue(":{$col}", $valor, $tipo);
                }

                $stmt->execute();
                return $this->UbsPDO->lastInsertId(); //-----------compatível com MySQL/MariaDB

            } catch (\PDOException $e) {
            
                echo 'Falha ao inserir registro';

            }
        }    




    /**
     * Atualiza um registro pela PK (id) com validações de segurança:
     * - Valida nome da tabela e colunas (regex + backticks)
     * - Aplica whitelist ($this->colunasPermitidas), se existir
     * - Remove 'id' de $dados (evita tentar atualizar PK)
     * - Bind tipado automático (int/bool/null/string)
     * - WHERE obrigatório, com placeholder exclusivo (evita colisão)
     *
     * @param mixed $id  Valor da chave primária (id). Pode ser int ou string (UUID, por ex.)
     * @param array $dados  Coluna => valor (apenas colunas permitidas serão usadas)
     * @return int Número de linhas afetadas (lembrendo: em MySQL pode retornar 0 se valores iguais)
     *
     * @throws InvalidArgumentException|RuntimeException
     */
    public function atualizar($id, array $dados): int
    {
        if (!isset($this->tabela) || !preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $this->tabela)) {
            throw new RuntimeException('Nome de tabela inválido.');
        }

        if (empty($dados)) {
            throw new InvalidArgumentException('Dados vazios para UPDATE.');
        }

        //----------------------------------------------Whitelist de colunas (se definida na classe)
        if (isset($this->colunasPermitidas) && is_array($this->colunasPermitidas) && !empty($this->colunasPermitidas)) {
            $dados = array_intersect_key($dados, array_flip($this->colunasPermitidas));
            if (empty($dados)) {
                throw new InvalidArgumentException('Nenhuma coluna permitida foi informada para UPDATE.');
            }
        }

        //--------------------------------------------Não permitir tentativa de atualizar o ID
        unset($dados['id']);

        //-----------------------------------------------Monta SET validando nomes de colunas
        $setParts = [];
        foreach (array_keys($dados) as $col) {
            if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $col)) {
                throw new InvalidArgumentException("Nome de coluna inválido: {$col}");
            }
            $setParts[] = "`{$col}` = :{$col}";
        }

        if (empty($setParts)) {
            throw new InvalidArgumentException('Nenhuma coluna válida restou para UPDATE.');
        }

        //--------------------Permite customizar o pk se a classe tiver a propriedade $chavePrimaria; padrão 'id'
        $pk = property_exists($this, 'chavePrimaria') && $this->chavePrimaria
            ? $this->chavePrimaria
            : 'id';

        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $pk)) {
            throw new RuntimeException('Nome de chave primária inválido.');
        }

        $sql = "UPDATE `{$this->tabela}` SET " . implode(', ', $setParts) . " WHERE `{$pk}` = :__where_pk";

        try {
            $stmt = $this->UbsPDO->prepare($sql);

            //===================================Bind dos dados com detecção de tipo
            foreach ($dados as $col => $valor) {
                $tipo = is_int($valor) ? \PDO::PARAM_INT
                    : (is_bool($valor) ? \PDO::PARAM_BOOL
                    : (is_null($valor) ? \PDO::PARAM_NULL : \PDO::PARAM_STR));
                $stmt->bindValue(":{$col}", $valor, $tipo);
            }

            //----------------------------------------Bind da PK com detecção de tipo
            $tipoId = is_int($id) ? \PDO::PARAM_INT
                : (is_bool($id) ? \PDO::PARAM_BOOL
                : (is_null($id) ? \PDO::PARAM_NULL : \PDO::PARAM_STR));
            $stmt->bindValue(':__where_pk', $id, $tipoId);

            $stmt->execute();
            return $stmt->rowCount(); //------------ pode ser 0 se não houve mudança efetiva

        } catch (\PDOException $e) {
            
            echo 'Falha ao atualizar registro.';
        }
    }


    /**
     * Deleta um registro pela PK (padrão 'id').
     * - Valida nome de tabela e PK
     * - WHERE obrigatório com placeholder exclusivo
     * - Bind tipado automático
     *
     * @param mixed $id  Valor da PK (int, string, UUID, etc.)
     * @return int Número de linhas afetadas (0 = nenhum registro encontrado)
     */
    public function deleteId($id): int
    {
        if (!isset($this->tabela) || !preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $this->tabela)) {
            throw new RuntimeException('Nome de tabela inválido.');
        }

        $pk = property_exists($this, 'chavePrimaria') && $this->chavePrimaria
            ? $this->chavePrimaria
            : 'id';

        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $pk)) {
            throw new RuntimeException('Nome de chave primária inválido.');
        }

        $sql = "DELETE FROM `{$this->tabela}` WHERE `{$pk}` = :__where_pk";

        try {
            $stmt = $this->UbsPDO->prepare($sql);

            $tipoId = is_int($id) ? \PDO::PARAM_INT
                : (is_bool($id) ? \PDO::PARAM_BOOL
                : (is_null($id) ? \PDO::PARAM_NULL : \PDO::PARAM_STR));
            $stmt->bindValue(':__where_pk', $id, $tipoId);

            $stmt->execute();
            return $stmt->rowCount();

        } catch (\PDOException $e) {
            
            echo 'Falha ao remover registro.';
        }
    }


    public function deleteOne($one, $where) {
        try {
            $sql = "DELETE FROM {$this->tabela} WHERE $where";
            $UbsPDODelete = $this->UbsPDO->prepare($sql);
            $UbsPDODelete->bindParam(":one", $one);
            $UbsPDODelete->execute();

            return $UbsPDODelete->rowCount();
        } catch (PDOException $e) {
            echo 'Erro ao deletar dados: ' . $e->getMessage();
        }
    }

    
    public function deleteTwo($one, $two, $where) {
        try {
            $sql = "DELETE FROM {$this->tabela} WHERE $where";
            $UbsPDODelete = $this->UbsPDO->prepare($sql);
            $UbsPDODelete->bindParam(":one", $one);
            $UbsPDODelete->bindParam(":two", $two);
            $UbsPDODelete->execute();

            return $UbsPDODelete->rowCount();

        } catch (PDOException $e) {
            echo 'Erro ao deletar dados: ' . $e->getMessage();
        }
    }

    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM {$this->tabela} WHERE id = :id";
            $stmt = $this->UbsPDO->prepare($sql);
            $stmt->execute(['id' => $id]);

            return $stmt->fetch();
        } catch (PDOException $e) {
            echo 'Erro ao buscar dados: ' . $e->getMessage();
        }
    }

    public function buscarTodos() {
        try {
            $sql = "SELECT * FROM {$this->tabela}";
            $stmt = $this->UbsPDO->query($sql);

            return $stmt->fetchAll(PDO::FETCH_ASSOC); 

        } catch (PDOException $e) {
            echo 'Erro ao buscar dados: ' . $e->getMessage();
        }
    }


    /**
     * Busca livre com parâmetros preparados (opcionais).
     * Se $where vier vazio, executa um SELECT puro (FROM tabela).
     *
     * $where pode começar por "WHERE ...", ou "INNER JOIN ...",
     * ou ainda só "ORDER BY ...", "LIMIT ...".
     *
     * $params aceita nomeados (['uf'=>'MA']) ou posicionais ([10,20]).
     *
     * @param string $where
     * @param array  $params
     * @param string $select
     * @return array
     */
    public function buscaLivreParams($where = '', $params = [], $select = '*')
    {
        try {
            $where = trim($where);

            $sql  = "SELECT {$select} FROM {$this->tabela}" . ($where !== '' ? " {$where}" : '');
            $stmt = $this->UbsPDO->prepare($sql);

            //-----------------------------Bind inteligente
            foreach ($params as $k => $v) {
                $type = PDO::PARAM_STR;
                if (is_int($v))      $type = PDO::PARAM_INT;
                elseif (is_bool($v)) $type = PDO::PARAM_BOOL;
                elseif ($v === null) $type = PDO::PARAM_NULL;

                if (is_int($k)) {
                    //--------------------posicionais: 1-based
                    $stmt->bindValue($k + 1, $v, $type);
                } else {
                    //--------------------------------nomeados
                    $param = ($k[0] === ':') ? $k : ':' . $k;
                    $stmt->bindValue($param, $v, $type);
                }
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Erro ao buscar dados: ' . $e->getMessage();
            return [];
        }
    }

    /**
     * Atalho para SELECT puro (sem WHERE), podendo escolher colunas
     */
    public function listarTodos($select = '*')
    {
        return $this->buscaLivreParams('', [], $select);
    }




    public function contarItens($condicao = '', $parametros = []) {
        try {
            $sql = "SELECT COUNT(*) AS total FROM {$this->tabela}";
    
            if (!empty($condicao)) {
                $sql .= " WHERE $condicao";
            }
    
            $stmt = $this->UbsPDO->prepare($sql);
            $stmt->execute($parametros);
    
            return $stmt->fetch(PDO::FETCH_OBJ)->total;
        } catch (PDOException $e) {
            echo 'Erro ao contar itens: ' . $e->getMessage();
        }
    }

    
    public function tabelaExiste($tabela) {
        try {
            $sql = "SHOW TABLES LIKE :tabela";
            $stmt = $this->UbsPDO->prepare($sql);
            $stmt->execute([':tabela' => $tabela]);
            return $stmt->rowCount() > 0; 
        } catch (PDOException $e) {
            echo 'Erro ao verificar tabela: ' . $e->getMessage();
            return false;
        }
    }

   

    public function alterarTabela($tabela, $alteracao) {

            $sql = "ALTER TABLE {$tabela} {$alteracao}";
            $this->UbsPDO->exec($sql);

    }


    

    public function criarIndexComposto($tabela, $nomeIndex, $colunas) {

            $sql = "ALTER TABLE {$tabela} ADD INDEX {$nomeIndex} ({$colunas})";
            $this->UbsPDO->exec($sql);
    
    }
    

}
?>
