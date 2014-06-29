<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "pessoainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$pessoa_addopt = NULL; // Initialize page object first

class cpessoa_addopt extends cpessoa {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'pessoa';

	// Page object name
	var $PageObjName = 'pessoa_addopt';

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

		// Table object (pessoa)
		if (!isset($GLOBALS["pessoa"])) {
			$GLOBALS["pessoa"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pessoa"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pessoa', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("pessoalist.php");
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
		global $objForm, $Language, $gsFormError;
		set_error_handler("ew_ErrorHandler");

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if ($objForm->GetValue("a_addopt") <> "") {
			$this->CurrentAction = $objForm->GetValue("a_addopt"); // Get form action
			$this->LoadFormValues(); // Load form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else { // Not post back
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow()) { // Add successful
					$row = array();
					$row["x_nu_pessoa"] = $this->nu_pessoa->DbValue;
					$row["x_no_pessoa"] = $this->no_pessoa->DbValue;
					$row["x_ic_tpEnvolvimento"] = $this->ic_tpEnvolvimento->DbValue;
					$row["x_nu_cargo"] = $this->nu_cargo->DbValue;
					$row["x_nu_areaLotacao"] = $this->nu_areaLotacao->DbValue;
					$row["x_no_email"] = $this->no_email->DbValue;
					$row["x_ds_telefone1"] = $this->ds_telefone1->DbValue;
					$row["x_ds_telefone2"] = $this->ds_telefone2->DbValue;
					$row["x_ic_ativo"] = $this->ic_ativo->DbValue;
					if (!EW_DEBUG_ENABLED && ob_get_length())
						ob_end_clean();
					echo ew_ArrayToJson(array($row));
				} else {
					$this->ShowMessage();
				}
				$this->Page_Terminate();
				exit();
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add type
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->no_pessoa->CurrentValue = NULL;
		$this->no_pessoa->OldValue = $this->no_pessoa->CurrentValue;
		$this->ic_tpEnvolvimento->CurrentValue = NULL;
		$this->ic_tpEnvolvimento->OldValue = $this->ic_tpEnvolvimento->CurrentValue;
		$this->nu_cargo->CurrentValue = NULL;
		$this->nu_cargo->OldValue = $this->nu_cargo->CurrentValue;
		$this->nu_areaLotacao->CurrentValue = NULL;
		$this->nu_areaLotacao->OldValue = $this->nu_areaLotacao->CurrentValue;
		$this->no_email->CurrentValue = NULL;
		$this->no_email->OldValue = $this->no_email->CurrentValue;
		$this->ds_telefone1->CurrentValue = NULL;
		$this->ds_telefone1->OldValue = $this->ds_telefone1->CurrentValue;
		$this->ds_telefone2->CurrentValue = NULL;
		$this->ds_telefone2->OldValue = $this->ds_telefone2->CurrentValue;
		$this->ic_ativo->CurrentValue = NULL;
		$this->ic_ativo->OldValue = $this->ic_ativo->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->no_pessoa->FldIsDetailKey) {
			$this->no_pessoa->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_no_pessoa")));
		}
		if (!$this->ic_tpEnvolvimento->FldIsDetailKey) {
			$this->ic_tpEnvolvimento->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ic_tpEnvolvimento")));
		}
		if (!$this->nu_cargo->FldIsDetailKey) {
			$this->nu_cargo->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_cargo")));
		}
		if (!$this->nu_areaLotacao->FldIsDetailKey) {
			$this->nu_areaLotacao->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_areaLotacao")));
		}
		if (!$this->no_email->FldIsDetailKey) {
			$this->no_email->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_no_email")));
		}
		if (!$this->ds_telefone1->FldIsDetailKey) {
			$this->ds_telefone1->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ds_telefone1")));
		}
		if (!$this->ds_telefone2->FldIsDetailKey) {
			$this->ds_telefone2->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ds_telefone2")));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ic_ativo")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->no_pessoa->CurrentValue = ew_ConvertToUtf8($this->no_pessoa->FormValue);
		$this->ic_tpEnvolvimento->CurrentValue = ew_ConvertToUtf8($this->ic_tpEnvolvimento->FormValue);
		$this->nu_cargo->CurrentValue = ew_ConvertToUtf8($this->nu_cargo->FormValue);
		$this->nu_areaLotacao->CurrentValue = ew_ConvertToUtf8($this->nu_areaLotacao->FormValue);
		$this->no_email->CurrentValue = ew_ConvertToUtf8($this->no_email->FormValue);
		$this->ds_telefone1->CurrentValue = ew_ConvertToUtf8($this->ds_telefone1->FormValue);
		$this->ds_telefone2->CurrentValue = ew_ConvertToUtf8($this->ds_telefone2->FormValue);
		$this->ic_ativo->CurrentValue = ew_ConvertToUtf8($this->ic_ativo->FormValue);
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->nu_pessoa->setDbValue($rs->fields('nu_pessoa'));
		$this->no_pessoa->setDbValue($rs->fields('no_pessoa'));
		$this->ic_tpEnvolvimento->setDbValue($rs->fields('ic_tpEnvolvimento'));
		$this->nu_cargo->setDbValue($rs->fields('nu_cargo'));
		$this->nu_areaLotacao->setDbValue($rs->fields('nu_areaLotacao'));
		$this->no_email->setDbValue($rs->fields('no_email'));
		$this->ds_telefone1->setDbValue($rs->fields('ds_telefone1'));
		$this->ds_telefone2->setDbValue($rs->fields('ds_telefone2'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_pessoa->DbValue = $row['nu_pessoa'];
		$this->no_pessoa->DbValue = $row['no_pessoa'];
		$this->ic_tpEnvolvimento->DbValue = $row['ic_tpEnvolvimento'];
		$this->nu_cargo->DbValue = $row['nu_cargo'];
		$this->nu_areaLotacao->DbValue = $row['nu_areaLotacao'];
		$this->no_email->DbValue = $row['no_email'];
		$this->ds_telefone1->DbValue = $row['ds_telefone1'];
		$this->ds_telefone2->DbValue = $row['ds_telefone2'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_pessoa
		// no_pessoa
		// ic_tpEnvolvimento
		// nu_cargo
		// nu_areaLotacao
		// no_email
		// ds_telefone1
		// ds_telefone2
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_pessoa
			$this->nu_pessoa->ViewValue = $this->nu_pessoa->CurrentValue;
			$this->nu_pessoa->ViewCustomAttributes = "";

			// no_pessoa
			$this->no_pessoa->ViewValue = $this->no_pessoa->CurrentValue;
			$this->no_pessoa->ViewCustomAttributes = "";

			// ic_tpEnvolvimento
			if (strval($this->ic_tpEnvolvimento->CurrentValue) <> "") {
				switch ($this->ic_tpEnvolvimento->CurrentValue) {
					case $this->ic_tpEnvolvimento->FldTagValue(1):
						$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->FldTagCaption(1) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(1) : $this->ic_tpEnvolvimento->CurrentValue;
						break;
					case $this->ic_tpEnvolvimento->FldTagValue(2):
						$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->FldTagCaption(2) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(2) : $this->ic_tpEnvolvimento->CurrentValue;
						break;
					case $this->ic_tpEnvolvimento->FldTagValue(3):
						$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->FldTagCaption(3) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(3) : $this->ic_tpEnvolvimento->CurrentValue;
						break;
					case $this->ic_tpEnvolvimento->FldTagValue(4):
						$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->FldTagCaption(4) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(4) : $this->ic_tpEnvolvimento->CurrentValue;
						break;
					default:
						$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->CurrentValue;
				}
			} else {
				$this->ic_tpEnvolvimento->ViewValue = NULL;
			}
			$this->ic_tpEnvolvimento->ViewCustomAttributes = "";

			// nu_cargo
			if (strval($this->nu_cargo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_cargo]" . ew_SearchString("=", $this->nu_cargo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_cargo], [no_cargo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[cargo]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_cargo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_cargo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_cargo->ViewValue = $this->nu_cargo->CurrentValue;
				}
			} else {
				$this->nu_cargo->ViewValue = NULL;
			}
			$this->nu_cargo->ViewCustomAttributes = "";

			// nu_areaLotacao
			if (strval($this->nu_areaLotacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_areaLotacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_areaLotacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_areaLotacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_areaLotacao->ViewValue = $this->nu_areaLotacao->CurrentValue;
				}
			} else {
				$this->nu_areaLotacao->ViewValue = NULL;
			}
			$this->nu_areaLotacao->ViewCustomAttributes = "";

			// no_email
			$this->no_email->ViewValue = $this->no_email->CurrentValue;
			$this->no_email->ViewCustomAttributes = "";

			// ds_telefone1
			$this->ds_telefone1->ViewValue = $this->ds_telefone1->CurrentValue;
			$this->ds_telefone1->ViewCustomAttributes = "";

			// ds_telefone2
			$this->ds_telefone2->ViewValue = $this->ds_telefone2->CurrentValue;
			$this->ds_telefone2->ViewCustomAttributes = "";

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

			// no_pessoa
			$this->no_pessoa->LinkCustomAttributes = "";
			$this->no_pessoa->HrefValue = "";
			$this->no_pessoa->TooltipValue = "";

			// ic_tpEnvolvimento
			$this->ic_tpEnvolvimento->LinkCustomAttributes = "";
			$this->ic_tpEnvolvimento->HrefValue = "";
			$this->ic_tpEnvolvimento->TooltipValue = "";

			// nu_cargo
			$this->nu_cargo->LinkCustomAttributes = "";
			$this->nu_cargo->HrefValue = "";
			$this->nu_cargo->TooltipValue = "";

			// nu_areaLotacao
			$this->nu_areaLotacao->LinkCustomAttributes = "";
			$this->nu_areaLotacao->HrefValue = "";
			$this->nu_areaLotacao->TooltipValue = "";

			// no_email
			$this->no_email->LinkCustomAttributes = "";
			$this->no_email->HrefValue = "";
			$this->no_email->TooltipValue = "";

			// ds_telefone1
			$this->ds_telefone1->LinkCustomAttributes = "";
			$this->ds_telefone1->HrefValue = "";
			$this->ds_telefone1->TooltipValue = "";

			// ds_telefone2
			$this->ds_telefone2->LinkCustomAttributes = "";
			$this->ds_telefone2->HrefValue = "";
			$this->ds_telefone2->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// no_pessoa
			$this->no_pessoa->EditCustomAttributes = "";
			$this->no_pessoa->EditValue = ew_HtmlEncode($this->no_pessoa->CurrentValue);
			$this->no_pessoa->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_pessoa->FldCaption()));

			// ic_tpEnvolvimento
			$this->ic_tpEnvolvimento->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpEnvolvimento->FldTagValue(1), $this->ic_tpEnvolvimento->FldTagCaption(1) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(1) : $this->ic_tpEnvolvimento->FldTagValue(1));
			$arwrk[] = array($this->ic_tpEnvolvimento->FldTagValue(2), $this->ic_tpEnvolvimento->FldTagCaption(2) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(2) : $this->ic_tpEnvolvimento->FldTagValue(2));
			$arwrk[] = array($this->ic_tpEnvolvimento->FldTagValue(3), $this->ic_tpEnvolvimento->FldTagCaption(3) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(3) : $this->ic_tpEnvolvimento->FldTagValue(3));
			$arwrk[] = array($this->ic_tpEnvolvimento->FldTagValue(4), $this->ic_tpEnvolvimento->FldTagCaption(4) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(4) : $this->ic_tpEnvolvimento->FldTagValue(4));
			$this->ic_tpEnvolvimento->EditValue = $arwrk;

