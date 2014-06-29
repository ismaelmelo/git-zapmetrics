<?php

// co_questao
// no_questao

?>
<?php if ($ciiquestao->Visible) { ?>
<table cellspacing="0" id="t_ciiquestao" class="ewGrid"><tr><td>
<table id="tbl_ciiquestaomaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($ciiquestao->co_questao->Visible) { // co_questao ?>
		<tr id="r_co_questao">
			<td><?php echo $ciiquestao->co_questao->FldCaption() ?></td>
			<td<?php echo $ciiquestao->co_questao->CellAttributes() ?>>
<span id="el_ciiquestao_co_questao" class="control-group">
<span<?php echo $ciiquestao->co_questao->ViewAttributes() ?>>
<?php echo $ciiquestao->co_questao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($ciiquestao->no_questao->Visible) { // no_questao ?>
		<tr id="r_no_questao">
			<td><?php echo $ciiquestao->no_questao->FldCaption() ?></td>
			<td<?php echo $ciiquestao->no_questao->CellAttributes() ?>>
<span id="el_ciiquestao_no_questao" class="control-group">
<span<?php echo $ciiquestao->no_questao->ViewAttributes() ?>>
<?php echo $ciiquestao->no_questao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
