<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$usuario_addopt = NULL; // Initialize page object first

class cusuario_addopt extends cusuario {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'usuario';

	// Page object name
	var $PageObjName = 'usuario_addopt';

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
	var $AuditTrailOnAdd = TRUE;

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

		// Table object (usuario)
		if (!isset($GLOBALS["usuario"])) {
			$GLOBALS["usuario"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["usuario"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'usuario', TRUE);

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
			$this->Page_Terminate("usuariolist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("usuariolist.php");
		}

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
					$row["x_nu_usuario"] = $this->nu_usuario->DbValue;
					$row["x_nu_pessoa"] = $this->nu_pessoa->DbValue;
					$row["x_nu_usuarioRedmine"] = $this->nu_usuarioRedmine->DbValue;
					$row["x_no_usuario"] = $this->no_usuario->DbValue;
					$row["x_no_login"] = $this->no_login->DbValue;
					$row["x_no_senha"] = $this->no_senha->DbValue;
					$row["x_no_email"] = $this->no_email->DbValue;
					$row["x_nu_perfil"] = $this->nu_perfil->DbValue;
					$row["x_nu_stUsuario"] = $this->nu_stUsuario->DbValue;
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
		$this->nu_pessoa->CurrentValue = NULL;
		$this->nu_pessoa->OldValue = $this->nu_pessoa->CurrentValue;
		$this->nu_usuarioRedmine->CurrentValue = NULL;
		$this->nu_usuarioRedmine->OldValue = $this->nu_usuarioRedmine->CurrentValue;
		$this->no_usuario->CurrentValue = NULL;
		$this->no_usuario->OldValue = $this->no_usuario->CurrentValue;
		$this->no_login->CurrentValue = NULL;
		$this->no_login->OldValue = $this->no_login->CurrentValue;
		$this->no_senha->CurrentValue = NULL;
		$this->no_senha->OldValue = $this->no_senha->CurrentValue;
		$this->no_email->CurrentValue = NULL;
		$this->no_email->OldValue = $this->no_email->CurrentValue;
		$this->nu_perfil->CurrentValue = NULL;
		$this->nu_perfil->OldValue = $this->nu_perfil->CurrentValue;
		$this->nu_stUsuario->CurrentValue = NULL;
		$this->nu_stUsuario->OldValue = $this->nu_stUsuario->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_pessoa->FldIsDetailKey) {
			$this->nu_pessoa->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_pessoa")));
		}
		if (!$this->nu_usuarioRedmine->FldIsDetailKey) {
			$this->nu_usuarioRedmine->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_usuarioRedmine")));
		}
		if (!$this->no_usuario->FldIsDetailKey) {
			$this->no_usuario->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_no_usuario")));
		}
		if (!$this->no_login->FldIsDetailKey) {
			$this->no_login->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_no_login")));
		}
		if (!$this->no_senha->FldIsDetailKey) {
			$this->no_senha->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_no_senha")));
		}
		if (!$this->no_email->FldIsDetailKey) {
			$this->no_email->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_no_email")));
		}
		if (!$this->nu_perfil->FldIsDetailKey) {
			$this->nu_perfil->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_perfil")));
		}
		if (!$this->nu_stUsuario->FldIsDetailKey) {
			$this->nu_stUsuario->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_stUsuario")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->nu_pessoa->CurrentValue = ew_ConvertToUtf8($this->nu_pessoa->FormValue);
		$this->nu_usuarioRedmine->CurrentValue = ew_ConvertToUtf8($this->nu_usuarioRedmine->FormValue);
		$this->no_usuario->CurrentValue = ew_ConvertToUtf8($this->no_usuario->FormValue);
		$this->no_login->CurrentValue = ew_ConvertToUtf8($this->no_login->FormValue);
		$this->no_senha->CurrentValue = ew_ConvertToUtf8($this->no_senha->FormValue);
		$this->no_email->CurrentValue = ew_ConvertToUtf8($this->no_email->FormValue);
		$this->nu_perfil->CurrentValue = ew_ConvertToUtf8($this->nu_perfil->FormValue);
		$this->nu_stUsuario->CurrentValue = ew_ConvertToUtf8($this->nu_stUsuario->FormValue);
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
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->nu_pessoa->setDbValue($rs->fields('nu_pessoa'));
		if (array_key_exists('EV__nu_pessoa', $rs->fields)) {
			$this->nu_pessoa->VirtualValue = $rs->fields('EV__nu_pessoa'); // Set up virtual field value
		} else {
			$this->nu_pessoa->VirtualValue = ""; // Clear value
		}
		$this->nu_usuarioRedmine->setDbValue($rs->fields('nu_usuarioRedmine'));
		$this->no_usuario->setDbValue($rs->fields('no_usuario'));
		$this->no_login->setDbValue($rs->fields('no_login'));
		$this->no_senha->setDbValue($rs->fields('no_senha'));
		$this->no_email->setDbValue($rs->fields('no_email'));
		$this->nu_perfil->setDbValue($rs->fields('nu_perfil'));
		$this->nu_stUsuario->setDbValue($rs->fields('nu_stUsuario'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->nu_pessoa->DbValue = $row['nu_pessoa'];
		$this->nu_usuarioRedmine->DbValue = $row['nu_usuarioRedmine'];
		$this->no_usuario->DbValue = $row['no_usuario'];
		$this->no_login->DbValue = $row['no_login'];
		$this->no_senha->DbValue = $row['no_senha'];
		$this->no_email->DbValue = $row['no_email'];
		$this->nu_perfil->DbValue = $row['nu_perfil'];
		$this->nu_stUsuario->DbValue = $row['nu_stUsuario'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_usuario
		// nu_pessoa
		// nu_usuarioRedmine
		// no_usuario
		// no_login
		// no_senha
		// no_email
		// nu_perfil
		// nu_stUsuario

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			$this->nu_usuario->ViewCustomAttributes = "";

			// nu_pessoa
			if ($this->nu_pessoa->VirtualValue <> "") {
				$this->nu_pessoa->ViewValue = $this->nu_pessoa->VirtualValue;
			} else {
			if (strval($this->nu_pessoa->CurrentValue) <> "") {
				$sFilterWrk = "[nu_pessoa]" . ew_SearchString("=", $this->nu_pessoa->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[pessoa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoa, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_pessoa->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_pessoa->ViewValue = $this->nu_pessoa->CurrentValue;
				}
			} else {
				$this->nu_pessoa->ViewValue = NULL;
			}
			}
			$this->nu_pessoa->ViewCustomAttributes = "";

			// nu_usuarioRedmine
			if (strval($this->nu_usuarioRedmine->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->nu_usuarioRedmine->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [login] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tbrdm_users]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioRedmine, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [name] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioRedmine->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioRedmine->ViewValue = $this->nu_usuarioRedmine->CurrentValue;
				}
			} else {
				$this->nu_usuarioRedmine->ViewValue = NULL;
			}
			$this->nu_usuarioRedmine->ViewCustomAttributes = "";

			// no_usuario
			$this->no_usuario->ViewValue = $this->no_usuario->CurrentValue;
			$this->no_usuario->ViewCustomAttributes = "";

			// no_login
			$this->no_login->ViewValue = $this->no_login->CurrentValue;
			$this->no_login->ViewCustomAttributes = "";

			// no_senha
			$this->no_senha->ViewValue = "********";
			$this->no_senha->ViewCustomAttributes = "";

			// no_email
			$this->no_email->ViewValue = $this->no_email->CurrentValue;
			$this->no_email->ViewCustomAttributes = "";

			// nu_perfil
			if ($Security->CanAdmin()) { // System admin
			if (strval($this->nu_perfil->CurrentValue) <> "") {
				$sFilterWrk = "[nu_level]" . ew_SearchString("=", $this->nu_perfil->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_level], [no_level] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario_permissoes]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_perfil, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_perfil->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_perfil->ViewValue = $this->nu_perfil->CurrentValue;
				}
			} else {
				$this->nu_perfil->ViewValue = NULL;
			}
			} else {
				$this->nu_perfil->ViewValue = "********";
			}
			$this->nu_perfil->ViewCustomAttributes = "";

			// nu_stUsuario
			if (strval($this->nu_stUsuario->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stUsuario]" . ew_SearchString("=", $this->nu_stUsuario->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stUsuario], [no_stUsuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stusuario]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stUsuario, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_stUsuario->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stUsuario->ViewValue = $this->nu_stUsuario->CurrentValue;
				}
			} else {
				$this->nu_stUsuario->ViewValue = NULL;
			}
			$this->nu_stUsuario->ViewCustomAttributes = "";

			// nu_pessoa
			$this->nu_pessoa->LinkCustomAttributes = "";
			$this->nu_pessoa->HrefValue = "";
			$this->nu_pessoa->TooltipValue = "";

			// nu_usuarioRedmine
			$this->nu_usuarioRedmine->LinkCustomAttributes = "";
			$this->nu_usuarioRedmine->HrefValue = "";
			$this->nu_usuarioRedmine->TooltipValue = "";

			// no_usuario
			$this->no_usuario->LinkCustomAttributes = "";
			$this->no_usuario->HrefValue = "";
			$this->no_usuario->TooltipValue = "";

			// no_login
			$this->no_login->LinkCustomAttributes = "";
			$this->no_login->HrefValue = "";
			$this->no_login->TooltipValue = "";

			// no_senha
			$this->no_senha->LinkCustomAttributes = "";
			$this->no_senha->HrefValue = "";
			$this->no_senha->TooltipValue = "";

			// no_email
			$this->no_email->LinkCustomAttributes = "";
			$this->no_email->HrefValue = "";
			$this->no_email->TooltipValue = "";

			// nu_perfil
			$this->nu_perfil->LinkCustomAttributes = "";
			$this->nu_perfil->HrefValue = "";
			$this->nu_perfil->TooltipValue = "";

			// nu_stUsuario
			$this->nu_stUsuario->LinkCustomAttributes = "";
			$this->nu_stUsuario->HrefValue = "";
			$this->nu_stUsuario->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_pessoa
			$this->nu_pessoa->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[pessoa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoa, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_pessoa->EditValue = $arwrk;

			// nu_usuarioRedmine
			$this->nu_usuarioRedmine->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [id], [login] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tbrdm_users]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioRedmine, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [name] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_usuarioRedmine->EditValue = $arwrk;

			// no_usuario
			$this->no_usuario->EditCustomAttributes = "";
			$this->no_usuario->EditValue = ew_HtmlEncode($this->no_usuario->CurrentValue);
			$this->no_usuario->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_usuario->FldCaption()));