			// nu_cargo
			$this->nu_cargo->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_cargo], [no_cargo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[cargo]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_cargo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_cargo->EditValue = $arwrk;

			// nu_areaLotacao
			$this->nu_areaLotacao->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[area]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_areaLotacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_areaLotacao->EditValue = $arwrk;

			// no_email
			$this->no_email->EditCustomAttributes = "";
			$this->no_email->EditValue = ew_HtmlEncode($this->no_email->CurrentValue);
			$this->no_email->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_email->FldCaption()));

			// ds_telefone1
			$this->ds_telefone1->EditCustomAttributes = "";
			$this->ds_telefone1->EditValue = ew_HtmlEncode($this->ds_telefone1->CurrentValue);
			$this->ds_telefone1->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_telefone1->FldCaption()));

			// ds_telefone2
			$this->ds_telefone2->EditCustomAttributes = "";
			$this->ds_telefone2->EditValue = ew_HtmlEncode($this->ds_telefone2->CurrentValue);
			$this->ds_telefone2->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_telefone2->FldCaption()));

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// Edit refer script
			// no_pessoa

			$this->no_pessoa->HrefValue = "";

			// ic_tpEnvolvimento
			$this->ic_tpEnvolvimento->HrefValue = "";

			// nu_cargo
			$this->nu_cargo->HrefValue = "";

			// nu_areaLotacao
			$this->nu_areaLotacao->HrefValue = "";

			// no_email
			$this->no_email->HrefValue = "";

			// ds_telefone1
			$this->ds_telefone1->HrefValue = "";

			// ds_telefone2
			$this->ds_telefone2->HrefValue = "";

			// ic_ativo
			$this->ic_ativo->HrefValue = "";
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

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->no_pessoa->FldIsDetailKey && !is_null($this->no_pessoa->FormValue) && $this->no_pessoa->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_pessoa->FldCaption());
		}
		if ($this->ic_tpEnvolvimento->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_tpEnvolvimento->FldCaption());
		}
		if (!ew_CheckEmail($this->no_email->FormValue)) {
			ew_AddMessage($gsFormError, $this->no_email->FldErrMsg());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// no_pessoa
		$this->no_pessoa->SetDbValueDef($rsnew, $this->no_pessoa->CurrentValue, NULL, FALSE);

		// ic_tpEnvolvimento
		$this->ic_tpEnvolvimento->SetDbValueDef($rsnew, $this->ic_tpEnvolvimento->CurrentValue, NULL, FALSE);

		// nu_cargo
		$this->nu_cargo->SetDbValueDef($rsnew, $this->nu_cargo->CurrentValue, NULL, FALSE);

		// nu_areaLotacao
		$this->nu_areaLotacao->SetDbValueDef($rsnew, $this->nu_areaLotacao->CurrentValue, NULL, FALSE);

		// no_email
		$this->no_email->SetDbValueDef($rsnew, $this->no_email->CurrentValue, NULL, FALSE);

		// ds_telefone1
		$this->ds_telefone1->SetDbValueDef($rsnew, $this->ds_telefone1->CurrentValue, NULL, FALSE);

		// ds_telefone2
		$this->ds_telefone2->SetDbValueDef($rsnew, $this->ds_telefone2->CurrentValue, NULL, FALSE);

		// ic_ativo
		$this->ic_ativo->SetDbValueDef($rsnew, $this->ic_ativo->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->nu_pessoa->setDbValue($conn->Insert_ID());
			$rsnew['nu_pessoa'] = $this->nu_pessoa->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "pessoalist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("addopt");
		$Breadcrumb->Add("addopt", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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

	// Custom validate event
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
if (!isset($pessoa_addopt)) $pessoa_addopt = new cpessoa_addopt();

// Page init
$pessoa_addopt->Page_Init();

// Page main
$pessoa_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pessoa_addopt->Page_Render();
?>
<script type="text/javascript">

// Page object
var pessoa_addopt = new ew_Page("pessoa_addopt");
pessoa_addopt.PageID = "addopt"; // Page ID
var EW_PAGE_ID = pessoa_addopt.PageID; // For backward compatibility

// Form object
var fpessoaaddopt = new ew_Form("fpessoaaddopt");

// Validate form
fpessoaaddopt.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_no_pessoa");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pessoa->no_pessoa->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_tpEnvolvimento");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pessoa->ic_tpEnvolvimento->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_email");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pessoa->no_email->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pessoa->ic_ativo->FldCaption()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fpessoaaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpessoaaddopt.ValidateRequired = true;
<?php } else { ?>
fpessoaaddopt.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpessoaaddopt.Lists["x_nu_cargo"] = {"LinkField":"x_nu_cargo","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_cargo","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpessoaaddopt.Lists["x_nu_areaLotacao"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$pessoa_addopt->ShowMessage();
?>
<form name="fpessoaaddopt" id="fpessoaaddopt" class="ewForm form-horizontal" action="pessoaaddopt.php" method="post">
<input type="hidden" name="t" value="pessoa">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<div id="tbl_pessoaaddopt">
	<div class="control-group">
		<label class="control-label" for="x_no_pessoa"><?php echo $pessoa->no_pessoa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<input type="text" data-field="x_no_pessoa" name="x_no_pessoa" id="x_no_pessoa" size="30" maxlength="100" placeholder="<?php echo $pessoa->no_pessoa->PlaceHolder ?>" value="<?php echo $pessoa->no_pessoa->EditValue ?>"<?php echo $pessoa->no_pessoa->EditAttributes() ?>>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_ic_tpEnvolvimento"><?php echo $pessoa->ic_tpEnvolvimento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<div id="tp_x_ic_tpEnvolvimento" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_tpEnvolvimento" id="x_ic_tpEnvolvimento" value="{value}"<?php echo $pessoa->ic_tpEnvolvimento->EditAttributes() ?>></div>
<div id="dsl_x_ic_tpEnvolvimento" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $pessoa->ic_tpEnvolvimento->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pessoa->ic_tpEnvolvimento->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_tpEnvolvimento" name="x_ic_tpEnvolvimento" id="x_ic_tpEnvolvimento_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $pessoa->ic_tpEnvolvimento->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_cargo"><?php echo $pessoa->nu_cargo->FldCaption() ?></label>
		<div class="controls">
<select data-field="x_nu_cargo" id="x_nu_cargo" name="x_nu_cargo"<?php echo $pessoa->nu_cargo->EditAttributes() ?>>
<?php
if (is_array($pessoa->nu_cargo->EditValue)) {
	$arwrk = $pessoa->nu_cargo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pessoa->nu_cargo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fpessoaaddopt.Lists["x_nu_cargo"].Options = <?php echo (is_array($pessoa->nu_cargo->EditValue)) ? ew_ArrayToJson($pessoa->nu_cargo->EditValue, 1) : "[]" ?>;
</script>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_areaLotacao"><?php echo $pessoa->nu_areaLotacao->FldCaption() ?></label>
		<div class="controls">
<select data-field="x_nu_areaLotacao" id="x_nu_areaLotacao" name="x_nu_areaLotacao"<?php echo $pessoa->nu_areaLotacao->EditAttributes() ?>>
<?php
if (is_array($pessoa->nu_areaLotacao->EditValue)) {
	$arwrk = $pessoa->nu_areaLotacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pessoa->nu_areaLotacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fpessoaaddopt.Lists["x_nu_areaLotacao"].Options = <?php echo (is_array($pessoa->nu_areaLotacao->EditValue)) ? ew_ArrayToJson($pessoa->nu_areaLotacao->EditValue, 1) : "[]" ?>;
</script>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_no_email"><?php echo $pessoa->no_email->FldCaption() ?></label>
		<div class="controls">
<input type="text" data-field="x_no_email" name="x_no_email" id="x_no_email" size="30" maxlength="150" placeholder="<?php echo $pessoa->no_email->PlaceHolder ?>" value="<?php echo $pessoa->no_email->EditValue ?>"<?php echo $pessoa->no_email->EditAttributes() ?>>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_ds_telefone1"><?php echo $pessoa->ds_telefone1->FldCaption() ?></label>
		<div class="controls">
<input type="text" data-field="x_ds_telefone1" name="x_ds_telefone1" id="x_ds_telefone1" size="30" maxlength="50" placeholder="<?php echo $pessoa->ds_telefone1->PlaceHolder ?>" value="<?php echo $pessoa->ds_telefone1->EditValue ?>"<?php echo $pessoa->ds_telefone1->EditAttributes() ?>>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_ds_telefone2"><?php echo $pessoa->ds_telefone2->FldCaption() ?></label>
		<div class="controls">
<input type="text" data-field="x_ds_telefone2" name="x_ds_telefone2" id="x_ds_telefone2" size="30" maxlength="50" placeholder="<?php echo $pessoa->ds_telefone2->PlaceHolder ?>" value="<?php echo $pessoa->ds_telefone2->EditValue ?>"<?php echo $pessoa->ds_telefone2->EditAttributes() ?>>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_ic_ativo"><?php echo $pessoa->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $pessoa->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $pessoa->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pessoa->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $pessoa->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</div>
	</div>
</div>
</form>
<script type="text/javascript">
fpessoaaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$pessoa_addopt->Page_Terminate();
?>
