-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/09/2025 às 03:08
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bio_ubs`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cadastro_paciente`
--

CREATE TABLE `cadastro_paciente` (
  `ID` int(11) NOT NULL,
  `NOME` varchar(100) NOT NULL,
  `DATA_NASCIMENTO` date NOT NULL,
  `CPF` varchar(30) NOT NULL,
  `RG` varchar(20) NOT NULL,
  `UF_RG` varchar(2) NOT NULL,
  `SSP` varchar(20) NOT NULL,
  `TELEFONE_CELULAR` varchar(15) NOT NULL,
  `CRIADO_EM` timestamp NOT NULL DEFAULT current_timestamp(),
  `ATUALIZADO_EM` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cadastro_profissional`
--

CREATE TABLE `cadastro_profissional` (
  `ID` int(11) NOT NULL,
  `NOME_COMPLETO` varchar(255) NOT NULL,
  `CPF` varchar(14) DEFAULT NULL,
  `CNS_PROFISSIONAL` varchar(15) DEFAULT NULL,
  `DATA_NASCIMENTO` date DEFAULT NULL,
  `SEXO` enum('Feminino','Masculino','Outro') DEFAULT NULL,
  `EMAIL` varchar(255) NOT NULL,
  `TELEFONE` varchar(15) DEFAULT NULL,
  `CONSELHO_CLASSE` varchar(50) DEFAULT NULL,
  `REGISTRO_CONSELHO` varchar(50) DEFAULT NULL,
  `ESTADO_EMISSOR_CONSELHO` varchar(2) DEFAULT NULL,
  `CEP` varchar(9) DEFAULT NULL,
  `ESTADO_ENDERECO` varchar(2) DEFAULT NULL,
  `MUNICIPIO` varchar(100) DEFAULT NULL,
  `BAIRRO` varchar(100) DEFAULT NULL,
  `LOGRADOURO` varchar(255) DEFAULT NULL,
  `NUMERO` varchar(20) DEFAULT NULL,
  `COMPLEMENTO` varchar(100) DEFAULT NULL,
  `PONTO_REFERENCIA` text DEFAULT NULL,
  `SENHA_HASH` varchar(255) NOT NULL,
  `CRIADO_EM` timestamp NOT NULL DEFAULT current_timestamp(),
  `ATUALIZADO_EM` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cadastro_unidade`
--

CREATE TABLE `cadastro_unidade` (
  `ID` int(11) NOT NULL,
  `NOME_FANTASIA` varchar(255) NOT NULL,
  `RAZAO_SOCIAL` varchar(255) DEFAULT NULL,
  `CNES` varchar(7) NOT NULL,
  `CNPJ` varchar(18) DEFAULT NULL,
  `TELEFONE` varchar(15) DEFAULT NULL,
  `EMAIL` varchar(255) DEFAULT NULL,
  `CEP` varchar(9) DEFAULT NULL,
  `ESTADO` varchar(2) DEFAULT NULL,
  `MUNICIPIO` varchar(100) DEFAULT NULL,
  `BAIRRO` varchar(100) DEFAULT NULL,
  `LOGRADOURO` varchar(255) DEFAULT NULL,
  `NUMERO` varchar(20) DEFAULT NULL,
  `COMPLEMENTO` varchar(100) DEFAULT NULL,
  `CRIADO_EM` timestamp NOT NULL DEFAULT current_timestamp(),
  `ATUALIZADO_EM` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cadastro_paciente`
--
ALTER TABLE `cadastro_paciente`
  ADD PRIMARY KEY (`ID`);

--
-- Índices de tabela `cadastro_profissional`
--
ALTER TABLE `cadastro_profissional`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`),
  ADD UNIQUE KEY `CPF` (`CPF`),
  ADD UNIQUE KEY `CNS_PROFISSIONAL` (`CNS_PROFISSIONAL`);

--
-- Índices de tabela `cadastro_unidade`
--
ALTER TABLE `cadastro_unidade`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `CNES` (`CNES`),
  ADD UNIQUE KEY `CNPJ` (`CNPJ`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cadastro_paciente`
--
ALTER TABLE `cadastro_paciente`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cadastro_profissional`
--
ALTER TABLE `cadastro_profissional`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cadastro_unidade`
--
ALTER TABLE `cadastro_unidade`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
