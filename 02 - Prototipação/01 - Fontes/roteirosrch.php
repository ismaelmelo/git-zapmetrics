<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "roteiroinfo.php" ?>
<?php include_once "metodologiainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$roteiro_search = NULL; // Initialize page object first

class croteiro_search extends croteiro {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'roteiro';

	// Page object name
	var $PageObjName = 'roteiro_search';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-error ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<table class=\"ewStdTable\"><tr><td><div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div></td></tr></table>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language, $UserAgent;

		// User agent
		$UserAgent = ew_UserAgent();
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (roteiro)
		if (!isset($GLOBALS["roteiro"])) {
			$GLOBALS["roteiro"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["roteiro"];
		}

		// Table object (metodologia)
		if (!isset($GLOBALS['metodologia'])) $GLOBALS['metodologia'] = new cmetodologia();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'roteiro', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();
		$UserProfile->LoadProfile(@$_SESSION[EW_SESSION_USER_PROFILE]);

		// Security
		$Security = new cAdvancedSecurity();
		if (IsPasswordExpired())
			$this->Page_Terminate("changepwd.php");
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		if (!$Security->CanSearch()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("roteirolist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$this->Page_Terminate("roteirolist.php" . "?" . $sSrchStr); // Go to list page
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->nu_metodologia); // nu_metodologia
		$this->BuildSearchUrl($sSrchUrl, $this->no_roteiro); // no_roteiro
		$this->BuildSearchUrl($sSrchUrl, $this->ds_roteiro); // ds_roteiro
		$this->BuildSearchUrl($sSrchUrl, $this->ic_ativo); // ic_ativo
		$this->BuildSearchUrl($sSrchUrl, $this->nu_ordem); // nu_ordem
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// nu_metodologia

		$this->nu_metodologia->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nu_metodologia"));
		$this->nu_metodologia->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nu_metodologia");

		// no_roteiro
		$this->no_roteiro->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_no_roteiro"));
		$this->no_roteiro->AdvancedSearch->SearchOperator = $objForm->GetValue("z_no_roteiro");

		// ds_roteiro
		$this->ds_roteiro->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_ds_roteiro"));
		$this->ds_roteiro->AdvancedSearch->SearchOperator = $objForm->GetValue("z_ds_roteiro");

		// ic_ativo
		$this->ic_ativo->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_ic_ativo"));
		$this->ic_ativo->AdvancedSearch->SearchOperator = $objForm->GetValue("z_ic_ativo");

		// nu_ordem
		$this->nu_ordem->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nu_ordem"));
		$this->nu_ordem->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nu_ordem");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_roteiro
		// nu_metodologia
		// no_roteiro
		// ds_roteiro
		// ic_ativo
		// nu_ordem

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_metodologia
			if (strval($this->nu_metodologia->CurrentValue) <> "") {
				$sFilterWrk = "[nu_metodologia]" . ew_SearchString("=", $this->nu_metodologia->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_metodologia], [no_metodologia] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[metodologia]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_metodologia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_metodologia->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_metodologia->ViewValue = $this->nu_metodologia->CurrentValue;
				}
			} else {
				$this->nu_metodologia->ViewValue = NULL;
			}
			$this->nu_metodologia->ViewCustomAttributes = "";

			// no_roteiro
			$this->no_roteiro->ViewValue = $this->no_roteiro->CurrentValue;
			$this->no_roteiro->ViewCustomAttributes = "";

			// ds_roteiro
			$this->ds_roteiro->ViewValue = $this->ds_roteiro->CurrentValue;
			$this->ds_roteiro->ViewCustomAttributes = "";

			// ic_ativo
			if (strval($this->ic_ativo->CurrentValue) <> "") {
				switch ($this->ic_ativo->CurrentValue) {
					case $this->ic_ativo->FldTagValue(1):
						$this->ic_ativo->ViewValue = $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->CurrentValue;
						break;
					case $this->ic_ativo->FldTagValue(2):
						$this->ic_ativo->ViewValue = $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->CurrentValue;
						break;
					default:
						$this->ic_ativo->ViewValue = $this->ic_ativo->CurrentValue;
				}
			} else {
				$this->ic_ativo->ViewValue = NULL;
			}
			$this->ic_ativo->ViewCustomAttributes = "";

			// nu_ordem
			$this->nu_ordem->ViewValue = $this->nu_ordem->CurrentValue;
			$this->nu_ordem->ViewCustomAttributes = "";

			// nu_metodologia
			$this->nu_metodologia->LinkCustomAttributes = "";
			$this->nu_metodologia->HrefValue = "";
			$this->nu_metodologia->TooltipValue = "";

			// no_roteiro
			$this->no_roteiro->LinkCustomAttributes = "";
			$this->no_roteiro->HrefValue = "";
			$this->no_roteiro->TooltipValue = "";

			// ds_roteiro
			$this->ds_roteiro->LinkCustomAttributes = "";
			$this->ds_roteiro->HrefValue = "";
			$this->ds_roteiro->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";

			// nu_ordem
			$this->nu_ordem->LinkCustomAttributes = "";
			$this->nu_ordem->HrefValue = "";
			$this->nu_ordem->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_metodologia
			$this->nu_metodologia->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_metodologia], [no_metodologia] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[metodologia]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_metodologia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_metodologia->EditValue = $arwrk;

