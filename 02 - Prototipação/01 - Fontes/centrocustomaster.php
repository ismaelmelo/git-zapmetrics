<?php

// nu_area
// co_alternativo
// no_centroCusto
// ic_ativo

?>
<?php if ($centrocusto->Visible) { ?>
<table cellspacing="0" id="t_centrocusto" class="ewGrid"><tr><td>
<table id="tbl_centrocustomaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($centrocusto->nu_area->Visible) { // nu_area ?>
		<tr id="r_nu_area">
			<td><?php echo $centrocusto->nu_area->FldCaption() ?></td>
			<td<?php echo $centrocusto->nu_area->CellAttributes() ?>>
<span id="el_centrocusto_nu_area" class="control-group">
<span<?php echo $centrocusto->nu_area->ViewAttributes() ?>>
<?php echo $centrocusto->nu_area->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($centrocusto->co_alternativo->Visible) { // co_alternativo ?>
		<tr id="r_co_alternativo">
			<td><?php echo $centrocusto->co_alternativo->FldCaption() ?></td>
			<td<?php echo $centrocusto->co_alternativo->CellAttributes() ?>>
<span id="el_centrocusto_co_alternativo" class="control-group">
<span<?php echo $centrocusto->co_alternativo->ViewAttributes() ?>>
<?php echo $centrocusto->co_alternativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($centrocusto->no_centroCusto->Visible) { // no_centroCusto ?>
		<tr id="r_no_centroCusto">
			<td><?php echo $centrocusto->no_centroCusto->FldCaption() ?></td>
			<td<?php echo $centrocusto->no_centroCusto->CellAttributes() ?>>
<span id="el_centrocusto_no_centroCusto" class="control-group">
<span<?php echo $centrocusto->no_centroCusto->ViewAttributes() ?>>
<?php echo $centrocusto->no_centroCusto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($centrocusto->ic_ativo->Visible) { // ic_ativo ?>
		<tr id="r_ic_ativo">
			<td><?php echo $centrocusto->ic_ativo->FldCaption() ?></td>
			<td<?php echo $centrocusto->ic_ativo->CellAttributes() ?>>
<span id="el_centrocusto_ic_ativo" class="control-group">
<span<?php echo $centrocusto->ic_ativo->ViewAttributes() ?>>
<?php echo $centrocusto->ic_ativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
