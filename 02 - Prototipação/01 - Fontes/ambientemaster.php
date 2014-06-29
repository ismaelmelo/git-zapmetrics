<?php

// no_ambiente
// nu_tpNegocio
// nu_plataforma
// nu_tpSistema
// nu_roteiro
// ic_ativo
// nu_ordem

?>
<?php if ($ambiente->Visible) { ?>
<table cellspacing="0" id="t_ambiente" class="ewGrid"><tr><td>
<table id="tbl_ambientemaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($ambiente->no_ambiente->Visible) { // no_ambiente ?>
		<tr id="r_no_ambiente">
			<td><?php echo $ambiente->no_ambiente->FldCaption() ?></td>
			<td<?php echo $ambiente->no_ambiente->CellAttributes() ?>>
<span id="el_ambiente_no_ambiente" class="control-group">
<span<?php echo $ambiente->no_ambiente->ViewAttributes() ?>>
<?php echo $ambiente->no_ambiente->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($ambiente->nu_tpNegocio->Visible) { // nu_tpNegocio ?>
		<tr id="r_nu_tpNegocio">
			<td><?php echo $ambiente->nu_tpNegocio->FldCaption() ?></td>
			<td<?php echo $ambiente->nu_tpNegocio->CellAttributes() ?>>
<span id="el_ambiente_nu_tpNegocio" class="control-group">
<span<?php echo $ambiente->nu_tpNegocio->ViewAttributes() ?>>
<?php echo $ambiente->nu_tpNegocio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($ambiente->nu_plataforma->Visible) { // nu_plataforma ?>
		<tr id="r_nu_plataforma">
			<td><?php echo $ambiente->nu_plataforma->FldCaption() ?></td>
			<td<?php echo $ambiente->nu_plataforma->CellAttributes() ?>>
<span id="el_ambiente_nu_plataforma" class="control-group">
<span<?php echo $ambiente->nu_plataforma->ViewAttributes() ?>>
<?php echo $ambiente->nu_plataforma->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($ambiente->nu_tpSistema->Visible) { // nu_tpSistema ?>
		<tr id="r_nu_tpSistema">
			<td><?php echo $ambiente->nu_tpSistema->FldCaption() ?></td>
			<td<?php echo $ambiente->nu_tpSistema->CellAttributes() ?>>
<span id="el_ambiente_nu_tpSistema" class="control-group">
<span<?php echo $ambiente->nu_tpSistema->ViewAttributes() ?>>
<?php echo $ambiente->nu_tpSistema->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($ambiente->nu_roteiro->Visible) { // nu_roteiro ?>
		<tr id="r_nu_roteiro">
			<td><?php echo $ambiente->nu_roteiro->FldCaption() ?></td>
			<td<?php echo $ambiente->nu_roteiro->CellAttributes() ?>>
<span id="el_ambiente_nu_roteiro" class="control-group">
<span<?php echo $ambiente->nu_roteiro->ViewAttributes() ?>>
<?php echo $ambiente->nu_roteiro->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($ambiente->ic_ativo->Visible) { // ic_ativo ?>
		<tr id="r_ic_ativo">
			<td><?php echo $ambiente->ic_ativo->FldCaption() ?></td>
			<td<?php echo $ambiente->ic_ativo->CellAttributes() ?>>
<span id="el_ambiente_ic_ativo" class="control-group">
<span<?php echo $ambiente->ic_ativo->ViewAttributes() ?>>
<?php echo $ambiente->ic_ativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($ambiente->nu_ordem->Visible) { // nu_ordem ?>
		<tr id="r_nu_ordem">
			<td><?php echo $ambiente->nu_ordem->FldCaption() ?></td>
			<td<?php echo $ambiente->nu_ordem->CellAttributes() ?>>
<span id="el_ambiente_nu_ordem" class="control-group">
<span<?php echo $ambiente->nu_ordem->ViewAttributes() ?>>
<?php echo $ambiente->nu_ordem->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
