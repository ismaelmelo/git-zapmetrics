<?php

// co_alternativo
// nu_fornecedor
// no_contrato
// dt_vencimento
// nu_stContrato

?>
<?php if ($contrato->Visible) { ?>
<table cellspacing="0" id="t_contrato" class="ewGrid"><tr><td>
<table id="tbl_contratomaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($contrato->co_alternativo->Visible) { // co_alternativo ?>
		<tr id="r_co_alternativo">
			<td><?php echo $contrato->co_alternativo->FldCaption() ?></td>
			<td<?php echo $contrato->co_alternativo->CellAttributes() ?>>
<span id="el_contrato_co_alternativo" class="control-group">
<span<?php echo $contrato->co_alternativo->ViewAttributes() ?>>
<?php echo $contrato->co_alternativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($contrato->nu_fornecedor->Visible) { // nu_fornecedor ?>
		<tr id="r_nu_fornecedor">
			<td><?php echo $contrato->nu_fornecedor->FldCaption() ?></td>
			<td<?php echo $contrato->nu_fornecedor->CellAttributes() ?>>
<span id="el_contrato_nu_fornecedor" class="control-group">
<span<?php echo $contrato->nu_fornecedor->ViewAttributes() ?>>
<?php echo $contrato->nu_fornecedor->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($contrato->no_contrato->Visible) { // no_contrato ?>
		<tr id="r_no_contrato">
			<td><?php echo $contrato->no_contrato->FldCaption() ?></td>
			<td<?php echo $contrato->no_contrato->CellAttributes() ?>>
<span id="el_contrato_no_contrato" class="control-group">
<span<?php echo $contrato->no_contrato->ViewAttributes() ?>>
<?php echo $contrato->no_contrato->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($contrato->dt_vencimento->Visible) { // dt_vencimento ?>
		<tr id="r_dt_vencimento">
			<td><?php echo $contrato->dt_vencimento->FldCaption() ?></td>
			<td<?php echo $contrato->dt_vencimento->CellAttributes() ?>>
<span id="el_contrato_dt_vencimento" class="control-group">
<span<?php echo $contrato->dt_vencimento->ViewAttributes() ?>>
<?php echo $contrato->dt_vencimento->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($contrato->nu_stContrato->Visible) { // nu_stContrato ?>
		<tr id="r_nu_stContrato">
			<td><?php echo $contrato->nu_stContrato->FldCaption() ?></td>
			<td<?php echo $contrato->nu_stContrato->CellAttributes() ?>>
<span id="el_contrato_nu_stContrato" class="control-group">
<span<?php echo $contrato->nu_stContrato->ViewAttributes() ?>>
<?php echo $contrato->nu_stContrato->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
