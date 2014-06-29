<?php

// no_metodologia
// ic_tpModeloDev
// ic_ativo
// nu_ordem

?>
<?php if ($metodologia->Visible) { ?>
<table cellspacing="0" id="t_metodologia" class="ewGrid"><tr><td>
<table id="tbl_metodologiamaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($metodologia->no_metodologia->Visible) { // no_metodologia ?>
		<tr id="r_no_metodologia">
			<td><?php echo $metodologia->no_metodologia->FldCaption() ?></td>
			<td<?php echo $metodologia->no_metodologia->CellAttributes() ?>>
<span id="el_metodologia_no_metodologia" class="control-group">
<span<?php echo $metodologia->no_metodologia->ViewAttributes() ?>>
<?php echo $metodologia->no_metodologia->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($metodologia->ic_tpModeloDev->Visible) { // ic_tpModeloDev ?>
		<tr id="r_ic_tpModeloDev">
			<td><?php echo $metodologia->ic_tpModeloDev->FldCaption() ?></td>
			<td<?php echo $metodologia->ic_tpModeloDev->CellAttributes() ?>>
<span id="el_metodologia_ic_tpModeloDev" class="control-group">
<span<?php echo $metodologia->ic_tpModeloDev->ViewAttributes() ?>>
<?php echo $metodologia->ic_tpModeloDev->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($metodologia->ic_ativo->Visible) { // ic_ativo ?>
		<tr id="r_ic_ativo">
			<td><?php echo $metodologia->ic_ativo->FldCaption() ?></td>
			<td<?php echo $metodologia->ic_ativo->CellAttributes() ?>>
<span id="el_metodologia_ic_ativo" class="control-group">
<span<?php echo $metodologia->ic_ativo->ViewAttributes() ?>>
<?php echo $metodologia->ic_ativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($metodologia->nu_ordem->Visible) { // nu_ordem ?>
		<tr id="r_nu_ordem">
			<td><?php echo $metodologia->nu_ordem->FldCaption() ?></td>
			<td<?php echo $metodologia->nu_ordem->CellAttributes() ?>>
<span id="el_metodologia_nu_ordem" class="control-group">
<span<?php echo $metodologia->nu_ordem->ViewAttributes() ?>>
<?php echo $metodologia->nu_ordem->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
