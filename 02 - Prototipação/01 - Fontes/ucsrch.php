<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "ucinfo.php" ?>
<?php include_once "sistemainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$uc_search = NULL; // Initialize page object first

class cuc_search extends cuc {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'uc';

	// Page object name
	var $PageObjName = 'uc_search';

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

		// Table object (uc)
		if (!isset($GLOBALS["uc"])) {
			$GLOBALS["uc"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["uc"];
		}

		// Table object (sistema)
		if (!isset($GLOBALS['sistema'])) $GLOBALS['sistema'] = new csistema();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'uc', TRUE);

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
			$this->Page_Terminate("uclist.php");
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
						$this->Page_Terminate("uclist.php" . "?" . $sSrchStr); // Go to list page
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
		$this->BuildSearchUrl($sSrchUrl, $this->nu_sistema); // nu_sistema
		$this->BuildSearchUrl($sSrchUrl, $this->nu_modulo); // nu_modulo
		$this->BuildSearchUrl($sSrchUrl, $this->co_alternativo); // co_alternativo
		$this->BuildSearchUrl($sSrchUrl, $this->no_uc); // no_uc
		$this->BuildSearchUrl($sSrchUrl, $this->ds_uc); // ds_uc
		$this->BuildSearchUrl($sSrchUrl, $this->nu_stUc); // nu_stUc
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
		// nu_sistema

		$this->nu_sistema->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nu_sistema"));
		$this->nu_sistema->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nu_sistema");

		// nu_modulo
		$this->nu_modulo->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nu_modulo"));
		$this->nu_modulo->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nu_modulo");

		// co_alternativo
		$this->co_alternativo->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_co_alternativo"));
		$this->co_alternativo->AdvancedSearch->SearchOperator = $objForm->GetValue("z_co_alternativo");

		// no_uc
		$this->no_uc->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_no_uc"));
		$this->no_uc->AdvancedSearch->SearchOperator = $objForm->GetValue("z_no_uc");

		// ds_uc
		$this->ds_uc->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_ds_uc"));
		$this->ds_uc->AdvancedSearch->SearchOperator = $objForm->GetValue("z_ds_uc");

