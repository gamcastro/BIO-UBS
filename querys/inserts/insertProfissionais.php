<?php


      //-------campos via post
        $nome = $_POST['nome']; //---------o name no formulario é livre mas deve ser recebido aqui
        
        $cpf = $_POST['cpf'];

        $data_nascimento = $_POST['data_nascimento'];

        $cns = $_POST['cns'];

        $conselho = $_POST['conselho'];

        $registro_conselho = $_POST['registro_conselho'];
       
        $estado = $_POST['uf_cns'];
      //----------------------

//------------------inserindo na tabela profissionais---------

    $tabela = 'cadastro_profissional'; //----tabela para a query

    $colunasPermitidas = 
    [
      'NOME_COMPLETO'  ,
      'CPF',
      'DATA_NASCIMENTO',
      'CNS_PROFISSIONAL',
        'CONSELHO_CLASSE',
        'REGISTRO_CONSELHO',
        'ESTADO_OMISSOR_CONSELHO'

    ]; //--nao informar ID chave primaria

    $objeto = new UbsCrudAll($tabela, $colunasPermitidas); //---receberá a tabela e as colunas

    $dados = ([
        'NOME'              => $nome,
        'CPF'               => $cpf,
        'DATA_NASCIMENTO'   => $data_nascimento,
        'CNS_PROFISSIONAL'  => $cns,
        'CONSELHO_CLASSE'   => $conselho,
        'REGISTRO_CONSELHO' => $registro_conselho, 
        'ESTADO_OMISSOR_CONSELHO' => $estado
        
    ]);

    $isertUbs = $objeto->inserir($dados);

//--------------------------------------------------------  

//----------mensagem de confirmacao-----------------------
/**/
  echo "<script>
  window.alert('Cadastro efetuado com sucesso!');
  window.location='cadastroDeProfissionais.php'
  </script>";
  
//----------------------------------------------------------  

  die;//----- se entrar para o código aqui
