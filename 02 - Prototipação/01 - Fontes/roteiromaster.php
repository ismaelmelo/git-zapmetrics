<?php

// nu_metodologia
// no_roteiro
// ic_ativo
// nu_ordem

?>
<?php if ($roteiro->Visible) { ?>
<table cellspacing="0" id="t_roteiro" class="ewGrid"><tr><td>
<table id="tbl_roteiromaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($roteiro->nu_metodologia->Visible) { // nu_metodologia ?>
		<tr id="r_nu_metodologia">
			<td><?php echo $roteiro->nu_metodologia->FldCaption() ?></td>
			<td<?php echo $roteiro->nu_metodologia->CellAttributes() ?>>
<span id="el_roteiro_nu_metodologia" class="control-group">
<span<?php echo $roteiro->nu_metodologia->ViewAttributes() ?>>
<?php echo $roteiro->nu_metodologia->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($roteiro->no_roteiro->Visible) { // no_roteiro ?>
		<tr id="r_no_roteiro">
			<td><?php echo $roteiro->no_roteiro->FldCaption() ?></td>
			<td<?php echo $roteiro->no_roteiro->CellAttributes() ?>>
<span id="el_roteiro_no_roteiro" class="control-group">
<span<?php echo $roteiro->no_roteiro->ViewAttributes() ?>>
<?php echo $roteiro->no_roteiro->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($roteiro->ic_ativo->Visible) { // ic_ativo ?>
		<tr id="r_ic_ativo">
			<td><?php echo $roteiro->ic_ativo->FldCaption() ?></td>
			<td<?php echo $roteiro->ic_ativo->CellAttributes() ?>>
<span id="el_roteiro_ic_ativo" class="control-group">
<span<?php echo $roteiro->ic_ativo->ViewAttributes() ?>>
<?php echo $roteiro->ic_ativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($roteiro->nu_ordem->Visible) { // nu_ordem ?>
		<tr id="r_nu_ordem">
			<td><?php echo $roteiro->nu_ordem->FldCaption() ?></td>
			<td<?php echo $roteiro->nu_ordem->CellAttributes() ?>>
<span id="el_roteiro_nu_ordem" class="control-group">
<span<?php echo $roteiro->nu_ordem->ViewAttributes() ?>>
<?php echo $roteiro->nu_ordem->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