			// no_login
			$this->no_login->EditCustomAttributes = "";
			$this->no_login->EditValue = ew_HtmlEncode($this->no_login->CurrentValue);
			$this->no_login->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_login->FldCaption()));

			// no_senha
			$this->no_senha->EditCustomAttributes = "";
			$this->no_senha->EditValue = ew_HtmlEncode($this->no_senha->CurrentValue);

			// no_email
			$this->no_email->EditCustomAttributes = "";
			$this->no_email->EditValue = ew_HtmlEncode($this->no_email->CurrentValue);
			$this->no_email->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_email->FldCaption()));

			// nu_perfil
			$this->nu_perfil->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->nu_perfil->EditValue = "********";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_level], [no_level] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[usuario_permissoes]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_perfil, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_perfil->EditValue = $arwrk;
			}

			// nu_stUsuario
			$this->nu_stUsuario->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_stUsuario], [no_stUsuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[stusuario]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stUsuario, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_stUsuario->EditValue = $arwrk;

			// Edit refer script
			// nu_pessoa

			$this->nu_pessoa->HrefValue = "";

			// nu_usuarioRedmine
			$this->nu_usuarioRedmine->HrefValue = "";

			// no_usuario
			$this->no_usuario->HrefValue = "";

			// no_login
			$this->no_login->HrefValue = "";

			// no_senha
			$this->no_senha->HrefValue = "";

			// no_email
			$this->no_email->HrefValue = "";

			// nu_perfil
			$this->nu_perfil->HrefValue = "";

			// nu_stUsuario
			$this->nu_stUsuario->HrefValue = "";
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
		if (!$this->nu_pessoa->FldIsDetailKey && !is_null($this->nu_pessoa->FormValue) && $this->nu_pessoa->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_pessoa->FldCaption());
		}
		if (!$this->nu_usuarioRedmine->FldIsDetailKey && !is_null($this->nu_usuarioRedmine->FormValue) && $this->nu_usuarioRedmine->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_usuarioRedmine->FldCaption());
		}
		if (!$this->no_usuario->FldIsDetailKey && !is_null($this->no_usuario->FormValue) && $this->no_usuario->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_usuario->FldCaption());
		}
		if (!$this->no_login->FldIsDetailKey && !is_null($this->no_login->FormValue) && $this->no_login->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_login->FldCaption());
		}
		if (!$this->no_senha->FldIsDetailKey && !is_null($this->no_senha->FormValue) && $this->no_senha->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_senha->FldCaption());
		}
		if (!$this->no_email->FldIsDetailKey && !is_null($this->no_email->FormValue) && $this->no_email->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_email->FldCaption());
		}
		if (!ew_CheckEmail($this->no_email->FormValue)) {
			ew_AddMessage($gsFormError, $this->no_email->FldErrMsg());
		}
		if (!$this->nu_perfil->FldIsDetailKey && !is_null($this->nu_perfil->FormValue) && $this->nu_perfil->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_perfil->FldCaption());
		}
		if (!$this->nu_stUsuario->FldIsDetailKey && !is_null($this->nu_stUsuario->FormValue) && $this->nu_stUsuario->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_stUsuario->FldCaption());
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
		if ($this->nu_usuarioRedmine->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(nu_usuarioRedmine = " . ew_AdjustSql($this->nu_usuarioRedmine->CurrentValue) . ")";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->nu_usuarioRedmine->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->nu_usuarioRedmine->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
		if ($this->no_usuario->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(no_usuario = '" . ew_AdjustSql($this->no_usuario->CurrentValue) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->no_usuario->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->no_usuario->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
		if ($this->no_login->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(no_login = '" . ew_AdjustSql($this->no_login->CurrentValue) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->no_login->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->no_login->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
		if ($this->no_email->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(no_email = '" . ew_AdjustSql($this->no_email->CurrentValue) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->no_email->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->no_email->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nu_pessoa
		$this->nu_pessoa->SetDbValueDef($rsnew, $this->nu_pessoa->CurrentValue, NULL, FALSE);

		// nu_usuarioRedmine
		$this->nu_usuarioRedmine->SetDbValueDef($rsnew, $this->nu_usuarioRedmine->CurrentValue, NULL, FALSE);

		// no_usuario
		$this->no_usuario->SetDbValueDef($rsnew, $this->no_usuario->CurrentValue, NULL, FALSE);

		// no_login
		$this->no_login->SetDbValueDef($rsnew, $this->no_login->CurrentValue, "", FALSE);

		// no_senha
		$this->no_senha->SetDbValueDef($rsnew, $this->no_senha->CurrentValue, "", FALSE);

		// no_email
		$this->no_email->SetDbValueDef($rsnew, $this->no_email->CurrentValue, "", FALSE);

		// nu_perfil
		if ($Security->CanAdmin()) { // System admin
		$this->nu_perfil->SetDbValueDef($rsnew, $this->nu_perfil->CurrentValue, NULL, FALSE);
		}

		// nu_stUsuario
		$this->nu_stUsuario->SetDbValueDef($rsnew, $this->nu_stUsuario->CurrentValue, NULL, FALSE);

		// nu_usuario
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
			$this->nu_usuario->setDbValue($conn->Insert_ID());
			$rsnew['nu_usuario'] = $this->nu_usuario->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "usuariolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("addopt");
		$Breadcrumb->Add("addopt", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'usuario';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'usuario';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_usuario'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
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
if (!isset($usuario_addopt)) $usuario_addopt = new cusuario_addopt();

// Page init
$usuario_addopt->Page_Init();

// Page main
$usuario_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$usuario_addopt->Page_Render();
?>
<script type="text/javascript">

// Page object
var usuario_addopt = new ew_Page("usuario_addopt");
usuario_addopt.PageID = "addopt"; // Page ID
var EW_PAGE_ID = usuario_addopt.PageID; // For backward compatibility

// Form object
var fusuarioaddopt = new ew_Form("fusuarioaddopt");

// Validate form
fusuarioaddopt.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_pessoa");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($usuario->nu_pessoa->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_usuarioRedmine");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($usuario->nu_usuarioRedmine->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_usuario");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($usuario->no_usuario->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_login");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($usuario->no_login->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_senha");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($usuario->no_senha->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_email");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($usuario->no_email->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_email");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($usuario->no_email->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_perfil");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($usuario->nu_perfil->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_stUsuario");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($usuario->nu_stUsuario->FldCaption()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fusuarioaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fusuarioaddopt.ValidateRequired = true;
<?php } else { ?>
fusuarioaddopt.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fusuarioaddopt.Lists["x_nu_pessoa"] = {"LinkField":"x_nu_pessoa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_pessoa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fusuarioaddopt.Lists["x_nu_usuarioRedmine"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x__login","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fusuarioaddopt.Lists["x_nu_perfil"] = {"LinkField":"x_nu_level","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_level","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fusuarioaddopt.Lists["x_nu_stUsuario"] = {"LinkField":"x_nu_stUsuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stUsuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$usuario_addopt->ShowMessage();
?>
<form name="fusuarioaddopt" id="fusuarioaddopt" class="ewForm form-horizontal" action="usuarioaddopt.php" method="post">
<input type="hidden" name="t" value="usuario">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<div id="tbl_usuarioaddopt">
	<div class="control-group">
		<label class="control-label" for="x_nu_pessoa"><?php echo $usuario->nu_pessoa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<select data-field="x_nu_pessoa" id="x_nu_pessoa" name="x_nu_pessoa"<?php echo $usuario->nu_pessoa->EditAttributes() ?>>
<?php
if (is_array($usuario->nu_pessoa->EditValue)) {
	$arwrk = $usuario->nu_pessoa->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($usuario->nu_pessoa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fusuarioaddopt.Lists["x_nu_pessoa"].Options = <?php echo (is_array($usuario->nu_pessoa->EditValue)) ? ew_ArrayToJson($usuario->nu_pessoa->EditValue, 1) : "[]" ?>;
</script>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_usuarioRedmine"><?php echo $usuario->nu_usuarioRedmine->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<select data-field="x_nu_usuarioRedmine" id="x_nu_usuarioRedmine" name="x_nu_usuarioRedmine"<?php echo $usuario->nu_usuarioRedmine->EditAttributes() ?>>
<?php
if (is_array($usuario->nu_usuarioRedmine->EditValue)) {
	$arwrk = $usuario->nu_usuarioRedmine->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($usuario->nu_usuarioRedmine->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fusuarioaddopt.Lists["x_nu_usuarioRedmine"].Options = <?php echo (is_array($usuario->nu_usuarioRedmine->EditValue)) ? ew_ArrayToJson($usuario->nu_usuarioRedmine->EditValue, 1) : "[]" ?>;
</script>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_no_usuario"><?php echo $usuario->no_usuario->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<input type="text" data-field="x_no_usuario" name="x_no_usuario" id="x_no_usuario" size="30" maxlength="120" placeholder="<?php echo $usuario->no_usuario->PlaceHolder ?>" value="<?php echo $usuario->no_usuario->EditValue ?>"<?php echo $usuario->no_usuario->EditAttributes() ?>>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_no_login"><?php echo $usuario->no_login->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<input type="text" data-field="x_no_login" name="x_no_login" id="x_no_login" size="30" maxlength="20" placeholder="<?php echo $usuario->no_login->PlaceHolder ?>" value="<?php echo $usuario->no_login->EditValue ?>"<?php echo $usuario->no_login->EditAttributes() ?>>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_no_senha"><?php echo $usuario->no_senha->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<input type="password" data-field="x_no_senha" name="x_no_senha" id="x_no_senha" size="30" maxlength="20"<?php echo $usuario->no_senha->EditAttributes() ?>>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_no_email"><?php echo $usuario->no_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<input type="text" data-field="x_no_email" name="x_no_email" id="x_no_email" size="30" maxlength="150" placeholder="<?php echo $usuario->no_email->PlaceHolder ?>" value="<?php echo $usuario->no_email->EditValue ?>"<?php echo $usuario->no_email->EditAttributes() ?>>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_perfil"><?php echo $usuario->nu_perfil->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<?php echo $usuario->nu_perfil->EditValue ?>
<?php } else { ?>
<select data-field="x_nu_perfil" id="x_nu_perfil" name="x_nu_perfil"<?php echo $usuario->nu_perfil->EditAttributes() ?>>
<?php
if (is_array($usuario->nu_perfil->EditValue)) {
	$arwrk = $usuario->nu_perfil->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($usuario->nu_perfil->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fusuarioaddopt.Lists["x_nu_perfil"].Options = <?php echo (is_array($usuario->nu_perfil->EditValue)) ? ew_ArrayToJson($usuario->nu_perfil->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_stUsuario"><?php echo $usuario->nu_stUsuario->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<select data-field="x_nu_stUsuario" id="x_nu_stUsuario" name="x_nu_stUsuario"<?php echo $usuario->nu_stUsuario->EditAttributes() ?>>
<?php
if (is_array($usuario->nu_stUsuario->EditValue)) {
	$arwrk = $usuario->nu_stUsuario->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($usuario->nu_stUsuario->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fusuarioaddopt.Lists["x_nu_stUsuario"].Options = <?php echo (is_array($usuario->nu_stUsuario->EditValue)) ? ew_ArrayToJson($usuario->nu_stUsuario->EditValue, 1) : "[]" ?>;
</script>
</div>
	</div>
</div>
</form>
<script type="text/javascript">
fusuarioaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$usuario_addopt->Page_Terminate();
?>
