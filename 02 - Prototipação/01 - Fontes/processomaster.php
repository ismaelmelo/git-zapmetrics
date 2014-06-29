<?php

// nu_divisao
// no_processo
// ic_ativo

?>
<?php if ($processo->Visible) { ?>
<table cellspacing="0" id="t_processo" class="ewGrid"><tr><td>
<table id="tbl_processomaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($processo->nu_divisao->Visible) { // nu_divisao ?>
		<tr id="r_nu_divisao">
			<td><?php echo $processo->nu_divisao->FldCaption() ?></td>
			<td<?php echo $processo->nu_divisao->CellAttributes() ?>>
<span id="el_processo_nu_divisao" class="control-group">
<span<?php echo $processo->nu_divisao->ViewAttributes() ?>>
<?php echo $processo->nu_divisao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($processo->no_processo->Visible) { // no_processo ?>
		<tr id="r_no_processo">
			<td><?php echo $processo->no_processo->FldCaption() ?></td>
			<td<?php echo $processo->no_processo->CellAttributes() ?>>
<span id="el_processo_no_processo" class="control-group">
<span<?php echo $processo->no_processo->ViewAttributes() ?>>
<?php echo $processo->no_processo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($processo->ic_ativo->Visible) { // ic_ativo ?>
		<tr id="r_ic_ativo">
			<td><?php echo $processo->ic_ativo->FldCaption() ?></td>
			<td<?php echo $processo->ic_ativo->CellAttributes() ?>>
<span id="el_processo_ic_ativo" class="control-group">
<span<?php echo $processo->ic_ativo->ViewAttributes() ?>>
<?php echo $processo->ic_ativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
