<?php

require_once ('../DataPrazo.php');

$dataInicial = '2025-01-31'; //------------data informada


//---------------------Prazo informando a quantidade de dias:

$p = new DataPrazo();       //-------------criando objeto
$p->setData($dataInicial);  //-------------setando a data
$p->setDiasPrazo(435);      //----!!! IMPORTQANTE informe a quantidade de dias 


//-------------------MOSTRANDO PRO USUÁRIO EM DIFERENTE FORMATOS--------------------
echo $p->formatarData(1);         //------ex.: "11/04/2026"
echo "<hr>";
echo $p->formatarData(2);         //------ex.: "11/abr/2026"
echo "<hr>";
echo $p->formatarData(3);         //------ex.: "11 de abril de 2026"
echo "<hr>";
echo $p->diferencaMesesDias();    //------"14 meses e 11 dias"
echo "<hr>";
echo $p->diferencaAnosMesesDias();//------"1 ano, 2 meses e 11 dias"

//------------------------------------------------------------------------------------



echo "<hr>";
echo "<hr>";



//---------------------Prazo informando a quantidade de meses:
$pm = new DataPrazo();

$pm->setMesesPrazo(1);       //-------------!!!IMPORTANTE setando a quantidade de meses
$pm->setData($dataInicial);
echo $pm->PrazoFinalMeses(); //-------------"2025-02-28" (ou "2025-02-29" em ano bissexto)
echo "<hr>";

//----------------------Outro exemplo (mantém dia quando possível):
$dataInicial = '2025-03-30';
$pm->setData($dataInicial);
$pm->setMesesPrazo(1);
echo $pm->PrazoFinalMeses(); //-------------"2025-04-30"
echo "<hr>";
//----------------------Meses negativos (voltar meses):
$pm->setData('2025-03-31');
$pm->setMesesPrazo(-1);
echo $pm->PrazoFinalMeses(); //--------------"2025-02-28" (ou "2025-02-29")

echo "<hr>";

//-----------------FORMATANDO A ULTIMA DATA COM A FUNCAO formatarDataMeses();

echo $pm->formatarDataMeses(1);       //-----------"28/02/2025"
echo "<hr>";
echo $pm->formatarDataMeses(2);       //-----------"28/fev/2025"
echo "<hr>";
echo $pm->formatarDataMeses(3);       //-----------"28 de fevereiro de 2025"

echo "<hr>";
