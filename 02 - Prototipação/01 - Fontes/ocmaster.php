<?php

// ic_tpOc
// co_alternativo
// dt_oc
// nu_stOc

?>
<?php if ($oc->Visible) { ?>
<table cellspacing="0" id="t_oc" class="ewGrid"><tr><td>
<table id="tbl_ocmaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($oc->ic_tpOc->Visible) { // ic_tpOc ?>
		<tr id="r_ic_tpOc">
			<td><?php echo $oc->ic_tpOc->FldCaption() ?></td>
			<td<?php echo $oc->ic_tpOc->CellAttributes() ?>>
<span id="el_oc_ic_tpOc" class="control-group">
<span<?php echo $oc->ic_tpOc->ViewAttributes() ?>>
<?php echo $oc->ic_tpOc->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($oc->co_alternativo->Visible) { // co_alternativo ?>
		<tr id="r_co_alternativo">
			<td><?php echo $oc->co_alternativo->FldCaption() ?></td>
			<td<?php echo $oc->co_alternativo->CellAttributes() ?>>
<span id="el_oc_co_alternativo" class="control-group">
<span<?php echo $oc->co_alternativo->ViewAttributes() ?>>
<?php echo $oc->co_alternativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($oc->dt_oc->Visible) { // dt_oc ?>
		<tr id="r_dt_oc">
			<td><?php echo $oc->dt_oc->FldCaption() ?></td>
			<td<?php echo $oc->dt_oc->CellAttributes() ?>>
<span id="el_oc_dt_oc" class="control-group">
<span<?php echo $oc->dt_oc->ViewAttributes() ?>>
<?php echo $oc->dt_oc->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($oc->nu_stOc->Visible) { // nu_stOc ?>
		<tr id="r_nu_stOc">
			<td><?php echo $oc->nu_stOc->FldCaption() ?></td>
			<td<?php echo $oc->nu_stOc->CellAttributes() ?>>
<span id="el_oc_nu_stOc" class="control-group">
<span<?php echo $oc->nu_stOc->ViewAttributes() ?>>
<?php echo $oc->nu_stOc->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
