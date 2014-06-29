<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "userfn10.php" ?>
<?php
	ew_Header(TRUE);
	$conn = ew_Connect();
	$Language = new cLanguage();

	// Security
	$Security = new cAdvancedSecurity();
	if (!$Security->IsLoggedIn()) $Security->AutoLogin();
	$Security->LoadUserLevel(); // Load User Level
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $Language->Phrase("MobileMenu") ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="<?php echo ew_jQueryFile("jquery.mobile-%v.min.css") ?>">
<link rel="stylesheet" type="text/css" href="<?php echo EW_PROJECT_STYLESHEET_FILENAME ?>">
<link rel="stylesheet" type="text/css" href="phpcss/ewmobile.css">
<script type="text/javascript" src="<?php echo ew_jQueryFile("jquery-%v.min.js") ?>"></script>
<script type="text/javascript">
	$(document).bind("mobileinit", function() {
		jQuery.mobile.ajaxEnabled = false;
		jQuery.mobile.ignoreContentEnabled = true;
	});
</script>
<script type="text/javascript" src="<?php echo ew_jQueryFile("jquery.mobile-%v.min.js") ?>"></script>
<meta name="generator" content="PHPMaker v10.0.1">
</head>
<body>
<div data-role="page">
	<div data-role="header">
		<h1><?php echo $Language->ProjectPhrase("BodyTitle") ?></h1>
	</div>
	<div data-role="content">
