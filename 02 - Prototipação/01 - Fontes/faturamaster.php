<?php

// nu_fatura
// nu_tpFatura
// dt_faturamento
// nu_stFatura

?>
<?php if ($fatura->Visible) { ?>
<table cellspacing="0" id="t_fatura" class="ewGrid"><tr><td>
<table id="tbl_faturamaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($fatura->nu_fatura->Visible) { // nu_fatura ?>
		<tr id="r_nu_fatura">
			<td><?php echo $fatura->nu_fatura->FldCaption() ?></td>
			<td<?php echo $fatura->nu_fatura->CellAttributes() ?>>
<span id="el_fatura_nu_fatura" class="control-group">
<span<?php echo $fatura->nu_fatura->ViewAttributes() ?>>
<?php echo $fatura->nu_fatura->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($fatura->nu_tpFatura->Visible) { // nu_tpFatura ?>
		<tr id="r_nu_tpFatura">
			<td><?php echo $fatura->nu_tpFatura->FldCaption() ?></td>
			<td<?php echo $fatura->nu_tpFatura->CellAttributes() ?>>
<span id="el_fatura_nu_tpFatura" class="control-group">
<span<?php echo $fatura->nu_tpFatura->ViewAttributes() ?>>
<?php echo $fatura->nu_tpFatura->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($fatura->dt_faturamento->Visible) { // dt_faturamento ?>
		<tr id="r_dt_faturamento">
			<td><?php echo $fatura->dt_faturamento->FldCaption() ?></td>
			<td<?php echo $fatura->dt_faturamento->CellAttributes() ?>>
<span id="el_fatura_dt_faturamento" class="control-group">
<span<?php echo $fatura->dt_faturamento->ViewAttributes() ?>>
<?php echo $fatura->dt_faturamento->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($fatura->nu_stFatura->Visible) { // nu_stFatura ?>
		<tr id="r_nu_stFatura">
			<td><?php echo $fatura->nu_stFatura->FldCaption() ?></td>
			<td<?php echo $fatura->nu_stFatura->CellAttributes() ?>>
<span id="el_fatura_nu_stFatura" class="control-group">
<span<?php echo $fatura->nu_stFatura->ViewAttributes() ?>>
<?php echo $fatura->nu_stFatura->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
