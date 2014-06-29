<?php

// co_alternativo
// no_uc
// nu_stUc

?>
<?php if ($uc->Visible) { ?>
<table cellspacing="0" id="t_uc" class="ewGrid"><tr><td>
<table id="tbl_ucmaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($uc->co_alternativo->Visible) { // co_alternativo ?>
		<tr id="r_co_alternativo">
			<td><?php echo $uc->co_alternativo->FldCaption() ?></td>
			<td<?php echo $uc->co_alternativo->CellAttributes() ?>>
<span id="el_uc_co_alternativo" class="control-group">
<span<?php echo $uc->co_alternativo->ViewAttributes() ?>>
<?php echo $uc->co_alternativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($uc->no_uc->Visible) { // no_uc ?>
		<tr id="r_no_uc">
			<td><?php echo $uc->no_uc->FldCaption() ?></td>
			<td<?php echo $uc->no_uc->CellAttributes() ?>>
<span id="el_uc_no_uc" class="control-group">
<span<?php echo $uc->no_uc->ViewAttributes() ?>>
<?php echo $uc->no_uc->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($uc->nu_stUc->Visible) { // nu_stUc ?>
		<tr id="r_nu_stUc">
			<td><?php echo $uc->nu_stUc->FldCaption() ?></td>
			<td<?php echo $uc->nu_stUc->CellAttributes() ?>>
<span id="el_uc_nu_stUc" class="control-group">
<span<?php echo $uc->nu_stUc->ViewAttributes() ?>>
<?php echo $uc->nu_stUc->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