<?php $RootMenu = new cMenu("RootMenu", TRUE); ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(33, $Language->MenuPhrase("33", "MenuText"), "", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(203, $Language->MenuPhrase("203", "MenuText"), "organizacaolist.php", 33, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}organizacao'), FALSE);
$RootMenu->AddMenuItem(3718, $Language->MenuPhrase("3718", "MenuText"), "", 33, "", TRUE, FALSE);
$RootMenu->AddMenuItem(1596, $Language->MenuPhrase("1596", "MenuText"), "", 33, "", TRUE, FALSE);
$RootMenu->AddMenuItem(4633, $Language->MenuPhrase("4633", "MenuText"), "", 33, "", TRUE, FALSE);
$RootMenu->AddMenuItem(3720, $Language->MenuPhrase("3720", "MenuText"), "", 33, "", TRUE, FALSE);
$RootMenu->AddMenuItem(762, $Language->MenuPhrase("762", "MenuText"), "", 33, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(514, $Language->MenuPhrase("514", "MenuText"), "criteriolist.php", 762, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}criterio'), FALSE);
$RootMenu->AddMenuItem(2837, $Language->MenuPhrase("2837", "MenuText"), "probocoriscolist.php", 762, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}probocorisco'), FALSE);
$RootMenu->AddMenuItem(2836, $Language->MenuPhrase("2836", "MenuText"), "impactoriscolist.php", 762, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}impactorisco'), FALSE);
$RootMenu->AddMenuItem(2835, $Language->MenuPhrase("2835", "MenuText"), "catriscoprojlist.php", 762, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}catriscoproj'), FALSE);
$RootMenu->AddMenuItem(2834, $Language->MenuPhrase("2834", "MenuText"), "acaoriscolist.php", 762, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}acaorisco'), FALSE);
$RootMenu->AddMenuItem(3719, $Language->MenuPhrase("3719", "MenuText"), "", 33, "", TRUE, FALSE);
$RootMenu->AddMenuItem(11, $Language->MenuPhrase("11", "MenuText"), "plataformalist.php", 3719, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}plataforma'), FALSE);
$RootMenu->AddMenuItem(20, $Language->MenuPhrase("20", "MenuText"), "tecdesenvsislist.php", 3719, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}tecdesenvsis'), FALSE);
$RootMenu->AddMenuItem(8, $Language->MenuPhrase("8", "MenuText"), "metodologialist.php", 3719, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}metodologia'), FALSE);
$RootMenu->AddMenuItem(13, $Language->MenuPhrase("13", "MenuText"), "roteirolist.php?cmd=resetall", 3719, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}roteiro'), FALSE);
$RootMenu->AddMenuItem(902, $Language->MenuPhrase("902", "MenuText"), "", 33, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(1, $Language->MenuPhrase("1", "MenuText"), "ambientelist.php", 902, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}ambiente'), FALSE);
$RootMenu->AddMenuItem(294, $Language->MenuPhrase("294", "MenuText"), "parsisplist.php", 902, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}parSisp'), FALSE);
$RootMenu->AddMenuItem(283, $Language->MenuPhrase("283", "MenuText"), "tpmetricalist.php", 902, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}tpmetrica'), FALSE);
$RootMenu->AddMenuItem(2632, $Language->MenuPhrase("2632", "MenuText"), "ciiquestaolist.php", 902, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}ciiquestao'), FALSE);
$RootMenu->AddMenuItem(22, $Language->MenuPhrase("22", "MenuText"), "tpsistemalist.php", 902, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}tpsistema'), FALSE);
$RootMenu->AddMenuItem(4650, $Language->MenuPhrase("4650", "MenuText"), "", 33, "", TRUE, FALSE);
$RootMenu->AddMenuItem(4648, $Language->MenuPhrase("4648", "MenuText"), "suptvusuariolist.php", 4650, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}suptvusuario'), FALSE);
$RootMenu->AddMenuItem(1887, $Language->MenuPhrase("1887", "MenuText"), "widgetlist.php", 4650, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}widget'), FALSE);
$RootMenu->AddMenuItem(4926, $Language->MenuPhrase("4926", "MenuText"), "", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(4927, $Language->MenuPhrase("4927", "MenuText"), "", 4926, "", TRUE, FALSE);
$RootMenu->AddMenuItem(207, $Language->MenuPhrase("207", "MenuText"), "verticalnegociolist.php", 4927, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}verticalnegocio'), FALSE);
$RootMenu->AddMenuItem(21, $Language->MenuPhrase("21", "MenuText"), "tpnegociolist.php", 4927, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}tpnegocio'), FALSE);
$RootMenu->AddMenuItem(206, $Language->MenuPhrase("206", "MenuText"), "tparealist.php", 4927, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}tparea'), FALSE);
$RootMenu->AddMenuItem(4639, $Language->MenuPhrase("4639", "MenuText"), "", 4926, "", TRUE, FALSE);
$RootMenu->AddMenuItem(19, $Language->MenuPhrase("19", "MenuText"), "stusuariolist.php", 4639, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}stusuario'), FALSE);
$RootMenu->AddMenuItem(4928, $Language->MenuPhrase("4928", "MenuText"), "", 4926, "", TRUE, FALSE);
$RootMenu->AddMenuItem(1443, $Language->MenuPhrase("1443", "MenuText"), "origemnectilist.php", 4928, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}origemnecti'), FALSE);
$RootMenu->AddMenuItem(1444, $Language->MenuPhrase("1444", "MenuText"), "tpnectilist.php", 4928, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}tpnecti'), FALSE);
$RootMenu->AddMenuItem(4929, $Language->MenuPhrase("4929", "MenuText"), "", 4926, "", TRUE, FALSE);
$RootMenu->AddMenuItem(4638, $Language->MenuPhrase("4638", "MenuText"), "", 4926, "", TRUE, FALSE);
$RootMenu->AddMenuItem(282, $Language->MenuPhrase("282", "MenuText"), "tpitemlist.php", 4638, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}tpitem'), FALSE);
$RootMenu->AddMenuItem(286, $Language->MenuPhrase("286", "MenuText"), "unidadelist.php", 4638, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}unidade'), FALSE);
$RootMenu->AddMenuItem(276, $Language->MenuPhrase("276", "MenuText"), "stfornecedorlist.php", 4638, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}stFornecedor'), FALSE);
$RootMenu->AddMenuItem(274, $Language->MenuPhrase("274", "MenuText"), "stcontratolist.php", 4638, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}stcontrato'), FALSE);
$RootMenu->AddMenuItem(278, $Language->MenuPhrase("278", "MenuText"), "stoslist.php", 4638, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}stos'), FALSE);
$RootMenu->AddMenuItem(280, $Language->MenuPhrase("280", "MenuText"), "tipofaturalist.php", 4638, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}tipofatura'), FALSE);
$RootMenu->AddMenuItem(275, $Language->MenuPhrase("275", "MenuText"), "stfaturalist.php", 4638, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}stfatura'), FALSE);
$RootMenu->AddMenuItem(277, $Language->MenuPhrase("277", "MenuText"), "stoclist.php", 4638, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}stoc'), FALSE);
$RootMenu->AddMenuItem(4930, $Language->MenuPhrase("4930", "MenuText"), "", 4926, "", TRUE, FALSE);
$RootMenu->AddMenuItem(513, $Language->MenuPhrase("513", "MenuText"), "catprospectolist.php", 4930, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}catprospecto'), FALSE);
$RootMenu->AddMenuItem(284, $Language->MenuPhrase("284", "MenuText"), "tpprojetolist.php", 4930, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}tpprojeto'), FALSE);
$RootMenu->AddMenuItem(4636, $Language->MenuPhrase("4636", "MenuText"), "", 4926, "", TRUE, FALSE);
$RootMenu->AddMenuItem(17, $Language->MenuPhrase("17", "MenuText"), "stsistemalist.php", 4636, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}stsistema'), FALSE);
$RootMenu->AddMenuItem(18, $Language->MenuPhrase("18", "MenuText"), "stuclist.php", 4636, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}stuc'), FALSE);
$RootMenu->AddMenuItem(15, $Language->MenuPhrase("15", "MenuText"), "stmensagemlist.php", 4636, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}stmensagem'), FALSE);
$RootMenu->AddMenuItem(16, $Language->MenuPhrase("16", "MenuText"), "stregranegociolist.php", 4636, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}stregranegocio'), FALSE);
$RootMenu->AddMenuItem(191, $Language->MenuPhrase("191", "MenuText"), "", 4926, "", TRUE, FALSE);
$RootMenu->AddMenuItem(285, $Language->MenuPhrase("285", "MenuText"), "tpsolicitacaolist.php", 191, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}tpsolicitacao'), FALSE);
$RootMenu->AddMenuItem(273, $Language->MenuPhrase("273", "MenuText"), "stcontagemlist.php", 191, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}stcontagem'), FALSE);
$RootMenu->AddMenuItem(3715, $Language->MenuPhrase("3715", "MenuText"), "", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(27, $Language->MenuPhrase("27", "MenuText"), "usuariolist.php", 3715, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}usuario'), FALSE);
$RootMenu->AddMenuItem(74, $Language->MenuPhrase("74", "MenuText"), "usuario_permissoeslist.php", 3715, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(1888, $Language->MenuPhrase("1888", "MenuText"), "widget_perfillist.php", 3715, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}widget_perfil'), FALSE);
$RootMenu->AddMenuItem(903, $Language->MenuPhrase("903", "MenuText"), "", 3715, "", TRUE, FALSE);
$RootMenu->AddMenuItem(1154, $Language->MenuPhrase("1154", "MenuText"), "parintegracoeslist.php", 903, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}parintegracoes'), FALSE);
$RootMenu->AddMenuItem(2832, $Language->MenuPhrase("2832", "MenuText"), "processamentolist.php", 903, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}processamento'), FALSE);
$RootMenu->AddMenuItem(2824, $Language->MenuPhrase("2824", "MenuText"), "projagruprdmlist.php", 903, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}projagruprdm'), FALSE);
$RootMenu->AddMenuItem(4637, $Language->MenuPhrase("4637", "MenuText"), "", 3715, "", TRUE, FALSE);
$RootMenu->AddMenuItem(142, $Language->MenuPhrase("142", "MenuText"), "auditorialist.php", 4637, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}auditoria'), FALSE);
$RootMenu->AddMenuItem(6, $Language->MenuPhrase("6", "MenuText"), "logacessolist.php", 4637, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}logacesso'), FALSE);
$RootMenu->AddMenuItem(1783, $Language->MenuPhrase("1783", "MenuText"), "custom.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(111, $Language->MenuPhrase("111", "MenuText"), "", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(4651, $Language->MenuPhrase("4651", "MenuText"), "planoestrategicolist.php", 111, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}planoestrategico'), FALSE);
$RootMenu->AddMenuItem(1435, $Language->MenuPhrase("1435", "MenuText"), "metaneglist.php", 1440, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}metaneg'), FALSE);
$RootMenu->AddMenuItem(4652, $Language->MenuPhrase("4652", "MenuText"), "planotilist.php", 111, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}planoti'), FALSE);
$RootMenu->AddMenuItem(1436, $Language->MenuPhrase("1436", "MenuText"), "metatilist.php", 1155, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}metati'), FALSE);
$RootMenu->AddMenuItem(4640, $Language->MenuPhrase("4640", "MenuText"), "", 111, "", TRUE, FALSE);
$RootMenu->AddMenuItem(2624, $Language->MenuPhrase("2624", "MenuText"), "", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(4647, $Language->MenuPhrase("4647", "MenuText"), "gpcomitelist.php", 2624, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}gpcomite'), FALSE);
$RootMenu->AddMenuItem(4644, $Language->MenuPhrase("4644", "MenuText"), "gc_membrolist.php", 2624, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}gc_membro'), FALSE);
$RootMenu->AddMenuItem(4646, $Language->MenuPhrase("4646", "MenuText"), "gc_reuniaolist.php", 2624, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}gc_reuniao'), FALSE);
$RootMenu->AddMenuItem(4643, $Language->MenuPhrase("4643", "MenuText"), "gc_atalist.php", 2624, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}gc_ata'), FALSE);
$RootMenu->AddMenuItem(4645, $Language->MenuPhrase("4645", "MenuText"), "gc_resolucaolist.php", 2624, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}gc_resolucao'), FALSE);
$RootMenu->AddMenuItem(4634, $Language->MenuPhrase("4634", "MenuText"), "", 2624, "", TRUE, FALSE);
$RootMenu->AddMenuItem(112, $Language->MenuPhrase("112", "MenuText"), "", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(4641, $Language->MenuPhrase("4641", "MenuText"), "demandalist.php", 112, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}demanda'), FALSE);
$RootMenu->AddMenuItem(266, $Language->MenuPhrase("266", "MenuText"), "oclist.php", 112, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}oc'), FALSE);
$RootMenu->AddMenuItem(259, $Language->MenuPhrase("259", "MenuText"), "fornecedorlist.php", 112, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}fornecedor'), FALSE);
$RootMenu->AddMenuItem(255, $Language->MenuPhrase("255", "MenuText"), "contratolist.php", 112, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}contrato'), FALSE);
$RootMenu->AddMenuItem(267, $Language->MenuPhrase("267", "MenuText"), "oslist.php", 112, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}os'), FALSE);
$RootMenu->AddMenuItem(4649, $Language->MenuPhrase("4649", "MenuText"), "termolist.php", 112, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}termo'), FALSE);
$RootMenu->AddMenuItem(257, $Language->MenuPhrase("257", "MenuText"), "faturalist.php", 112, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}fatura'), FALSE);
$RootMenu->AddMenuItem(4635, $Language->MenuPhrase("4635", "MenuText"), "", 112, "", TRUE, FALSE);
$RootMenu->AddMenuItem(3721, $Language->MenuPhrase("3721", "MenuText"), "", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(4368, $Language->MenuPhrase("4368", "MenuText"), "pessoalist.php", 3721, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}pessoa'), FALSE);
$RootMenu->AddMenuItem(253, $Language->MenuPhrase("253", "MenuText"), "cargolist.php", 3721, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}cargo'), FALSE);
$RootMenu->AddMenuItem(204, $Language->MenuPhrase("204", "MenuText"), "papellist.php", 3721, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}papel'), FALSE);
$RootMenu->AddMenuItem(4632, $Language->MenuPhrase("4632", "MenuText"), "", 3721, "", TRUE, FALSE);
$RootMenu->AddMenuItem(181, $Language->MenuPhrase("181", "MenuText"), "", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(511, $Language->MenuPhrase("511", "MenuText"), "prospectolist.php?cmd=resetall", 181, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}prospecto'), FALSE);
$RootMenu->AddMenuItem(268, $Language->MenuPhrase("268", "MenuText"), "projetolist.php", 181, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}projeto'), FALSE);
$RootMenu->AddMenuItem(3220, $Language->MenuPhrase("3220", "MenuText"), "", 181, "", TRUE, FALSE);
$RootMenu->AddMenuItem(1885, $Language->MenuPhrase("1885", "MenuText"), "rprospresumolist.php", 3220, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}rprospresumo'), FALSE);
$RootMenu->AddMenuItem(4038, $Language->MenuPhrase("4038", "MenuText"), "vwgitd_stprospectoslist.php", 3220, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}vwgitd_stProspectos'), FALSE);
$RootMenu->AddMenuItem(4037, $Language->MenuPhrase("4037", "MenuText"), "vwgitd_catprospectoslist.php", 3220, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}vwgitd_catProspectos'), FALSE);
$RootMenu->AddMenuItem(4176, $Language->MenuPhrase("4176", "MenuText"), "acompanhamentoProjeto.php", 3220, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(3748, $Language->MenuPhrase("3748", "MenuText"), "relatf3rio_de_prioridades_do_financeiroreport.php", 3220, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}Relatório de Prioridades do Financeiro'), FALSE);
$RootMenu->AddMenuItem(3605, $Language->MenuPhrase("3605", "MenuText"), "vwrdmd_financeiropriorilist.php", 3220, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}vwrdmd_financeiroPriori'), FALSE);
$RootMenu->AddMenuItem(113, $Language->MenuPhrase("113", "MenuText"), "", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(14, $Language->MenuPhrase("14", "MenuText"), "sistemalist.php", 113, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}sistema'), FALSE);
$RootMenu->AddMenuItem(287, $Language->MenuPhrase("287", "MenuText"), "cornlist.php", 113, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}corn'), FALSE);
$RootMenu->AddMenuItem(7, $Language->MenuPhrase("7", "MenuText"), "mensagemlist.php", 113, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}mensagem'), FALSE);
$RootMenu->AddMenuItem(1038, $Language->MenuPhrase("1038", "MenuText"), "", 113, "", TRUE, FALSE);
$RootMenu->AddMenuItem(502, $Language->MenuPhrase("502", "MenuText"), "casos_de_usoreport.php", 1038, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}Casos de Uso'), FALSE);
$RootMenu->AddMenuItem(500, $Language->MenuPhrase("500", "MenuText"), "regras_de_negf3cioreport.php", 1038, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}Regras de Negócio'), FALSE);
$RootMenu->AddMenuItem(504, $Language->MenuPhrase("504", "MenuText"), "rnversoeslist.php", 1038, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}RnVersoes'), FALSE);
$RootMenu->AddMenuItem(505, $Language->MenuPhrase("505", "MenuText"), "versf5es_de_rn_por_uc_e_sistemareport.php", 1038, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}Versões de RN por UC e Sistema'), FALSE);
$RootMenu->AddMenuItem(501, $Language->MenuPhrase("501", "MenuText"), "mensagensreport.php", 1038, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}Mensagens'), FALSE);
$RootMenu->AddMenuItem(71, $Language->MenuPhrase("71", "MenuText"), "ambientes_por_tipo_de_negf3cioreport.php", 1038, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}Ambientes por Tipo de Negócio'), FALSE);
$RootMenu->AddMenuItem(199, $Language->MenuPhrase("199", "MenuText"), "atividadelist.php?cmd=resetall", 205, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}atividade'), FALSE);
$RootMenu->AddMenuItem(200, $Language->MenuPhrase("200", "MenuText"), "atividade_papellist.php?cmd=resetall", 199, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}atividade_papel'), FALSE);
$RootMenu->AddMenuItem(141, $Language->MenuPhrase("141", "MenuText"), "", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(270, $Language->MenuPhrase("270", "MenuText"), "solicitacaometricaslist.php", 141, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}solicitacaoMetricas'), FALSE);
$RootMenu->AddMenuItem(254, $Language->MenuPhrase("254", "MenuText"), "contagempflist.php?cmd=resetall", 141, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}contagempf'), FALSE);
$RootMenu->AddMenuItem(256, $Language->MenuPhrase("256", "MenuText"), "estimativalist.php?cmd=resetall", 141, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}estimativa'), FALSE);
$RootMenu->AddMenuItem(264, $Language->MenuPhrase("264", "MenuText"), "laudolist.php?cmd=resetall", 141, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}laudo'), FALSE);
$RootMenu->AddMenuItem(4066, $Language->MenuPhrase("4066", "MenuText"), "", 141, "", TRUE, FALSE);
$RootMenu->AddMenuItem(503, $Language->MenuPhrase("503", "MenuText"), "solicitae7f5es_de_me9tricasreport.php", 4066, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}Solicitações de Métricas'), FALSE);
$RootMenu->AddMenuItem(3008, $Language->MenuPhrase("3008", "MenuText"), "qtpfsollist.php", 4066, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}qtpfsol'), FALSE);
$RootMenu->AddMenuItem(4039, $Language->MenuPhrase("4039", "MenuText"), "vwgitd_stsolmetricaslist.php", 4066, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}vwgitd_stSolMetricas'), FALSE);
$RootMenu->AddMenuItem(3005, $Language->MenuPhrase("3005", "MenuText"), "prodambientelist.php", 4066, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}prodambiente'), FALSE);
$RootMenu->AddMenuItem(4040, $Language->MenuPhrase("4040", "MenuText"), "vwgitd_tamsistemalist.php", 4066, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}vwgitd_tamSistema'), FALSE);
$RootMenu->AddMenuItem(3717, $Language->MenuPhrase("3717", "MenuText"), "", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(1598, $Language->MenuPhrase("1598", "MenuText"), "itembaseconhecimentolist.php", 3717, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}itembaseconhecimento'), FALSE);
$RootMenu->AddMenuItem(1886, $Language->MenuPhrase("1886", "MenuText"), "glossariotilist.php", 3717, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}glossarioti'), FALSE);
$RootMenu->AddMenuItem(69, $Language->MenuPhrase("69", "MenuText"), "", 3717, "", TRUE, FALSE);
$RootMenu->AddMenuItem(3747, $Language->MenuPhrase("3747", "MenuText"), "vwrdmd_lancamentohoraslist.php", 69, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}vwrdmd_lancamentoHoras'), FALSE);
$RootMenu->AddMenuItem(4067, $Language->MenuPhrase("4067", "MenuText"), "vwrdmd_esttipotarlist.php", 69, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}vwrdmd_estTipoTar'), FALSE);
$RootMenu->AddMenuItem(4068, $Language->MenuPhrase("4068", "MenuText"), "vwrdmd_taragrtptarsitmmaalist.php", 69, "", AllowListMenu('{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}vwrdmd_tarAgrTpTarSitMMAA'), FALSE);
$RootMenu->AddMenuItem(-2, $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
	</div><!-- /content -->
</div><!-- /page -->
</body>
</html>
<?php

	 // Close connection
	$conn->Close();
?>
