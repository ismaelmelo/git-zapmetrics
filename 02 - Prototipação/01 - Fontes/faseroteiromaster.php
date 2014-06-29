<?php

// no_faseRoteiro
// pc_distribuicao
// ic_ativo
// nu_ordem

?>
<?php if ($faseroteiro->Visible) { ?>
<table cellspacing="0" id="t_faseroteiro" class="ewGrid"><tr><td>
<table id="tbl_faseroteiromaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($faseroteiro->no_faseRoteiro->Visible) { // no_faseRoteiro ?>
		<tr id="r_no_faseRoteiro">
			<td><?php echo $faseroteiro->no_faseRoteiro->FldCaption() ?></td>
			<td<?php echo $faseroteiro->no_faseRoteiro->CellAttributes() ?>>
<span id="el_faseroteiro_no_faseRoteiro" class="control-group">
<span<?php echo $faseroteiro->no_faseRoteiro->ViewAttributes() ?>>
<?php echo $faseroteiro->no_faseRoteiro->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($faseroteiro->pc_distribuicao->Visible) { // pc_distribuicao ?>
		<tr id="r_pc_distribuicao">
			<td><?php echo $faseroteiro->pc_distribuicao->FldCaption() ?></td>
			<td<?php echo $faseroteiro->pc_distribuicao->CellAttributes() ?>>
<span id="el_faseroteiro_pc_distribuicao" class="control-group">
<span<?php echo $faseroteiro->pc_distribuicao->ViewAttributes() ?>>
<?php echo $faseroteiro->pc_distribuicao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($faseroteiro->ic_ativo->Visible) { // ic_ativo ?>
		<tr id="r_ic_ativo">
			<td><?php echo $faseroteiro->ic_ativo->FldCaption() ?></td>
			<td<?php echo $faseroteiro->ic_ativo->CellAttributes() ?>>
<span id="el_faseroteiro_ic_ativo" class="control-group">
<span<?php echo $faseroteiro->ic_ativo->ViewAttributes() ?>>
<?php echo $faseroteiro->ic_ativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($faseroteiro->nu_ordem->Visible) { // nu_ordem ?>
		<tr id="r_nu_ordem">
			<td><?php echo $faseroteiro->nu_ordem->FldCaption() ?></td>
			<td<?php echo $faseroteiro->nu_ordem->CellAttributes() ?>>
<span id="el_faseroteiro_nu_ordem" class="control-group">
<span<?php echo $faseroteiro->nu_ordem->ViewAttributes() ?>>
<?php echo $faseroteiro->nu_ordem->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
