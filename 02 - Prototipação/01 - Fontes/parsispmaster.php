<?php

// no_parSisp
// ds_parametro

?>
<?php if ($parSisp->Visible) { ?>
<table cellspacing="0" id="t_parSisp" class="ewGrid"><tr><td>
<table id="tbl_parSispmaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($parSisp->no_parSisp->Visible) { // no_parSisp ?>
		<tr id="r_no_parSisp">
			<td><?php echo $parSisp->no_parSisp->FldCaption() ?></td>
			<td<?php echo $parSisp->no_parSisp->CellAttributes() ?>>
<span id="el_parSisp_no_parSisp" class="control-group">
<span<?php echo $parSisp->no_parSisp->ViewAttributes() ?>>
<?php echo $parSisp->no_parSisp->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($parSisp->ds_parametro->Visible) { // ds_parametro ?>
		<tr id="r_ds_parametro">
			<td><?php echo $parSisp->ds_parametro->FldCaption() ?></td>
			<td<?php echo $parSisp->ds_parametro->CellAttributes() ?>>
<span id="el_parSisp_ds_parametro" class="control-group">
<span<?php echo $parSisp->ds_parametro->ViewAttributes() ?>>
<?php echo $parSisp->ds_parametro->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
