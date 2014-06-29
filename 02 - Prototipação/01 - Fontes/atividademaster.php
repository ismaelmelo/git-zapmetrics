<?php

// no_atividade
// vr_duracao
// ic_ativo

?>
<?php if ($atividade->Visible) { ?>
<table cellspacing="0" id="t_atividade" class="ewGrid"><tr><td>
<table id="tbl_atividademaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($atividade->no_atividade->Visible) { // no_atividade ?>
		<tr id="r_no_atividade">
			<td><?php echo $atividade->no_atividade->FldCaption() ?></td>
			<td<?php echo $atividade->no_atividade->CellAttributes() ?>>
<span id="el_atividade_no_atividade" class="control-group">
<span<?php echo $atividade->no_atividade->ViewAttributes() ?>>
<?php echo $atividade->no_atividade->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($atividade->vr_duracao->Visible) { // vr_duracao ?>
		<tr id="r_vr_duracao">
			<td><?php echo $atividade->vr_duracao->FldCaption() ?></td>
			<td<?php echo $atividade->vr_duracao->CellAttributes() ?>>
<span id="el_atividade_vr_duracao" class="control-group">
<span<?php echo $atividade->vr_duracao->ViewAttributes() ?>>
<?php echo $atividade->vr_duracao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($atividade->ic_ativo->Visible) { // ic_ativo ?>
		<tr id="r_ic_ativo">
			<td><?php echo $atividade->ic_ativo->FldCaption() ?></td>
			<td<?php echo $atividade->ic_ativo->CellAttributes() ?>>
<span id="el_atividade_ic_ativo" class="control-group">
<span<?php echo $atividade->ic_ativo->ViewAttributes() ?>>
<?php echo $atividade->ic_ativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
