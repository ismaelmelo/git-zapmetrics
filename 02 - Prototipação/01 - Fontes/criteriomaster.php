<?php

// no_criterioPrioridade
// vr_pesoCriterio

?>
<?php if ($criterio->Visible) { ?>
<table cellspacing="0" id="t_criterio" class="ewGrid"><tr><td>
<table id="tbl_criteriomaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($criterio->no_criterioPrioridade->Visible) { // no_criterioPrioridade ?>
		<tr id="r_no_criterioPrioridade">
			<td><?php echo $criterio->no_criterioPrioridade->FldCaption() ?></td>
			<td<?php echo $criterio->no_criterioPrioridade->CellAttributes() ?>>
<span id="el_criterio_no_criterioPrioridade" class="control-group">
<span<?php echo $criterio->no_criterioPrioridade->ViewAttributes() ?>>
<?php echo $criterio->no_criterioPrioridade->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($criterio->vr_pesoCriterio->Visible) { // vr_pesoCriterio ?>
		<tr id="r_vr_pesoCriterio">
			<td><?php echo $criterio->vr_pesoCriterio->FldCaption() ?></td>
			<td<?php echo $criterio->vr_pesoCriterio->CellAttributes() ?>>
<span id="el_criterio_vr_pesoCriterio" class="control-group">
<span<?php echo $criterio->vr_pesoCriterio->ViewAttributes() ?>>
<?php echo $criterio->vr_pesoCriterio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