		// nu_stUc
		$this->nu_stUc->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nu_stUc"));
		$this->nu_stUc->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nu_stUc");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_uc
		// nu_sistema
		// nu_modulo
		// co_alternativo
		// no_uc
		// ds_uc
		// nu_stUc

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_sistema
			if (strval($this->nu_sistema->CurrentValue) <> "") {
				$sFilterWrk = "[nu_sistema]" . ew_SearchString("=", $this->nu_sistema->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_sistema], [co_alternativo] AS [DispFld], [no_sistema] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[sistema]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_sistema, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_sistema->ViewValue = $rswrk->fields('DispFld');
					$this->nu_sistema->ViewValue .= ew_ValueSeparator(1,$this->nu_sistema) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_sistema->ViewValue = $this->nu_sistema->CurrentValue;
				}
			} else {
				$this->nu_sistema->ViewValue = NULL;
			}
			$this->nu_sistema->ViewCustomAttributes = "";

			// nu_modulo
			if (strval($this->nu_modulo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_modulo]" . ew_SearchString("=", $this->nu_modulo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_modulo], [no_modulo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[modulo]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_modulo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_modulo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_modulo->ViewValue = $this->nu_modulo->CurrentValue;
				}
			} else {
				$this->nu_modulo->ViewValue = NULL;
			}
			$this->nu_modulo->ViewCustomAttributes = "";

			// co_alternativo
			$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
			$this->co_alternativo->ViewCustomAttributes = "";

			// no_uc
			$this->no_uc->ViewValue = $this->no_uc->CurrentValue;
			$this->no_uc->ViewCustomAttributes = "";

			// ds_uc
			$this->ds_uc->ViewValue = $this->ds_uc->CurrentValue;
			$this->ds_uc->ViewCustomAttributes = "";

			// nu_stUc
			if (strval($this->nu_stUc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stUc]" . ew_SearchString("=", $this->nu_stUc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stUc], [no_stUc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stuc]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stUc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_stUc->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stUc->ViewValue = $this->nu_stUc->CurrentValue;
				}
			} else {
				$this->nu_stUc->ViewValue = NULL;
			}
			$this->nu_stUc->ViewCustomAttributes = "";

			// nu_sistema
			$this->nu_sistema->LinkCustomAttributes = "";
			$this->nu_sistema->HrefValue = "";
			$this->nu_sistema->TooltipValue = "";

			// nu_modulo
			$this->nu_modulo->LinkCustomAttributes = "";
			$this->nu_modulo->HrefValue = "";
			$this->nu_modulo->TooltipValue = "";

			// co_alternativo
			$this->co_alternativo->LinkCustomAttributes = "";
			$this->co_alternativo->HrefValue = "";
			$this->co_alternativo->TooltipValue = "";

			// no_uc
			$this->no_uc->LinkCustomAttributes = "";
			$this->no_uc->HrefValue = "";
			$this->no_uc->TooltipValue = "";

			// ds_uc
			$this->ds_uc->LinkCustomAttributes = "";
			$this->ds_uc->HrefValue = "";
			$this->ds_uc->TooltipValue = "";

			// nu_stUc
			$this->nu_stUc->LinkCustomAttributes = "";
			$this->nu_stUc->HrefValue = "";
			$this->nu_stUc->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_sistema
			$this->nu_sistema->EditCustomAttributes = "";
			if (trim(strval($this->nu_sistema->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "[nu_sistema]" . ew_SearchString("=", $this->nu_sistema->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT [nu_sistema], [co_alternativo] AS [DispFld], [no_sistema] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[sistema]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_sistema, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_sistema->EditValue = $arwrk;

			// nu_modulo
			$this->nu_modulo->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_modulo], [no_modulo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_sistema] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[modulo]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_modulo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_modulo->EditValue = $arwrk;

			// co_alternativo
			$this->co_alternativo->EditCustomAttributes = "";
			$this->co_alternativo->EditValue = ew_HtmlEncode($this->co_alternativo->AdvancedSearch->SearchValue);
			$this->co_alternativo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->co_alternativo->FldCaption()));

			// no_uc
			$this->no_uc->EditCustomAttributes = "";
			$this->no_uc->EditValue = ew_HtmlEncode($this->no_uc->AdvancedSearch->SearchValue);
			$this->no_uc->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_uc->FldCaption()));

			// ds_uc
			$this->ds_uc->EditCustomAttributes = "";
			$this->ds_uc->EditValue = $this->ds_uc->AdvancedSearch->SearchValue;
			$this->ds_uc->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_uc->FldCaption()));

			// nu_stUc
			$this->nu_stUc->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_stUc], [no_stUc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[stuc]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stUc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_stUc->EditValue = $arwrk;
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
		$this->nu_sistema->AdvancedSearch->Load();
		$this->nu_modulo->AdvancedSearch->Load();
		$this->co_alternativo->AdvancedSearch->Load();
		$this->no_uc->AdvancedSearch->Load();
		$this->ds_uc->AdvancedSearch->Load();
		$this->nu_stUc->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "uclist.php", $this->TableVar);
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
if (!isset($uc_search)) $uc_search = new cuc_search();

// Page init
$uc_search->Page_Init();

// Page main
$uc_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$uc_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var uc_search = new ew_Page("uc_search");
uc_search.PageID = "search"; // Page ID
var EW_PAGE_ID = uc_search.PageID; // For backward compatibility

// Form object
var fucsearch = new ew_Form("fucsearch");