			// no_roteiro
			$this->no_roteiro->EditCustomAttributes = "";
			$this->no_roteiro->EditValue = ew_HtmlEncode($this->no_roteiro->AdvancedSearch->SearchValue);
			$this->no_roteiro->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_roteiro->FldCaption()));

			// ds_roteiro
			$this->ds_roteiro->EditCustomAttributes = "";
			$this->ds_roteiro->EditValue = $this->ds_roteiro->AdvancedSearch->SearchValue;
			$this->ds_roteiro->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_roteiro->FldCaption()));

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// nu_ordem
			$this->nu_ordem->EditCustomAttributes = "";
			$this->nu_ordem->EditValue = ew_HtmlEncode($this->nu_ordem->AdvancedSearch->SearchValue);
			$this->nu_ordem->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_ordem->FldCaption()));
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckInteger($this->nu_ordem->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->nu_ordem->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->nu_metodologia->AdvancedSearch->Load();
		$this->no_roteiro->AdvancedSearch->Load();
		$this->ds_roteiro->AdvancedSearch->Load();
		$this->ic_ativo->AdvancedSearch->Load();
		$this->nu_ordem->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "roteirolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("search");
		$Breadcrumb->Add("search", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($roteiro_search)) $roteiro_search = new croteiro_search();

// Page init
$roteiro_search->Page_Init();

// Page main
$roteiro_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$roteiro_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var roteiro_search = new ew_Page("roteiro_search");
roteiro_search.PageID = "search"; // Page ID
var EW_PAGE_ID = roteiro_search.PageID; // For backward compatibility

// Form object
var froteirosearch = new ew_Form("froteirosearch");

// Form_CustomValidate event
froteirosearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
froteirosearch.ValidateRequired = true;
<?php } else { ?>
froteirosearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
froteirosearch.Lists["x_nu_metodologia"] = {"LinkField":"x_nu_metodologia","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_metodologia","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
// Validate function for search

froteirosearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_nu_ordem");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($roteiro->nu_ordem->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
froteirosearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
froteirosearch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
froteirosearch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $roteiro_search->ShowPageHeader(); ?>
<?php
$roteiro_search->ShowMessage();
?>
<form name="froteirosearch" id="froteirosearch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="roteiro">
<input type="hidden" name="a_search" id="a_search" value="S">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_roteirosearch" class="table table-bordered table-striped">
<?php if ($roteiro->nu_metodologia->Visible) { // nu_metodologia ?>
	<tr id="r_nu_metodologia">
		<td><span id="elh_roteiro_nu_metodologia"><?php echo $roteiro->nu_metodologia->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_metodologia" id="z_nu_metodologia" value="="></span></td>
		<td<?php echo $roteiro->nu_metodologia->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_roteiro_nu_metodologia" class="control-group">
<select data-field="x_nu_metodologia" id="x_nu_metodologia" name="x_nu_metodologia"<?php echo $roteiro->nu_metodologia->EditAttributes() ?>>
<?php
if (is_array($roteiro->nu_metodologia->EditValue)) {
	$arwrk = $roteiro->nu_metodologia->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($roteiro->nu_metodologia->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
froteirosearch.Lists["x_nu_metodologia"].Options = <?php echo (is_array($roteiro->nu_metodologia->EditValue)) ? ew_ArrayToJson($roteiro->nu_metodologia->EditValue, 1) : "[]" ?>;
</script>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($roteiro->no_roteiro->Visible) { // no_roteiro ?>
	<tr id="r_no_roteiro">
		<td><span id="elh_roteiro_no_roteiro"><?php echo $roteiro->no_roteiro->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_roteiro" id="z_no_roteiro" value="LIKE"></span></td>
		<td<?php echo $roteiro->no_roteiro->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_roteiro_no_roteiro" class="control-group">
<input type="text" data-field="x_no_roteiro" name="x_no_roteiro" id="x_no_roteiro" size="30" maxlength="75" placeholder="<?php echo $roteiro->no_roteiro->PlaceHolder ?>" value="<?php echo $roteiro->no_roteiro->EditValue ?>"<?php echo $roteiro->no_roteiro->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($roteiro->ds_roteiro->Visible) { // ds_roteiro ?>
	<tr id="r_ds_roteiro">
		<td><span id="elh_roteiro_ds_roteiro"><?php echo $roteiro->ds_roteiro->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ds_roteiro" id="z_ds_roteiro" value="LIKE"></span></td>
		<td<?php echo $roteiro->ds_roteiro->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_roteiro_ds_roteiro" class="control-group">
<input type="text" data-field="x_ds_roteiro" name="x_ds_roteiro" id="x_ds_roteiro" placeholder="<?php echo $roteiro->ds_roteiro->PlaceHolder ?>" value="<?php echo $roteiro->ds_roteiro->EditValue ?>"<?php echo $roteiro->ds_roteiro->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($roteiro->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_roteiro_ic_ativo"><?php echo $roteiro->ic_ativo->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_ativo" id="z_ic_ativo" value="LIKE"></span></td>
		<td<?php echo $roteiro->ic_ativo->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_roteiro_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $roteiro->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $roteiro->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($roteiro->ic_ativo->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $roteiro->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($roteiro->nu_ordem->Visible) { // nu_ordem ?>
	<tr id="r_nu_ordem">
		<td><span id="elh_roteiro_nu_ordem"><?php echo $roteiro->nu_ordem->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_ordem" id="z_nu_ordem" value="="></span></td>
		<td<?php echo $roteiro->nu_ordem->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_roteiro_nu_ordem" class="control-group">
<input type="text" data-field="x_nu_ordem" name="x_nu_ordem" id="x_nu_ordem" size="30" placeholder="<?php echo $roteiro->nu_ordem->PlaceHolder ?>" value="<?php echo $roteiro->nu_ordem->EditValue ?>"<?php echo $roteiro->nu_ordem->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
</form>
<script type="text/javascript">
froteirosearch.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$roteiro_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$roteiro_search->Page_Terminate();
?>
