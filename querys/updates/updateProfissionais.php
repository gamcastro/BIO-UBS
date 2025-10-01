<?php


      //-------ID -----------
        $id = $_POST['id']; 
      //---------------------


      /*-------campos do formulário 
      via post
      */
        $nome = $_POST['nome_completo']; // nome do profissional
        $data_nascimento = $_POST['data_nascimento'];
        $cpf = $_POST['cpf'];
        $cns_profissional = $_POST['cns_profissional'];
        $conselho = $_POST['conselho_classe'];
        $sexo = $_POST['sexo'];
        $perfil = $_POST['perfil'] ;
        $registro_conselho = $_POST['registro_conselho'];
        $estado_conselho = $_POST['estado_emissor_conselho'];

        //------------------ Dados para contato ----------------
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];

        //---------------------- Dados do Endereço ----------------
        $cep = $_POST['cep'];
        $logradouro = $_POST['logradouro'];
        $numero = $_POST['numero'];
        $bairro = $_POST['bairro'];
        $complemento = $_POST['complemento'];
        $municipio = $_POST['municipio'];
        $estado_endereco = $_POST['estado_endereco'];
        $ponto_referencia = $_POST['ponto_referencia'];
        //----------------------

        //--------------------- Dados de Acesso ----------------
        $senha = $_POST['senha_hash'];

      //------------------alterando os dados do profissional---------


    $tabela = 'cadastro_profissional'; //--- nome da tabela

    $colunasPermitidas = [
      'NOME_COMPLETO',
      'CPF',
      'CNS_PROFISSIONAL',
      'DATA_NASCIMENTO',
      'SEXO',
      'PERFIL',
      'EMAIL',
      'TELEFONE',
      'CONSELHO_CLASSE',
      'REGISTRO_CONSELHO',
      'ESTADO_EMISSOR_CONSELHO',
      'CEP',
      'ESTADO_ENDERECO',
      'MUNICIPIO',
      'BAIRRO',
      'LOGRADOURO',
      'NUMERO',
      'COMPLEMENTO',
      'PONTO_REFERENCIA',
      'SENHA_HASH'
    ];

    $objeto = new UbsCrudAll($tabela, $colunasPermitidas);

    $dados = [
      'NOME_COMPLETO'              => $nome,
      'CPF'                        => $cpf,
      'CNS_PROFISSIONAL'           => $cns_profissional,
      'DATA_NASCIMENTO'            => $data_nascimento,
      'SEXO'                       => $sexo,
      'PERFIL'                     => $perfil,
      'EMAIL'                      => $email,
      'TELEFONE'                   => $telefone,
      'CONSELHO_CLASSE'            => $conselho,
      'REGISTRO_CONSELHO'          => $registro_conselho,
      'ESTADO_EMISSOR_CONSELHO'    => $estado_conselho,
      'CEP'                        => $cep,
      'ESTADO_ENDERECO'            => $estado_endereco,
      'MUNICIPIO'                  => $municipio,
      'BAIRRO'                     => $bairro,
      'LOGRADOURO'                 => $logradouro,
      'NUMERO'                     => $numero,
      'COMPLEMENTO'                => $complemento,
      'PONTO_REFERENCIA'           => $ponto_referencia,
      'SENHA_HASH'                 => $senha
    ];


    $updateUbs = $objeto->atualizar($id, $dados); //---funcao atualizar com 2 parametros

//--------------------------------------------------------  

//----------mensagem de confirmacao-----------------------
/**/
  echo "<script>
  window.alert('Cadastro alterado com sucesso!');
  window.location='cadastroDeProfissionais.php'
  </script>";
  
//----------------------------------------------------------  

  die;//----- se entrar para o código aqui