// Form_CustomValidate event
fucsearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fucsearch.ValidateRequired = true;
<?php } else { ?>
fucsearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fucsearch.Lists["x_nu_sistema"] = {"LinkField":"x_nu_sistema","Ajax":true,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_sistema","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fucsearch.Lists["x_nu_modulo"] = {"LinkField":"x_nu_modulo","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_modulo","","",""],"ParentFields":["x_nu_sistema"],"FilterFields":["x_nu_sistema"],"Options":[]};
fucsearch.Lists["x_nu_stUc"] = {"LinkField":"x_nu_stUc","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stUc","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
// Validate function for search

fucsearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fucsearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fucsearch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fucsearch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fucsearch.Lists["x_nu_stUc"] = {"LinkField":"x_nu_stUc","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stUc","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $uc_search->ShowPageHeader(); ?>
<?php
$uc_search->ShowMessage();
?>
<form name="fucsearch" id="fucsearch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="uc">
<input type="hidden" name="a_search" id="a_search" value="S">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_ucsearch" class="table table-bordered table-striped">
<?php if ($uc->nu_sistema->Visible) { // nu_sistema ?>
	<tr id="r_nu_sistema">
		<td><span id="elh_uc_nu_sistema"><?php echo $uc->nu_sistema->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_sistema" id="z_nu_sistema" value="="></span></td>
		<td<?php echo $uc->nu_sistema->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_uc_nu_sistema" class="control-group">
<?php $uc->nu_sistema->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_modulo']); " . @$uc->nu_sistema->EditAttrs["onchange"]; ?>
<select data-field="x_nu_sistema" id="x_nu_sistema" name="x_nu_sistema"<?php echo $uc->nu_sistema->EditAttributes() ?>>
<?php
if (is_array($uc->nu_sistema->EditValue)) {
	$arwrk = $uc->nu_sistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($uc->nu_sistema->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$uc->nu_sistema) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT [nu_sistema], [co_alternativo] AS [DispFld], [no_sistema] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[sistema]";
$sWhereWrk = "";

// Call Lookup selecting
$uc->Lookup_Selecting($uc->nu_sistema, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_nu_sistema" id="s_x_nu_sistema" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("[nu_sistema] = {filter_value}"); ?>&t0=3">
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($uc->nu_modulo->Visible) { // nu_modulo ?>
	<tr id="r_nu_modulo">
		<td><span id="elh_uc_nu_modulo"><?php echo $uc->nu_modulo->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_modulo" id="z_nu_modulo" value="="></span></td>
		<td<?php echo $uc->nu_modulo->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_uc_nu_modulo" class="control-group">
<select data-field="x_nu_modulo" id="x_nu_modulo" name="x_nu_modulo"<?php echo $uc->nu_modulo->EditAttributes() ?>>
<?php
if (is_array($uc->nu_modulo->EditValue)) {
	$arwrk = $uc->nu_modulo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($uc->nu_modulo->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fucsearch.Lists["x_nu_modulo"].Options = <?php echo (is_array($uc->nu_modulo->EditValue)) ? ew_ArrayToJson($uc->nu_modulo->EditValue, 1) : "[]" ?>;
</script>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($uc->co_alternativo->Visible) { // co_alternativo ?>
	<tr id="r_co_alternativo">
		<td><span id="elh_uc_co_alternativo"><?php echo $uc->co_alternativo->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_co_alternativo" id="z_co_alternativo" value="LIKE"></span></td>
		<td<?php echo $uc->co_alternativo->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_uc_co_alternativo" class="control-group">
<input type="text" data-field="x_co_alternativo" name="x_co_alternativo" id="x_co_alternativo" size="30" maxlength="20" placeholder="<?php echo $uc->co_alternativo->PlaceHolder ?>" value="<?php echo $uc->co_alternativo->EditValue ?>"<?php echo $uc->co_alternativo->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($uc->no_uc->Visible) { // no_uc ?>
	<tr id="r_no_uc">
		<td><span id="elh_uc_no_uc"><?php echo $uc->no_uc->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_uc" id="z_no_uc" value="LIKE"></span></td>
		<td<?php echo $uc->no_uc->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_uc_no_uc" class="control-group">
<input type="text" data-field="x_no_uc" name="x_no_uc" id="x_no_uc" size="30" maxlength="120" placeholder="<?php echo $uc->no_uc->PlaceHolder ?>" value="<?php echo $uc->no_uc->EditValue ?>"<?php echo $uc->no_uc->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($uc->ds_uc->Visible) { // ds_uc ?>
	<tr id="r_ds_uc">
		<td><span id="elh_uc_ds_uc"><?php echo $uc->ds_uc->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ds_uc" id="z_ds_uc" value="LIKE"></span></td>
		<td<?php echo $uc->ds_uc->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_uc_ds_uc" class="control-group">
<input type="text" data-field="x_ds_uc" name="x_ds_uc" id="x_ds_uc" placeholder="<?php echo $uc->ds_uc->PlaceHolder ?>" value="<?php echo $uc->ds_uc->EditValue ?>"<?php echo $uc->ds_uc->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($uc->nu_stUc->Visible) { // nu_stUc ?>
	<tr id="r_nu_stUc">
		<td><span id="elh_uc_nu_stUc"><?php echo $uc->nu_stUc->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_stUc" id="z_nu_stUc" value="="></span></td>
		<td<?php echo $uc->nu_stUc->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_uc_nu_stUc" class="control-group">
<select data-field="x_nu_stUc" id="x_nu_stUc" name="x_nu_stUc"<?php echo $uc->nu_stUc->EditAttributes() ?>>
<?php
if (is_array($uc->nu_stUc->EditValue)) {
	$arwrk = $uc->nu_stUc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($uc->nu_stUc->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fucsearch.Lists["x_nu_stUc"].Options = <?php echo (is_array($uc->nu_stUc->EditValue)) ? ew_ArrayToJson($uc->nu_stUc->EditValue, 1) : "[]" ?>;
</script>
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
fucsearch.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$uc_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$uc_search->Page_Terminate();
?>
