<?php
class DataPrazo {

    private $data;    //----------Data inicial (YYYY-MM-DD)
    private $prazo;   //----------Prazo em dias (int)
    private $meses;   //----------Prazo em meses (int) 

    public function setData($e) {
        return $this->data = $e;
    }

    public function setDiasPrazo($p) {
        return $this->prazo = (int)$p;
    }
    
    public function setMesesPrazo($p) {
        return $this->meses = (int)$p;
    }

    /**
     * Calcula a data final somando $this->meses à data inicial,
     * respeitando o tamanho de cada mês.
     *
     * Regra de “fim de mês”:
     * - Se a data inicial está no último dia do mês, o resultado será o último dia do mês-alvo.
     * - Caso contrário, mantém o "número do dia" quando possível; se o mês-alvo não tiver esse dia,
     *   usa o último dia disponível do mês-alvo. Ex.: 30/01 + 1 mês -> 29/02 (ou 28/02 em ano não bissexto).
     */
    public function PrazoFinalMeses() {
        if (!$this->data) {
            throw new RuntimeException("Data inicial não definida. Use setData('YYYY-MM-DD').");
        }
        if (!isset($this->meses)) {
            throw new RuntimeException("Prazo em meses não definido. Use setMesesPrazo(int).");
        }

        $inicio = new DateTime($this->data);
        $diaInicial = (int)$inicio->format('d');
        $ultimoDiaInicio = (int)$inicio->format('t'); //-----------------------último dia do mês de início

        //---------------------------------------------Avança para o 1º dia do mês, soma os meses, então ajusta o dia
        $alvo = (clone $inicio)->modify('first day of this month');
        if ($this->meses >= 0) {
            $alvo->add(new DateInterval('P' . $this->meses . 'M'));
        } else {
            //---------------------------------------------------meses negativos
            $alvo->sub(new DateInterval('P' . abs($this->meses) . 'M'));
        }

        $ultimoDiaAlvo = (int)$alvo->format('t');

        //-----------------------------Se a data inicial era o último dia do mês, força último dia do mês-alvo
        if ($diaInicial === $ultimoDiaInicio) {
            $diaFinal = $ultimoDiaAlvo;
        } else {
            //----------------------------Mantém o dia quando possível; senão, usa o último dia do mês-alvo
            $diaFinal = min($diaInicial, $ultimoDiaAlvo);
        }

        //---------------------------------Define Y/m do alvo e ajusta o "diaFinal"
        $alvo->setDate(
            (int)$alvo->format('Y'),
            (int)$alvo->format('m'),
            $diaFinal
        );

        return $alvo->format('Y-m-d');
    }


    //---------------------------------Data final somando dias (YYYY-MM-DD)
    public function PrazoFinal() {
        return date(
            'Y-m-d',
            strtotime("+" . $this->prazo . " days", strtotime($this->data))
        );
    }

    //-----------------------------------Formata a data final (por dias) em 3 estilos PT-BR
    //-----------------------------------1) 00/00/0000 | 2) 00/jan/0000 | 3) 00 de janeiro de 0000
    public function formatarData($formato = 1) {
        $timestamp = strtotime("+" . $this->prazo . " days", strtotime($this->data));

        $meses_abrev = [
            1=>'jan',2=>'fev',3=>'mar',4=>'abr',5=>'mai',6=>'jun',
            7=>'jul',8=>'ago',9=>'set',10=>'out',11=>'nov',12=>'dez'
        ];
        $meses_extenso = [
            1=>'janeiro',2=>'fevereiro',3=>'março',4=>'abril',5=>'maio',6=>'junho',
            7=>'julho',8=>'agosto',9=>'setembro',10=>'outubro',11=>'novembro',12=>'dezembro'
        ];

        $dia = date("d", $timestamp);
        $mes_num = (int)date("m", $timestamp);
        $ano = date("Y", $timestamp);

        switch ($formato) {
            case 1: return date("d/m/Y", $timestamp);
            case 2: return $dia . "/" . $meses_abrev[$mes_num] . "/" . $ano;
            case 3: return $dia . " de " . $meses_extenso[$mes_num] . " de " . $ano;
            default: return date("Y-m-d", $timestamp);
        }
    }


    private function textoUnidade(int $qtd, string $singular, string $plural): string {
        return $qtd . ' ' . ($qtd === 1 ? $singular : $plural);
    }

    private function juntarPartes(array $partes): string {
        $partes = array_values(array_filter($partes, fn($p) => $p !== '' && $p !== null));
        $n = count($partes);
        if ($n === 0) return '0 dias';
        if ($n === 1) return $partes[0];
        return implode(', ', array_slice($partes, 0, $n - 1)) . ' e ' . $partes[$n - 1];
    }

    public function diferencaMesesDias(): string {
        $inicio = new DateTime($this->data);
        $fim = new DateTime($this->PrazoFinal());
        $diff = $inicio->diff($fim);

        $totalMeses = ($diff->y * 12) + $diff->m;
        $partes = [];

        if ($totalMeses > 0) {
            $partes[] = $this->textoUnidade($totalMeses, 'mês', 'meses');
        }
        if ($diff->d > 0 || empty($partes)) {
            $partes[] = $this->textoUnidade($diff->d, 'dia', 'dias');
        }

        return $this->juntarPartes($partes);
    }

    public function diferencaAnosMesesDias(): string {
        $inicio = new DateTime($this->data);
        $fim = new DateTime($this->PrazoFinal());
        $diff = $inicio->diff($fim);

        $partes = [];
        if ($diff->y > 0) $partes[] = $this->textoUnidade($diff->y, 'ano', 'anos');
        if ($diff->m > 0) $partes[] = $this->textoUnidade($diff->m, 'mês', 'meses');
        if ($diff->d > 0 || empty($partes)) {
            $partes[] = $this->textoUnidade($diff->d, 'dia', 'dias');
        }

        return $this->juntarPartes($partes);
    }


    public function formatarDataMeses($formato = 1) {
        
        $dataFinal = $this->PrazoFinalMeses(); //-------"Y-m-d" (pode lançar exceção se faltar data/meses)
        $timestamp = strtotime($dataFinal);

        $meses_abrev = [
            1=>'jan',2=>'fev',3=>'mar',4=>'abr',5=>'mai',6=>'jun',
            7=>'jul',8=>'ago',9=>'set',10=>'out',11=>'nov',12=>'dez'
        ];
        $meses_extenso = [
            1=>'janeiro',2=>'fevereiro',3=>'março',4=>'abril',5=>'maio',6=>'junho',
            7=>'julho',8=>'agosto',9=>'setembro',10=>'outubro',11=>'novembro',12=>'dezembro'
        ];

        $dia = date("d", $timestamp);
        $mes_num = (int)date("m", $timestamp);
        $ano = date("Y", $timestamp);

        switch ($formato) {
            case 1: return date("d/m/Y", $timestamp);
            case 2: return $dia . "/" . $meses_abrev[$mes_num] . "/" . $ano;
            case 3: return $dia . " de " . $meses_extenso[$mes_num] . " de " . $ano;
            default: return date("Y-m-d", $timestamp);
        }
    }


   
}
