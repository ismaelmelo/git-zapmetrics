<?php

// co_alternativo
// no_sistema
// nu_fornecedor
// nu_stSistema
// ic_ativo

?>
<?php if ($sistema->Visible) { ?>
<table cellspacing="0" id="t_sistema" class="ewGrid"><tr><td>
<table id="tbl_sistemamaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($sistema->co_alternativo->Visible) { // co_alternativo ?>
		<tr id="r_co_alternativo">
			<td><?php echo $sistema->co_alternativo->FldCaption() ?></td>
			<td<?php echo $sistema->co_alternativo->CellAttributes() ?>>
<span id="el_sistema_co_alternativo" class="control-group">
<span<?php echo $sistema->co_alternativo->ViewAttributes() ?>>
<?php echo $sistema->co_alternativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sistema->no_sistema->Visible) { // no_sistema ?>
		<tr id="r_no_sistema">
			<td><?php echo $sistema->no_sistema->FldCaption() ?></td>
			<td<?php echo $sistema->no_sistema->CellAttributes() ?>>
<span id="el_sistema_no_sistema" class="control-group">
<span<?php echo $sistema->no_sistema->ViewAttributes() ?>>
<?php echo $sistema->no_sistema->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sistema->nu_fornecedor->Visible) { // nu_fornecedor ?>
		<tr id="r_nu_fornecedor">
			<td><?php echo $sistema->nu_fornecedor->FldCaption() ?></td>
			<td<?php echo $sistema->nu_fornecedor->CellAttributes() ?>>
<span id="el_sistema_nu_fornecedor" class="control-group">
<span<?php echo $sistema->nu_fornecedor->ViewAttributes() ?>>
<?php echo $sistema->nu_fornecedor->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sistema->nu_stSistema->Visible) { // nu_stSistema ?>
		<tr id="r_nu_stSistema">
			<td><?php echo $sistema->nu_stSistema->FldCaption() ?></td>
			<td<?php echo $sistema->nu_stSistema->CellAttributes() ?>>
<span id="el_sistema_nu_stSistema" class="control-group">
<span<?php echo $sistema->nu_stSistema->ViewAttributes() ?>>
<?php echo $sistema->nu_stSistema->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sistema->ic_ativo->Visible) { // ic_ativo ?>
		<tr id="r_ic_ativo">
			<td><?php echo $sistema->ic_ativo->FldCaption() ?></td>
			<td<?php echo $sistema->ic_ativo->CellAttributes() ?>>
<span id="el_sistema_ic_ativo" class="control-group">
<span<?php echo $sistema->ic_ativo->ViewAttributes() ?>>
<?php echo $sistema->ic_ativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
