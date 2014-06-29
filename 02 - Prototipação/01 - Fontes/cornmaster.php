<?php

// co_rn
?>
<?php if ($corn->Visible) { ?>
<table cellspacing="0" id="t_corn" class="ewGrid"><tr><td>
<table id="tbl_cornmaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($corn->co_rn->Visible) { // co_rn ?>
		<tr id="r_co_rn">
			<td><?php echo $corn->co_rn->FldCaption() ?></td>
			<td<?php echo $corn->co_rn->CellAttributes() ?>>
<span id="el_corn_co_rn" class="control-group">
<span<?php echo $corn->co_rn->ViewAttributes() ?>>
<?php echo $corn->co_rn->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
