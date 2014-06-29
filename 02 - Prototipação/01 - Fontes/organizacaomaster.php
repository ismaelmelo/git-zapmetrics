<?php

// no_organizacao
// nu_verticalNegocio
// ic_ativo

?>
<?php if ($organizacao->Visible) { ?>
<table cellspacing="0" id="t_organizacao" class="ewGrid"><tr><td>
<table id="tbl_organizacaomaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($organizacao->no_organizacao->Visible) { // no_organizacao ?>
		<tr id="r_no_organizacao">
			<td><?php echo $organizacao->no_organizacao->FldCaption() ?></td>
			<td<?php echo $organizacao->no_organizacao->CellAttributes() ?>>
<span id="el_organizacao_no_organizacao" class="control-group">
<span<?php echo $organizacao->no_organizacao->ViewAttributes() ?>>
<?php echo $organizacao->no_organizacao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($organizacao->nu_verticalNegocio->Visible) { // nu_verticalNegocio ?>
		<tr id="r_nu_verticalNegocio">
			<td><?php echo $organizacao->nu_verticalNegocio->FldCaption() ?></td>
			<td<?php echo $organizacao->nu_verticalNegocio->CellAttributes() ?>>
<span id="el_organizacao_nu_verticalNegocio" class="control-group">
<span<?php echo $organizacao->nu_verticalNegocio->ViewAttributes() ?>>
<?php echo $organizacao->nu_verticalNegocio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($organizacao->ic_ativo->Visible) { // ic_ativo ?>
		<tr id="r_ic_ativo">
			<td><?php echo $organizacao->ic_ativo->FldCaption() ?></td>
			<td<?php echo $organizacao->ic_ativo->CellAttributes() ?>>
<span id="el_organizacao_ic_ativo" class="control-group">
<span<?php echo $organizacao->ic_ativo->ViewAttributes() ?>>
<?php echo $organizacao->ic_ativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
