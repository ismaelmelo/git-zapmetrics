<?php

// no_tpContagem
// ic_ativo

?>
<?php if ($tpcontagem->Visible) { ?>
<table cellspacing="0" id="t_tpcontagem" class="ewGrid"><tr><td>
<table id="tbl_tpcontagemmaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($tpcontagem->no_tpContagem->Visible) { // no_tpContagem ?>
		<tr id="r_no_tpContagem">
			<td><?php echo $tpcontagem->no_tpContagem->FldCaption() ?></td>
			<td<?php echo $tpcontagem->no_tpContagem->CellAttributes() ?>>
<span id="el_tpcontagem_no_tpContagem" class="control-group">
<span<?php echo $tpcontagem->no_tpContagem->ViewAttributes() ?>>
<?php echo $tpcontagem->no_tpContagem->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tpcontagem->ic_ativo->Visible) { // ic_ativo ?>
		<tr id="r_ic_ativo">
			<td><?php echo $tpcontagem->ic_ativo->FldCaption() ?></td>
			<td<?php echo $tpcontagem->ic_ativo->CellAttributes() ?>>
<span id="el_tpcontagem_ic_ativo" class="control-group">
<span<?php echo $tpcontagem->ic_ativo->ViewAttributes() ?>>
<?php echo $tpcontagem->ic_ativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
