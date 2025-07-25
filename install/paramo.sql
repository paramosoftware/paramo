/*M!999999\- enable the sandbox mode */
-- MariaDB dump 10.19  Distrib 10.11.13-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: paramo
-- ------------------------------------------------------
-- Server version	10.11.13-MariaDB-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acervo`
--

DROP TABLE IF EXISTS `acervo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `acervo` (
  `codigo` int(11) NOT NULL,
  `instituicao_codigo` int(11) DEFAULT NULL,
  `entidade_codigo` int(11) DEFAULT NULL,
  `setor_sistema_codigo` int(11) DEFAULT NULL,
  `nome` varchar(250) NOT NULL,
  `identificador` varchar(250) DEFAULT NULL,
  `tipo_codigo` int(11) DEFAULT NULL,
  `tipo_arquivo_codigo` int(11) DEFAULT NULL,
  `sigla` varchar(10) DEFAULT NULL,
  `situacao_codigo` int(11) DEFAULT NULL,
  `historico` text DEFAULT NULL,
  `procedencia` text DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `estado_organizacao_codigo` int(11) DEFAULT NULL,
  `data_inicial` date DEFAULT NULL,
  `data_final` date DEFAULT NULL,
  `localidade_codigo` int(11) DEFAULT NULL,
  `quantidade_itens` int(11) DEFAULT NULL,
  `tipos_materiais` text DEFAULT NULL,
  `natureza_codigo` int(11) DEFAULT NULL,
  `cor` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `Entidade_Codigo` (`entidade_codigo`),
  KEY `Estado_Organizacao_Codigo` (`estado_organizacao_codigo`),
  KEY `Instituicao_Codigo` (`instituicao_codigo`),
  KEY `acervo_FK` (`tipo_codigo`),
  KEY `acervo_FK_1` (`tipo_arquivo_codigo`),
  KEY `acervo_FK_2` (`localidade_codigo`),
  KEY `acervo_FK_3` (`setor_sistema_codigo`),
  KEY `acervo_FK_4` (`natureza_codigo`),
  CONSTRAINT `acervo_FK_1` FOREIGN KEY (`tipo_arquivo_codigo`) REFERENCES `tipo_arquivo` (`codigo`),
  CONSTRAINT `acervo_FK_2` FOREIGN KEY (`localidade_codigo`) REFERENCES `localidade` (`codigo`),
  CONSTRAINT `acervo_FK_3` FOREIGN KEY (`setor_sistema_codigo`) REFERENCES `setor_sistema` (`codigo`),
  CONSTRAINT `acervo_FK_4` FOREIGN KEY (`natureza_codigo`) REFERENCES `tipo_acervo` (`codigo`),
  CONSTRAINT `acervo_ibfk_1` FOREIGN KEY (`entidade_codigo`) REFERENCES `entidade` (`codigo`),
  CONSTRAINT `acervo_ibfk_2` FOREIGN KEY (`estado_organizacao_codigo`) REFERENCES `estado_organizacao_acervo` (`codigo`),
  CONSTRAINT `acervo_ibfk_3` FOREIGN KEY (`instituicao_codigo`) REFERENCES `instituicao` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acervo`
--

LOCK TABLES `acervo` WRITE;
/*!40000 ALTER TABLE `acervo` DISABLE KEYS */;
/*!40000 ALTER TABLE `acervo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acervo_acervo`
--

DROP TABLE IF EXISTS `acervo_acervo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `acervo_acervo` (
  `acervo_1_codigo` int(11) NOT NULL,
  `acervo_2_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acervo_acervo`
--

LOCK TABLES `acervo_acervo` WRITE;
/*!40000 ALTER TABLE `acervo_acervo` DISABLE KEYS */;
/*!40000 ALTER TABLE `acervo_acervo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acervo_assunto`
--

DROP TABLE IF EXISTS `acervo_assunto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `acervo_assunto` (
  `assunto_codigo` int(11) NOT NULL,
  `acervo_codigo` int(11) NOT NULL,
  PRIMARY KEY (`assunto_codigo`,`acervo_codigo`),
  KEY `acervo_assunto_FK` (`acervo_codigo`),
  CONSTRAINT `acervo_assunto_FK` FOREIGN KEY (`acervo_codigo`) REFERENCES `acervo` (`codigo`),
  CONSTRAINT `acervo_assunto_FK_1` FOREIGN KEY (`assunto_codigo`) REFERENCES `assunto` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acervo_assunto`
--

LOCK TABLES `acervo_assunto` WRITE;
/*!40000 ALTER TABLE `acervo_assunto` DISABLE KEYS */;
/*!40000 ALTER TABLE `acervo_assunto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acervo_contexto`
--

DROP TABLE IF EXISTS `acervo_contexto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `acervo_contexto` (
  `acervo_codigo` int(11) NOT NULL,
  `contexto_codigo` int(11) NOT NULL,
  PRIMARY KEY (`acervo_codigo`,`contexto_codigo`),
  KEY `palavra_chave_codigo` (`contexto_codigo`) USING BTREE,
  KEY `item_acervo_codigo` (`acervo_codigo`) USING BTREE,
  CONSTRAINT `acervo_contexto_FK` FOREIGN KEY (`acervo_codigo`) REFERENCES `acervo` (`codigo`),
  CONSTRAINT `acervo_contexto_FK_1` FOREIGN KEY (`contexto_codigo`) REFERENCES `contexto` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acervo_contexto`
--

LOCK TABLES `acervo_contexto` WRITE;
/*!40000 ALTER TABLE `acervo_contexto` DISABLE KEYS */;
/*!40000 ALTER TABLE `acervo_contexto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acervo_entidade`
--

DROP TABLE IF EXISTS `acervo_entidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `acervo_entidade` (
  `acervo_codigo` int(11) NOT NULL,
  `entidade_codigo` int(11) NOT NULL,
  PRIMARY KEY (`entidade_codigo`,`acervo_codigo`),
  KEY `acervo_entidade_FK` (`acervo_codigo`),
  CONSTRAINT `acervo_entidade_FK` FOREIGN KEY (`acervo_codigo`) REFERENCES `acervo` (`codigo`),
  CONSTRAINT `acervo_entidade_FK_1` FOREIGN KEY (`entidade_codigo`) REFERENCES `entidade` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acervo_entidade`
--

LOCK TABLES `acervo_entidade` WRITE;
/*!40000 ALTER TABLE `acervo_entidade` DISABLE KEYS */;
/*!40000 ALTER TABLE `acervo_entidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acervo_tipo_material`
--

DROP TABLE IF EXISTS `acervo_tipo_material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `acervo_tipo_material` (
  `acervo_codigo` int(11) NOT NULL,
  `tipo_material_codigo` int(11) NOT NULL,
  PRIMARY KEY (`tipo_material_codigo`,`acervo_codigo`),
  KEY `acervo_tipo_material_FK` (`acervo_codigo`),
  CONSTRAINT `acervo_tipo_material_FK` FOREIGN KEY (`acervo_codigo`) REFERENCES `acervo` (`codigo`),
  CONSTRAINT `acervo_tipo_material_FK_1` FOREIGN KEY (`tipo_material_codigo`) REFERENCES `tipo_material` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acervo_tipo_material`
--

LOCK TABLES `acervo_tipo_material` WRITE;
/*!40000 ALTER TABLE `acervo_tipo_material` DISABLE KEYS */;
/*!40000 ALTER TABLE `acervo_tipo_material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agrupamento`
--

DROP TABLE IF EXISTS `agrupamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `agrupamento` (
  `codigo` int(11) NOT NULL,
  `id` varchar(1000) DEFAULT NULL,
  `codigo_referencia` varchar(250) DEFAULT NULL,
  `acervo_codigo` int(11) DEFAULT NULL,
  `agrupamento_superior_codigo` int(11) DEFAULT NULL,
  `data_inicial` date DEFAULT NULL,
  `data_final` date DEFAULT NULL,
  `data_presumida` tinyint(1) DEFAULT NULL,
  `sem_data` tinyint(1) DEFAULT NULL,
  `identificador` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `Agrupamento_Superior_Codigo` (`agrupamento_superior_codigo`),
  KEY `Acervo_Codigo` (`acervo_codigo`),
  CONSTRAINT `agrupamento_ibfk_1` FOREIGN KEY (`agrupamento_superior_codigo`) REFERENCES `agrupamento` (`codigo`),
  CONSTRAINT `agrupamento_ibfk_2` FOREIGN KEY (`acervo_codigo`) REFERENCES `acervo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agrupamento`
--

LOCK TABLES `agrupamento` WRITE;
/*!40000 ALTER TABLE `agrupamento` DISABLE KEYS */;
/*!40000 ALTER TABLE `agrupamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agrupamento_assunto`
--

DROP TABLE IF EXISTS `agrupamento_assunto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `agrupamento_assunto` (
  `agrupamento_codigo` int(11) NOT NULL,
  `assunto_codigo` int(11) NOT NULL,
  PRIMARY KEY (`agrupamento_codigo`,`assunto_codigo`),
  KEY `agrupamento_assunto_FK_1` (`assunto_codigo`),
  CONSTRAINT `agrupamento_assunto_FK` FOREIGN KEY (`agrupamento_codigo`) REFERENCES `agrupamento` (`codigo`),
  CONSTRAINT `agrupamento_assunto_FK_1` FOREIGN KEY (`assunto_codigo`) REFERENCES `assunto` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agrupamento_assunto`
--

LOCK TABLES `agrupamento_assunto` WRITE;
/*!40000 ALTER TABLE `agrupamento_assunto` DISABLE KEYS */;
/*!40000 ALTER TABLE `agrupamento_assunto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agrupamento_dados_textuais`
--

DROP TABLE IF EXISTS `agrupamento_dados_textuais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `agrupamento_dados_textuais` (
  `agrupamento_codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  `idioma_codigo` int(11) NOT NULL,
  PRIMARY KEY (`agrupamento_codigo`,`idioma_codigo`),
  KEY `idioma_codigo` (`idioma_codigo`),
  CONSTRAINT `agrupamento_dados_textuais_ibfk_1` FOREIGN KEY (`agrupamento_codigo`) REFERENCES `agrupamento` (`codigo`),
  CONSTRAINT `agrupamento_dados_textuais_ibfk_2` FOREIGN KEY (`idioma_codigo`) REFERENCES `idioma` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agrupamento_dados_textuais`
--

LOCK TABLES `agrupamento_dados_textuais` WRITE;
/*!40000 ALTER TABLE `agrupamento_dados_textuais` DISABLE KEYS */;
/*!40000 ALTER TABLE `agrupamento_dados_textuais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agrupamento_entidade`
--

DROP TABLE IF EXISTS `agrupamento_entidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `agrupamento_entidade` (
  `agrupamento_codigo` int(11) NOT NULL,
  `entidade_codigo` int(11) NOT NULL,
  PRIMARY KEY (`agrupamento_codigo`,`entidade_codigo`),
  KEY `agrupamento_entidade_FK_1` (`entidade_codigo`),
  CONSTRAINT `agrupamento_entidade_FK` FOREIGN KEY (`agrupamento_codigo`) REFERENCES `agrupamento` (`codigo`),
  CONSTRAINT `agrupamento_entidade_FK_1` FOREIGN KEY (`entidade_codigo`) REFERENCES `entidade` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agrupamento_entidade`
--

LOCK TABLES `agrupamento_entidade` WRITE;
/*!40000 ALTER TABLE `agrupamento_entidade` DISABLE KEYS */;
/*!40000 ALTER TABLE `agrupamento_entidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `area_conhecimento`
--

DROP TABLE IF EXISTS `area_conhecimento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `area_conhecimento` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `area_conhecimento`
--

LOCK TABLES `area_conhecimento` WRITE;
/*!40000 ALTER TABLE `area_conhecimento` DISABLE KEYS */;
/*!40000 ALTER TABLE `area_conhecimento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assunto`
--

DROP TABLE IF EXISTS `assunto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `assunto` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assunto`
--

LOCK TABLES `assunto` WRITE;
/*!40000 ALTER TABLE `assunto` DISABLE KEYS */;
/*!40000 ALTER TABLE `assunto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `atividade_geradora`
--

DROP TABLE IF EXISTS `atividade_geradora`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `atividade_geradora` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `atividade_geradora`
--

LOCK TABLES `atividade_geradora` WRITE;
/*!40000 ALTER TABLE `atividade_geradora` DISABLE KEYS */;
/*!40000 ALTER TABLE `atividade_geradora` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campo_sistema`
--

DROP TABLE IF EXISTS `campo_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `campo_sistema` (
  `codigo` int(11) NOT NULL,
  `recurso_sistema_codigo` int(11) DEFAULT NULL,
  `tipo_codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `tabela_intermediaria` varchar(50) DEFAULT NULL,
  `objeto_chave_estrangeira` varchar(50) DEFAULT NULL,
  `tipo_campo_ui_codigo` int(11) DEFAULT NULL,
  `label_ui` varchar(100) DEFAULT NULL,
  `foco_ui` tinyint(1) DEFAULT NULL,
  `campo_visualizacao_codigo` int(11) DEFAULT NULL,
  `expressao_visualizacao` varchar(200) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `identificador_recurso_sistema` tinyint(1) DEFAULT NULL,
  `obrigatorio` tinyint(1) DEFAULT NULL,
  `campo_sistema_superior_codigo` int(11) DEFAULT NULL,
  `exibir_lista_agrupadores` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `campo_sistema_FK` (`campo_sistema_superior_codigo`),
  KEY `campo_sistema_recurso_sistema_codigo_fk` (`recurso_sistema_codigo`),
  KEY `campo_sistema_tipo_campo_sistema_codigo_fk` (`tipo_codigo`),
  CONSTRAINT `campo_sistema_FK` FOREIGN KEY (`campo_sistema_superior_codigo`) REFERENCES `campo_sistema` (`codigo`),
  CONSTRAINT `campo_sistema_recurso_sistema_codigo_fk` FOREIGN KEY (`recurso_sistema_codigo`) REFERENCES `recurso_sistema` (`codigo`),
  CONSTRAINT `campo_sistema_tipo_campo_sistema_codigo_fk` FOREIGN KEY (`tipo_codigo`) REFERENCES `tipo_campo_sistema` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campo_sistema`
--

LOCK TABLES `campo_sistema` WRITE;
/*!40000 ALTER TABLE `campo_sistema` DISABLE KEYS */;
INSERT INTO `campo_sistema` VALUES
(1,1,1,'item_acervo_identificador','Identificador',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,1,0,NULL,1),
(3,5,1,'localidade_nome','Nome',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,1,NULL,NULL,1),
(4,1,5,'item_acervo_localidade_codigo','Local',NULL,'5',NULL,NULL,0,NULL,NULL,NULL,0,0,NULL,1),
(5,3,1,'instituicao_nome','Nome',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,1,NULL,NULL,1),
(6,67,1,'acervo_nome','Nome',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,1,0,NULL,1),
(10,7,1,'especie_documental_dados_textuais_0_especie_documental_nome','Nome',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,1,0,NULL,1),
(11,8,1,'tipo_documental_nome','Nome',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,1,NULL,NULL,1),
(12,8,5,'tipo_documental_especie_documental_codigo','Espécie documental',NULL,'7',NULL,NULL,0,NULL,NULL,NULL,0,NULL,NULL,1),
(13,1,5,'documento_formato_codigo','Formato',NULL,'9',NULL,NULL,0,NULL,NULL,NULL,0,0,NULL,1),
(15,31,1,'idioma_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,1),
(16,1,5,'item_acervo_idioma_codigo','Idioma',NULL,'31',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(17,1,1,'item_acervo_dados_textuais_0_item_acervo_observacoes','Notas',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(25,9,1,'formato_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,1),
(27,1,5,'item_acervo_suporte_codigo','Suporte',NULL,'10',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(29,10,1,'suporte_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,1),
(35,1,5,'documento_especie_documental_codigo','Espécie documental',NULL,'7',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(36,1,6,'item_acervo_estado_conservacao','Conservação',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(37,1,5,'item_acervo_estado_conservacao_codigo','Estado de conservação',NULL,'81',NULL,NULL,NULL,NULL,NULL,NULL,0,0,36,0),
(38,1,5,'item_acervo_contexto_codigo','Contexto',NULL,'40',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(39,1,5,'item_acervo_assunto_codigo','Assunto',NULL,'60',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(40,1,4,'item_acervo_publicado_online','Publicado online',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),
(42,1,5,'item_acervo_autoria_codigo','Entidades (autoria, agentes e autoridades)',NULL,'2',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(43,69,1,'tecnica_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(45,1,5,'documento_agrupamento_codigo','Grupo e subgrupo',NULL,'79',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,0),
(48,82,1,'cromia_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(49,1,5,'documento_cromia_codigo','Cromia',NULL,'82',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(50,79,1,'agrupamento_dados_textuais_0_agrupamento_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(51,79,5,'agrupamento_agrupamento_superior_codigo','Agrupamento superior',NULL,'79',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(52,79,5,'agrupamento_acervo_codigo','Acervo',NULL,'67',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,1),
(53,79,2,'agrupamento_codigo','Código',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(56,1,3,'item_acervo_data','Data',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,0,0,NULL,1),
(63,16,1,'tipo_dimensao_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,1),
(64,17,1,'unidade_medida_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,1),
(78,25,3,'item_acervo_data','Data',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,0,0,NULL,1),
(88,60,1,'assunto_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(108,15,1,'material_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(119,2,1,'entidade_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,1),
(126,1,1,'item_acervo_dados_textuais_0_item_acervo_titulo','Título',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(139,42,1,'palavra_chave_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(145,1,5,'item_acervo_unidade_armazenamento_codigo','Unidade de armazenamento',NULL,'12',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(146,12,1,'unidade_armazenamento_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,1),
(149,12,5,'unidade_armazenamento_unidade_armazenamento_superior_codigo','Unidade de armazenamento superior',NULL,'12',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,1),
(150,12,5,'unidade_armazenamento_instituicao_codigo','Entidade custodiadora',NULL,'3',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,1),
(151,12,2,'unidade_armazenamento_codigo','Código',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,1),
(152,1,5,'documento_genero_documental_codigo','Gênero documental',NULL,'6',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(153,6,1,'genero_documental_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(154,25,5,'item_acervo_instituicao_codigo','Entidade custodiadora',NULL,'3',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(155,25,1,'item_acervo_identificador','Identificador',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,0),
(156,25,1,'livro_classificacao','Classificação',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,0),
(157,25,1,'item_acervo_dados_textuais_0_item_acervo_titulo','Título',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,0),
(158,25,1,'item_acervo_dados_textuais_0_item_acervo_subtitulo','Subtítulo',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,0),
(159,25,5,'item_acervo_entidade_codigo','Autoria',NULL,'2',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(160,25,5,'livro_genero_textual_codigo','Gênero textual',NULL,'58',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(161,58,1,'genero_textual_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(162,25,5,'livro_area_conhecimento_codigo','Área do conhecimento',NULL,'59',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(163,59,1,'area_conhecimento_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(164,25,5,'item_acervo_localidade_codigo','Local',NULL,'5',NULL,NULL,0,NULL,NULL,NULL,0,0,NULL,1),
(165,25,5,'item_acervo_idioma_codigo','Idioma',NULL,'31',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(166,25,5,'item_acervo_palavra_chave_codigo','Palavra-chave',NULL,'42',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(167,25,5,'item_acervo_unidade_armazenamento_codigo','Unidade de armazenamento',NULL,'12',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(168,25,5,'livro_editora_codigo','Editora',NULL,'35',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,0),
(169,35,1,'entidade_codigo_0_entidade_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(170,35,2,'entidade_codigo','Código',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(171,65,5,'item_acervo_instituicao_codigo','Entidade custodiadora',NULL,'3',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(172,65,1,'item_acervo_identificador','Identificador',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(173,65,1,'item_acervo_dados_textuais_0_item_acervo_titulo','Título',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(174,65,5,'item_acervo_entidade_entrevistado_codigo','Entidades (entrevistado e entrevistador)',NULL,'2',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(175,65,5,'entrevista_projeto_codigo','Projeto',NULL,'70',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(176,65,1,'entrevista_circulo','Evento',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(177,65,3,'item_acervo_data','Data',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(178,47,1,'tipo_entrevista_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(179,48,1,'formato_entrevista_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(180,40,1,'contexto_dados_textuais_0_contexto_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(181,15,1,'material_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(182,69,1,'tecnica_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(183,76,1,'tipo_material_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(184,25,5,'livro_tipo_material_codigo','Tipo de material',NULL,'76',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(185,65,5,'entrevista_formato_entrevista_codigo','Formato da entrevista',NULL,'48',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(186,65,5,'entrevista_tipo_entrevista_codigo','Tipo da entrevista',NULL,'47',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(187,65,4,'entrevista_transcrito','Trascrito',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(188,14,5,'item_acervo_unidade_armazenamento_codigo','Unidade de armazenamento',NULL,'12',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(189,65,5,'item_acervo_contexto_codigo','Contexto',NULL,'40',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(190,14,5,'item_acervo_contexto_codigo','Contexto',NULL,'40',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(191,25,4,'item_acervo_publicado_online','Publicado online',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(192,65,4,'item_acervo_publicado_online','Publicado online',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(193,14,4,'item_acervo_publicado_online','Publicado online',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(194,22,1,'tipo_objeto_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(195,14,1,'item_acervo_identificador','Identificador',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(196,14,1,'item_acervo_dados_textuais_0_item_acervo_titulo','Título',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1),
(197,14,5,'item_acervo_autoria_codigo','Entidades (autoria, agentes e autoridades)',NULL,'2',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(198,14,3,'item_acervo_data','Data',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(199,14,5,'item_acervo_localidade_codigo','Local',NULL,'5',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(200,14,5,'objeto_tipo_objeto_codigo','Tipo de objeto',NULL,'22',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(201,14,5,'objeto_tecnica_codigo','Técnica',NULL,'69',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(202,14,5,'item_acervo_estado_conservacao_codigo','Estado de conservação',NULL,'81',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,0),
(203,14,5,'item_acervo_assunto_codigo','Assunto',NULL,'60',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(204,65,5,'item_acervo_assunto_codigo','Assunto',NULL,'60',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,1),
(206,70,1,'projeto_nome','Nome',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,1);
/*!40000 ALTER TABLE `campo_sistema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campo_sistema_tipo_visualizacao`
--

DROP TABLE IF EXISTS `campo_sistema_tipo_visualizacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `campo_sistema_tipo_visualizacao` (
  `campo_sistema_codigo` int(11) NOT NULL,
  `tipo_visualizacao_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campo_sistema_tipo_visualizacao`
--

LOCK TABLES `campo_sistema_tipo_visualizacao` WRITE;
/*!40000 ALTER TABLE `campo_sistema_tipo_visualizacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `campo_sistema_tipo_visualizacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colecao`
--

DROP TABLE IF EXISTS `colecao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `colecao` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  `acervo_codigo` int(11) NOT NULL,
  `entidade_codigo` int(11) DEFAULT NULL,
  `tipos_materiais` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `quantidade_itens` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `colecao_acervo_null_fk` (`acervo_codigo`),
  KEY `colecao_FK` (`entidade_codigo`),
  CONSTRAINT `colecao_FK` FOREIGN KEY (`entidade_codigo`) REFERENCES `entidade` (`codigo`),
  CONSTRAINT `colecao_acervo_null_fk` FOREIGN KEY (`acervo_codigo`) REFERENCES `acervo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colecao`
--

LOCK TABLES `colecao` WRITE;
/*!40000 ALTER TABLE `colecao` DISABLE KEYS */;
/*!40000 ALTER TABLE `colecao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colecao_assunto`
--

DROP TABLE IF EXISTS `colecao_assunto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `colecao_assunto` (
  `colecao_codigo` int(11) NOT NULL,
  `assunto_codigo` int(11) NOT NULL,
  PRIMARY KEY (`assunto_codigo`,`colecao_codigo`),
  KEY `colecao_assunto_FK_1` (`colecao_codigo`),
  CONSTRAINT `colecao_assunto_FK` FOREIGN KEY (`assunto_codigo`) REFERENCES `assunto` (`codigo`),
  CONSTRAINT `colecao_assunto_FK_1` FOREIGN KEY (`colecao_codigo`) REFERENCES `colecao` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colecao_assunto`
--

LOCK TABLES `colecao_assunto` WRITE;
/*!40000 ALTER TABLE `colecao_assunto` DISABLE KEYS */;
/*!40000 ALTER TABLE `colecao_assunto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colecao_entidade`
--

DROP TABLE IF EXISTS `colecao_entidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `colecao_entidade` (
  `colecao_codigo` int(11) NOT NULL,
  `entidade_codigo` int(11) NOT NULL,
  PRIMARY KEY (`colecao_codigo`,`entidade_codigo`),
  KEY `colecao_entidade_FK_1` (`entidade_codigo`),
  CONSTRAINT `colecao_entidade_FK` FOREIGN KEY (`colecao_codigo`) REFERENCES `colecao` (`codigo`),
  CONSTRAINT `colecao_entidade_FK_1` FOREIGN KEY (`entidade_codigo`) REFERENCES `entidade` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colecao_entidade`
--

LOCK TABLES `colecao_entidade` WRITE;
/*!40000 ALTER TABLE `colecao_entidade` DISABLE KEYS */;
/*!40000 ALTER TABLE `colecao_entidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colecao_tipo_material`
--

DROP TABLE IF EXISTS `colecao_tipo_material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `colecao_tipo_material` (
  `colecao_codigo` int(11) NOT NULL,
  `tipo_material_codigo` int(11) NOT NULL,
  PRIMARY KEY (`tipo_material_codigo`,`colecao_codigo`),
  KEY `colecao_tipo_material_FK` (`colecao_codigo`),
  CONSTRAINT `colecao_tipo_material_FK` FOREIGN KEY (`colecao_codigo`) REFERENCES `colecao` (`codigo`),
  CONSTRAINT `colecao_tipo_material_FK_1` FOREIGN KEY (`tipo_material_codigo`) REFERENCES `tipo_material` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colecao_tipo_material`
--

LOCK TABLES `colecao_tipo_material` WRITE;
/*!40000 ALTER TABLE `colecao_tipo_material` DISABLE KEYS */;
/*!40000 ALTER TABLE `colecao_tipo_material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consulente`
--

DROP TABLE IF EXISTS `consulente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `consulente` (
  `codigo` int(11) NOT NULL,
  `usuario_codigo` int(11) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consulente`
--

LOCK TABLES `consulente` WRITE;
/*!40000 ALTER TABLE `consulente` DISABLE KEYS */;
/*!40000 ALTER TABLE `consulente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contexto`
--

DROP TABLE IF EXISTS `contexto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contexto` (
  `codigo` int(11) NOT NULL,
  `id` varchar(1000) DEFAULT NULL,
  `acervo_codigo` int(11) DEFAULT NULL,
  `data_inicial` date DEFAULT NULL,
  `data_final` date DEFAULT NULL,
  `data_presumida` tinyint(1) DEFAULT NULL,
  `sem_data` tinyint(1) DEFAULT NULL,
  `contexto_superior_codigo` int(11) DEFAULT NULL,
  `publicado_online` int(11) DEFAULT 0,
  PRIMARY KEY (`codigo`),
  KEY `acervo_codigo` (`acervo_codigo`),
  KEY `contexto_superior_codigo` (`contexto_superior_codigo`),
  CONSTRAINT `contexto_ibfk_1` FOREIGN KEY (`acervo_codigo`) REFERENCES `acervo` (`codigo`),
  CONSTRAINT `contexto_ibfk_2` FOREIGN KEY (`contexto_superior_codigo`) REFERENCES `contexto` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contexto`
--

LOCK TABLES `contexto` WRITE;
/*!40000 ALTER TABLE `contexto` DISABLE KEYS */;
/*!40000 ALTER TABLE `contexto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contexto_dados_textuais`
--

DROP TABLE IF EXISTS `contexto_dados_textuais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contexto_dados_textuais` (
  `contexto_codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `sinopse` text DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `idioma_codigo` int(11) NOT NULL,
  PRIMARY KEY (`contexto_codigo`,`idioma_codigo`),
  KEY `idioma_codigo` (`idioma_codigo`),
  CONSTRAINT `contexto_dados_textuais_ibfk_1` FOREIGN KEY (`contexto_codigo`) REFERENCES `contexto` (`codigo`),
  CONSTRAINT `contexto_dados_textuais_ibfk_2` FOREIGN KEY (`idioma_codigo`) REFERENCES `idioma` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contexto_dados_textuais`
--

LOCK TABLES `contexto_dados_textuais` WRITE;
/*!40000 ALTER TABLE `contexto_dados_textuais` DISABLE KEYS */;
/*!40000 ALTER TABLE `contexto_dados_textuais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contexto_item_acervo`
--

DROP TABLE IF EXISTS `contexto_item_acervo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contexto_item_acervo` (
  `contexto_codigo` int(11) NOT NULL,
  `item_acervo_codigo` int(11) NOT NULL,
  `sequencia` int(11) DEFAULT NULL,
  PRIMARY KEY (`contexto_codigo`,`item_acervo_codigo`),
  KEY `contexto_item_acervo_FK_1` (`item_acervo_codigo`),
  CONSTRAINT `contexto_item_acervo_FK` FOREIGN KEY (`contexto_codigo`) REFERENCES `contexto` (`codigo`),
  CONSTRAINT `contexto_item_acervo_FK_1` FOREIGN KEY (`item_acervo_codigo`) REFERENCES `item_acervo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contexto_item_acervo`
--

LOCK TABLES `contexto_item_acervo` WRITE;
/*!40000 ALTER TABLE `contexto_item_acervo` DISABLE KEYS */;
/*!40000 ALTER TABLE `contexto_item_acervo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contexto_tipo_contexto`
--

DROP TABLE IF EXISTS `contexto_tipo_contexto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contexto_tipo_contexto` (
  `contexto_codigo` int(11) NOT NULL,
  `tipo_contexto_codigo` int(11) NOT NULL,
  PRIMARY KEY (`contexto_codigo`,`tipo_contexto_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contexto_tipo_contexto`
--

LOCK TABLES `contexto_tipo_contexto` WRITE;
/*!40000 ALTER TABLE `contexto_tipo_contexto` DISABLE KEYS */;
/*!40000 ALTER TABLE `contexto_tipo_contexto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contexto_visualizacao`
--

DROP TABLE IF EXISTS `contexto_visualizacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contexto_visualizacao` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contexto_visualizacao`
--

LOCK TABLES `contexto_visualizacao` WRITE;
/*!40000 ALTER TABLE `contexto_visualizacao` DISABLE KEYS */;
INSERT INTO `contexto_visualizacao` VALUES
(1,'criação de registro'),
(2,'edição de registro'),
(3,'navegação'),
(4,'ficha');
/*!40000 ALTER TABLE `contexto_visualizacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cromia`
--

DROP TABLE IF EXISTS `cromia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cromia` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cromia`
--

LOCK TABLES `cromia` WRITE;
/*!40000 ALTER TABLE `cromia` DISABLE KEYS */;
/*!40000 ALTER TABLE `cromia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documento`
--

DROP TABLE IF EXISTS `documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `documento` (
  `codigo` int(11) NOT NULL,
  `item_acervo_codigo` int(11) DEFAULT NULL,
  `agrupamento_codigo` int(11) DEFAULT NULL,
  `atividade_geradora_codigo` int(11) DEFAULT NULL,
  `local_armazenamento_codigo` int(11) DEFAULT NULL,
  `serie_codigo` int(11) DEFAULT NULL,
  `tecnica_registro_codigo` int(11) DEFAULT NULL,
  `cromia_codigo` int(11) DEFAULT NULL,
  `documento_pai_codigo` int(11) DEFAULT NULL,
  `genero_documental_codigo` int(11) DEFAULT NULL,
  `validacao` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `item_acervo_codigo` (`item_acervo_codigo`),
  KEY `Agrupamento_Codigo` (`agrupamento_codigo`),
  KEY `atividade_geradora_codigo` (`atividade_geradora_codigo`),
  KEY `serie_codigo` (`serie_codigo`),
  KEY `documento_genero_documental_null_fk` (`genero_documental_codigo`),
  KEY `documento_cromia_null_fk` (`cromia_codigo`),
  KEY `documento_tecnica_registro_null_fk` (`tecnica_registro_codigo`),
  CONSTRAINT `documento_cromia_null_fk` FOREIGN KEY (`cromia_codigo`) REFERENCES `cromia` (`codigo`),
  CONSTRAINT `documento_genero_documental_null_fk` FOREIGN KEY (`genero_documental_codigo`) REFERENCES `genero_documental` (`codigo`),
  CONSTRAINT `documento_ibfk_2` FOREIGN KEY (`agrupamento_codigo`) REFERENCES `agrupamento` (`codigo`),
  CONSTRAINT `documento_ibfk_3` FOREIGN KEY (`atividade_geradora_codigo`) REFERENCES `atividade_geradora` (`codigo`),
  CONSTRAINT `documento_ibfk_4` FOREIGN KEY (`item_acervo_codigo`) REFERENCES `item_acervo` (`codigo`),
  CONSTRAINT `documento_ibfk_6` FOREIGN KEY (`serie_codigo`) REFERENCES `serie` (`codigo`),
  CONSTRAINT `documento_tecnica_registro_null_fk` FOREIGN KEY (`tecnica_registro_codigo`) REFERENCES `tecnica_registro` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento`
--

LOCK TABLES `documento` WRITE;
/*!40000 ALTER TABLE `documento` DISABLE KEYS */;
/*!40000 ALTER TABLE `documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documento_especie_documental`
--

DROP TABLE IF EXISTS `documento_especie_documental`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `documento_especie_documental` (
  `documento_codigo` int(11) NOT NULL,
  `especie_documental_codigo` int(11) NOT NULL,
  `tipo_documental_codigo` int(11) DEFAULT NULL,
  KEY `Documento_Codigo` (`documento_codigo`),
  KEY `documento_especie_documental_FK_1` (`especie_documental_codigo`),
  KEY `documento_especie_documental_FK_2` (`tipo_documental_codigo`),
  CONSTRAINT `documento_especie_documental_FK` FOREIGN KEY (`documento_codigo`) REFERENCES `documento` (`codigo`),
  CONSTRAINT `documento_especie_documental_FK_1` FOREIGN KEY (`especie_documental_codigo`) REFERENCES `especie_documental` (`Codigo`),
  CONSTRAINT `documento_especie_documental_FK_2` FOREIGN KEY (`tipo_documental_codigo`) REFERENCES `tipo_documental` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento_especie_documental`
--

LOCK TABLES `documento_especie_documental` WRITE;
/*!40000 ALTER TABLE `documento_especie_documental` DISABLE KEYS */;
/*!40000 ALTER TABLE `documento_especie_documental` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documento_formato`
--

DROP TABLE IF EXISTS `documento_formato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `documento_formato` (
  `documento_codigo` int(11) NOT NULL,
  `formato_codigo` int(11) NOT NULL,
  PRIMARY KEY (`documento_codigo`,`formato_codigo`),
  KEY `documento_formato_formato_null_fk` (`formato_codigo`),
  CONSTRAINT `documento_formato_documento_null_fk` FOREIGN KEY (`documento_codigo`) REFERENCES `documento` (`codigo`),
  CONSTRAINT `documento_formato_formato_null_fk` FOREIGN KEY (`formato_codigo`) REFERENCES `formato` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento_formato`
--

LOCK TABLES `documento_formato` WRITE;
/*!40000 ALTER TABLE `documento_formato` DISABLE KEYS */;
/*!40000 ALTER TABLE `documento_formato` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documento_genero_documental`
--

DROP TABLE IF EXISTS `documento_genero_documental`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `documento_genero_documental` (
  `documento_codigo` int(11) NOT NULL,
  `genero_documental_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento_genero_documental`
--

LOCK TABLES `documento_genero_documental` WRITE;
/*!40000 ALTER TABLE `documento_genero_documental` DISABLE KEYS */;
/*!40000 ALTER TABLE `documento_genero_documental` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `editora`
--

DROP TABLE IF EXISTS `editora`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `editora` (
  `codigo` int(11) NOT NULL,
  `entidade_codigo` int(11) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `editora`
--

LOCK TABLES `editora` WRITE;
/*!40000 ALTER TABLE `editora` DISABLE KEYS */;
/*!40000 ALTER TABLE `editora` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `editora_localidade`
--

DROP TABLE IF EXISTS `editora_localidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `editora_localidade` (
  `editora_codigo` int(11) NOT NULL,
  `localidade_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `editora_localidade`
--

LOCK TABLES `editora_localidade` WRITE;
/*!40000 ALTER TABLE `editora_localidade` DISABLE KEYS */;
/*!40000 ALTER TABLE `editora_localidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entidade`
--

DROP TABLE IF EXISTS `entidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `entidade` (
  `codigo` int(11) NOT NULL,
  `tipo_codigo` int(11) DEFAULT NULL,
  `entidade_principal_codigo` int(11) DEFAULT NULL,
  `nome` varchar(250) NOT NULL,
  `Genero_Gramatical_Codigo` int(11) DEFAULT NULL,
  `Qualificador_Nome_Codigo` int(11) DEFAULT NULL,
  `Data_Nascimento_Inicial` date DEFAULT NULL,
  `Data_Nascimento_Final` date DEFAULT NULL,
  `Data_Nascimento_Presumida` tinyint(1) DEFAULT NULL,
  `data_nascimento_sem_data` tinyint(1) DEFAULT NULL,
  `Data_Morte` date DEFAULT NULL,
  `local_nascimento_codigo` int(11) DEFAULT NULL,
  `local_morte_codigo` int(11) DEFAULT NULL,
  `Data_Morte_Inicial` date DEFAULT NULL,
  `Data_Morte_Final` date DEFAULT NULL,
  `Data_Morte_Presumida` tinyint(1) DEFAULT NULL,
  `data_morte_sem_data` tinyint(1) DEFAULT NULL,
  `biografia` text DEFAULT NULL,
  `raca_codigo` int(11) DEFAULT NULL,
  `genero_codigo` int(11) DEFAULT NULL,
  `telefone` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `site` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `entidade_genero_null_fk` (`genero_codigo`),
  KEY `entidade_raca_null_fk` (`raca_codigo`),
  CONSTRAINT `entidade_genero_null_fk` FOREIGN KEY (`genero_codigo`) REFERENCES `genero` (`codigo`),
  CONSTRAINT `entidade_raca_null_fk` FOREIGN KEY (`raca_codigo`) REFERENCES `raca` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entidade`
--

LOCK TABLES `entidade` WRITE;
/*!40000 ALTER TABLE `entidade` DISABLE KEYS */;
/*!40000 ALTER TABLE `entidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entidade_endereco`
--

DROP TABLE IF EXISTS `entidade_endereco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `entidade_endereco` (
  `entidade_codigo` int(11) NOT NULL,
  `logradouro` varchar(2000) DEFAULT NULL,
  `numero` varchar(50) DEFAULT NULL,
  `complemento` varchar(500) DEFAULT NULL,
  `bairro` varchar(2000) DEFAULT NULL,
  `localidade_codigo` int(11) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  KEY `bairro_codigo` (`bairro`(768)) USING BTREE,
  KEY `entidade_codigo` (`entidade_codigo`) USING BTREE,
  KEY `localidade_codigo` (`localidade_codigo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entidade_endereco`
--

LOCK TABLES `entidade_endereco` WRITE;
/*!40000 ALTER TABLE `entidade_endereco` DISABLE KEYS */;
/*!40000 ALTER TABLE `entidade_endereco` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entidade_entidade`
--

DROP TABLE IF EXISTS `entidade_entidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `entidade_entidade` (
  `entidade_1_codigo` int(11) NOT NULL,
  `entidade_2_codigo` int(11) NOT NULL,
  `tipo_relacao_codigo` int(11) DEFAULT NULL,
  KEY `entidade_1_codigo` (`entidade_1_codigo`),
  KEY `entidade_2_codigo` (`entidade_2_codigo`),
  CONSTRAINT `entidade_entidade_ibfk_1` FOREIGN KEY (`entidade_1_codigo`) REFERENCES `entidade` (`codigo`),
  CONSTRAINT `entidade_entidade_ibfk_2` FOREIGN KEY (`entidade_2_codigo`) REFERENCES `entidade` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entidade_entidade`
--

LOCK TABLES `entidade_entidade` WRITE;
/*!40000 ALTER TABLE `entidade_entidade` DISABLE KEYS */;
/*!40000 ALTER TABLE `entidade_entidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entidade_localidade`
--

DROP TABLE IF EXISTS `entidade_localidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `entidade_localidade` (
  `entidade_codigo` int(11) NOT NULL,
  `localidade_codigo` int(11) NOT NULL,
  `tipo_relacao` int(11) DEFAULT NULL,
  KEY `entidade_codigo` (`entidade_codigo`),
  KEY `localidade_codigo` (`localidade_codigo`),
  CONSTRAINT `entidade_localidade_ibfk_1` FOREIGN KEY (`entidade_codigo`) REFERENCES `entidade` (`codigo`),
  CONSTRAINT `entidade_localidade_ibfk_2` FOREIGN KEY (`localidade_codigo`) REFERENCES `localidade` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entidade_localidade`
--

LOCK TABLES `entidade_localidade` WRITE;
/*!40000 ALTER TABLE `entidade_localidade` DISABLE KEYS */;
/*!40000 ALTER TABLE `entidade_localidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entrevista`
--

DROP TABLE IF EXISTS `entrevista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `entrevista` (
  `codigo` int(11) NOT NULL,
  `instituicao_codigo` int(11) DEFAULT NULL,
  `circulo` varchar(500) DEFAULT NULL,
  `duracao` int(11) DEFAULT NULL,
  `formato_entrevista_codigo` int(11) DEFAULT NULL,
  `transcrito` tinyint(4) DEFAULT NULL,
  `projeto_codigo` int(11) DEFAULT NULL,
  `item_acervo_codigo` int(11) DEFAULT NULL,
  `tipo_entrevista_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `historia_oral_item_acervo_null_fk` (`item_acervo_codigo`),
  KEY `historia_oral_projeto_null_fk` (`projeto_codigo`),
  KEY `entrevista_FK` (`instituicao_codigo`),
  KEY `entrevista_FK_1` (`tipo_entrevista_codigo`),
  CONSTRAINT `entrevista_FK` FOREIGN KEY (`instituicao_codigo`) REFERENCES `instituicao` (`codigo`),
  CONSTRAINT `entrevista_FK_1` FOREIGN KEY (`tipo_entrevista_codigo`) REFERENCES `tipo_entrevista` (`codigo`),
  CONSTRAINT `historia_oral_item_acervo_null_fk` FOREIGN KEY (`item_acervo_codigo`) REFERENCES `item_acervo` (`codigo`),
  CONSTRAINT `historia_oral_projeto_null_fk` FOREIGN KEY (`projeto_codigo`) REFERENCES `projeto` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entrevista`
--

LOCK TABLES `entrevista` WRITE;
/*!40000 ALTER TABLE `entrevista` DISABLE KEYS */;
/*!40000 ALTER TABLE `entrevista` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entrevista_formato_entrevista`
--

DROP TABLE IF EXISTS `entrevista_formato_entrevista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `entrevista_formato_entrevista` (
  `entrevista_codigo` int(11) NOT NULL,
  `formato_entrevista_codigo` int(11) NOT NULL,
  PRIMARY KEY (`entrevista_codigo`,`formato_entrevista_codigo`),
  CONSTRAINT `entrevista_formato_FK` FOREIGN KEY (`entrevista_codigo`) REFERENCES `entrevista` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entrevista_formato_entrevista`
--

LOCK TABLES `entrevista_formato_entrevista` WRITE;
/*!40000 ALTER TABLE `entrevista_formato_entrevista` DISABLE KEYS */;
/*!40000 ALTER TABLE `entrevista_formato_entrevista` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `especie_documental`
--

DROP TABLE IF EXISTS `especie_documental`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `especie_documental` (
  `Codigo` int(11) NOT NULL,
  PRIMARY KEY (`Codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `especie_documental`
--

LOCK TABLES `especie_documental` WRITE;
/*!40000 ALTER TABLE `especie_documental` DISABLE KEYS */;
INSERT INTO `especie_documental` VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19),
(20),
(21),
(22),
(23),
(24),
(25),
(26),
(27),
(28),
(29),
(30),
(31),
(32),
(33),
(34),
(35),
(36),
(37),
(38),
(39),
(40),
(41),
(42),
(43),
(44),
(45),
(46),
(47),
(48),
(49),
(50),
(51),
(52),
(53),
(54),
(55),
(56),
(57),
(58),
(59),
(60),
(61),
(62),
(63),
(64),
(65),
(66),
(67),
(68),
(69),
(70),
(71),
(72),
(73),
(74),
(75),
(76),
(77),
(78),
(79),
(80),
(81),
(82),
(83),
(84),
(85),
(86),
(87),
(88),
(89),
(90),
(91),
(92),
(93),
(94),
(95),
(96),
(97),
(98),
(99),
(100),
(101),
(102),
(103),
(104),
(105),
(106),
(107),
(108),
(109),
(110),
(111),
(112),
(113),
(114),
(115),
(116),
(117),
(118),
(119),
(120),
(121),
(122),
(123),
(124),
(125),
(126),
(127),
(128),
(129),
(130),
(131),
(132),
(133),
(134),
(135),
(136),
(137),
(138),
(139),
(140),
(141),
(142),
(143),
(144),
(145),
(146),
(147),
(148),
(149),
(150),
(151),
(152),
(153),
(154),
(155),
(156),
(157),
(158),
(159),
(160),
(161),
(162),
(163),
(164),
(165),
(166),
(167),
(168),
(169),
(170),
(171),
(172),
(173),
(174),
(175),
(176),
(177),
(178),
(179),
(180),
(181),
(182),
(183),
(184),
(185),
(186),
(187),
(188),
(189),
(190),
(191),
(192),
(193),
(194),
(195),
(196),
(197),
(198),
(199),
(200),
(201),
(202),
(203),
(204),
(205),
(206),
(207),
(208),
(209),
(210),
(211),
(212),
(213),
(214),
(215),
(216),
(217),
(218),
(219),
(220),
(221),
(222),
(223),
(224),
(225),
(226),
(227),
(228),
(229),
(230),
(231),
(232),
(233),
(234),
(235),
(236),
(237),
(238),
(239),
(240),
(241),
(242),
(243),
(244),
(245),
(246),
(247),
(248);
/*!40000 ALTER TABLE `especie_documental` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `especie_documental_dados_textuais`
--

DROP TABLE IF EXISTS `especie_documental_dados_textuais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `especie_documental_dados_textuais` (
  `especie_documental_codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  `idioma_codigo` int(11) NOT NULL,
  PRIMARY KEY (`especie_documental_codigo`,`idioma_codigo`),
  KEY `idioma_codigo` (`idioma_codigo`),
  CONSTRAINT `especie_documental_dados_textuais_ibfk_1` FOREIGN KEY (`especie_documental_codigo`) REFERENCES `especie_documental` (`Codigo`),
  CONSTRAINT `especie_documental_dados_textuais_ibfk_2` FOREIGN KEY (`idioma_codigo`) REFERENCES `idioma` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `especie_documental_dados_textuais`
--

LOCK TABLES `especie_documental_dados_textuais` WRITE;
/*!40000 ALTER TABLE `especie_documental_dados_textuais` DISABLE KEYS */;
INSERT INTO `especie_documental_dados_textuais` VALUES
(1,'Acordo',NULL,1),
(2,'Agenda',NULL,1),
(3,'Anais',NULL,1),
(4,'Anteprojeto',NULL,1),
(5,'Anuário',NULL,1),
(6,'Anúncio',NULL,1),
(7,'Apostila',NULL,1),
(8,'Aprovação',NULL,1),
(9,'Apólice de seguro',NULL,1),
(10,'Arte final',NULL,1),
(11,'Artigo',NULL,1),
(12,'Ata',NULL,1),
(13,'Atestado',NULL,1),
(14,'Ato',NULL,1),
(15,'Ato administrativo',NULL,1),
(16,'Ato normativo',NULL,1),
(17,'Auto',NULL,1),
(18,'Autorização',NULL,1),
(19,'Avaliação',NULL,1),
(20,'Aviso',NULL,1),
(21,'Balancete',NULL,1),
(22,'Balanço',NULL,1),
(23,'Base de dados/banco de dados',NULL,1),
(24,'Bilhete',NULL,1),
(25,'Boletim',NULL,1),
(26,'Boneco',NULL,1),
(27,'Cadastro',NULL,1),
(28,'Caderneta',NULL,1),
(29,'Caderno',NULL,1),
(30,'Caderno de protocolo',NULL,1),
(31,'Calendário',NULL,1),
(32,'Cardápio',NULL,1),
(33,'Carta',NULL,1),
(34,'Carta de validade',NULL,1),
(35,'Carta patente',NULL,1),
(36,'Cartaz',NULL,1),
(37,'Cartão',NULL,1),
(38,'Catálogo',NULL,1),
(39,'Certidão',NULL,1),
(40,'Certificado',NULL,1),
(41,'Certificação',NULL,1),
(42,'Certificação',NULL,1),
(43,'Circular',NULL,1),
(44,'Comprovante',NULL,1),
(45,'Comunicado',NULL,1),
(46,'Comunicação',NULL,1),
(47,'Conta',NULL,1),
(48,'Contrato',NULL,1),
(49,'Controle',NULL,1),
(50,'Convenção',NULL,1),
(51,'Convite',NULL,1),
(52,'Convocação',NULL,1),
(53,'Convênio',NULL,1),
(54,'Correspondência',NULL,1),
(55,'Crachá',NULL,1),
(56,'Cronograma',NULL,1),
(57,'Cronograma',NULL,1),
(58,'Croquis',NULL,1),
(59,'Curriculum vitae (currículo)',NULL,1),
(60,'Currículo de curso',NULL,1),
(61,'Cálculo',NULL,1),
(62,'Cédula de eleição',NULL,1),
(63,'Cédula de identidade',NULL,1),
(64,'Decisão',NULL,1),
(65,'Declaração',NULL,1),
(66,'Decreto',NULL,1),
(67,'Decreto–lei',NULL,1),
(68,'Deliberação',NULL,1),
(69,'Demonstrativo',NULL,1),
(70,'Depoimento',NULL,1),
(71,'Designação',NULL,1),
(72,'Despacho',NULL,1),
(73,'Devolutiva',NULL,1),
(74,'Diagnóstico',NULL,1),
(75,'Diploma',NULL,1),
(76,'Diretriz orçamentária',NULL,1),
(77,'Discurso',NULL,1),
(78,'Dissertação',NULL,1),
(79,'Diário',NULL,1),
(80,'Diário de classe',NULL,1),
(81,'Documentadas e facilmente acessáveis para comprovação futura',NULL,1),
(82,'Documentário',NULL,1),
(83,'Dossiê',NULL,1),
(84,'E-mail',NULL,1),
(85,'Edital',NULL,1),
(86,'Ementa',NULL,1),
(87,'Empenho',NULL,1),
(88,'Escala',NULL,1),
(89,'Estatuto',NULL,1),
(90,'Estudo',NULL,1),
(91,'Exemplar de obra/livro (original)',NULL,1),
(92,'Expediente',NULL,1),
(93,'Exposição de motivos',NULL,1),
(94,'Extrato',NULL,1),
(95,'Extrato bancário',NULL,1),
(96,'Fatura',NULL,1),
(97,'Fax',NULL,1),
(98,'Ficha',NULL,1),
(99,'Ficha de avaliação sócio',NULL,1),
(100,'Filipeta',NULL,1),
(101,'Filme',NULL,1),
(102,'Fita backup',NULL,1),
(103,'Fluxograma',NULL,1),
(104,'Folder (folheto)',NULL,1),
(105,'Folha',NULL,1),
(106,'Folha de frequência',NULL,1),
(107,'Folha de pagamento',NULL,1),
(108,'Fonetografia',NULL,1),
(109,'Formulário',NULL,1),
(110,'Foto/fotografia',NULL,1),
(111,'Fotolito',NULL,1),
(112,'Gabarito',NULL,1),
(113,'Grade curricular',NULL,1),
(114,'Gráfico',NULL,1),
(115,'Guia',NULL,1),
(116,'Guia',NULL,1),
(117,'Histórico',NULL,1),
(118,'Histórico escolar',NULL,1),
(119,'Homologação',NULL,1),
(120,'Impressos',NULL,1),
(121,'Indicação',NULL,1),
(122,'Informativo',NULL,1),
(123,'Informativo proteos',NULL,1),
(124,'Informação',NULL,1),
(125,'Informe',NULL,1),
(126,'Instrução',NULL,1),
(127,'Inventário',NULL,1),
(128,'Justificativa',NULL,1),
(129,'Lançamento',NULL,1),
(130,'Laudo',NULL,1),
(131,'Layout',NULL,1),
(132,'Legislação',NULL,1),
(133,'Lei',NULL,1),
(134,'Levantamento estatístico',NULL,1),
(135,'Levantamento topográfico',NULL,1),
(136,'Licença de uso',NULL,1),
(137,'Lista/listagem',NULL,1),
(138,'Livro',NULL,1),
(139,'Lâmina',NULL,1),
(140,'Manifestação/manifesto',NULL,1),
(141,'Manifesto',NULL,1),
(142,'Manifesto de carga',NULL,1),
(143,'Manual',NULL,1),
(144,'Mapa',NULL,1),
(145,'Maquete',NULL,1),
(146,'Matrícula',NULL,1),
(147,'Medida provisória',NULL,1),
(148,'Memorando',NULL,1),
(149,'Memorial',NULL,1),
(150,'Memorial descritivo',NULL,1),
(151,'Memória',NULL,1),
(152,'Memória técnica',NULL,1),
(153,'Mensagem',NULL,1),
(154,'Minuta',NULL,1),
(155,'Modelo',NULL,1),
(156,'Monografia',NULL,1),
(157,'Mosaico',NULL,1),
(158,'Moção',NULL,1),
(159,'Norma',NULL,1),
(160,'Nota',NULL,1),
(161,'Nota de recebimento/nota de transferência',NULL,1),
(162,'Nota fiscal',NULL,1),
(163,'Notificação',NULL,1),
(164,'Ofício',NULL,1),
(165,'Ofício-circular',NULL,1),
(166,'Ordem de pagamento',NULL,1),
(167,'Ordem de serviço',NULL,1),
(168,'Ordem do dia',NULL,1),
(169,'Organograma',NULL,1),
(170,'Original de obras',NULL,1),
(171,'Orçamento',NULL,1),
(172,'Panfleto',NULL,1),
(173,'Papeleta',NULL,1),
(174,'Paper (texto)',NULL,1),
(175,'Parecer',NULL,1),
(176,'Partitura',NULL,1),
(177,'Passagem',NULL,1),
(178,'Pasta de eleição',NULL,1),
(179,'Pauta',NULL,1),
(180,'Pedido',NULL,1),
(181,'Petição',NULL,1),
(182,'Planejamento',NULL,1),
(183,'Planilha',NULL,1),
(184,'Plano',NULL,1),
(185,'Planta',NULL,1),
(186,'Portaria',NULL,1),
(187,'Prestação de contas',NULL,1),
(188,'Processo',NULL,1),
(189,'Processo seletivo',NULL,1),
(190,'Procuração',NULL,1),
(191,'Programa',NULL,1),
(192,'Projeto',NULL,1),
(193,'Prontuário',NULL,1),
(194,'Pronunciamento',NULL,1),
(195,'Proposição',NULL,1),
(196,'Proposta',NULL,1),
(197,'Proposta',NULL,1),
(198,'Prospecto',NULL,1),
(199,'Protocolado',NULL,1),
(200,'Protocolo',NULL,1),
(201,'Prova',NULL,1),
(202,'Prova heliográfica',NULL,1),
(203,'Prova tipográfica',NULL,1),
(204,'Provimento',NULL,1),
(205,'Quadro',NULL,1),
(206,'Questionário',NULL,1),
(207,'Razão de contas correntes',NULL,1),
(208,'Receita',NULL,1),
(209,'Receituário',NULL,1),
(210,'Recibo',NULL,1),
(211,'Recorte/clip',NULL,1),
(212,'Recurso',NULL,1),
(213,'Regimento',NULL,1),
(214,'Registro',NULL,1),
(215,'Regulamentação',NULL,1),
(216,'Regulamento',NULL,1),
(217,'Relatório',NULL,1),
(218,'Relação',NULL,1),
(219,'Relação de remessa',NULL,1),
(220,'Relação de remessa',NULL,1),
(221,'Release',NULL,1),
(222,'Remanejamento orçamentário',NULL,1),
(223,'Remessa',NULL,1),
(224,'Requerimento',NULL,1),
(225,'Requisitos',NULL,1),
(226,'Requisição',NULL,1),
(227,'Resenha',NULL,1),
(228,'Resolução',NULL,1),
(229,'Resultado de testes',NULL,1),
(230,'Resumo',NULL,1),
(231,'Rol',NULL,1),
(232,'Roteiro',NULL,1),
(233,'Seguro',NULL,1),
(234,'Seqüência de decisões e/ou providências',NULL,1),
(235,'Sinopse',NULL,1),
(236,'Software',NULL,1),
(237,'Solicitação',NULL,1),
(238,'Tabela',NULL,1),
(239,'Tabela',NULL,1),
(240,'Talão/talonário',NULL,1),
(241,'Termo',NULL,1),
(242,'Tese',NULL,1),
(243,'Teste',NULL,1),
(244,'Texto',NULL,1),
(245,'Tiquetes',NULL,1),
(246,'Trabalho de conclusão de curso (TCC)',NULL,1),
(247,'Trabalho de graduação',NULL,1),
(248,'Transposição orçamentária',NULL,1);
/*!40000 ALTER TABLE `especie_documental_dados_textuais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado_conservacao`
--

DROP TABLE IF EXISTS `estado_conservacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado_conservacao` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado_conservacao`
--

LOCK TABLES `estado_conservacao` WRITE;
/*!40000 ALTER TABLE `estado_conservacao` DISABLE KEYS */;
INSERT INTO `estado_conservacao` VALUES
(1,'Ótimo'),
(2,'Bom'),
(3,'Regular'),
(4,'Ruim'),
(5,'Péssimo');
/*!40000 ALTER TABLE `estado_conservacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado_organizacao_acervo`
--

DROP TABLE IF EXISTS `estado_organizacao_acervo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado_organizacao_acervo` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado_organizacao_acervo`
--

LOCK TABLES `estado_organizacao_acervo` WRITE;
/*!40000 ALTER TABLE `estado_organizacao_acervo` DISABLE KEYS */;
/*!40000 ALTER TABLE `estado_organizacao_acervo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `etapa_fluxo`
--

DROP TABLE IF EXISTS `etapa_fluxo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `etapa_fluxo` (
  `codigo` int(11) NOT NULL,
  `fluxo_codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `etapa_fluxo_substitutiva_codigo` int(11) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `tipo_operacao_log_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `etapa_fluxo_substitutiva_codigo` (`etapa_fluxo_substitutiva_codigo`),
  KEY `fluxo_codigo` (`fluxo_codigo`),
  KEY `etapa_fluxo_FK` (`tipo_operacao_log_codigo`),
  CONSTRAINT `etapa_fluxo_FK` FOREIGN KEY (`tipo_operacao_log_codigo`) REFERENCES `tipo_operacao_log` (`codigo`),
  CONSTRAINT `etapa_fluxo_ibfk_1` FOREIGN KEY (`etapa_fluxo_substitutiva_codigo`) REFERENCES `etapa_fluxo` (`codigo`),
  CONSTRAINT `etapa_fluxo_ibfk_2` FOREIGN KEY (`fluxo_codigo`) REFERENCES `fluxo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `etapa_fluxo`
--

LOCK TABLES `etapa_fluxo` WRITE;
/*!40000 ALTER TABLE `etapa_fluxo` DISABLE KEYS */;
/*!40000 ALTER TABLE `etapa_fluxo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `etapa_fluxo_grupo_usuario`
--

DROP TABLE IF EXISTS `etapa_fluxo_grupo_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `etapa_fluxo_grupo_usuario` (
  `etapa_fluxo_codigo` int(11) NOT NULL,
  `grupo_usuario_codigo` int(11) NOT NULL,
  `acesso_registro` tinyint(1) DEFAULT NULL,
  `acesso_etapa_fluxo` tinyint(1) DEFAULT NULL,
  `alterar_etapa_salvar` tinyint(1) DEFAULT NULL,
  `etapa_fluxo_acesso_salvar_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`etapa_fluxo_codigo`,`grupo_usuario_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `etapa_fluxo_grupo_usuario`
--

LOCK TABLES `etapa_fluxo_grupo_usuario` WRITE;
/*!40000 ALTER TABLE `etapa_fluxo_grupo_usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `etapa_fluxo_grupo_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evento`
--

DROP TABLE IF EXISTS `evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `evento` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  `tipo_codigo` int(11) DEFAULT NULL,
  `data_inicial` date DEFAULT NULL,
  `data_final` date DEFAULT NULL,
  `data_presumida` tinyint(1) DEFAULT NULL,
  `sem_data` tinyint(1) DEFAULT NULL,
  `localidade_codigo` int(11) DEFAULT NULL,
  `item_acervo_introdutorio` text DEFAULT NULL,
  `fonte_informacao` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evento`
--

LOCK TABLES `evento` WRITE;
/*!40000 ALTER TABLE `evento` DISABLE KEYS */;
/*!40000 ALTER TABLE `evento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evento_entidade`
--

DROP TABLE IF EXISTS `evento_entidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `evento_entidade` (
  `evento_codigo` int(11) NOT NULL,
  `entidade_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evento_entidade`
--

LOCK TABLES `evento_entidade` WRITE;
/*!40000 ALTER TABLE `evento_entidade` DISABLE KEYS */;
/*!40000 ALTER TABLE `evento_entidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fluxo`
--

DROP TABLE IF EXISTS `fluxo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `fluxo` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fluxo`
--

LOCK TABLES `fluxo` WRITE;
/*!40000 ALTER TABLE `fluxo` DISABLE KEYS */;
/*!40000 ALTER TABLE `fluxo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fluxo_recurso_sistema`
--

DROP TABLE IF EXISTS `fluxo_recurso_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `fluxo_recurso_sistema` (
  `fluxo_codigo` int(11) NOT NULL,
  `recurso_sistema_codigo` int(11) NOT NULL,
  PRIMARY KEY (`fluxo_codigo`,`recurso_sistema_codigo`),
  KEY `recurso_sistema_codigo` (`recurso_sistema_codigo`),
  CONSTRAINT `fluxo_recurso_sistema_ibfk_1` FOREIGN KEY (`fluxo_codigo`) REFERENCES `fluxo` (`codigo`),
  CONSTRAINT `fluxo_recurso_sistema_ibfk_2` FOREIGN KEY (`recurso_sistema_codigo`) REFERENCES `recurso_sistema` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fluxo_recurso_sistema`
--

LOCK TABLES `fluxo_recurso_sistema` WRITE;
/*!40000 ALTER TABLE `fluxo_recurso_sistema` DISABLE KEYS */;
/*!40000 ALTER TABLE `fluxo_recurso_sistema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formato`
--

DROP TABLE IF EXISTS `formato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `formato` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formato`
--

LOCK TABLES `formato` WRITE;
/*!40000 ALTER TABLE `formato` DISABLE KEYS */;
/*!40000 ALTER TABLE `formato` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formato_entrevista`
--

DROP TABLE IF EXISTS `formato_entrevista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `formato_entrevista` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formato_entrevista`
--

LOCK TABLES `formato_entrevista` WRITE;
/*!40000 ALTER TABLE `formato_entrevista` DISABLE KEYS */;
INSERT INTO `formato_entrevista` VALUES
(1,'Áudio',NULL),
(2,'Áudiovisual',NULL);
/*!40000 ALTER TABLE `formato_entrevista` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formato_pagina`
--

DROP TABLE IF EXISTS `formato_pagina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `formato_pagina` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `altura` float NOT NULL,
  `largura` float NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formato_pagina`
--

LOCK TABLES `formato_pagina` WRITE;
/*!40000 ALTER TABLE `formato_pagina` DISABLE KEYS */;
INSERT INTO `formato_pagina` VALUES
(1,'A4',297,210),
(2,'Carta',279,216);
/*!40000 ALTER TABLE `formato_pagina` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genero`
--

DROP TABLE IF EXISTS `genero`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `genero` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genero`
--

LOCK TABLES `genero` WRITE;
/*!40000 ALTER TABLE `genero` DISABLE KEYS */;
/*!40000 ALTER TABLE `genero` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genero_documental`
--

DROP TABLE IF EXISTS `genero_documental`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `genero_documental` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genero_documental`
--

LOCK TABLES `genero_documental` WRITE;
/*!40000 ALTER TABLE `genero_documental` DISABLE KEYS */;
INSERT INTO `genero_documental` VALUES
(1,'Audiovisual',NULL),
(2,'Cartográfico',NULL),
(3,'Filmográfico',NULL),
(4,'Fotográfico',NULL),
(5,'Hemerográfico',NULL),
(6,'Iconográfico',NULL),
(7,'Musicográfico',NULL),
(8,'Textual',NULL);
/*!40000 ALTER TABLE `genero_documental` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genero_gramatical`
--

DROP TABLE IF EXISTS `genero_gramatical`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `genero_gramatical` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genero_gramatical`
--

LOCK TABLES `genero_gramatical` WRITE;
/*!40000 ALTER TABLE `genero_gramatical` DISABLE KEYS */;
/*!40000 ALTER TABLE `genero_gramatical` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genero_textual`
--

DROP TABLE IF EXISTS `genero_textual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `genero_textual` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genero_textual`
--

LOCK TABLES `genero_textual` WRITE;
/*!40000 ALTER TABLE `genero_textual` DISABLE KEYS */;
/*!40000 ALTER TABLE `genero_textual` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupo_usuario`
--

DROP TABLE IF EXISTS `grupo_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupo_usuario` (
  `Codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `controlar_acesso_acervos` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo_usuario`
--

LOCK TABLES `grupo_usuario` WRITE;
/*!40000 ALTER TABLE `grupo_usuario` DISABLE KEYS */;
INSERT INTO `grupo_usuario` VALUES
(1,'Admistrador(a) do sistema',1),
(2,'Coordenador(a)',1),
(3,'Pesquisador(a)',1),
(4,'Cadastrador(a)',1);
/*!40000 ALTER TABLE `grupo_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupo_usuario_recurso_sistema`
--

DROP TABLE IF EXISTS `grupo_usuario_recurso_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupo_usuario_recurso_sistema` (
  `grupo_usuario_codigo` int(11) NOT NULL,
  `recurso_sistema_codigo` int(11) NOT NULL,
  `ler` tinyint(1) NOT NULL,
  `editar` tinyint(1) NOT NULL,
  `excluir` tinyint(1) DEFAULT NULL,
  `substituir` tinyint(1) DEFAULT NULL,
  `editar_lote` tinyint(1) DEFAULT NULL,
  `excluir_lote` tinyint(1) DEFAULT NULL,
  `inserir` tinyint(1) DEFAULT NULL,
  KEY `Grupo_Usuario_Codigo` (`grupo_usuario_codigo`),
  KEY `Recurso_Sistema_Codigo` (`recurso_sistema_codigo`),
  CONSTRAINT `grupo_usuario_recurso_sistema_ibfk_1` FOREIGN KEY (`grupo_usuario_codigo`) REFERENCES `grupo_usuario` (`Codigo`),
  CONSTRAINT `grupo_usuario_recurso_sistema_ibfk_2` FOREIGN KEY (`recurso_sistema_codigo`) REFERENCES `recurso_sistema` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo_usuario_recurso_sistema`
--

LOCK TABLES `grupo_usuario_recurso_sistema` WRITE;
/*!40000 ALTER TABLE `grupo_usuario_recurso_sistema` DISABLE KEYS */;
INSERT INTO `grupo_usuario_recurso_sistema` VALUES
(1,67,1,1,1,1,1,1,0),
(1,79,0,0,0,0,0,0,0),
(1,59,1,1,1,1,1,1,0),
(1,60,1,1,1,1,1,1,0),
(1,46,1,1,1,1,1,1,0),
(1,66,1,1,1,1,1,1,0),
(1,30,1,1,1,1,1,1,0),
(1,40,1,1,1,1,1,1,0),
(1,82,0,0,0,0,0,0,0),
(1,1,1,1,1,1,1,1,0),
(1,35,1,1,1,1,1,1,0),
(1,2,1,1,1,1,1,1,0),
(1,65,1,1,1,1,1,1,0),
(1,7,1,1,1,1,1,1,0),
(1,81,0,0,0,0,0,0,0),
(1,45,1,1,1,1,1,1,0),
(1,44,1,1,1,1,1,1,0),
(1,52,1,1,1,1,1,1,0),
(1,9,1,1,1,1,1,1,0),
(1,48,0,0,0,0,0,0,0),
(1,4,1,1,1,1,1,1,0),
(1,6,1,1,1,1,1,1,0),
(1,58,0,0,0,0,0,0,0),
(1,11,1,1,1,1,1,1,0),
(1,20,1,1,1,1,1,1,0),
(1,31,1,1,1,1,1,1,0),
(1,77,0,0,0,0,0,0,0),
(1,3,1,1,1,1,1,1,0),
(1,51,1,1,1,1,1,1,0),
(1,25,1,1,1,1,1,1,0),
(1,13,1,1,1,1,1,1,0),
(1,5,1,1,1,1,1,1,0),
(1,15,0,0,0,0,0,0,0),
(1,14,0,0,0,0,0,0,0),
(1,53,1,1,1,1,1,1,0),
(1,42,1,1,1,1,1,1,0),
(1,70,1,1,1,1,1,1,0),
(1,19,1,1,1,1,1,1,0),
(1,21,1,1,1,1,1,1,0),
(1,64,1,1,1,1,1,1,0),
(1,54,1,1,1,1,1,1,0),
(1,78,0,0,0,0,0,0,0),
(1,10,1,1,1,1,1,1,0),
(1,69,1,1,1,1,1,1,0),
(1,16,1,1,1,1,1,1,0),
(1,47,0,0,0,0,0,0,0),
(1,28,1,1,1,1,1,1,0),
(1,76,1,1,1,1,1,1,0),
(1,22,0,0,0,0,0,0,0),
(1,8,1,1,1,1,1,1,0),
(1,12,1,1,1,1,1,1,0),
(1,17,1,1,1,1,1,1,0),
(1,18,1,1,1,1,1,1,0),
(1,33,1,1,1,1,1,1,0),
(4,67,1,0,0,0,0,0,0),
(4,79,1,1,0,0,0,0,1),
(4,59,1,1,1,0,0,0,1),
(4,60,1,1,1,0,0,0,1),
(4,46,1,0,0,0,0,0,0),
(4,66,1,0,0,0,0,0,0),
(4,30,0,0,0,0,0,0,0),
(4,40,1,0,0,0,0,0,0),
(4,82,1,0,0,0,0,0,0),
(4,1,1,1,1,0,0,0,1),
(4,35,1,1,1,0,0,0,1),
(4,2,1,1,1,0,0,0,1),
(4,65,1,1,1,0,0,0,1),
(4,7,1,1,0,0,0,0,1),
(4,81,1,0,0,0,0,0,0),
(4,45,0,0,0,0,0,0,0),
(4,44,0,0,0,0,0,0,0),
(4,52,0,0,0,0,0,0,0),
(4,9,1,0,0,0,0,0,0),
(4,48,1,0,0,0,0,0,0),
(4,4,1,0,0,0,0,0,0),
(4,6,1,0,0,0,0,0,0),
(4,58,1,1,0,0,0,0,1),
(4,11,1,1,0,0,0,0,1),
(4,20,0,0,0,0,0,0,0),
(4,31,1,1,0,0,0,0,0),
(4,77,0,0,0,0,0,0,0),
(4,3,0,0,0,0,0,0,0),
(4,51,0,0,0,0,0,0,0),
(4,25,1,1,1,0,0,0,1),
(4,13,0,0,0,0,0,0,0),
(4,5,1,1,1,0,0,0,1),
(4,15,1,1,1,0,0,0,1),
(4,14,1,1,1,0,0,0,1),
(4,53,0,0,0,0,0,0,0),
(4,42,1,1,1,0,0,0,1),
(4,70,1,0,0,0,0,0,0),
(4,19,0,0,0,0,0,0,0),
(4,21,1,1,1,0,0,0,1),
(4,64,0,0,0,0,0,0,0),
(4,54,0,0,0,0,0,0,0),
(4,78,1,1,0,0,0,0,1),
(4,10,1,1,0,0,0,0,0),
(4,69,1,1,1,0,0,0,1),
(4,16,0,0,0,0,0,0,0),
(4,47,1,0,0,0,0,0,0),
(4,28,0,0,0,0,0,0,0),
(4,76,1,0,0,0,0,0,0),
(4,22,1,0,0,0,0,0,0),
(4,8,1,1,1,0,0,0,1),
(4,12,1,0,0,0,0,0,0),
(4,17,0,0,0,0,0,0,0),
(4,18,0,0,0,0,0,0,0),
(4,33,0,0,0,0,0,0,0),
(2,67,1,1,1,1,0,0,1),
(2,79,1,1,1,1,0,0,1),
(2,59,1,1,1,1,0,0,1),
(2,60,1,1,1,1,0,0,1),
(2,46,1,1,1,1,0,0,1),
(2,66,1,1,1,1,0,0,1),
(2,30,0,0,0,0,0,0,0),
(2,40,1,1,1,1,0,0,1),
(2,82,1,1,1,1,0,0,1),
(2,1,1,1,1,1,1,0,1),
(2,35,1,1,1,1,0,0,1),
(2,2,1,1,1,1,0,0,1),
(2,65,1,1,1,1,0,0,1),
(2,7,1,1,1,1,0,0,1),
(2,81,1,1,1,1,0,0,1),
(2,45,0,0,0,0,0,0,0),
(2,44,0,0,0,0,0,0,0),
(2,52,1,1,1,1,0,0,1),
(2,9,1,1,1,1,0,0,1),
(2,48,1,1,1,1,0,0,1),
(2,4,1,1,1,1,0,0,1),
(2,6,1,1,1,1,0,0,1),
(2,58,1,1,1,1,0,0,1),
(2,11,1,1,1,1,0,0,1),
(2,20,0,0,0,0,0,0,0),
(2,31,1,1,1,1,0,0,1),
(2,77,0,0,0,0,0,0,0),
(2,3,1,0,0,0,0,0,0),
(2,51,0,0,0,0,0,0,0),
(2,25,1,1,1,1,0,0,1),
(2,13,1,1,1,1,0,0,1),
(2,5,1,1,1,1,0,0,1),
(2,15,1,1,1,1,0,0,1),
(2,14,1,1,1,1,0,0,1),
(2,53,1,1,1,1,0,0,1),
(2,42,1,1,1,1,0,0,1),
(2,70,1,1,1,1,0,0,1),
(2,19,0,0,0,0,0,0,0),
(2,21,1,1,1,1,0,0,1),
(2,64,0,0,0,0,0,0,0),
(2,54,0,0,0,0,0,0,0),
(2,78,1,1,1,1,0,0,1),
(2,10,1,1,1,1,0,0,1),
(2,69,1,1,1,1,0,0,1),
(2,16,1,1,0,0,0,0,0),
(2,47,1,1,1,1,0,0,1),
(2,28,1,0,0,0,0,0,0),
(2,76,1,1,1,1,0,0,1),
(2,22,1,1,1,1,0,0,1),
(2,8,1,1,1,1,0,0,1),
(2,12,1,1,1,1,0,0,1),
(2,17,1,0,0,0,0,0,0),
(2,18,1,1,1,0,0,0,1),
(2,33,0,0,0,0,0,0,0),
(3,67,1,0,0,0,0,0,0),
(3,79,1,0,0,0,0,0,0),
(3,59,1,0,0,0,0,0,0),
(3,60,1,0,0,0,0,0,0),
(3,46,0,0,0,0,0,0,0),
(3,66,1,0,0,0,0,0,0),
(3,30,0,0,0,0,0,0,0),
(3,40,1,0,0,0,0,0,0),
(3,82,1,0,0,0,0,0,0),
(3,1,1,0,0,0,0,0,0),
(3,35,1,0,0,0,0,0,0),
(3,2,1,0,0,0,0,0,0),
(3,65,1,0,0,0,0,0,0),
(3,7,1,0,0,0,0,0,0),
(3,81,1,0,0,0,0,0,0),
(3,45,0,0,0,0,0,0,0),
(3,44,0,0,0,0,0,0,0),
(3,52,0,0,0,0,0,0,0),
(3,9,1,0,0,0,0,0,0),
(3,48,1,0,0,0,0,0,0),
(3,4,1,0,0,0,0,0,0),
(3,6,1,0,0,0,0,0,0),
(3,58,1,0,0,0,0,0,0),
(3,11,1,0,0,0,0,0,0),
(3,20,0,0,0,0,0,0,0),
(3,31,1,0,0,0,0,0,0),
(3,77,0,0,0,0,0,0,0),
(3,3,0,0,0,0,0,0,0),
(3,51,0,0,0,0,0,0,0),
(3,25,1,0,0,0,0,0,0),
(3,13,1,0,0,0,0,0,0),
(3,5,1,0,0,0,0,0,0),
(3,15,1,0,0,0,0,0,0),
(3,14,1,0,0,0,0,0,0),
(3,53,0,0,0,0,0,0,0),
(3,42,1,0,0,0,0,0,0),
(3,70,1,0,0,0,0,0,0),
(3,19,0,0,0,0,0,0,0),
(3,21,1,1,1,0,0,0,1),
(3,64,0,0,0,0,0,0,0),
(3,54,0,0,0,0,0,0,0),
(3,78,1,0,0,0,0,0,0),
(3,10,1,0,0,0,0,0,0),
(3,69,1,0,0,0,0,0,0),
(3,16,0,0,0,0,0,0,0),
(3,47,1,0,0,0,0,0,0),
(3,28,0,0,0,0,0,0,0),
(3,76,1,0,0,0,0,0,0),
(3,22,1,0,0,0,0,0,0),
(3,8,1,0,0,0,0,0,0),
(3,12,1,0,0,0,0,0,0),
(3,17,1,0,0,0,0,0,0),
(3,18,0,0,0,0,0,0,0),
(3,33,0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `grupo_usuario_recurso_sistema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `idioma`
--

DROP TABLE IF EXISTS `idioma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `idioma` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `sigla` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `idioma`
--

LOCK TABLES `idioma` WRITE;
/*!40000 ALTER TABLE `idioma` DISABLE KEYS */;
INSERT INTO `idioma` VALUES
(1,'Português','PT'),
(3,'Abecásio','AB'),
(4,'Afar','AA'),
(5,'Africâner','AF'),
(6,'Akan','AK'),
(7,'Albanês','SQ'),
(8,'Amárico','AM'),
(9,'Árabe','AR'),
(10,'Aragonês','AN'),
(11,'Armênio','HY'),
(12,'Assamês','AS'),
(13,'Ávar','AV'),
(14,'Avéstico','AE'),
(15,'Aymará','AY'),
(16,'Azerbaijano','AZ'),
(17,'Bambara','BM'),
(18,'Bashkir','BA'),
(19,'Basco','EU'),
(20,'Bielorrusso','BE'),
(21,'Bengali','BN'),
(22,'Bihari','BH'),
(23,'Bislamá','BI'),
(24,'Bósnio','BS'),
(25,'Bretão','BR'),
(26,'Búlgaro','BG'),
(27,'Birmanês','MY'),
(28,'Catalão','CA'),
(29,'Chamorro','CH'),
(30,'Chichewa','NY'),
(31,'Chinês','ZH'),
(32,'Chuvash','CV'),
(33,'Córnico','KW'),
(34,'Córso','CO'),
(35,'Croata','HR'),
(36,'Dinamarquês','DA'),
(37,'Divehi','DV'),
(38,'Holandês','NL'),
(39,'Inglês','EN'),
(40,'Esperanto','EO'),
(41,'Estoniano','ET'),
(42,'Ewe','EE'),
(43,'Faroês','FO'),
(44,'Fijiano','FJ'),
(45,'Finlandês','FI'),
(46,'Francês','FR'),
(47,'Fula','FF'),
(48,'Galego','GL'),
(49,'Georgiano','KA'),
(50,'Alemão','DE'),
(51,'Grego','EL'),
(52,'Guarani','GN'),
(53,'Guzerate','GU'),
(54,'Crioulo haitiano','HT'),
(55,'Hauçá','HA'),
(56,'Hebraico','HE'),
(57,'Herero','HZ'),
(58,'Hindi','HI'),
(59,'Hiri Motu','HO'),
(60,'Húngaro','HU'),
(61,'Interlíngua','IA'),
(62,'Indonésio','ID'),
(63,'Interlingue','IE'),
(64,'Irlandês','GA'),
(65,'Islandês','IS'),
(66,'Italiano','IT'),
(67,'Inuktitut','IU'),
(68,'Japonês','JA'),
(69,'Javanês','JV'),
(70,'Groenlandês','KL'),
(71,'Canará','KN'),
(72,'Kanuri','KR'),
(73,'Caxemira','KS'),
(74,'Cazaque','KK'),
(75,'Khmer','KM'),
(76,'Congolês','KG'),
(77,'Coreano','KO'),
(78,'Curdo','KU'),
(79,'Latim','LA'),
(80,'Luxemburguês','LB'),
(81,'Luganda','LG'),
(82,'Limburguês','LI'),
(83,'Lingala','LN'),
(84,'Lao','LO'),
(85,'Lituano','LT'),
(86,'Luba-Katanga','LU'),
(87,'Letão','LV'),
(88,'Manx','GV'),
(89,'Macedônio','MK'),
(90,'Malgaxe','MG'),
(91,'Malaio','MS'),
(92,'Malayalam','ML'),
(93,'Maltês','MT'),
(94,'Maori','MI'),
(95,'Marathi','MR'),
(96,'Marshalês','MH'),
(97,'Mongol','MN'),
(98,'Nauru','NA'),
(99,'Navajo','NV'),
(100,'Ndebele do Norte','ND'),
(101,'Nepali','NE'),
(102,'Ndonga','NG'),
(103,'Norueguês ','NN'),
(104,'Nuosu','II'),
(105,'Ndebele do Sul','NR'),
(106,'Ossétio','OC'),
(107,'Ojibua','OJ'),
(108,'Eslavo','CU'),
(109,'Oromo','OM'),
(110,'Oriá','OR'),
(111,'Osseta','OS'),
(112,'Panjabi','PA'),
(113,'Pāli','PI'),
(114,'Persa','FA'),
(115,'Polonês','PL'),
(116,'Pachto','PS'),
(117,'Quechua','QU'),
(118,'Romanche','RM'),
(119,'Kirundi','RN'),
(120,'Romeno','RO'),
(121,'Russo','RU'),
(122,'Sânscrito','SA'),
(123,'Sardo','SC'),
(124,'Sindi','SD'),
(125,'Samoano','SM'),
(126,'Sango','SG'),
(127,'Sérvio','SR'),
(128,'Gaélico','GD'),
(129,'Cingalês','SI'),
(130,'Eslovaco','SK'),
(131,'Esloveno','SL'),
(132,'Somali','SO'),
(133,'Espanhol','ES'),
(134,'Sundanês','SU'),
(135,'Suaíli','SW'),
(136,'Suazi','SS'),
(137,'Sueco','SV'),
(138,'Tâmil','TA'),
(139,'Telugu','TE'),
(140,'Tajique','TG'),
(141,'Tailandês','TH'),
(142,'Tigrínia','TI'),
(143,'Tibetano','BO'),
(144,'Turcomeno','TK'),
(145,'Tonga','TO'),
(146,'Turco','TR'),
(147,'Tsonga','TS'),
(148,'Tatar','TT'),
(149,'Twi','TW'),
(150,'Taitiano','TY'),
(151,'Uigur','UG'),
(152,'Ucraniano','UK'),
(153,'Urdu','UR'),
(154,'Uzbeque','UZ'),
(155,'Venda','VE'),
(156,'Vietnamita','VI'),
(157,'Volapük','VO'),
(158,'Valão','WA'),
(159,'Galês','CY'),
(160,'Wolof','WO'),
(161,'Frísio','FY'),
(162,'Xhosa','XH'),
(163,'Iídiche','YI'),
(164,'Iorubá','YO'),
(165,'Zhuang','ZA'),
(166,'Zulu','ZU');
/*!40000 ALTER TABLE `idioma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `importacao`
--

DROP TABLE IF EXISTS `importacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `importacao` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `recurso_sistema_codigo` int(11) NOT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `recurso_sistema_codigo` (`recurso_sistema_codigo`) USING BTREE,
  CONSTRAINT `visualizacao_ibfk_1_copy` FOREIGN KEY (`recurso_sistema_codigo`) REFERENCES `recurso_sistema` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `importacao`
--

LOCK TABLES `importacao` WRITE;
/*!40000 ALTER TABLE `importacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `importacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `importacao_campo_sistema`
--

DROP TABLE IF EXISTS `importacao_campo_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `importacao_campo_sistema` (
  `importacao_codigo` int(11) NOT NULL,
  `campo_sistema_codigo` int(11) NOT NULL,
  `sequencia` int(11) DEFAULT NULL,
  KEY `visualizacao_campo_sistema_FK` (`campo_sistema_codigo`) USING BTREE,
  KEY `visualizacao_campo_sistema_FK_1` (`importacao_codigo`) USING BTREE,
  CONSTRAINT `importacao_campo_sistema_FK` FOREIGN KEY (`importacao_codigo`) REFERENCES `importacao` (`codigo`),
  CONSTRAINT `visualizacao_campo_sistema_FK_copy` FOREIGN KEY (`campo_sistema_codigo`) REFERENCES `campo_sistema` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `importacao_campo_sistema`
--

LOCK TABLES `importacao_campo_sistema` WRITE;
/*!40000 ALTER TABLE `importacao_campo_sistema` DISABLE KEYS */;
/*!40000 ALTER TABLE `importacao_campo_sistema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `incorporacao`
--

DROP TABLE IF EXISTS `incorporacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `incorporacao` (
  `codigo` int(11) NOT NULL,
  `tipo_codigo` int(11) NOT NULL,
  `data` date NOT NULL,
  `descricao` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incorporacao`
--

LOCK TABLES `incorporacao` WRITE;
/*!40000 ALTER TABLE `incorporacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `incorporacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `incorporacao_entidade`
--

DROP TABLE IF EXISTS `incorporacao_entidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `incorporacao_entidade` (
  `incorporacao_codigo` int(11) NOT NULL,
  `entidade_codigo` int(11) NOT NULL,
  `funcao_codigo` int(11) DEFAULT NULL,
  KEY `entidade_codigo` (`entidade_codigo`),
  KEY `item_acervo_codigo` (`incorporacao_codigo`),
  CONSTRAINT `incorporacao_entidade_ibfk_1` FOREIGN KEY (`entidade_codigo`) REFERENCES `entidade` (`codigo`),
  CONSTRAINT `incorporacao_entidade_ibfk_2` FOREIGN KEY (`incorporacao_codigo`) REFERENCES `incorporacao` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incorporacao_entidade`
--

LOCK TABLES `incorporacao_entidade` WRITE;
/*!40000 ALTER TABLE `incorporacao_entidade` DISABLE KEYS */;
/*!40000 ALTER TABLE `incorporacao_entidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `incorporacao_item_acervo`
--

DROP TABLE IF EXISTS `incorporacao_item_acervo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `incorporacao_item_acervo` (
  `incorporacao_codigo` int(11) NOT NULL,
  `item_acervo_codigo` int(11) NOT NULL,
  PRIMARY KEY (`incorporacao_codigo`,`item_acervo_codigo`),
  KEY `selecao_codigo` (`incorporacao_codigo`),
  KEY `item_acervo_codigo` (`item_acervo_codigo`),
  CONSTRAINT `incorporacao_item_acervo_FK` FOREIGN KEY (`incorporacao_codigo`) REFERENCES `incorporacao` (`codigo`),
  CONSTRAINT `incorporacao_item_acervo_FK_1` FOREIGN KEY (`item_acervo_codigo`) REFERENCES `item_acervo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incorporacao_item_acervo`
--

LOCK TABLES `incorporacao_item_acervo` WRITE;
/*!40000 ALTER TABLE `incorporacao_item_acervo` DISABLE KEYS */;
/*!40000 ALTER TABLE `incorporacao_item_acervo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instituicao`
--

DROP TABLE IF EXISTS `instituicao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `instituicao` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `entidade_codigo` int(11) DEFAULT NULL,
  `admin` tinyint(1) DEFAULT NULL COMMENT 'Especifica se os usuários dessa instituição podem gerenciar outras instituições.',
  `sigla` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `Entidade_Codigo` (`entidade_codigo`),
  CONSTRAINT `instituicao_ibfk_1` FOREIGN KEY (`entidade_codigo`) REFERENCES `entidade` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instituicao`
--

LOCK TABLES `instituicao` WRITE;
/*!40000 ALTER TABLE `instituicao` DISABLE KEYS */;
/*!40000 ALTER TABLE `instituicao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_acervo`
--

DROP TABLE IF EXISTS `item_acervo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_acervo` (
  `codigo` int(11) NOT NULL,
  `instituicao_codigo` int(11) DEFAULT NULL,
  `acervo_codigo` int(11) DEFAULT NULL,
  `identificador` varchar(100) DEFAULT NULL,
  `titulo` varchar(1000) DEFAULT NULL,
  `subtitulo` varchar(1000) DEFAULT NULL,
  `titulo_completo` varchar(2000) DEFAULT NULL,
  `titulo_traduzido` varchar(1000) DEFAULT NULL,
  `genero_textual_codigo` int(11) DEFAULT NULL,
  `data_inicial` date DEFAULT NULL,
  `data_final` date DEFAULT NULL,
  `data_presumida` tinyint(1) DEFAULT NULL,
  `sem_data` tinyint(1) DEFAULT NULL,
  `status_codigo` int(11) DEFAULT NULL,
  `publicado_online` tinyint(1) NOT NULL DEFAULT 0,
  `unidade_armazenamento_codigo` int(11) DEFAULT NULL,
  `tipo_acesso_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `status_codigo` (`status_codigo`),
  KEY `acervo_codigo` (`acervo_codigo`),
  KEY `genero_textual_codigo` (`genero_textual_codigo`),
  KEY `item_acervo_unidade_armazenamento_null_fk` (`unidade_armazenamento_codigo`),
  KEY `item_acervo_tipo_acesso_null_fk` (`tipo_acesso_codigo`),
  KEY `item_acervo_FK` (`instituicao_codigo`),
  CONSTRAINT `item_acervo_FK` FOREIGN KEY (`instituicao_codigo`) REFERENCES `instituicao` (`codigo`),
  CONSTRAINT `item_acervo_ibfk_1` FOREIGN KEY (`status_codigo`) REFERENCES `status_item_acervo` (`codigo`),
  CONSTRAINT `item_acervo_ibfk_2` FOREIGN KEY (`acervo_codigo`) REFERENCES `acervo` (`codigo`),
  CONSTRAINT `item_acervo_ibfk_3` FOREIGN KEY (`genero_textual_codigo`) REFERENCES `genero_textual` (`codigo`),
  CONSTRAINT `item_acervo_tipo_acesso_null_fk` FOREIGN KEY (`tipo_acesso_codigo`) REFERENCES `tipo_acesso` (`codigo`),
  CONSTRAINT `item_acervo_unidade_armazenamento_null_fk` FOREIGN KEY (`unidade_armazenamento_codigo`) REFERENCES `unidade_armazenamento` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_acervo`
--

LOCK TABLES `item_acervo` WRITE;
/*!40000 ALTER TABLE `item_acervo` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_acervo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_acervo_assunto`
--

DROP TABLE IF EXISTS `item_acervo_assunto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_acervo_assunto` (
  `item_acervo_codigo` int(11) NOT NULL,
  `assunto_codigo` int(11) NOT NULL,
  PRIMARY KEY (`item_acervo_codigo`,`assunto_codigo`),
  KEY `item_acervo_assunto_assunto_null_fk` (`assunto_codigo`),
  CONSTRAINT `item_acervo_assunto_assunto_null_fk` FOREIGN KEY (`assunto_codigo`) REFERENCES `assunto` (`codigo`),
  CONSTRAINT `item_acervo_assunto_item_acervo_null_fk` FOREIGN KEY (`item_acervo_codigo`) REFERENCES `item_acervo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_acervo_assunto`
--

LOCK TABLES `item_acervo_assunto` WRITE;
/*!40000 ALTER TABLE `item_acervo_assunto` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_acervo_assunto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_acervo_dados_textuais`
--

DROP TABLE IF EXISTS `item_acervo_dados_textuais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_acervo_dados_textuais` (
  `item_acervo_codigo` int(11) NOT NULL,
  `titulo` varchar(1000) DEFAULT NULL,
  `subtitulo` varchar(1000) DEFAULT NULL,
  `titulo_completo` varchar(2000) DEFAULT NULL,
  `titulo_original` varchar(1000) DEFAULT NULL,
  `titulo_transliterado` varchar(2000) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `notas_conteudo` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `idioma_codigo` int(11) NOT NULL,
  PRIMARY KEY (`item_acervo_codigo`,`idioma_codigo`),
  KEY `item_acervo_codigo` (`item_acervo_codigo`),
  KEY `idioma_codigo` (`idioma_codigo`),
  CONSTRAINT `item_acervo_dados_textuais_ibfk_1` FOREIGN KEY (`item_acervo_codigo`) REFERENCES `item_acervo` (`codigo`),
  CONSTRAINT `item_acervo_dados_textuais_ibfk_2` FOREIGN KEY (`idioma_codigo`) REFERENCES `idioma` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_acervo_dados_textuais`
--

LOCK TABLES `item_acervo_dados_textuais` WRITE;
/*!40000 ALTER TABLE `item_acervo_dados_textuais` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_acervo_dados_textuais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_acervo_dimensao`
--

DROP TABLE IF EXISTS `item_acervo_dimensao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_acervo_dimensao` (
  `item_acervo_codigo` int(11) NOT NULL,
  `tipo_dimensao_codigo` int(11) NOT NULL,
  `valor` varchar(50) NOT NULL,
  `unidade_medida_codigo` int(11) DEFAULT NULL,
  KEY `item_acervo_dimensao_item_acervo_null_fk` (`item_acervo_codigo`),
  KEY `item_acervo_dimensao_tipo_dimensao_null_fk` (`tipo_dimensao_codigo`),
  KEY `item_acervo_dimensao_unidade_medida_null_fk` (`unidade_medida_codigo`),
  CONSTRAINT `item_acervo_dimensao_item_acervo_null_fk` FOREIGN KEY (`item_acervo_codigo`) REFERENCES `item_acervo` (`codigo`),
  CONSTRAINT `item_acervo_dimensao_tipo_dimensao_null_fk` FOREIGN KEY (`tipo_dimensao_codigo`) REFERENCES `tipo_dimensao` (`Codigo`),
  CONSTRAINT `item_acervo_dimensao_unidade_medida_null_fk` FOREIGN KEY (`unidade_medida_codigo`) REFERENCES `unidade_medida` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_acervo_dimensao`
--

LOCK TABLES `item_acervo_dimensao` WRITE;
/*!40000 ALTER TABLE `item_acervo_dimensao` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_acervo_dimensao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_acervo_entidade`
--

DROP TABLE IF EXISTS `item_acervo_entidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_acervo_entidade` (
  `item_acervo_codigo` int(11) NOT NULL,
  `tipo_autor_codigo` int(11) DEFAULT NULL,
  `entidade_codigo` int(11) DEFAULT NULL,
  `funcao_entidade` varchar(300) DEFAULT NULL,
  `entidade_presumida` tinyint(1) DEFAULT NULL,
  KEY `entidade_codigo` (`entidade_codigo`),
  KEY `item_acervo_codigo` (`item_acervo_codigo`),
  KEY `item_acervo_entidade_FK` (`tipo_autor_codigo`),
  CONSTRAINT `item_acervo_entidade_FK` FOREIGN KEY (`tipo_autor_codigo`) REFERENCES `tipo_autor` (`codigo`),
  CONSTRAINT `item_acervo_entidade_ibfk_1` FOREIGN KEY (`entidade_codigo`) REFERENCES `entidade` (`codigo`),
  CONSTRAINT `item_acervo_entidade_ibfk_2` FOREIGN KEY (`item_acervo_codigo`) REFERENCES `item_acervo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_acervo_entidade`
--

LOCK TABLES `item_acervo_entidade` WRITE;
/*!40000 ALTER TABLE `item_acervo_entidade` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_acervo_entidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_acervo_estado_conservacao`
--

DROP TABLE IF EXISTS `item_acervo_estado_conservacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_acervo_estado_conservacao` (
  `item_acervo_codigo` int(11) DEFAULT NULL,
  `estado_conservacao_codigo` int(11) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  KEY `item_acervo_estado_conservacao_item_acervo_codigo_fk` (`item_acervo_codigo`),
  KEY `item_acervo_estado_conservacao_estado_conservacao_codigo_fk` (`estado_conservacao_codigo`),
  CONSTRAINT `item_acervo_estado_conservacao_estado_conservacao_codigo_fk` FOREIGN KEY (`estado_conservacao_codigo`) REFERENCES `estado_conservacao` (`codigo`),
  CONSTRAINT `item_acervo_estado_conservacao_item_acervo_codigo_fk` FOREIGN KEY (`item_acervo_codigo`) REFERENCES `item_acervo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_acervo_estado_conservacao`
--

LOCK TABLES `item_acervo_estado_conservacao` WRITE;
/*!40000 ALTER TABLE `item_acervo_estado_conservacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_acervo_estado_conservacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_acervo_idioma`
--

DROP TABLE IF EXISTS `item_acervo_idioma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_acervo_idioma` (
  `item_acervo_codigo` int(11) NOT NULL,
  `idioma_codigo` int(11) NOT NULL,
  PRIMARY KEY (`item_acervo_codigo`,`idioma_codigo`),
  KEY `Idioma_Codigo` (`idioma_codigo`),
  KEY `item_acervo_codigo` (`item_acervo_codigo`),
  CONSTRAINT `item_acervo_idioma_ibfk_1` FOREIGN KEY (`idioma_codigo`) REFERENCES `idioma` (`codigo`),
  CONSTRAINT `item_acervo_idioma_ibfk_2` FOREIGN KEY (`item_acervo_codigo`) REFERENCES `item_acervo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_acervo_idioma`
--

LOCK TABLES `item_acervo_idioma` WRITE;
/*!40000 ALTER TABLE `item_acervo_idioma` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_acervo_idioma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_acervo_item_acervo`
--

DROP TABLE IF EXISTS `item_acervo_item_acervo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_acervo_item_acervo` (
  `item_acervo_1_codigo` int(11) NOT NULL,
  `item_acervo_2_codigo` int(11) NOT NULL,
  PRIMARY KEY (`item_acervo_1_codigo`,`item_acervo_2_codigo`),
  KEY `item_acervo_item_acervo_FK_1` (`item_acervo_2_codigo`),
  CONSTRAINT `item_acervo_item_acervo_FK` FOREIGN KEY (`item_acervo_1_codigo`) REFERENCES `item_acervo` (`codigo`),
  CONSTRAINT `item_acervo_item_acervo_FK_1` FOREIGN KEY (`item_acervo_2_codigo`) REFERENCES `item_acervo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_acervo_item_acervo`
--

LOCK TABLES `item_acervo_item_acervo` WRITE;
/*!40000 ALTER TABLE `item_acervo_item_acervo` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_acervo_item_acervo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_acervo_localidade`
--

DROP TABLE IF EXISTS `item_acervo_localidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_acervo_localidade` (
  `item_acervo_codigo` int(11) NOT NULL,
  `localidade_codigo` int(11) NOT NULL,
  `localidade_presumida` int(11) DEFAULT NULL,
  PRIMARY KEY (`localidade_codigo`,`item_acervo_codigo`),
  KEY `localidade_codigo` (`localidade_codigo`),
  KEY `item_acervo_codigo` (`item_acervo_codigo`),
  CONSTRAINT `item_acervo_localidade_ibfk_1` FOREIGN KEY (`localidade_codigo`) REFERENCES `localidade` (`codigo`),
  CONSTRAINT `item_acervo_localidade_ibfk_2` FOREIGN KEY (`item_acervo_codigo`) REFERENCES `item_acervo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_acervo_localidade`
--

LOCK TABLES `item_acervo_localidade` WRITE;
/*!40000 ALTER TABLE `item_acervo_localidade` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_acervo_localidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_acervo_palavra_chave`
--

DROP TABLE IF EXISTS `item_acervo_palavra_chave`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_acervo_palavra_chave` (
  `item_acervo_codigo` int(11) NOT NULL,
  `palavra_chave_codigo` int(11) NOT NULL,
  PRIMARY KEY (`item_acervo_codigo`,`palavra_chave_codigo`),
  KEY `palavra_chave_codigo` (`palavra_chave_codigo`),
  KEY `item_acervo_codigo` (`item_acervo_codigo`),
  CONSTRAINT `item_acervo_palavra_chave_ibfk_1` FOREIGN KEY (`palavra_chave_codigo`) REFERENCES `palavra_chave` (`codigo`),
  CONSTRAINT `item_acervo_palavra_chave_ibfk_2` FOREIGN KEY (`item_acervo_codigo`) REFERENCES `item_acervo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_acervo_palavra_chave`
--

LOCK TABLES `item_acervo_palavra_chave` WRITE;
/*!40000 ALTER TABLE `item_acervo_palavra_chave` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_acervo_palavra_chave` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_acervo_suporte`
--

DROP TABLE IF EXISTS `item_acervo_suporte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_acervo_suporte` (
  `item_acervo_codigo` int(11) NOT NULL,
  `suporte_codigo` int(11) NOT NULL,
  PRIMARY KEY (`item_acervo_codigo`,`suporte_codigo`),
  KEY `item_acervo_suporte_suporte_null_fk` (`suporte_codigo`),
  CONSTRAINT `item_acervo_suporte_item_acervo_null_fk` FOREIGN KEY (`item_acervo_codigo`) REFERENCES `item_acervo` (`codigo`),
  CONSTRAINT `item_acervo_suporte_suporte_null_fk` FOREIGN KEY (`suporte_codigo`) REFERENCES `suporte` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_acervo_suporte`
--

LOCK TABLES `item_acervo_suporte` WRITE;
/*!40000 ALTER TABLE `item_acervo_suporte` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_acervo_suporte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `livro`
--

DROP TABLE IF EXISTS `livro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `livro` (
  `codigo` int(11) NOT NULL,
  `item_acervo_codigo` int(11) NOT NULL,
  `classificacao` varchar(50) DEFAULT NULL,
  `volume` int(11) DEFAULT NULL,
  `titulo_volume` varchar(2000) DEFAULT NULL,
  `colecao` varchar(500) DEFAULT NULL,
  `numero_item_colecao` varchar(20) DEFAULT NULL,
  `edicao_princeps` tinyint(1) DEFAULT NULL,
  `edicao` varchar(50) DEFAULT NULL,
  `tipo_edicao_codigo` int(11) DEFAULT NULL,
  `numero_paginas` int(11) DEFAULT NULL,
  `ISBN` varchar(50) DEFAULT NULL,
  `ISSN` varchar(10) DEFAULT NULL,
  `cutter_pha` varchar(50) DEFAULT NULL,
  `creditos` varchar(1000) DEFAULT NULL,
  `exemplar` int(11) DEFAULT NULL,
  `tipo_material_codigo` int(11) DEFAULT NULL,
  `tombo` varchar(500) DEFAULT NULL,
  `serie` varchar(500) DEFAULT NULL,
  `numero` varchar(500) DEFAULT NULL,
  `tomo` varchar(500) DEFAULT NULL,
  `genero_textual_codigo` int(11) DEFAULT NULL,
  `epc` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `item_acervo_Codigo` (`item_acervo_codigo`),
  KEY `Tipo_Edicao_Codigo` (`tipo_edicao_codigo`),
  KEY `livro_tipo_material_null_fk` (`tipo_material_codigo`),
  KEY `livro_genero_textual_codigo_fk` (`genero_textual_codigo`),
  CONSTRAINT `livro_genero_textual_codigo_fk` FOREIGN KEY (`genero_textual_codigo`) REFERENCES `genero_textual` (`codigo`),
  CONSTRAINT `livro_ibfk_2` FOREIGN KEY (`item_acervo_codigo`) REFERENCES `item_acervo` (`codigo`),
  CONSTRAINT `livro_ibfk_3` FOREIGN KEY (`tipo_edicao_codigo`) REFERENCES `tipo_edicao` (`codigo`),
  CONSTRAINT `livro_tipo_material_null_fk` FOREIGN KEY (`tipo_material_codigo`) REFERENCES `tipo_material` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `livro`
--

LOCK TABLES `livro` WRITE;
/*!40000 ALTER TABLE `livro` DISABLE KEYS */;
/*!40000 ALTER TABLE `livro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `livro_area_conhecimento`
--

DROP TABLE IF EXISTS `livro_area_conhecimento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `livro_area_conhecimento` (
  `livro_codigo` int(11) NOT NULL,
  `area_conhecimento_codigo` int(11) NOT NULL,
  PRIMARY KEY (`livro_codigo`,`area_conhecimento_codigo`),
  KEY `item_acervo_area_conhecimento_area_conhecimento_null_fk` (`area_conhecimento_codigo`),
  CONSTRAINT `item_acervo_area_conhecimento_area_conhecimento_null_fk` FOREIGN KEY (`area_conhecimento_codigo`) REFERENCES `area_conhecimento` (`codigo`),
  CONSTRAINT `item_acervo_area_conhecimento_item_acervo_null_fk` FOREIGN KEY (`livro_codigo`) REFERENCES `livro` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `livro_area_conhecimento`
--

LOCK TABLES `livro_area_conhecimento` WRITE;
/*!40000 ALTER TABLE `livro_area_conhecimento` DISABLE KEYS */;
/*!40000 ALTER TABLE `livro_area_conhecimento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `livro_colecao`
--

DROP TABLE IF EXISTS `livro_colecao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `livro_colecao` (
  `livro_codigo` int(11) NOT NULL,
  `colecao_codigo` int(11) NOT NULL,
  PRIMARY KEY (`livro_codigo`,`colecao_codigo`),
  KEY `livro_colecao_FK_1` (`colecao_codigo`),
  CONSTRAINT `livro_colecao_FK` FOREIGN KEY (`livro_codigo`) REFERENCES `livro` (`codigo`),
  CONSTRAINT `livro_colecao_FK_1` FOREIGN KEY (`colecao_codigo`) REFERENCES `colecao` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `livro_colecao`
--

LOCK TABLES `livro_colecao` WRITE;
/*!40000 ALTER TABLE `livro_colecao` DISABLE KEYS */;
/*!40000 ALTER TABLE `livro_colecao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `livro_editora`
--

DROP TABLE IF EXISTS `livro_editora`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `livro_editora` (
  `livro_codigo` int(11) NOT NULL,
  `editora_codigo` int(11) NOT NULL,
  `localidade_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`livro_codigo`,`editora_codigo`),
  KEY `livro_codigo` (`livro_codigo`),
  KEY `localidade_codigo` (`localidade_codigo`),
  KEY `editora_codigo` (`editora_codigo`),
  CONSTRAINT `livro_editora_ibfk_1` FOREIGN KEY (`livro_codigo`) REFERENCES `livro` (`codigo`),
  CONSTRAINT `livro_editora_ibfk_2` FOREIGN KEY (`localidade_codigo`) REFERENCES `localidade` (`codigo`),
  CONSTRAINT `livro_editora_ibfk_3` FOREIGN KEY (`editora_codigo`) REFERENCES `editora` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `livro_editora`
--

LOCK TABLES `livro_editora` WRITE;
/*!40000 ALTER TABLE `livro_editora` DISABLE KEYS */;
/*!40000 ALTER TABLE `livro_editora` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `livro_tema`
--

DROP TABLE IF EXISTS `livro_tema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `livro_tema` (
  `livro_codigo` int(11) NOT NULL,
  `tema_codigo` int(11) NOT NULL,
  PRIMARY KEY (`livro_codigo`,`tema_codigo`),
  KEY `livro_tema_FK_1` (`tema_codigo`),
  CONSTRAINT `livro_tema_FK` FOREIGN KEY (`livro_codigo`) REFERENCES `livro` (`codigo`),
  CONSTRAINT `livro_tema_FK_1` FOREIGN KEY (`tema_codigo`) REFERENCES `tema` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `livro_tema`
--

LOCK TABLES `livro_tema` WRITE;
/*!40000 ALTER TABLE `livro_tema` DISABLE KEYS */;
/*!40000 ALTER TABLE `livro_tema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `local_armazenamento`
--

DROP TABLE IF EXISTS `local_armazenamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `local_armazenamento` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `instituicao_codigo` int(11) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `local_armazenamento`
--

LOCK TABLES `local_armazenamento` WRITE;
/*!40000 ALTER TABLE `local_armazenamento` DISABLE KEYS */;
/*!40000 ALTER TABLE `local_armazenamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `localidade`
--

DROP TABLE IF EXISTS `localidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `localidade` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `localidade`
--

LOCK TABLES `localidade` WRITE;
/*!40000 ALTER TABLE `localidade` DISABLE KEYS */;
INSERT INTO `localidade` VALUES
(2,'Ariquemes, RO, BRA'),
(3,'Cacoal, RO, BRA'),
(4,'Jaru, RO, BRA'),
(5,'Ji-Paraná, RO, BRA'),
(6,'Porto Velho, RO, BRA'),
(7,'Rolim de Moura, RO, BRA'),
(8,'Vilhena, RO, BRA'),
(9,'Cruzeiro do Sul, AC, BRA'),
(10,'Rio Branco, AC, BRA'),
(11,'Coari, AM, BRA'),
(12,'Humaitá, AM, BRA'),
(13,'Itacoatiara, AM, BRA'),
(14,'Manacapuru, AM, BRA'),
(15,'Manaus, AM, BRA'),
(16,'Manicoré, AM, BRA'),
(17,'Maués, AM, BRA'),
(18,'Parintins, AM, BRA'),
(19,'Tabatinga, AM, BRA'),
(20,'Tefé, AM, BRA'),
(21,'Boa Vista, RR, BRA'),
(22,'Abaetetuba, PA, BRA'),
(23,'Acará, PA, BRA'),
(24,'Alenquer, PA, BRA'),
(25,'Altamira, PA, BRA'),
(26,'Ananindeua, PA, BRA'),
(27,'Barcarena, PA, BRA'),
(28,'Belém, PA, BRA'),
(29,'Benevides, PA, BRA'),
(30,'Bragança, PA, BRA'),
(31,'Breu Branco, PA, BRA'),
(32,'Breves, PA, BRA'),
(33,'Cametá, PA, BRA'),
(34,'Capanema, PA, BRA'),
(35,'Capitão Poço, PA, BRA'),
(36,'Castanhal, PA, BRA'),
(37,'Dom Eliseu, PA, BRA'),
(38,'Igarapé-Miri, PA, BRA'),
(39,'Ipixuna do Pará, PA, BRA'),
(40,'Itaituba, PA, BRA'),
(41,'Itupiranga, PA, BRA'),
(42,'Jacundá, PA, BRA'),
(43,'Juruti, PA, BRA'),
(44,'Marabá, PA, BRA'),
(45,'Marituba, PA, BRA'),
(46,'Moju, PA, BRA'),
(47,'Monte Alegre, PA, BRA'),
(48,'Novo Repartimento, PA, BRA'),
(49,'Óbidos, PA, BRA'),
(50,'Oriximiná, PA, BRA'),
(51,'Paragominas, PA, BRA'),
(52,'Parauapebas, PA, BRA'),
(53,'Portel, PA, BRA'),
(54,'Redenção, PA, BRA'),
(55,'Rondon do Pará, PA, BRA'),
(56,'Rurópolis, PA, BRA'),
(57,'Santa Izabel do Pará, PA, BRA'),
(58,'Santana do Araguaia, PA, BRA'),
(59,'Santarém, PA, BRA'),
(60,'São Félix do Xingu, PA, BRA'),
(61,'São Miguel do Guamá, PA, BRA'),
(62,'Tailândia, PA, BRA'),
(63,'Tomé-Açu, PA, BRA'),
(64,'Tucuruí, PA, BRA'),
(65,'Ulianópolis, PA, BRA'),
(66,'Vigia, PA, BRA'),
(67,'Viseu, PA, BRA'),
(68,'Laranjal do Jari, AP, BRA'),
(69,'Macapá, AP, BRA'),
(70,'Santana, AP, BRA'),
(71,'Araguaína, TO, BRA'),
(72,'Gurupi, TO, BRA'),
(73,'Paraíso do Tocantins, TO, BRA'),
(74,'Porto Nacional, TO, BRA'),
(75,'Palmas, TO, BRA'),
(76,'Açailândia, MA, BRA'),
(77,'Bacabal, MA, BRA'),
(78,'Balsas, MA, BRA'),
(79,'Barra do Corda, MA, BRA'),
(80,'Barreirinhas, MA, BRA'),
(81,'Buriticupu, MA, BRA'),
(82,'Caxias, MA, BRA'),
(83,'Chapadinha, MA, BRA'),
(84,'Codó, MA, BRA'),
(85,'Coroatá, MA, BRA'),
(86,'Grajaú, MA, BRA'),
(87,'Imperatriz, MA, BRA'),
(88,'Itapecuru Mirim, MA, BRA'),
(89,'Lago da Pedra, MA, BRA'),
(90,'Paço do Lumiar, MA, BRA'),
(91,'Pinheiro, MA, BRA'),
(92,'Santa Inês, MA, BRA'),
(93,'Santa Luzia, MA, BRA'),
(94,'São José de Ribamar, MA, BRA'),
(95,'São Luís, MA, BRA'),
(96,'Timon, MA, BRA'),
(97,'Tutóia, MA, BRA'),
(98,'Vargem Grande, MA, BRA'),
(99,'Viana, MA, BRA'),
(100,'Zé Doca, MA, BRA'),
(101,'Floriano, PI, BRA'),
(102,'Parnaíba, PI, BRA'),
(103,'Picos, PI, BRA'),
(104,'Piripiri, PI, BRA'),
(105,'Teresina, PI, BRA'),
(106,'Acaraú, CE, BRA'),
(107,'Acopiara, CE, BRA'),
(108,'Aquiraz, CE, BRA'),
(109,'Aracati, CE, BRA'),
(110,'Barbalha, CE, BRA'),
(111,'Beberibe, CE, BRA'),
(112,'Boa Viagem, CE, BRA'),
(113,'Brejo Santo, CE, BRA'),
(114,'Camocim, CE, BRA'),
(115,'Canindé, CE, BRA'),
(116,'Cascavel, CE, BRA'),
(117,'Caucaia, CE, BRA'),
(118,'Crateús, CE, BRA'),
(119,'Crato, CE, BRA'),
(120,'Eusébio, CE, BRA'),
(121,'Fortaleza, CE, BRA'),
(122,'Granja, CE, BRA'),
(123,'Horizonte, CE, BRA'),
(124,'Icó, CE, BRA'),
(125,'Iguatu, CE, BRA'),
(126,'Itapajé, CE, BRA'),
(127,'Itapipoca, CE, BRA'),
(128,'Juazeiro do Norte, CE, BRA'),
(129,'Limoeiro do Norte, CE, BRA'),
(130,'Maracanaú, CE, BRA'),
(131,'Maranguape, CE, BRA'),
(132,'Morada Nova, CE, BRA'),
(133,'Pacajus, CE, BRA'),
(134,'Pacatuba, CE, BRA'),
(135,'Quixadá, CE, BRA'),
(136,'Quixeramobim, CE, BRA'),
(137,'Russas, CE, BRA'),
(138,'Sobral, CE, BRA'),
(139,'Tauá, CE, BRA'),
(140,'Tianguá, CE, BRA'),
(141,'Trairi, CE, BRA'),
(142,'Viçosa do Ceará, CE, BRA'),
(143,'Açu, RN, BRA'),
(144,'Caicó, RN, BRA'),
(145,'Ceará-Mirim, RN, BRA'),
(146,'Parnamirim, RN, BRA'),
(147,'Macaíba, RN, BRA'),
(148,'Mossoró, RN, BRA'),
(149,'Natal, RN, BRA'),
(150,'São Gonçalo do Amarante, RN, BRA'),
(151,'Bayeux, PB, BRA'),
(152,'Cabedelo, PB, BRA'),
(153,'Cajazeiras, PB, BRA'),
(154,'Campina Grande, PB, BRA'),
(155,'Guarabira, PB, BRA'),
(156,'João Pessoa, PB, BRA'),
(157,'Patos, PB, BRA'),
(158,'Santa Rita, PB, BRA'),
(159,'Sapé, PB, BRA'),
(160,'Sousa, PB, BRA'),
(161,'Abreu e Lima, PE, BRA'),
(162,'Araripina, PE, BRA'),
(163,'Arcoverde, PE, BRA'),
(164,'Belo Jardim, PE, BRA'),
(165,'Bezerros, PE, BRA'),
(166,'Brejo da Madre de Deus, PE, BRA'),
(167,'Buíque, PE, BRA'),
(168,'Cabo de Santo Agostinho, PE, BRA'),
(169,'Camaragibe, PE, BRA'),
(170,'Carpina, PE, BRA'),
(171,'Caruaru, PE, BRA'),
(172,'Escada, PE, BRA'),
(173,'Garanhuns, PE, BRA'),
(174,'Goiana, PE, BRA'),
(175,'Gravatá, PE, BRA'),
(176,'Igarassu, PE, BRA'),
(177,'Ipojuca, PE, BRA'),
(178,'Jaboatão dos Guararapes, PE, BRA'),
(179,'Limoeiro, PE, BRA'),
(180,'Moreno, PE, BRA'),
(181,'Olinda, PE, BRA'),
(182,'Ouricuri, PE, BRA'),
(183,'Palmares, PE, BRA'),
(184,'Paudalho, PE, BRA'),
(185,'Paulista, PE, BRA'),
(186,'Pesqueira, PE, BRA'),
(187,'Petrolina, PE, BRA'),
(188,'Recife, PE, BRA'),
(189,'Salgueiro, PE, BRA'),
(190,'Santa Cruz do Capibaribe, PE, BRA'),
(191,'São Bento do Una, PE, BRA'),
(192,'São Lourenço da Mata, PE, BRA'),
(193,'Serra Talhada, PE, BRA'),
(194,'Surubim, PE, BRA'),
(195,'Timbaúba, PE, BRA'),
(196,'Vitória de Santo Antão, PE, BRA'),
(197,'Arapiraca, AL, BRA'),
(198,'Campo Alegre, AL, BRA'),
(199,'Coruripe, AL, BRA'),
(200,'Delmiro Gouveia, AL, BRA'),
(201,'Maceió, AL, BRA'),
(202,'Marechal Deodoro, AL, BRA'),
(203,'Palmeira dos Índios, AL, BRA'),
(204,'Penedo, AL, BRA'),
(205,'Rio Largo, AL, BRA'),
(206,'São Miguel dos Campos, AL, BRA'),
(207,'União dos Palmares, AL, BRA'),
(208,'Aracaju, SE, BRA'),
(209,'Estância, SE, BRA'),
(210,'Itabaiana, SE, BRA'),
(211,'Lagarto, SE, BRA'),
(212,'Nossa Senhora do Socorro, SE, BRA'),
(213,'São Cristóvão, SE, BRA'),
(214,'Tobias Barreto, SE, BRA'),
(215,'Alagoinhas, BA, BRA'),
(216,'Araci, BA, BRA'),
(217,'Barra, BA, BRA'),
(218,'Barreiras, BA, BRA'),
(219,'Bom Jesus da Lapa, BA, BRA'),
(220,'Brumado, BA, BRA'),
(221,'Caetité, BA, BRA'),
(222,'Camaçari, BA, BRA'),
(223,'Campo Formoso, BA, BRA'),
(224,'Candeias, BA, BRA'),
(225,'Casa Nova, BA, BRA'),
(226,'Catu, BA, BRA'),
(227,'Conceição do Coité, BA, BRA'),
(228,'Cruz das Almas, BA, BRA'),
(229,'Dias d\'Ávila, BA, BRA'),
(230,'Euclides da Cunha, BA, BRA'),
(231,'Eunápolis, BA, BRA'),
(232,'Feira de Santana, BA, BRA'),
(233,'Guanambi, BA, BRA'),
(234,'Ilhéus, BA, BRA'),
(235,'Ipirá, BA, BRA'),
(236,'Irecê, BA, BRA'),
(237,'Itaberaba, BA, BRA'),
(238,'Itabuna, BA, BRA'),
(239,'Itamaraju, BA, BRA'),
(240,'Itapetinga, BA, BRA'),
(241,'Jacobina, BA, BRA'),
(242,'Jaguaquara, BA, BRA'),
(243,'Jequié, BA, BRA'),
(244,'Juazeiro, BA, BRA'),
(245,'Lauro de Freitas, BA, BRA'),
(246,'Luís Eduardo Magalhães, BA, BRA'),
(247,'Macaúbas, BA, BRA'),
(248,'Paulo Afonso, BA, BRA'),
(249,'Porto Seguro, BA, BRA'),
(250,'Ribeira do Pombal, BA, BRA'),
(251,'Salvador, BA, BRA'),
(252,'Santo Amaro, BA, BRA'),
(253,'Santo Antônio de Jesus, BA, BRA'),
(254,'Santo Estêvão, BA, BRA'),
(255,'Senhor do Bonfim, BA, BRA'),
(256,'Serrinha, BA, BRA'),
(257,'Simões Filho, BA, BRA'),
(258,'Teixeira de Freitas, BA, BRA'),
(259,'Tucano, BA, BRA'),
(260,'Valença, BA, BRA'),
(261,'Vitória da Conquista, BA, BRA'),
(262,'Alfenas, MG, BRA'),
(263,'Araguari, MG, BRA'),
(264,'Araxá, MG, BRA'),
(265,'Barbacena, MG, BRA'),
(266,'Belo Horizonte, MG, BRA'),
(267,'Betim, MG, BRA'),
(268,'Bocaiuva, MG, BRA'),
(269,'Bom Despacho, MG, BRA'),
(270,'Campo Belo, MG, BRA'),
(271,'Caratinga, MG, BRA'),
(272,'Cataguases, MG, BRA'),
(273,'Congonhas, MG, BRA'),
(274,'Conselheiro Lafaiete, MG, BRA'),
(275,'Contagem, MG, BRA'),
(276,'Coronel Fabriciano, MG, BRA'),
(277,'Curvelo, MG, BRA'),
(278,'Divinópolis, MG, BRA'),
(279,'Esmeraldas, MG, BRA'),
(280,'Formiga, MG, BRA'),
(281,'Frutal, MG, BRA'),
(282,'Governador Valadares, MG, BRA'),
(283,'Guaxupé, MG, BRA'),
(284,'Ibirité, MG, BRA'),
(285,'Ipatinga, MG, BRA'),
(286,'Itabira, MG, BRA'),
(287,'Itabirito, MG, BRA'),
(288,'Itajubá, MG, BRA'),
(289,'Itaúna, MG, BRA'),
(290,'Ituiutaba, MG, BRA'),
(291,'Janaúba, MG, BRA'),
(292,'Januária, MG, BRA'),
(293,'João Monlevade, MG, BRA'),
(294,'Juiz de Fora, MG, BRA'),
(295,'Lagoa da Prata, MG, BRA'),
(296,'Lagoa Santa, MG, BRA'),
(297,'Lavras, MG, BRA'),
(298,'Leopoldina, MG, BRA'),
(299,'Manhuaçu, MG, BRA'),
(300,'Mariana, MG, BRA'),
(301,'Montes Claros, MG, BRA'),
(302,'Muriaé, MG, BRA'),
(303,'Nova Lima, MG, BRA'),
(304,'Nova Serrana, MG, BRA'),
(305,'Ouro Preto, MG, BRA'),
(306,'Paracatu, MG, BRA'),
(307,'Pará de Minas, MG, BRA'),
(308,'Passos, MG, BRA'),
(309,'Patos de Minas, MG, BRA'),
(310,'Patrocínio, MG, BRA'),
(311,'Pedro Leopoldo, MG, BRA'),
(312,'Pirapora, MG, BRA'),
(313,'Poços de Caldas, MG, BRA'),
(314,'Ponte Nova, MG, BRA'),
(315,'Pouso Alegre, MG, BRA'),
(316,'Ribeirão das Neves, MG, BRA'),
(317,'Sabará, MG, BRA'),
(318,'Santa Luzia, MG, BRA'),
(319,'São Francisco, MG, BRA'),
(320,'São João del-Rei, MG, BRA'),
(321,'São Sebastião do Paraíso, MG, BRA'),
(322,'Sete Lagoas, MG, BRA'),
(323,'Teófilo Otoni, MG, BRA'),
(324,'Timóteo, MG, BRA'),
(325,'Três Corações, MG, BRA'),
(326,'Três Pontas, MG, BRA'),
(327,'Ubá, MG, BRA'),
(328,'Uberaba, MG, BRA'),
(329,'Uberlândia, MG, BRA'),
(330,'Unaí, MG, BRA'),
(331,'Varginha, MG, BRA'),
(332,'Vespasiano, MG, BRA'),
(333,'Viçosa, MG, BRA'),
(334,'Aracruz, ES, BRA'),
(335,'Cachoeiro de Itapemirim, ES, BRA'),
(336,'Cariacica, ES, BRA'),
(337,'Colatina, ES, BRA'),
(338,'Guarapari, ES, BRA'),
(339,'Linhares, ES, BRA'),
(340,'Nova Venécia, ES, BRA'),
(341,'São Mateus, ES, BRA'),
(342,'Serra, ES, BRA'),
(343,'Viana, ES, BRA'),
(344,'Vila Velha, ES, BRA'),
(345,'Vitória, ES, BRA'),
(346,'Angra dos Reis, RJ, BRA'),
(347,'Araruama, RJ, BRA'),
(348,'Barra do Piraí, RJ, BRA'),
(349,'Barra Mansa, RJ, BRA'),
(350,'Belford Roxo, RJ, BRA'),
(351,'Cabo Frio, RJ, BRA'),
(352,'Cachoeiras de Macacu, RJ, BRA'),
(353,'Campos dos Goytacazes, RJ, BRA'),
(354,'Duque de Caxias, RJ, BRA'),
(355,'Guapimirim, RJ, BRA'),
(356,'Itaboraí, RJ, BRA'),
(357,'Itaguaí, RJ, BRA'),
(358,'Itaperuna, RJ, BRA'),
(359,'Japeri, RJ, BRA'),
(360,'Macaé, RJ, BRA'),
(361,'Magé, RJ, BRA'),
(362,'Maricá, RJ, BRA'),
(363,'Mesquita, RJ, BRA'),
(364,'Nilópolis, RJ, BRA'),
(365,'Niterói, RJ, BRA'),
(366,'Nova Friburgo, RJ, BRA'),
(367,'Nova Iguaçu, RJ, BRA'),
(368,'Paracambi, RJ, BRA'),
(369,'Petrópolis, RJ, BRA'),
(370,'Queimados, RJ, BRA'),
(371,'Resende, RJ, BRA'),
(372,'Rio Bonito, RJ, BRA'),
(373,'Rio das Ostras, RJ, BRA'),
(374,'Rio de Janeiro, RJ, BRA'),
(375,'São Gonçalo, RJ, BRA'),
(376,'São João de Meriti, RJ, BRA'),
(377,'São Pedro da Aldeia, RJ, BRA'),
(378,'Saquarema, RJ, BRA'),
(379,'Seropédica, RJ, BRA'),
(380,'Teresópolis, RJ, BRA'),
(381,'Três Rios, RJ, BRA'),
(382,'Valença, RJ, BRA'),
(383,'Volta Redonda, RJ, BRA'),
(384,'Americana, SP, BRA'),
(385,'Amparo, SP, BRA'),
(386,'Andradina, SP, BRA'),
(387,'Araçatuba, SP, BRA'),
(388,'Araraquara, SP, BRA'),
(389,'Araras, SP, BRA'),
(390,'Artur Nogueira, SP, BRA'),
(391,'Arujá, SP, BRA'),
(392,'Assis, SP, BRA'),
(393,'Atibaia, SP, BRA'),
(394,'Avaré, SP, BRA'),
(395,'Barretos, SP, BRA'),
(396,'Barueri, SP, BRA'),
(397,'Batatais, SP, BRA'),
(398,'Bauru, SP, BRA'),
(399,'Bebedouro, SP, BRA'),
(400,'Bertioga, SP, BRA'),
(401,'Birigui, SP, BRA'),
(402,'Boituva, SP, BRA'),
(403,'Botucatu, SP, BRA'),
(404,'Bragança Paulista, SP, BRA'),
(405,'Cabreúva, SP, BRA'),
(406,'Caçapava, SP, BRA'),
(407,'Caieiras, SP, BRA'),
(408,'Cajamar, SP, BRA'),
(409,'Campinas, SP, BRA'),
(410,'Campo Limpo Paulista, SP, BRA'),
(411,'Campos do Jordão, SP, BRA'),
(412,'Capivari, SP, BRA'),
(413,'Caraguatatuba, SP, BRA'),
(414,'Carapicuíba, SP, BRA'),
(415,'Catanduva, SP, BRA'),
(416,'Cerquilho, SP, BRA'),
(417,'Cosmópolis, SP, BRA'),
(418,'Cotia, SP, BRA'),
(419,'Cruzeiro, SP, BRA'),
(420,'Cubatão, SP, BRA'),
(421,'Diadema, SP, BRA'),
(422,'Embu das Artes, SP, BRA'),
(423,'Embu-Guaçu, SP, BRA'),
(424,'Fernandópolis, SP, BRA'),
(425,'Ferraz de Vasconcelos, SP, BRA'),
(426,'Franca, SP, BRA'),
(427,'Francisco Morato, SP, BRA'),
(428,'Franco da Rocha, SP, BRA'),
(429,'Guaratinguetá, SP, BRA'),
(430,'Guarujá, SP, BRA'),
(431,'Guarulhos, SP, BRA'),
(432,'Hortolândia, SP, BRA'),
(433,'Ibitinga, SP, BRA'),
(434,'Ibiúna, SP, BRA'),
(435,'Indaiatuba, SP, BRA'),
(436,'Itanhaém, SP, BRA'),
(437,'Itapecerica da Serra, SP, BRA'),
(438,'Itapetininga, SP, BRA'),
(439,'Itapeva, SP, BRA'),
(440,'Itapevi, SP, BRA'),
(441,'Itapira, SP, BRA'),
(442,'Itaquaquecetuba, SP, BRA'),
(443,'Itararé, SP, BRA'),
(444,'Itatiba, SP, BRA'),
(445,'Itu, SP, BRA'),
(446,'Itupeva, SP, BRA'),
(447,'Jaboticabal, SP, BRA'),
(448,'Jacareí, SP, BRA'),
(449,'Jaguariúna, SP, BRA'),
(450,'Jandira, SP, BRA'),
(451,'Jaú, SP, BRA'),
(452,'Jundiaí, SP, BRA'),
(453,'Leme, SP, BRA'),
(454,'Lençóis Paulista, SP, BRA'),
(455,'Limeira, SP, BRA'),
(456,'Lins, SP, BRA'),
(457,'Lorena, SP, BRA'),
(458,'Louveira, SP, BRA'),
(459,'Mairiporã, SP, BRA'),
(460,'Marília, SP, BRA'),
(461,'Matão, SP, BRA'),
(462,'Mauá, SP, BRA'),
(463,'Mirassol, SP, BRA'),
(464,'Mococa, SP, BRA'),
(465,'Mogi das Cruzes, SP, BRA'),
(466,'Mogi Guaçu, SP, BRA'),
(467,'Mogi Mirim, SP, BRA'),
(468,'Mongaguá, SP, BRA'),
(469,'Monte Alto, SP, BRA'),
(470,'Monte Mor, SP, BRA'),
(471,'Nova Odessa, SP, BRA'),
(472,'Olímpia, SP, BRA'),
(473,'Osasco, SP, BRA'),
(474,'Ourinhos, SP, BRA'),
(475,'Paulínia, SP, BRA'),
(476,'Penápolis, SP, BRA'),
(477,'Peruíbe, SP, BRA'),
(478,'Piedade, SP, BRA'),
(479,'Pindamonhangaba, SP, BRA'),
(480,'Piracicaba, SP, BRA'),
(481,'Pirassununga, SP, BRA'),
(482,'Poá, SP, BRA'),
(483,'Pontal, SP, BRA'),
(484,'Porto Feliz, SP, BRA'),
(485,'Porto Ferreira, SP, BRA'),
(486,'Praia Grande, SP, BRA'),
(487,'Presidente Prudente, SP, BRA'),
(488,'Registro, SP, BRA'),
(489,'Ribeirão Pires, SP, BRA'),
(490,'Ribeirão Preto, SP, BRA'),
(491,'Rio Claro, SP, BRA'),
(492,'Rio Grande da Serra, SP, BRA'),
(493,'Salto, SP, BRA'),
(494,'Santa Bárbara d\'Oeste, SP, BRA'),
(495,'Santa Isabel, SP, BRA'),
(496,'Santana de Parnaíba, SP, BRA'),
(497,'Santo André, SP, BRA'),
(498,'Santos, SP, BRA'),
(499,'São Bernardo do Campo, SP, BRA'),
(500,'São Caetano do Sul, SP, BRA'),
(501,'São Carlos, SP, BRA'),
(502,'São João da Boa Vista, SP, BRA'),
(503,'São Joaquim da Barra, SP, BRA'),
(504,'São José do Rio Pardo, SP, BRA'),
(505,'São José do Rio Preto, SP, BRA'),
(506,'São José dos Campos, SP, BRA'),
(507,'São Paulo, SP, BRA'),
(508,'São Roque, SP, BRA'),
(509,'São Sebastião, SP, BRA'),
(510,'São Vicente, SP, BRA'),
(511,'Sertãozinho, SP, BRA'),
(512,'Sorocaba, SP, BRA'),
(513,'Sumaré, SP, BRA'),
(514,'Suzano, SP, BRA'),
(515,'Taboão da Serra, SP, BRA'),
(516,'Taquaritinga, SP, BRA'),
(517,'Tatuí, SP, BRA'),
(518,'Taubaté, SP, BRA'),
(519,'Tupã, SP, BRA'),
(520,'Ubatuba, SP, BRA'),
(521,'Valinhos, SP, BRA'),
(522,'Vargem Grande Paulista, SP, BRA'),
(523,'Várzea Paulista, SP, BRA'),
(524,'Vinhedo, SP, BRA'),
(525,'Votorantim, SP, BRA'),
(526,'Votuporanga, SP, BRA'),
(527,'Almirante Tamandaré, PR, BRA'),
(528,'Apucarana, PR, BRA'),
(529,'Arapongas, PR, BRA'),
(530,'Araucária, PR, BRA'),
(531,'Cambé, PR, BRA'),
(532,'Campo Largo, PR, BRA'),
(533,'Campo Mourão, PR, BRA'),
(534,'Cascavel, PR, BRA'),
(535,'Castro, PR, BRA'),
(536,'Cianorte, PR, BRA'),
(537,'Colombo, PR, BRA'),
(538,'Curitiba, PR, BRA'),
(539,'Fazenda Rio Grande, PR, BRA'),
(540,'Foz do Iguaçu, PR, BRA'),
(541,'Francisco Beltrão, PR, BRA'),
(542,'Guarapuava, PR, BRA'),
(543,'Ibiporã, PR, BRA'),
(544,'Irati, PR, BRA'),
(545,'Londrina, PR, BRA'),
(546,'Marechal Cândido Rondon, PR, BRA'),
(547,'Maringá, PR, BRA'),
(548,'Palmas, PR, BRA'),
(549,'Paranaguá, PR, BRA'),
(550,'Paranavaí, PR, BRA'),
(551,'Pato Branco, PR, BRA'),
(552,'Pinhais, PR, BRA'),
(553,'Piraquara, PR, BRA'),
(554,'Ponta Grossa, PR, BRA'),
(555,'Prudentópolis, PR, BRA'),
(556,'Rolândia, PR, BRA'),
(557,'São José dos Pinhais, PR, BRA'),
(558,'Sarandi, PR, BRA'),
(559,'Telêmaco Borba, PR, BRA'),
(560,'Toledo, PR, BRA'),
(561,'Umuarama, PR, BRA'),
(562,'União da Vitória, PR, BRA'),
(563,'Araranguá, SC, BRA'),
(564,'Balneário Camboriú, SC, BRA'),
(565,'Biguaçu, SC, BRA'),
(566,'Blumenau, SC, BRA'),
(567,'Brusque, SC, BRA'),
(568,'Caçador, SC, BRA'),
(569,'Camboriú, SC, BRA'),
(570,'Canoinhas, SC, BRA'),
(571,'Chapecó, SC, BRA'),
(572,'Concórdia, SC, BRA'),
(573,'Criciúma, SC, BRA'),
(574,'Florianópolis, SC, BRA'),
(575,'Gaspar, SC, BRA'),
(576,'Içara, SC, BRA'),
(577,'Indaial, SC, BRA'),
(578,'Itajaí, SC, BRA'),
(579,'Itapema, SC, BRA'),
(580,'Jaraguá do Sul, SC, BRA'),
(581,'Joinville, SC, BRA'),
(582,'Lages, SC, BRA'),
(583,'Mafra, SC, BRA'),
(584,'Navegantes, SC, BRA'),
(585,'Palhoça, SC, BRA'),
(586,'Rio do Sul, SC, BRA'),
(587,'São Bento do Sul, SC, BRA'),
(588,'São Francisco do Sul, SC, BRA'),
(589,'São José, SC, BRA'),
(590,'Tubarão, SC, BRA'),
(591,'Videira, SC, BRA'),
(592,'Xanxerê, SC, BRA'),
(593,'Alegrete, RS, BRA'),
(594,'Alvorada, RS, BRA'),
(595,'Bagé, RS, BRA'),
(596,'Bento Gonçalves, RS, BRA'),
(597,'Cachoeira do Sul, RS, BRA'),
(598,'Cachoeirinha, RS, BRA'),
(599,'Camaquã, RS, BRA'),
(600,'Campo Bom, RS, BRA'),
(601,'Canguçu, RS, BRA'),
(602,'Canoas, RS, BRA'),
(603,'Capão da Canoa, RS, BRA'),
(604,'Carazinho, RS, BRA'),
(605,'Caxias do Sul, RS, BRA'),
(606,'Cruz Alta, RS, BRA'),
(607,'Erechim, RS, BRA'),
(608,'Estância Velha, RS, BRA'),
(609,'Esteio, RS, BRA'),
(610,'Farroupilha, RS, BRA'),
(611,'Gravataí, RS, BRA'),
(612,'Guaíba, RS, BRA'),
(613,'Ijuí, RS, BRA'),
(614,'Lajeado, RS, BRA'),
(615,'Montenegro, RS, BRA'),
(616,'Novo Hamburgo, RS, BRA'),
(617,'Parobé, RS, BRA'),
(618,'Passo Fundo, RS, BRA'),
(619,'Pelotas, RS, BRA'),
(620,'Porto Alegre, RS, BRA'),
(621,'Rio Grande, RS, BRA'),
(622,'Santa Cruz do Sul, RS, BRA'),
(623,'Santa Maria, RS, BRA'),
(624,'Sant\'Ana do Livramento, RS, BRA'),
(625,'Santa Rosa, RS, BRA'),
(626,'Santo Ângelo, RS, BRA'),
(627,'São Borja, RS, BRA'),
(628,'São Gabriel, RS, BRA'),
(629,'São Leopoldo, RS, BRA'),
(630,'Sapiranga, RS, BRA'),
(631,'Sapucaia do Sul, RS, BRA'),
(632,'Taquara, RS, BRA'),
(633,'Tramandaí, RS, BRA'),
(634,'Uruguaiana, RS, BRA'),
(635,'Vacaria, RS, BRA'),
(636,'Venâncio Aires, RS, BRA'),
(637,'Viamão, RS, BRA'),
(638,'Campo Grande, MS, BRA'),
(639,'Corumbá, MS, BRA'),
(640,'Dourados, MS, BRA'),
(641,'Naviraí, MS, BRA'),
(642,'Nova Andradina, MS, BRA'),
(643,'Ponta Porã, MS, BRA'),
(644,'Sidrolândia, MS, BRA'),
(645,'Três Lagoas, MS, BRA'),
(646,'Alta Floresta, MT, BRA'),
(647,'Barra do Garças, MT, BRA'),
(648,'Cáceres, MT, BRA'),
(649,'Cuiabá, MT, BRA'),
(650,'Lucas do Rio Verde, MT, BRA'),
(651,'Primavera do Leste, MT, BRA'),
(652,'Rondonópolis, MT, BRA'),
(653,'Sinop, MT, BRA'),
(654,'Sorriso, MT, BRA'),
(655,'Tangará da Serra, MT, BRA'),
(656,'Várzea Grande, MT, BRA'),
(657,'Águas Lindas de Goiás, GO, BRA'),
(658,'Anápolis, GO, BRA'),
(659,'Aparecida de Goiânia, GO, BRA'),
(660,'Caldas Novas, GO, BRA'),
(661,'Catalão, GO, BRA'),
(662,'Cidade Ocidental, GO, BRA'),
(663,'Cristalina, GO, BRA'),
(664,'Formosa, GO, BRA'),
(665,'Goianésia, GO, BRA'),
(666,'Goiânia, GO, BRA'),
(667,'Inhumas, GO, BRA'),
(668,'Itumbiara, GO, BRA'),
(669,'Jaraguá, GO, BRA'),
(670,'Jataí, GO, BRA'),
(671,'Luziânia, GO, BRA'),
(672,'Mineiros, GO, BRA'),
(673,'Novo Gama, GO, BRA'),
(674,'Planaltina, GO, BRA'),
(675,'Quirinópolis, GO, BRA'),
(676,'Rio Verde, GO, BRA'),
(677,'Santo Antônio do Descoberto, GO, BRA'),
(678,'Senador Canedo, GO, BRA'),
(679,'Trindade, GO, BRA'),
(680,'Valparaíso de Goiás, GO, BRA'),
(681,'Brasília, DF, BRA');
/*!40000 ALTER TABLE `localidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `log` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `id_registro` varchar(50) NOT NULL,
  `registro_codigo` int(11) NOT NULL,
  `usuario_codigo` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `tipo_operacao_codigo` int(11) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=4321 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log`
--

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_evento`
--

DROP TABLE IF EXISTS `log_evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_evento` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `data` datetime NOT NULL,
  `sucesso` tinyint(1) NOT NULL,
  `mensagem` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_evento`
--

LOCK TABLES `log_evento` WRITE;
/*!40000 ALTER TABLE `log_evento` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_evento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `material`
--

DROP TABLE IF EXISTS `material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `material` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `material`
--

LOCK TABLES `material` WRITE;
/*!40000 ALTER TABLE `material` DISABLE KEYS */;
/*!40000 ALTER TABLE `material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth`
--

DROP TABLE IF EXISTS `oauth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth` (
  `codigo` int(11) NOT NULL,
  `servico` varchar(255) NOT NULL,
  `token` text DEFAULT NULL,
  `usuario_codigo` int(11) NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `usuario_codigo` (`usuario_codigo`),
  CONSTRAINT `oauth_ibfk_1` FOREIGN KEY (`usuario_codigo`) REFERENCES `usuario` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth`
--

LOCK TABLES `oauth` WRITE;
/*!40000 ALTER TABLE `oauth` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objeto`
--

DROP TABLE IF EXISTS `objeto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `objeto` (
  `codigo` int(11) NOT NULL,
  `instituicao_codigo` int(11) DEFAULT NULL,
  `numero_registro` varchar(50) DEFAULT NULL,
  `item_acervo_codigo` int(11) DEFAULT NULL,
  `tipo_objeto_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `objeto_FK` (`instituicao_codigo`),
  KEY `objeto_item_acervo_null_fk` (`item_acervo_codigo`),
  KEY `objeto_tipo_objeto_null_fk` (`tipo_objeto_codigo`),
  CONSTRAINT `objeto_FK` FOREIGN KEY (`instituicao_codigo`) REFERENCES `instituicao` (`codigo`),
  CONSTRAINT `objeto_item_acervo_null_fk` FOREIGN KEY (`item_acervo_codigo`) REFERENCES `item_acervo` (`codigo`),
  CONSTRAINT `objeto_tipo_objeto_null_fk` FOREIGN KEY (`tipo_objeto_codigo`) REFERENCES `tipo_objeto` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objeto`
--

LOCK TABLES `objeto` WRITE;
/*!40000 ALTER TABLE `objeto` DISABLE KEYS */;
/*!40000 ALTER TABLE `objeto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objeto_material`
--

DROP TABLE IF EXISTS `objeto_material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `objeto_material` (
  `objeto_codigo` int(11) DEFAULT NULL,
  `material_codigo` int(11) DEFAULT NULL,
  KEY `objeto_material_material_null_fk` (`material_codigo`),
  KEY `objeto_material_objeto_null_fk` (`objeto_codigo`),
  CONSTRAINT `objeto_material_material_null_fk` FOREIGN KEY (`material_codigo`) REFERENCES `material` (`codigo`),
  CONSTRAINT `objeto_material_objeto_null_fk` FOREIGN KEY (`objeto_codigo`) REFERENCES `objeto` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objeto_material`
--

LOCK TABLES `objeto_material` WRITE;
/*!40000 ALTER TABLE `objeto_material` DISABLE KEYS */;
/*!40000 ALTER TABLE `objeto_material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objeto_tecnica`
--

DROP TABLE IF EXISTS `objeto_tecnica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `objeto_tecnica` (
  `objeto_codigo` int(11) DEFAULT NULL,
  `tecnica_codigo` int(11) DEFAULT NULL,
  KEY `objeto_tecnica_objeto_null_fk` (`objeto_codigo`),
  KEY `objeto_tecnica_tecnica_null_fk` (`tecnica_codigo`),
  CONSTRAINT `objeto_tecnica_objeto_null_fk` FOREIGN KEY (`objeto_codigo`) REFERENCES `objeto` (`codigo`),
  CONSTRAINT `objeto_tecnica_tecnica_null_fk` FOREIGN KEY (`tecnica_codigo`) REFERENCES `tecnica` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objeto_tecnica`
--

LOCK TABLES `objeto_tecnica` WRITE;
/*!40000 ALTER TABLE `objeto_tecnica` DISABLE KEYS */;
/*!40000 ALTER TABLE `objeto_tecnica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagina_etiquetas`
--

DROP TABLE IF EXISTS `pagina_etiquetas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagina_etiquetas` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `formato_codigo` int(11) NOT NULL,
  `margem_superior` float NOT NULL,
  `margem_esquerda` float NOT NULL,
  `altura_etiqueta` float NOT NULL,
  `largura_etiqueta` float NOT NULL,
  `intervalo_etiquetas` float DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `formato_codigo` (`formato_codigo`),
  CONSTRAINT `pagina_etiquetas_ibfk_1` FOREIGN KEY (`formato_codigo`) REFERENCES `formato_pagina` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagina_etiquetas`
--

LOCK TABLES `pagina_etiquetas` WRITE;
/*!40000 ALTER TABLE `pagina_etiquetas` DISABLE KEYS */;
INSERT INTO `pagina_etiquetas` VALUES
(1,'Etiqueta grande',1,0,1,5,10,0);
/*!40000 ALTER TABLE `pagina_etiquetas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `palavra_chave`
--

DROP TABLE IF EXISTS `palavra_chave`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `palavra_chave` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `palavra_chave`
--

LOCK TABLES `palavra_chave` WRITE;
/*!40000 ALTER TABLE `palavra_chave` DISABLE KEYS */;
/*!40000 ALTER TABLE `palavra_chave` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projeto`
--

DROP TABLE IF EXISTS `projeto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `projeto` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projeto`
--

LOCK TABLES `projeto` WRITE;
/*!40000 ALTER TABLE `projeto` DISABLE KEYS */;
/*!40000 ALTER TABLE `projeto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `raca`
--

DROP TABLE IF EXISTS `raca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `raca` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `raca`
--

LOCK TABLES `raca` WRITE;
/*!40000 ALTER TABLE `raca` DISABLE KEYS */;
/*!40000 ALTER TABLE `raca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recurso_sistema`
--

DROP TABLE IF EXISTS `recurso_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `recurso_sistema` (
  `codigo` int(11) NOT NULL,
  `id` varchar(50) DEFAULT NULL,
  `nome_plural` varchar(100) NOT NULL,
  `nome_singular` varchar(100) DEFAULT NULL,
  `genero_gramatical_codigo` int(11) DEFAULT NULL,
  `tabela_banco` varchar(50) DEFAULT NULL,
  `hierarquico` tinyint(1) DEFAULT NULL,
  `campo_hierarquico_codigo` int(11) DEFAULT NULL,
  `item_acervo` tinyint(1) DEFAULT NULL,
  `agrupado_acervo` tinyint(1) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  `selecionavel` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recurso_sistema`
--

LOCK TABLES `recurso_sistema` WRITE;
/*!40000 ALTER TABLE `recurso_sistema` DISABLE KEYS */;
INSERT INTO `recurso_sistema` VALUES
(1,'documento','Documentos','Documento',2,'documento',0,NULL,1,1,1,1),
(2,'entidade','Entidades','Entidade',NULL,'entidade',NULL,NULL,NULL,NULL,1,0),
(3,'instituicao','Instituições','Instituição',1,NULL,0,NULL,0,NULL,1,0),
(4,'conjunto_documental','Fundos/Coleções','Fundo/Coleção',2,'acervo',0,NULL,0,NULL,1,0),
(5,'localidade','Localidades','Localidade',1,'localidade',0,NULL,0,NULL,1,0),
(6,'genero_documental','Gêneros documentais','Gênero documental',1,'genero_documental',0,NULL,0,0,1,0),
(7,'especie_documental','Espécies documentais','Espécie documental',NULL,'especie_documental',0,NULL,NULL,NULL,1,0),
(8,'tipo_documental','Tipos documentais','Tipo documental',2,'tipo_documental',0,NULL,0,NULL,1,0),
(9,'formato','Formatos','Formato',1,NULL,0,NULL,0,NULL,1,0),
(10,'suporte','Suportes','Suporte',1,NULL,0,NULL,0,NULL,1,0),
(11,'grupo','Grupos','Grupo',2,'agrupamento',1,NULL,0,0,1,0),
(12,'unidade_armazenamento','Unidades de armazenamento','Unidade de armazenamento',1,NULL,0,NULL,0,NULL,1,0),
(13,'local_armazenamento','Locais de armazenamento','Local de armazenamento',1,NULL,0,NULL,0,NULL,1,0),
(14,'objeto','Objetos','Objeto',2,'objeto',0,NULL,1,0,1,1),
(15,'material','Materiais','Material',2,'material',0,NULL,0,NULL,1,0),
(16,'tipo_dimensao','Tipos de dimensão','Tipo de dimensão',1,NULL,0,NULL,0,NULL,1,0),
(17,'unidade_medida','Unidades de medida','Unidade de medida',1,NULL,0,NULL,0,NULL,1,0),
(18,'usuario','Usuários','Usuário',2,'usuario',0,NULL,0,NULL,1,0),
(19,'recurso_sistema','Recursos do sistema','Recurso do sistema',2,'recurso_sistema',0,NULL,0,NULL,1,0),
(20,'grupo_usuario','Grupos de usuários','Grupo de usuário',2,NULL,0,NULL,0,NULL,1,0),
(21,'selecao','Seleções','Seleção',1,NULL,0,NULL,0,NULL,1,0),
(22,'tipo_objeto','Tipos de objetos','Tipo de objeto',2,'tipo_objeto',0,NULL,0,0,1,0),
(25,'livro','Livros','Livro',2,'livro',0,NULL,1,1,1,1),
(28,'tipo_evento','Tipos de eventos','Tipo de evento',1,NULL,0,NULL,0,NULL,1,0),
(30,'campo_sistema','Campos do sistema','Campo do sistema',2,'campo_sistema',0,NULL,0,NULL,1,0),
(31,'idioma','Idiomas','Idioma',NULL,'idioma',NULL,NULL,NULL,NULL,1,0),
(33,'visualizacao','Visualizações','Visualização',NULL,'visualizacao',NULL,NULL,NULL,NULL,1,0),
(35,'editora','Editoras','Editora',NULL,'editora',NULL,NULL,NULL,NULL,1,0),
(40,'contexto','Contextos','Contexto',2,'contexto',1,NULL,0,NULL,1,0),
(42,'palavra_chave','Palavras-chave','Palavra-chave',1,'palavra_chave',0,NULL,0,NULL,1,0),
(44,'fluxo','Fluxos','Fluxo',2,'fluxo',0,NULL,0,NULL,1,0),
(45,'etapa_fluxo','Etapas de fluxo','Etapa de fluxo',1,'etapa_fluxo',0,NULL,0,NULL,1,0),
(46,'atividade_geradora','Atividades geradoras','Atividade geradora',1,'atividade_geradora',0,NULL,0,NULL,1,0),
(47,'tipo_entrevista','Tipos de entrevista','Tipo de entrevista',2,'tipo_entrevista',0,NULL,0,0,1,0),
(48,'formato_entrevista','Formatos de entrevista','Formato de entrevista',2,'entrevista_formato_entrevista',0,NULL,0,0,1,0),
(51,'item_acervo','Item de acervo','Itens de acervo',2,'item_acervo',0,NULL,0,NULL,1,0),
(52,'formato_pagina','Formato de página','Formatos de páginas',2,'formato_pagina',0,NULL,0,NULL,1,0),
(53,'pagina_etiquetas','Páginas de etiquetas','Página de etiqueta',1,'pagina_etiquetas',0,NULL,0,NULL,1,0),
(54,'status_item_acervo','Status de item de acervo','Status de item de acervo',2,'status_item_acervo',0,NULL,0,NULL,1,0),
(58,'genero_textual','Gêneros textuais','Gênero textual',1,'genero_textual',0,NULL,0,0,1,0),
(59,'area_conhecimento','Áreas do conhecimento','Área do conhecimento',1,'area_conhecimento',0,NULL,0,NULL,1,0),
(60,'assunto','Assuntos','Assunto',2,'assunto',0,NULL,0,NULL,1,0),
(64,'setor_sistema','Setores do sistema','Setor do sistema',2,'setor_sistema',0,NULL,0,NULL,1,0),
(65,'entrevista','Entrevistas','Entrevista',1,'entrevista',0,NULL,1,0,1,1),
(66,'biblioteca','Bibliotecas','Biblioteca',1,'acervo',0,NULL,0,NULL,1,0),
(67,'acervo','Acervos','Acervo',2,'acervo',0,NULL,0,NULL,1,0),
(69,'tecnica','Técnicas','Técnica',1,'tecnica',0,NULL,0,NULL,1,0),
(70,'projeto','Projetos','Projeto',2,'projeto',0,NULL,0,NULL,1,0),
(76,'tipo_material','Tipos de materiais','Tipo de material',2,'tipo_material',0,NULL,0,0,1,0),
(77,'importacao','Importações','Importação',1,'importacao',0,NULL,0,0,1,0),
(78,'subgrupo','Subgrupos','Subgrupo',2,'agrupamento',1,NULL,0,0,1,0),
(79,'agrupamento','Agrupamentos','Agrupamento',2,'agrupamento',1,NULL,0,0,1,0),
(81,'estado_conservacao','Estados de conservação','Estado de conservação',2,'estado_conservacao',0,NULL,0,0,1,0),
(82,'cromia','Cromias','Cromia',1,'cromia',0,NULL,0,0,1,0);
/*!40000 ALTER TABLE `recurso_sistema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registro_etapa_fluxo`
--

DROP TABLE IF EXISTS `registro_etapa_fluxo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `registro_etapa_fluxo` (
  `recurso_sistema_codigo` int(11) NOT NULL,
  `registro_codigo` int(11) NOT NULL,
  `etapa_fluxo_codigo` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `usuario_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registro_etapa_fluxo`
--

LOCK TABLES `registro_etapa_fluxo` WRITE;
/*!40000 ALTER TABLE `registro_etapa_fluxo` DISABLE KEYS */;
/*!40000 ALTER TABLE `registro_etapa_fluxo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `representante_digital`
--

DROP TABLE IF EXISTS `representante_digital`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `representante_digital` (
  `codigo` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `recurso_sistema_codigo` int(11) NOT NULL,
  `registro_codigo` int(11) NOT NULL,
  `tipo` int(11) DEFAULT NULL,
  `formato` varchar(10) DEFAULT NULL,
  `path` varchar(500) DEFAULT NULL,
  `sequencia` int(11) DEFAULT NULL,
  `publicado_online` tinyint(1) DEFAULT 1,
  `tipo_codigo` int(11) DEFAULT NULL,
  `legenda` varchar(250) DEFAULT NULL,
  `nome_original` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `recurso_sistema_codigo` (`recurso_sistema_codigo`),
  KEY `representante_digital_tipo_representante_digital_codigo_fk` (`tipo_codigo`),
  CONSTRAINT `representante_digital_ibfk_1` FOREIGN KEY (`recurso_sistema_codigo`) REFERENCES `recurso_sistema` (`codigo`),
  CONSTRAINT `representante_digital_tipo_representante_digital_codigo_fk` FOREIGN KEY (`tipo_codigo`) REFERENCES `tipo_representante_digital` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=4321 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `representante_digital`
--

LOCK TABLES `representante_digital` WRITE;
/*!40000 ALTER TABLE `representante_digital` DISABLE KEYS */;
/*!40000 ALTER TABLE `representante_digital` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saida_item_acervo`
--

DROP TABLE IF EXISTS `saida_item_acervo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `saida_item_acervo` (
  `codigo` int(11) NOT NULL,
  `tipo_codigo` int(11) NOT NULL,
  `data` date NOT NULL,
  `data_prevista_retorno` date NOT NULL,
  `descricao` text DEFAULT NULL,
  `itens_devolvidos` tinyint(1) DEFAULT NULL,
  `data_retorno` date DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `tipo_codigo` (`tipo_codigo`),
  CONSTRAINT `saida_item_acervo_ibfk_1` FOREIGN KEY (`tipo_codigo`) REFERENCES `tipo_saida_item_acervo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saida_item_acervo`
--

LOCK TABLES `saida_item_acervo` WRITE;
/*!40000 ALTER TABLE `saida_item_acervo` DISABLE KEYS */;
/*!40000 ALTER TABLE `saida_item_acervo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saida_item_acervo_consulente`
--

DROP TABLE IF EXISTS `saida_item_acervo_consulente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `saida_item_acervo_consulente` (
  `saida_item_acervo_codigo` int(11) NOT NULL,
  `consulente_codigo` int(11) NOT NULL,
  PRIMARY KEY (`saida_item_acervo_codigo`,`consulente_codigo`),
  KEY `saida_item_acervo_consulente_FK` (`consulente_codigo`),
  CONSTRAINT `saida_item_acervo_consulente_FK` FOREIGN KEY (`consulente_codigo`) REFERENCES `consulente` (`codigo`),
  CONSTRAINT `saida_item_acervo_consulente_FK_1` FOREIGN KEY (`saida_item_acervo_codigo`) REFERENCES `saida_item_acervo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saida_item_acervo_consulente`
--

LOCK TABLES `saida_item_acervo_consulente` WRITE;
/*!40000 ALTER TABLE `saida_item_acervo_consulente` DISABLE KEYS */;
/*!40000 ALTER TABLE `saida_item_acervo_consulente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saida_item_acervo_item_acervo_status`
--

DROP TABLE IF EXISTS `saida_item_acervo_item_acervo_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `saida_item_acervo_item_acervo_status` (
  `saida_item_acervo_codigo` int(11) NOT NULL,
  `item_acervo_codigo` int(11) NOT NULL,
  `data_saida` date DEFAULT NULL,
  `data_retorno_prevista` date DEFAULT NULL,
  `data_retorno` date DEFAULT NULL,
  `status_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`saida_item_acervo_codigo`,`item_acervo_codigo`),
  KEY `item_acervo_codigo` (`item_acervo_codigo`),
  CONSTRAINT `saida_item_acervo_item_acervo_status_ibfk_1` FOREIGN KEY (`saida_item_acervo_codigo`) REFERENCES `saida_item_acervo` (`codigo`),
  CONSTRAINT `saida_item_acervo_item_acervo_status_ibfk_2` FOREIGN KEY (`item_acervo_codigo`) REFERENCES `item_acervo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saida_item_acervo_item_acervo_status`
--

LOCK TABLES `saida_item_acervo_item_acervo_status` WRITE;
/*!40000 ALTER TABLE `saida_item_acervo_item_acervo_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `saida_item_acervo_item_acervo_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `selecao`
--

DROP TABLE IF EXISTS `selecao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `selecao` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `tipo_codigo` int(11) DEFAULT NULL,
  `usuario_codigo` int(11) NOT NULL,
  `data` date NOT NULL,
  `recurso_sistema_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `Usuario_Codigo` (`usuario_codigo`),
  KEY `selecao_FK` (`recurso_sistema_codigo`),
  CONSTRAINT `selecao_FK` FOREIGN KEY (`recurso_sistema_codigo`) REFERENCES `recurso_sistema` (`codigo`),
  CONSTRAINT `selecao_ibfk_1` FOREIGN KEY (`usuario_codigo`) REFERENCES `usuario` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `selecao`
--

LOCK TABLES `selecao` WRITE;
/*!40000 ALTER TABLE `selecao` DISABLE KEYS */;
/*!40000 ALTER TABLE `selecao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `selecao_item`
--

DROP TABLE IF EXISTS `selecao_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `selecao_item` (
  `selecao_codigo` int(11) NOT NULL,
  `item_codigo` int(11) NOT NULL,
  `item_acervo_consultado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`item_codigo`,`selecao_codigo`),
  KEY `selecao_codigo` (`selecao_codigo`),
  KEY `item_acervo_codigo` (`item_codigo`),
  CONSTRAINT `selecao_item_ibfk_1` FOREIGN KEY (`selecao_codigo`) REFERENCES `selecao` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `selecao_item`
--

LOCK TABLES `selecao_item` WRITE;
/*!40000 ALTER TABLE `selecao_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `selecao_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `selecao_usuario`
--

DROP TABLE IF EXISTS `selecao_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `selecao_usuario` (
  `selecao_codigo` int(11) DEFAULT NULL,
  `usuario_codigo` int(11) DEFAULT NULL,
  KEY `selecao_usuario_selecao_FK` (`selecao_codigo`),
  KEY `selecao_usuario_usuario_FK` (`usuario_codigo`),
  CONSTRAINT `selecao_usuario_selecao_FK` FOREIGN KEY (`selecao_codigo`) REFERENCES `selecao` (`codigo`),
  CONSTRAINT `selecao_usuario_usuario_FK` FOREIGN KEY (`usuario_codigo`) REFERENCES `usuario` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `selecao_usuario`
--

LOCK TABLES `selecao_usuario` WRITE;
/*!40000 ALTER TABLE `selecao_usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `selecao_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `serie`
--

DROP TABLE IF EXISTS `serie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `serie` (
  `codigo` int(11) NOT NULL,
  `acervo_codigo` int(11) DEFAULT NULL,
  `codigo_referencia` varchar(250) DEFAULT NULL,
  `nome` varchar(250) NOT NULL,
  `identificador` varchar(250) DEFAULT NULL,
  `agrupamento_codigo` int(11) DEFAULT NULL,
  `especie_documental_codigo` int(11) DEFAULT NULL,
  `tipo_documental_codigo` int(11) DEFAULT NULL,
  `quantidade_itens` int(11) DEFAULT NULL,
  `atividade_geradora_codigo` int(11) DEFAULT NULL,
  `data_inicial` date DEFAULT NULL,
  `data_final` date DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `serie_superior_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `acervo_codigo` (`acervo_codigo`),
  KEY `serie_FK` (`atividade_geradora_codigo`),
  KEY `serie_FK_1` (`agrupamento_codigo`),
  KEY `serie_FK_2` (`especie_documental_codigo`),
  KEY `serie_FK_3` (`tipo_documental_codigo`),
  KEY `serie_FK_4` (`serie_superior_codigo`),
  CONSTRAINT `serie_FK` FOREIGN KEY (`atividade_geradora_codigo`) REFERENCES `atividade_geradora` (`codigo`),
  CONSTRAINT `serie_FK_1` FOREIGN KEY (`agrupamento_codigo`) REFERENCES `agrupamento` (`codigo`),
  CONSTRAINT `serie_FK_2` FOREIGN KEY (`especie_documental_codigo`) REFERENCES `especie_documental` (`Codigo`),
  CONSTRAINT `serie_FK_3` FOREIGN KEY (`tipo_documental_codigo`) REFERENCES `tipo_documental` (`codigo`),
  CONSTRAINT `serie_FK_4` FOREIGN KEY (`serie_superior_codigo`) REFERENCES `serie` (`codigo`),
  CONSTRAINT `serie_ibfk_1` FOREIGN KEY (`acervo_codigo`) REFERENCES `acervo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `serie`
--

LOCK TABLES `serie` WRITE;
/*!40000 ALTER TABLE `serie` DISABLE KEYS */;
/*!40000 ALTER TABLE `serie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `serie_assunto`
--

DROP TABLE IF EXISTS `serie_assunto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `serie_assunto` (
  `serie_codigo` int(11) NOT NULL,
  `assunto_codigo` int(11) NOT NULL,
  PRIMARY KEY (`serie_codigo`,`assunto_codigo`),
  KEY `serie_assunto_FK` (`assunto_codigo`),
  CONSTRAINT `serie_assunto_FK` FOREIGN KEY (`assunto_codigo`) REFERENCES `assunto` (`codigo`),
  CONSTRAINT `serie_assunto_FK_1` FOREIGN KEY (`serie_codigo`) REFERENCES `serie` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `serie_assunto`
--

LOCK TABLES `serie_assunto` WRITE;
/*!40000 ALTER TABLE `serie_assunto` DISABLE KEYS */;
/*!40000 ALTER TABLE `serie_assunto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `serie_entidade`
--

DROP TABLE IF EXISTS `serie_entidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `serie_entidade` (
  `serie_codigo` int(11) NOT NULL,
  `entidade_codigo` int(11) NOT NULL,
  PRIMARY KEY (`serie_codigo`,`entidade_codigo`),
  KEY `serie_entidade_FK` (`entidade_codigo`),
  CONSTRAINT `serie_entidade_FK` FOREIGN KEY (`entidade_codigo`) REFERENCES `entidade` (`codigo`),
  CONSTRAINT `serie_entidade_FK_1` FOREIGN KEY (`serie_codigo`) REFERENCES `serie` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `serie_entidade`
--

LOCK TABLES `serie_entidade` WRITE;
/*!40000 ALTER TABLE `serie_entidade` DISABLE KEYS */;
/*!40000 ALTER TABLE `serie_entidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setor_sistema`
--

DROP TABLE IF EXISTS `setor_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `setor_sistema` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `recurso_sistema_padrao_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `setor_sistema_FK` (`recurso_sistema_padrao_codigo`),
  CONSTRAINT `setor_sistema_FK` FOREIGN KEY (`recurso_sistema_padrao_codigo`) REFERENCES `recurso_sistema` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setor_sistema`
--

LOCK TABLES `setor_sistema` WRITE;
/*!40000 ALTER TABLE `setor_sistema` DISABLE KEYS */;
INSERT INTO `setor_sistema` VALUES
(1,'Arquivo',1),
(2,'Biblioteca',25),
(3,'História oral',65),
(4,'Tridimensional',14);
/*!40000 ALTER TABLE `setor_sistema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setor_sistema_recurso_sistema`
--

DROP TABLE IF EXISTS `setor_sistema_recurso_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `setor_sistema_recurso_sistema` (
  `setor_sistema_codigo` int(11) NOT NULL,
  `recurso_sistema_codigo` int(11) NOT NULL,
  KEY `setor_sistema_recurso_sistema_FK` (`recurso_sistema_codigo`),
  KEY `setor_sistema_recurso_sistema_FK_1` (`setor_sistema_codigo`),
  CONSTRAINT `setor_sistema_recurso_sistema_FK` FOREIGN KEY (`recurso_sistema_codigo`) REFERENCES `recurso_sistema` (`codigo`),
  CONSTRAINT `setor_sistema_recurso_sistema_FK_1` FOREIGN KEY (`setor_sistema_codigo`) REFERENCES `setor_sistema` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setor_sistema_recurso_sistema`
--

LOCK TABLES `setor_sistema_recurso_sistema` WRITE;
/*!40000 ALTER TABLE `setor_sistema_recurso_sistema` DISABLE KEYS */;
INSERT INTO `setor_sistema_recurso_sistema` VALUES
(4,22),
(4,69),
(4,14),
(4,15),
(4,40),
(3,2),
(3,65),
(3,70),
(3,48),
(3,47),
(2,25),
(2,42),
(2,59),
(2,2),
(2,35),
(2,58),
(1,1),
(1,2),
(1,5),
(1,6),
(1,7),
(1,8),
(1,10),
(1,11),
(1,40),
(1,46),
(1,59),
(1,60),
(1,78),
(1,79);
/*!40000 ALTER TABLE `setor_sistema_recurso_sistema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status_item_acervo`
--

DROP TABLE IF EXISTS `status_item_acervo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `status_item_acervo` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status_item_acervo`
--

LOCK TABLES `status_item_acervo` WRITE;
/*!40000 ALTER TABLE `status_item_acervo` DISABLE KEYS */;
/*!40000 ALTER TABLE `status_item_acervo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suporte`
--

DROP TABLE IF EXISTS `suporte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `suporte` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suporte`
--

LOCK TABLES `suporte` WRITE;
/*!40000 ALTER TABLE `suporte` DISABLE KEYS */;
/*!40000 ALTER TABLE `suporte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tecnica`
--

DROP TABLE IF EXISTS `tecnica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tecnica` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tecnica`
--

LOCK TABLES `tecnica` WRITE;
/*!40000 ALTER TABLE `tecnica` DISABLE KEYS */;
/*!40000 ALTER TABLE `tecnica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tecnica_registro`
--

DROP TABLE IF EXISTS `tecnica_registro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tecnica_registro` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tecnica_registro`
--

LOCK TABLES `tecnica_registro` WRITE;
/*!40000 ALTER TABLE `tecnica_registro` DISABLE KEYS */;
/*!40000 ALTER TABLE `tecnica_registro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tema`
--

DROP TABLE IF EXISTS `tema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tema` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `acervo_codigo` int(11) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `tema_FK` (`acervo_codigo`),
  CONSTRAINT `tema_FK` FOREIGN KEY (`acervo_codigo`) REFERENCES `acervo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tema`
--

LOCK TABLES `tema` WRITE;
/*!40000 ALTER TABLE `tema` DISABLE KEYS */;
/*!40000 ALTER TABLE `tema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_acervo`
--

DROP TABLE IF EXISTS `tipo_acervo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_acervo` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_acervo`
--

LOCK TABLES `tipo_acervo` WRITE;
/*!40000 ALTER TABLE `tipo_acervo` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_acervo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_acesso`
--

DROP TABLE IF EXISTS `tipo_acesso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_acesso` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_acesso`
--

LOCK TABLES `tipo_acesso` WRITE;
/*!40000 ALTER TABLE `tipo_acesso` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_acesso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_arquivo`
--

DROP TABLE IF EXISTS `tipo_arquivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_arquivo` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_arquivo`
--

LOCK TABLES `tipo_arquivo` WRITE;
/*!40000 ALTER TABLE `tipo_arquivo` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_arquivo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_autor`
--

DROP TABLE IF EXISTS `tipo_autor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_autor` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_autor`
--

LOCK TABLES `tipo_autor` WRITE;
/*!40000 ALTER TABLE `tipo_autor` DISABLE KEYS */;
INSERT INTO `tipo_autor` VALUES
(1,'autoria'),
(5,'entrevistado'),
(11,'entrevistador'),
(27,'assunto'),
(32,'proprietário'),
(33,'custodiador');
/*!40000 ALTER TABLE `tipo_autor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_campo_sistema`
--

DROP TABLE IF EXISTS `tipo_campo_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_campo_sistema` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_campo_sistema`
--

LOCK TABLES `tipo_campo_sistema` WRITE;
/*!40000 ALTER TABLE `tipo_campo_sistema` DISABLE KEYS */;
INSERT INTO `tipo_campo_sistema` VALUES
(1,'texto'),
(2,'número'),
(3,'data'),
(4,'booleano'),
(5,'relação com objeto'),
(6,'agrupador');
/*!40000 ALTER TABLE `tipo_campo_sistema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_contexto`
--

DROP TABLE IF EXISTS `tipo_contexto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_contexto` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_contexto`
--

LOCK TABLES `tipo_contexto` WRITE;
/*!40000 ALTER TABLE `tipo_contexto` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_contexto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_dado`
--

DROP TABLE IF EXISTS `tipo_dado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_dado` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_dado`
--

LOCK TABLES `tipo_dado` WRITE;
/*!40000 ALTER TABLE `tipo_dado` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_dado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_dimensao`
--

DROP TABLE IF EXISTS `tipo_dimensao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_dimensao` (
  `Codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`Codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_dimensao`
--

LOCK TABLES `tipo_dimensao` WRITE;
/*!40000 ALTER TABLE `tipo_dimensao` DISABLE KEYS */;
INSERT INTO `tipo_dimensao` VALUES
(1,'altura'),
(2,'largura'),
(3,'profundidade'),
(4,'número de páginas'),
(5,'número de folhas'),
(6,'altura x largura'),
(7,'moldura (V x H x P)');
/*!40000 ALTER TABLE `tipo_dimensao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_dimensao_unidade_medida`
--

DROP TABLE IF EXISTS `tipo_dimensao_unidade_medida`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_dimensao_unidade_medida` (
  `tipo_dimensao_codigo` int(11) NOT NULL,
  `unidade_medida_codigo` int(11) NOT NULL,
  PRIMARY KEY (`tipo_dimensao_codigo`,`unidade_medida_codigo`),
  KEY `tipo_dimensao_unidade_medida_FK_1` (`unidade_medida_codigo`),
  CONSTRAINT `tipo_dimensao_unidade_medida_FK` FOREIGN KEY (`tipo_dimensao_codigo`) REFERENCES `tipo_dimensao` (`Codigo`),
  CONSTRAINT `tipo_dimensao_unidade_medida_FK_1` FOREIGN KEY (`unidade_medida_codigo`) REFERENCES `unidade_medida` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_dimensao_unidade_medida`
--

LOCK TABLES `tipo_dimensao_unidade_medida` WRITE;
/*!40000 ALTER TABLE `tipo_dimensao_unidade_medida` DISABLE KEYS */;
INSERT INTO `tipo_dimensao_unidade_medida` VALUES
(1,1),
(2,2),
(3,3),
(7,1);
/*!40000 ALTER TABLE `tipo_dimensao_unidade_medida` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_documental`
--

DROP TABLE IF EXISTS `tipo_documental`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_documental` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `especie_documental_codigo` int(11) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `Especie_Documental_Codigo` (`especie_documental_codigo`),
  CONSTRAINT `tipo_documental_ibfk_1` FOREIGN KEY (`especie_documental_codigo`) REFERENCES `especie_documental` (`Codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_documental`
--

LOCK TABLES `tipo_documental` WRITE;
/*!40000 ALTER TABLE `tipo_documental` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_documental` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_edicao`
--

DROP TABLE IF EXISTS `tipo_edicao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_edicao` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_edicao`
--

LOCK TABLES `tipo_edicao` WRITE;
/*!40000 ALTER TABLE `tipo_edicao` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_edicao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_entrevista`
--

DROP TABLE IF EXISTS `tipo_entrevista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_entrevista` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_entrevista`
--

LOCK TABLES `tipo_entrevista` WRITE;
/*!40000 ALTER TABLE `tipo_entrevista` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_entrevista` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_material`
--

DROP TABLE IF EXISTS `tipo_material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_material` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_material`
--

LOCK TABLES `tipo_material` WRITE;
/*!40000 ALTER TABLE `tipo_material` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_objeto`
--

DROP TABLE IF EXISTS `tipo_objeto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_objeto` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_objeto`
--

LOCK TABLES `tipo_objeto` WRITE;
/*!40000 ALTER TABLE `tipo_objeto` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_objeto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_operacao_log`
--

DROP TABLE IF EXISTS `tipo_operacao_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_operacao_log` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_operacao_log`
--

LOCK TABLES `tipo_operacao_log` WRITE;
/*!40000 ALTER TABLE `tipo_operacao_log` DISABLE KEYS */;
INSERT INTO `tipo_operacao_log` VALUES
(1,'Criação do registro',NULL),
(2,'Alteração do registro',NULL),
(3,'Inserção de imagem digital',NULL),
(4,'Inserção de anexo',NULL),
(7,'Exclusão de imagem digital',NULL),
(8,'Exclusão de anexo',NULL);
/*!40000 ALTER TABLE `tipo_operacao_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_representante_digital`
--

DROP TABLE IF EXISTS `tipo_representante_digital`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_representante_digital` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_representante_digital`
--

LOCK TABLES `tipo_representante_digital` WRITE;
/*!40000 ALTER TABLE `tipo_representante_digital` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_representante_digital` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_saida_item_acervo`
--

DROP TABLE IF EXISTS `tipo_saida_item_acervo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_saida_item_acervo` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `status_item_acervo_saida_codigo` int(11) DEFAULT NULL,
  `status_item_acervo_retorno_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `status_item_acervo_retorno_codigo` (`status_item_acervo_retorno_codigo`),
  KEY `status_item_acervo_saida_codigo` (`status_item_acervo_saida_codigo`),
  CONSTRAINT `tipo_saida_item_acervo_ibfk_1` FOREIGN KEY (`status_item_acervo_retorno_codigo`) REFERENCES `status_item_acervo` (`codigo`),
  CONSTRAINT `tipo_saida_item_acervo_ibfk_2` FOREIGN KEY (`status_item_acervo_saida_codigo`) REFERENCES `status_item_acervo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_saida_item_acervo`
--

LOCK TABLES `tipo_saida_item_acervo` WRITE;
/*!40000 ALTER TABLE `tipo_saida_item_acervo` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_saida_item_acervo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidade_armazenamento`
--

DROP TABLE IF EXISTS `unidade_armazenamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `unidade_armazenamento` (
  `codigo` int(11) NOT NULL,
  `instituicao_codigo` int(11) DEFAULT NULL,
  `acervo_codigo` int(11) DEFAULT NULL,
  `nome` varchar(250) NOT NULL,
  `descricao` text DEFAULT NULL,
  `unidade_armazenamento_superior_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `Acervo_Codigo` (`acervo_codigo`),
  KEY `Instituicao_Codigo` (`instituicao_codigo`),
  KEY `Unidade_Armazenamento_Superior_Codigo` (`unidade_armazenamento_superior_codigo`),
  CONSTRAINT `unidade_armazenamento_ibfk_1` FOREIGN KEY (`acervo_codigo`) REFERENCES `acervo` (`codigo`),
  CONSTRAINT `unidade_armazenamento_ibfk_2` FOREIGN KEY (`instituicao_codigo`) REFERENCES `instituicao` (`codigo`),
  CONSTRAINT `unidade_armazenamento_ibfk_3` FOREIGN KEY (`unidade_armazenamento_superior_codigo`) REFERENCES `unidade_armazenamento` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidade_armazenamento`
--

LOCK TABLES `unidade_armazenamento` WRITE;
/*!40000 ALTER TABLE `unidade_armazenamento` DISABLE KEYS */;
/*!40000 ALTER TABLE `unidade_armazenamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidade_medida`
--

DROP TABLE IF EXISTS `unidade_medida`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `unidade_medida` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidade_medida`
--

LOCK TABLES `unidade_medida` WRITE;
/*!40000 ALTER TABLE `unidade_medida` DISABLE KEYS */;
INSERT INTO `unidade_medida` VALUES
(1,'cm'),
(2,'unidades'),
(3,'min');
/*!40000 ALTER TABLE `unidade_medida` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `codigo` int(11) NOT NULL,
  `instituicao_codigo` int(11) DEFAULT NULL,
  `tipo_codigo` int(11) DEFAULT NULL,
  `nome` varchar(250) NOT NULL,
  `login` varchar(100) DEFAULT NULL,
  `senha` varchar(250) DEFAULT NULL,
  `telefone` varchar(100) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL,
  `token` varchar(500) DEFAULT NULL,
  `ultimo_login` datetime DEFAULT NULL,
  `senha_provisoria` varchar(250) DEFAULT NULL,
  `expiracao_senha_provisoria` datetime DEFAULT NULL,
  `setor_sistema_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `fk_usuario_setor_sistema` (`setor_sistema_codigo`),
  CONSTRAINT `fk_usuario_setor_sistema` FOREIGN KEY (`setor_sistema_codigo`) REFERENCES `setor_sistema` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_acervo`
--

DROP TABLE IF EXISTS `usuario_acervo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_acervo` (
  `usuario_codigo` int(11) NOT NULL,
  `acervo_codigo` int(11) NOT NULL,
  KEY `Acervo_Codigo` (`acervo_codigo`),
  KEY `Usuario_Codigo` (`usuario_codigo`),
  CONSTRAINT `usuario_acervo_ibfk_1` FOREIGN KEY (`acervo_codigo`) REFERENCES `acervo` (`codigo`),
  CONSTRAINT `usuario_acervo_ibfk_2` FOREIGN KEY (`usuario_codigo`) REFERENCES `usuario` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_acervo`
--

LOCK TABLES `usuario_acervo` WRITE;
/*!40000 ALTER TABLE `usuario_acervo` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_acervo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_endereco`
--

DROP TABLE IF EXISTS `usuario_endereco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_endereco` (
  `usuario_codigo` int(11) DEFAULT NULL,
  `logradouro` varchar(1000) DEFAULT NULL,
  `bairro` varchar(1000) DEFAULT NULL,
  `localidade_codigo` int(11) DEFAULT NULL,
  KEY `usuario_endereco_FK` (`localidade_codigo`),
  CONSTRAINT `usuario_endereco_FK` FOREIGN KEY (`localidade_codigo`) REFERENCES `localidade` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_endereco`
--

LOCK TABLES `usuario_endereco` WRITE;
/*!40000 ALTER TABLE `usuario_endereco` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_endereco` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_grupo_usuario`
--

DROP TABLE IF EXISTS `usuario_grupo_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_grupo_usuario` (
  `usuario_codigo` int(11) NOT NULL,
  `grupo_usuario_codigo` int(11) NOT NULL,
  KEY `Grupo_Usuario_Codigo` (`grupo_usuario_codigo`),
  KEY `Usuario_Codigo` (`usuario_codigo`),
  CONSTRAINT `usuario_grupo_usuario_ibfk_1` FOREIGN KEY (`grupo_usuario_codigo`) REFERENCES `grupo_usuario` (`Codigo`),
  CONSTRAINT `usuario_grupo_usuario_ibfk_2` FOREIGN KEY (`usuario_codigo`) REFERENCES `usuario` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_grupo_usuario`
--

LOCK TABLES `usuario_grupo_usuario` WRITE;
/*!40000 ALTER TABLE `usuario_grupo_usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_grupo_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_setor_sistema`
--

DROP TABLE IF EXISTS `usuario_setor_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_setor_sistema` (
  `usuario_codigo` int(11) NOT NULL,
  `setor_sistema_codigo` int(11) NOT NULL,
  KEY `setor_codigo` (`setor_sistema_codigo`) USING BTREE,
  KEY `usuario_codigo` (`usuario_codigo`) USING BTREE,
  CONSTRAINT `usuario_setor_sistema_FK` FOREIGN KEY (`setor_sistema_codigo`) REFERENCES `setor_sistema` (`codigo`),
  CONSTRAINT `usuario_setor_sistema_FK_1` FOREIGN KEY (`usuario_codigo`) REFERENCES `usuario` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_setor_sistema`
--

LOCK TABLES `usuario_setor_sistema` WRITE;
/*!40000 ALTER TABLE `usuario_setor_sistema` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_setor_sistema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visualizacao`
--

DROP TABLE IF EXISTS `visualizacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `visualizacao` (
  `codigo` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `recurso_sistema_codigo` int(11) NOT NULL,
  `habilitado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `recurso_sistema_codigo` (`recurso_sistema_codigo`),
  CONSTRAINT `visualizacao_ibfk_1` FOREIGN KEY (`recurso_sistema_codigo`) REFERENCES `recurso_sistema` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visualizacao`
--

LOCK TABLES `visualizacao` WRITE;
/*!40000 ALTER TABLE `visualizacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `visualizacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visualizacao_campo_sistema`
--

DROP TABLE IF EXISTS `visualizacao_campo_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `visualizacao_campo_sistema` (
  `visualizacao_codigo` int(11) NOT NULL,
  `campo_sistema_codigo` int(11) NOT NULL,
  `sequencia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visualizacao_campo_sistema`
--

LOCK TABLES `visualizacao_campo_sistema` WRITE;
/*!40000 ALTER TABLE `visualizacao_campo_sistema` DISABLE KEYS */;
/*!40000 ALTER TABLE `visualizacao_campo_sistema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visualizacao_contexto_visualizacao`
--

DROP TABLE IF EXISTS `visualizacao_contexto_visualizacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `visualizacao_contexto_visualizacao` (
  `visualizacao_codigo` int(11) DEFAULT NULL,
  `contexto_visualizacao_codigo` int(11) DEFAULT NULL,
  KEY `visualizacao_contexto_visualizacao_FK` (`visualizacao_codigo`),
  KEY `visualizacao_contexto_visualizacao_FK_1` (`contexto_visualizacao_codigo`),
  CONSTRAINT `visualizacao_contexto_visualizacao_FK` FOREIGN KEY (`visualizacao_codigo`) REFERENCES `visualizacao` (`codigo`),
  CONSTRAINT `visualizacao_contexto_visualizacao_FK_1` FOREIGN KEY (`contexto_visualizacao_codigo`) REFERENCES `contexto_visualizacao` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visualizacao_contexto_visualizacao`
--

LOCK TABLES `visualizacao_contexto_visualizacao` WRITE;
/*!40000 ALTER TABLE `visualizacao_contexto_visualizacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `visualizacao_contexto_visualizacao` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-22 18:52:11
