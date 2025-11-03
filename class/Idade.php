<?php
namespace BioUBS; 

use DateTime;
use InvalidArgumentException;
use PDO;
use RuntimeException;

class Idade{

	private $data;

	
	public function setIdade($e){
        return $this->data = $e;
    }

			 public function dataBr(){
				return implode('/', array_reverse(explode('-', $this->data))); //-------'2025-09-06' -> '06/09/2025'
			}

	
			public function IdadeAnos(){

				$date = new DateTime($this->data);
				$intervalo = $date->diff( new DateTime( date('Y-m-d') ) );
				$idade = $intervalo->format( '%Y' );
				
				return $idade;
			}	


			public function IdadeMeses(){

				$date = new DateTime($this->data);
				$intervalo = $date->diff( new DateTime( date('Y-m-d') ) );
				$idade = $intervalo->format( '%m' );
				
				return $idade;

			}	


			public function IdadeDias(){

				$date = new DateTime($this->data);
				$intervalo = $date->diff( new DateTime( date('Y-m-d') ) );
				$idade = $intervalo->format( '%d' );
				
				return $idade;

			}

			//-------- verificando se numero no singular ou plural
			private function textoUnidade(int $qtd, string $singular, string $plural): string
			{
				return $qtd . ' ' . ($qtd === 1 ? $singular : $plural);
			}

			private function juntarPartes(array $partes): string
				{
					//--------removendo partes vazias
					$filtradas = array();
					foreach ($partes as $p) {
						if ($p !== '' && $p !== null) {
							$filtradas[] = $p;
						}
					}
					$n = count($filtradas);
					if ($n === 0) return '0 dias';
					if ($n === 1) return $filtradas[0];
					return implode(', ', array_slice($filtradas, 0, $n - 1)) . ' e ' . $filtradas[$n - 1];
				}


			public function IdadeCompleta(string $retorno = 'string') {
				if (!$this->data) {
					throw new RuntimeException("Data base não definida. Use setData('YYYY-MM-DD').");
				}
				$nasc = new DateTime($this->data);
				$hoje = new DateTime('today');
				$diff = $nasc->diff($hoje);

				if ($retorno === 'array') {
					return ['anos' => $diff->y, 'meses' => $diff->m, 'dias' => $diff->d];
				}

				//--------string amigável com pluralização e omissão de zeros
				$partes = [];
				if ($diff->y > 0) $partes[] = $this->textoUnidade($diff->y, 'ano', 'anos');
				if ($diff->m > 0) $partes[] = $this->textoUnidade($diff->m, 'mês', 'meses');
				if ($diff->d > 0 || empty($partes)) $partes[] = $this->textoUnidade($diff->d, 'dia', 'dias');

				return $this->juntarPartes($partes);
			}


			public function IdadeEntreDatas(string $dataA, string $dataB, string $retorno = 'string', bool $absoluto = true) {
				$d1 = new DateTime($dataA);
				$d2 = new DateTime($dataB);

				if ($absoluto && $d1 > $d2) {
					[$d1, $d2] = [$d2, $d1]; //----garantindo ordem cronológica
				}

				$diff = $d1->diff($d2);

				if ($retorno === 'array') {
					return ['anos' => $diff->y, 'meses' => $diff->m, 'dias' => $diff->d];
				}

				$partes = [];
				if ($diff->y > 0) $partes[] = $this->textoUnidade($diff->y, 'ano', 'anos');
				if ($diff->m > 0) $partes[] = $this->textoUnidade($diff->m, 'mês', 'meses');
				if ($diff->d > 0 || empty($partes)) $partes[] = $this->textoUnidade($diff->d, 'dia', 'dias');

				return $this->juntarPartes($partes);
			}

			
			//-------------FUNÇÕES PRA TRATAR DE ANIVERSÁRIOS---------------------

			//-------!!!IMPORTANTE regras-----------------------
			//-----------respeitando fins de mês (ex.: 31 -> 30 quando o 
			//-----------mês/ano não tem 31; 29/02 -> 28/02 se não for bissexto).
			private function aniversarioNoAno(int $ano): DateTime {
				if (!$this->data) {
					throw new RuntimeException("Data base não definida. Use setData('YYYY-MM-DD').");
				}
				$nasc = new DateTime($this->data);
				$mes  = (int)$nasc->format('m');
				$dia  = (int)$nasc->format('d');

				//-----------------Começa no 1º dia do mês para descobrir o último dia daquele mês/ano
				$base = new DateTime();
				$base->setDate($ano, $mes, 1);
				$ultimoDia = (int)$base->format('t');

				//-----------------Ajusta o dia caso o mês/ano não possua aquele dia (ex.: 31 ou 29/02)
				$diaAjustado = min($dia, $ultimoDia);
				$base->setDate($ano, $mes, $diaAjustado);
				return $base;
			}




			/****************************************************************** */
			//------------CAMPOS PARA ANIVERSARIANTE  DO MES-----------------
			private PDO $pdo;          	//------conexão PDO
			private string $table;     	//------nome da tabela (ex.: 'paciente')
			private string $campoTable; //------nome do campo de data de nascimento (ex.: 'dt_nasc')
			private int $mes;          	//------mês alvo (1-12)
			//-------------------------------------------------------------------

			public function setPDO(PDO $pdo) {
				$this->pdo = $pdo;
				return $this;
			}

			public function setTable(string $t) {
				$this->validateIdentifier($t);
				$this->table = $t;
				return $this;
			}

			public function setCampoTable(string $c) {
				$this->validateIdentifier($c);
				$this->campoTable = $c;
				return $this;
			}

			public function setMes(int $m) {
				if ($m < 1 || $m > 12) {
					throw new InvalidArgumentException("Mês inválido: use 1 a 12.");
				}
				$this->mes = $m;
				return $this;
			}

			//-----garantindo mais segurança (tabela/campo)------------------------
			private function validateIdentifier(string $id): void {
				//---------Permite apenas letras, números e underscore (evita injection em identificadores)
				if (!preg_match('/^[A-Za-z0-9_]+$/', $id)) {
					throw new InvalidArgumentException("Identificador inválido: {$id}");
				}
			}

			//***************** !!!IMPORTANTE: buscar aniversariantes do mês ******/
			public function buscarAniversariantesDoMes(): array {
				if (!isset($this->pdo)) {
					throw new RuntimeException("PDO não definido. Use setPDO(\$pdo).");
				}
				if (!isset($this->table)) {
					throw new RuntimeException("Tabela não definida. Use setTable('minha_tabela').");
				}
				if (!isset($this->campoTable)) {
					throw new RuntimeException("Campo de data não definido. Use setCampoTable('dt_nasc').");
				}

				//-----------------------------Mês padrão = mês atual, se não tiver sido setado
				$mes = isset($this->mes) ? $this->mes : (int)date('n');

				//------------------------------Montando SQL 
				$t = "`{$this->table}`";
				$c = "`{$this->campoTable}`";

				$sql = "
					SELECT 
						{$t}.*,
						DAY({$c})           AS dia,
						DATE_FORMAT({$c}, '%d/%m') AS dia_mes,
						(YEAR(CURDATE()) - YEAR({$c})) AS idade_este_ano
					FROM {$t}
					WHERE {$c} IS NOT NULL
					AND MONTH({$c}) = :mes
					ORDER BY DAY({$c}) ASC
				";

				$stmt = $this->pdo->prepare($sql);
				$stmt->bindValue(':mes', $mes, PDO::PARAM_INT);
				$stmt->execute();

				return $stmt->fetchAll(PDO::FETCH_ASSOC);
			}

	
}


		