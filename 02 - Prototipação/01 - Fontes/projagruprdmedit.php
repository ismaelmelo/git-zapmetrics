<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "projagruprdminfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$projagruprdm_edit = NULL; // Initialize page object first

class cprojagruprdm_edit extends cprojagruprdm {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'projagruprdm';

	// Page object name
	var $PageObjName = 'projagruprdm_edit';

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

		// Table object (projagruprdm)
		if (!isset($GLOBALS["projagruprdm"])) {
			$GLOBALS["projagruprdm"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["projagruprdm"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'projagruprdm', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("projagruprdmlist.php");
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["nu_projAgrupRedmine"] <> "") {
			$this->nu_projAgrupRedmine->setQueryStringValue($_GET["nu_projAgrupRedmine"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->nu_projAgrupRedmine->CurrentValue == "")
			$this->Page_Terminate("projagruprdmlist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("projagruprdmlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "projagruprdmview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to View page directly
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_nivel->FldIsDetailKey) {
			$this->nu_nivel->setFormValue($objForm->GetValue("x_nu_nivel"));
		}
		if (!$this->nu_projAgrupPai->FldIsDetailKey) {
			$this->nu_projAgrupPai->setFormValue($objForm->GetValue("x_nu_projAgrupPai"));
		}
		if (!$this->ds_projredmine->FldIsDetailKey) {
			$this->ds_projredmine->setFormValue($objForm->GetValue("x_ds_projredmine"));
		}
		if (!$this->nu_usuarioAlt->FldIsDetailKey) {
			$this->nu_usuarioAlt->setFormValue($objForm->GetValue("x_nu_usuarioAlt"));
		}
		if (!$this->dh_alteracao->FldIsDetailKey) {
			$this->dh_alteracao->setFormValue($objForm->GetValue("x_dh_alteracao"));
			$this->dh_alteracao->CurrentValue = ew_UnFormatDateTime($this->dh_alteracao->CurrentValue, 7);
		}
		if (!$this->nu_projAgrupRedmine->FldIsDetailKey)
			$this->nu_projAgrupRedmine->setFormValue($objForm->GetValue("x_nu_projAgrupRedmine"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_projAgrupRedmine->CurrentValue = $this->nu_projAgrupRedmine->FormValue;
		$this->nu_nivel->CurrentValue = $this->nu_nivel->FormValue;
		$this->nu_projAgrupPai->CurrentValue = $this->nu_projAgrupPai->FormValue;
		$this->ds_projredmine->CurrentValue = $this->ds_projredmine->FormValue;
		$this->nu_usuarioAlt->CurrentValue = $this->nu_usuarioAlt->FormValue;
		$this->dh_alteracao->CurrentValue = $this->dh_alteracao->FormValue;
		$this->dh_alteracao->CurrentValue = ew_UnFormatDateTime($this->dh_alteracao->CurrentValue, 7);
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
		$this->nu_projAgrupRedmine->setDbValue($rs->fields('nu_projAgrupRedmine'));
		$this->nu_nivel->setDbValue($rs->fields('nu_nivel'));
		$this->nu_projAgrupPai->setDbValue($rs->fields('nu_projAgrupPai'));
		$this->ds_projredmine->setDbValue($rs->fields('ds_projredmine'));
		$this->nu_usuarioInc->setDbValue($rs->fields('nu_usuarioInc'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->nu_usuarioAlt->setDbValue($rs->fields('nu_usuarioAlt'));
		$this->dh_alteracao->setDbValue($rs->fields('dh_alteracao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_projAgrupRedmine->DbValue = $row['nu_projAgrupRedmine'];
		$this->nu_nivel->DbValue = $row['nu_nivel'];
		$this->nu_projAgrupPai->DbValue = $row['nu_projAgrupPai'];
		$this->ds_projredmine->DbValue = $row['ds_projredmine'];
		$this->nu_usuarioInc->DbValue = $row['nu_usuarioInc'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
		$this->nu_usuarioAlt->DbValue = $row['nu_usuarioAlt'];
		$this->dh_alteracao->DbValue = $row['dh_alteracao'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_projAgrupRedmine
		// nu_nivel
		// nu_projAgrupPai
		// ds_projredmine
		// nu_usuarioInc
		// dh_inclusao
		// nu_usuarioAlt
		// dh_alteracao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_projAgrupRedmine
			$this->nu_projAgrupRedmine->ViewValue = $this->nu_projAgrupRedmine->CurrentValue;
			$this->nu_projAgrupRedmine->ViewCustomAttributes = "";

			// nu_nivel
			if (strval($this->nu_nivel->CurrentValue) <> "") {
				switch ($this->nu_nivel->CurrentValue) {
					case $this->nu_nivel->FldTagValue(1):
						$this->nu_nivel->ViewValue = $this->nu_nivel->FldTagCaption(1) <> "" ? $this->nu_nivel->FldTagCaption(1) : $this->nu_nivel->CurrentValue;
						break;
					case $this->nu_nivel->FldTagValue(2):
						$this->nu_nivel->ViewValue = $this->nu_nivel->FldTagCaption(2) <> "" ? $this->nu_nivel->FldTagCaption(2) : $this->nu_nivel->CurrentValue;
						break;
					case $this->nu_nivel->FldTagValue(3):
						$this->nu_nivel->ViewValue = $this->nu_nivel->FldTagCaption(3) <> "" ? $this->nu_nivel->FldTagCaption(3) : $this->nu_nivel->CurrentValue;
						break;
					case $this->nu_nivel->FldTagValue(4):
						$this->nu_nivel->ViewValue = $this->nu_nivel->FldTagCaption(4) <> "" ? $this->nu_nivel->FldTagCaption(4) : $this->nu_nivel->CurrentValue;
						break;
					case $this->nu_nivel->FldTagValue(5):
						$this->nu_nivel->ViewValue = $this->nu_nivel->FldTagCaption(5) <> "" ? $this->nu_nivel->FldTagCaption(5) : $this->nu_nivel->CurrentValue;
						break;
					default:
						$this->nu_nivel->ViewValue = $this->nu_nivel->CurrentValue;
				}
			} else {
				$this->nu_nivel->ViewValue = NULL;
			}
			$this->nu_nivel->ViewCustomAttributes = "";

			// nu_projAgrupPai
			$this->nu_projAgrupPai->ViewValue = $this->nu_projAgrupPai->CurrentValue;
			$this->nu_projAgrupPai->ViewCustomAttributes = "";

			// ds_projredmine
			$this->ds_projredmine->ViewValue = $this->ds_projredmine->CurrentValue;
			$this->ds_projredmine->ViewCustomAttributes = "";

			// nu_usuarioInc
			if (strval($this->nu_usuarioInc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioInc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioInc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioInc->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioInc->ViewValue = $this->nu_usuarioInc->CurrentValue;
				}
			} else {
				$this->nu_usuarioInc->ViewValue = NULL;
			}
			$this->nu_usuarioInc->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// nu_usuarioAlt
			if (strval($this->nu_usuarioAlt->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAlt->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioAlt, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioAlt->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioAlt->ViewValue = $this->nu_usuarioAlt->CurrentValue;
				}
			} else {
				$this->nu_usuarioAlt->ViewValue = NULL;
			}
			$this->nu_usuarioAlt->ViewCustomAttributes = "";

			// dh_alteracao
			$this->dh_alteracao->ViewValue = $this->dh_alteracao->CurrentValue;
			$this->dh_alteracao->ViewValue = ew_FormatDateTime($this->dh_alteracao->ViewValue, 7);
			$this->dh_alteracao->ViewCustomAttributes = "";

			// nu_nivel
			$this->nu_nivel->LinkCustomAttributes = "";
			$this->nu_nivel->HrefValue = "";
			$this->nu_nivel->TooltipValue = "";

			// nu_projAgrupPai
			$this->nu_projAgrupPai->LinkCustomAttributes = "";
			$this->nu_projAgrupPai->HrefValue = "";
			$this->nu_projAgrupPai->TooltipValue = "";

			// ds_projredmine
			$this->ds_projredmine->LinkCustomAttributes = "";
			$this->ds_projredmine->HrefValue = "";
			$this->ds_projredmine->TooltipValue = "";

			// nu_usuarioAlt
			$this->nu_usuarioAlt->LinkCustomAttributes = "";
			$this->nu_usuarioAlt->HrefValue = "";
			$this->nu_usuarioAlt->TooltipValue = "";

			// dh_alteracao
			$this->dh_alteracao->LinkCustomAttributes = "";
			$this->dh_alteracao->HrefValue = "";
			$this->dh_alteracao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_nivel
			$this->nu_nivel->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->nu_nivel->FldTagValue(1), $this->nu_nivel->FldTagCaption(1) <> "" ? $this->nu_nivel->FldTagCaption(1) : $this->nu_nivel->FldTagValue(1));
			$arwrk[] = array($this->nu_nivel->FldTagValue(2), $this->nu_nivel->FldTagCaption(2) <> "" ? $this->nu_nivel->FldTagCaption(2) : $this->nu_nivel->FldTagValue(2));
			$arwrk[] = array($this->nu_nivel->FldTagValue(3), $this->nu_nivel->FldTagCaption(3) <> "" ? $this->nu_nivel->FldTagCaption(3) : $this->nu_nivel->FldTagValue(3));
			$arwrk[] = array($this->nu_nivel->FldTagValue(4), $this->nu_nivel->FldTagCaption(4) <> "" ? $this->nu_nivel->FldTagCaption(4) : $this->nu_nivel->FldTagValue(4));
			$arwrk[] = array($this->nu_nivel->FldTagValue(5), $this->nu_nivel->FldTagCaption(5) <> "" ? $this->nu_nivel->FldTagCaption(5) : $this->nu_nivel->FldTagValue(5));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->nu_nivel->EditValue = $arwrk;

			// nu_projAgrupPai
			$this->nu_projAgrupPai->EditCustomAttributes = "";
			$this->nu_projAgrupPai->EditValue = ew_HtmlEncode($this->nu_projAgrupPai->CurrentValue);
			$this->nu_projAgrupPai->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_projAgrupPai->FldCaption()));

			// ds_projredmine
			$this->ds_projredmine->EditCustomAttributes = "";
			$this->ds_projredmine->EditValue = $this->ds_projredmine->CurrentValue;
			$this->ds_projredmine->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_projredmine->FldCaption()));

			// nu_usuarioAlt
			// dh_alteracao
			// Edit refer script
			// nu_nivel

			$this->nu_nivel->HrefValue = "";

			// nu_projAgrupPai
			$this->nu_projAgrupPai->HrefValue = "";

			// ds_projredmine
			$this->ds_projredmine->HrefValue = "";

			// nu_usuarioAlt
			$this->nu_usuarioAlt->HrefValue = "";

			// dh_alteracao
			$this->dh_alteracao->HrefValue = "";
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
		if (!$this->nu_nivel->FldIsDetailKey && !is_null($this->nu_nivel->FormValue) && $this->nu_nivel->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_nivel->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_projAgrupPai->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_projAgrupPai->FldErrMsg());
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// nu_nivel
			$this->nu_nivel->SetDbValueDef($rsnew, $this->nu_nivel->CurrentValue, 0, $this->nu_nivel->ReadOnly);

			// nu_projAgrupPai
			$this->nu_projAgrupPai->SetDbValueDef($rsnew, $this->nu_projAgrupPai->CurrentValue, NULL, $this->nu_projAgrupPai->ReadOnly);

			// ds_projredmine
			$this->ds_projredmine->SetDbValueDef($rsnew, $this->ds_projredmine->CurrentValue, NULL, $this->ds_projredmine->ReadOnly);

			// nu_usuarioAlt
			$this->nu_usuarioAlt->SetDbValueDef($rsnew, CurrentUserID(), NULL);
			$rsnew['nu_usuarioAlt'] = &$this->nu_usuarioAlt->DbValue;

			// dh_alteracao
			$this->dh_alteracao->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
			$rsnew['dh_alteracao'] = &$this->dh_alteracao->DbValue;

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "projagruprdmlist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($projagruprdm_edit)) $projagruprdm_edit = new cprojagruprdm_edit();

// Page init
$projagruprdm_edit->Page_Init();

// Page main
$projagruprdm_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$projagruprdm_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var projagruprdm_edit = new ew_Page("projagruprdm_edit");
projagruprdm_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = projagruprdm_edit.PageID; // For backward compatibility

// Form object
var fprojagruprdmedit = new ew_Form("fprojagruprdmedit");

// Validate form
fprojagruprdmedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_nivel");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($projagruprdm->nu_nivel->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_projAgrupPai");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($projagruprdm->nu_projAgrupPai->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fprojagruprdmedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprojagruprdmedit.ValidateRequired = true;
<?php } else { ?>
fprojagruprdmedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fprojagruprdmedit.Lists["x_nu_usuarioAlt"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $projagruprdm_edit->ShowPageHeader(); ?>
<?php
$projagruprdm_edit->ShowMessage();
?>
<form name="fprojagruprdmedit" id="fprojagruprdmedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="projagruprdm">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_projagruprdmedit" class="table table-bordered table-striped">
<?php if ($projagruprdm->nu_nivel->Visible) { // nu_nivel ?>
	<tr id="r_nu_nivel">
		<td><span id="elh_projagruprdm_nu_nivel"><?php echo $projagruprdm->nu_nivel->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $projagruprdm->nu_nivel->CellAttributes() ?>>
<span id="el_projagruprdm_nu_nivel" class="control-group">
<select data-field="x_nu_nivel" id="x_nu_nivel" name="x_nu_nivel"<?php echo $projagruprdm->nu_nivel->EditAttributes() ?>>
<?php
if (is_array($projagruprdm->nu_nivel->EditValue)) {
	$arwrk = $projagruprdm->nu_nivel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($projagruprdm->nu_nivel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</span>
<?php echo $projagruprdm->nu_nivel->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($projagruprdm->nu_projAgrupPai->Visible) { // nu_projAgrupPai ?>
	<tr id="r_nu_projAgrupPai">
		<td><span id="elh_projagruprdm_nu_projAgrupPai"><?php echo $projagruprdm->nu_projAgrupPai->FldCaption() ?></span></td>
		<td<?php echo $projagruprdm->nu_projAgrupPai->CellAttributes() ?>>
<span id="el_projagruprdm_nu_projAgrupPai" class="control-group">
<input type="text" data-field="x_nu_projAgrupPai" name="x_nu_projAgrupPai" id="x_nu_projAgrupPai" size="30" placeholder="<?php echo $projagruprdm->nu_projAgrupPai->PlaceHolder ?>" value="<?php echo $projagruprdm->nu_projAgrupPai->EditValue ?>"<?php echo $projagruprdm->nu_projAgrupPai->EditAttributes() ?>>
</span>
<?php echo $projagruprdm->nu_projAgrupPai->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($projagruprdm->ds_projredmine->Visible) { // ds_projredmine ?>
	<tr id="r_ds_projredmine">
		<td><span id="elh_projagruprdm_ds_projredmine"><?php echo $projagruprdm->ds_projredmine->FldCaption() ?></span></td>
		<td<?php echo $projagruprdm->ds_projredmine->CellAttributes() ?>>
<span id="el_projagruprdm_ds_projredmine" class="control-group">
<textarea data-field="x_ds_projredmine" name="x_ds_projredmine" id="x_ds_projredmine" cols="35" rows="4" placeholder="<?php echo $projagruprdm->ds_projredmine->PlaceHolder ?>"<?php echo $projagruprdm->ds_projredmine->EditAttributes() ?>><?php echo $projagruprdm->ds_projredmine->EditValue ?></textarea>
</span>
<?php echo $projagruprdm->ds_projredmine->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_nu_projAgrupRedmine" name="x_nu_projAgrupRedmine" id="x_nu_projAgrupRedmine" value="<?php echo ew_HtmlEncode($projagruprdm->nu_projAgrupRedmine->CurrentValue) ?>">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fprojagruprdmedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$projagruprdm_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$projagruprdm_edit->Page_Terminate();
?>
