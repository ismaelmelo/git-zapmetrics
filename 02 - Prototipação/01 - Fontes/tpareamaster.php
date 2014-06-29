<?php

// no_tpArea
// ic_ativo

?>
<?php if ($tparea->Visible) { ?>
<table cellspacing="0" id="t_tparea" class="ewGrid"><tr><td>
<table id="tbl_tpareamaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($tparea->no_tpArea->Visible) { // no_tpArea ?>
		<tr id="r_no_tpArea">
			<td><?php echo $tparea->no_tpArea->FldCaption() ?></td>
			<td<?php echo $tparea->no_tpArea->CellAttributes() ?>>
<span id="el_tparea_no_tpArea" class="control-group">
<span<?php echo $tparea->no_tpArea->ViewAttributes() ?>>
<?php echo $tparea->no_tpArea->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tparea->ic_ativo->Visible) { // ic_ativo ?>
		<tr id="r_ic_ativo">
			<td><?php echo $tparea->ic_ativo->FldCaption() ?></td>
			<td<?php echo $tparea->ic_ativo->CellAttributes() ?>>
<span id="el_tparea_ic_ativo" class="control-group">
<span<?php echo $tparea->ic_ativo->ViewAttributes() ?>>
<?php echo $tparea->ic_ativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
