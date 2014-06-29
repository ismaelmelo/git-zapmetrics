<?php

// no_area
// nu_tpArea
// nu_pessoaResp
// ic_ativo

?>
<?php if ($area->Visible) { ?>
<table cellspacing="0" id="t_area" class="ewGrid"><tr><td>
<table id="tbl_areamaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($area->no_area->Visible) { // no_area ?>
		<tr id="r_no_area">
			<td><?php echo $area->no_area->FldCaption() ?></td>
			<td<?php echo $area->no_area->CellAttributes() ?>>
<span id="el_area_no_area" class="control-group">
<span<?php echo $area->no_area->ViewAttributes() ?>>
<?php echo $area->no_area->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($area->nu_tpArea->Visible) { // nu_tpArea ?>
		<tr id="r_nu_tpArea">
			<td><?php echo $area->nu_tpArea->FldCaption() ?></td>
			<td<?php echo $area->nu_tpArea->CellAttributes() ?>>
<span id="el_area_nu_tpArea" class="control-group">
<span<?php echo $area->nu_tpArea->ViewAttributes() ?>>
<?php echo $area->nu_tpArea->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($area->nu_pessoaResp->Visible) { // nu_pessoaResp ?>
		<tr id="r_nu_pessoaResp">
			<td><?php echo $area->nu_pessoaResp->FldCaption() ?></td>
			<td<?php echo $area->nu_pessoaResp->CellAttributes() ?>>
<span id="el_area_nu_pessoaResp" class="control-group">
<span<?php echo $area->nu_pessoaResp->ViewAttributes() ?>>
<?php echo $area->nu_pessoaResp->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($area->ic_ativo->Visible) { // ic_ativo ?>
		<tr id="r_ic_ativo">
			<td><?php echo $area->ic_ativo->FldCaption() ?></td>
			<td<?php echo $area->ic_ativo->CellAttributes() ?>>
<span id="el_area_ic_ativo" class="control-group">
<span<?php echo $area->ic_ativo->ViewAttributes() ?>>
<?php echo $area->ic_ativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
