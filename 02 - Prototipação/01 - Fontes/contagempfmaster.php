<?php

// nu_contagem
// nu_tpMetrica
// nu_tpContagem
// nu_sistema
// nu_faseMedida
// nu_usuarioLogado
// ic_stContagem
// pc_varFasesRoteiro
// vr_pfFaturamento

?>
<?php if ($contagempf->Visible) { ?>
<table cellspacing="0" id="t_contagempf" class="ewGrid"><tr><td>
<table id="tbl_contagempfmaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($contagempf->nu_contagem->Visible) { // nu_contagem ?>
		<tr id="r_nu_contagem">
			<td><?php echo $contagempf->nu_contagem->FldCaption() ?></td>
			<td<?php echo $contagempf->nu_contagem->CellAttributes() ?>>
<span id="el_contagempf_nu_contagem" class="control-group">
<span<?php echo $contagempf->nu_contagem->ViewAttributes() ?>>
<?php echo $contagempf->nu_contagem->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($contagempf->nu_tpMetrica->Visible) { // nu_tpMetrica ?>
		<tr id="r_nu_tpMetrica">
			<td><?php echo $contagempf->nu_tpMetrica->FldCaption() ?></td>
			<td<?php echo $contagempf->nu_tpMetrica->CellAttributes() ?>>
<span id="el_contagempf_nu_tpMetrica" class="control-group">
<span<?php echo $contagempf->nu_tpMetrica->ViewAttributes() ?>>
<?php echo $contagempf->nu_tpMetrica->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($contagempf->nu_tpContagem->Visible) { // nu_tpContagem ?>
		<tr id="r_nu_tpContagem">
			<td><?php echo $contagempf->nu_tpContagem->FldCaption() ?></td>
			<td<?php echo $contagempf->nu_tpContagem->CellAttributes() ?>>
<span id="el_contagempf_nu_tpContagem" class="control-group">
<span<?php echo $contagempf->nu_tpContagem->ViewAttributes() ?>>
<?php echo $contagempf->nu_tpContagem->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($contagempf->nu_sistema->Visible) { // nu_sistema ?>
		<tr id="r_nu_sistema">
			<td><?php echo $contagempf->nu_sistema->FldCaption() ?></td>
			<td<?php echo $contagempf->nu_sistema->CellAttributes() ?>>
<span id="el_contagempf_nu_sistema" class="control-group">
<span<?php echo $contagempf->nu_sistema->ViewAttributes() ?>>
<?php echo $contagempf->nu_sistema->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($contagempf->nu_faseMedida->Visible) { // nu_faseMedida ?>
		<tr id="r_nu_faseMedida">
			<td><?php echo $contagempf->nu_faseMedida->FldCaption() ?></td>
			<td<?php echo $contagempf->nu_faseMedida->CellAttributes() ?>>
<span id="el_contagempf_nu_faseMedida" class="control-group">
<span<?php echo $contagempf->nu_faseMedida->ViewAttributes() ?>>
<?php echo $contagempf->nu_faseMedida->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($contagempf->nu_usuarioLogado->Visible) { // nu_usuarioLogado ?>
		<tr id="r_nu_usuarioLogado">
			<td><?php echo $contagempf->nu_usuarioLogado->FldCaption() ?></td>
			<td<?php echo $contagempf->nu_usuarioLogado->CellAttributes() ?>>
<span id="el_contagempf_nu_usuarioLogado" class="control-group">
<span<?php echo $contagempf->nu_usuarioLogado->ViewAttributes() ?>>
<?php echo $contagempf->nu_usuarioLogado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($contagempf->ic_stContagem->Visible) { // ic_stContagem ?>
		<tr id="r_ic_stContagem">
			<td><?php echo $contagempf->ic_stContagem->FldCaption() ?></td>
			<td<?php echo $contagempf->ic_stContagem->CellAttributes() ?>>
<span id="el_contagempf_ic_stContagem" class="control-group">
<span<?php echo $contagempf->ic_stContagem->ViewAttributes() ?>>
<?php echo $contagempf->ic_stContagem->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($contagempf->pc_varFasesRoteiro->Visible) { // pc_varFasesRoteiro ?>
		<tr id="r_pc_varFasesRoteiro">
			<td><?php echo $contagempf->pc_varFasesRoteiro->FldCaption() ?></td>
			<td<?php echo $contagempf->pc_varFasesRoteiro->CellAttributes() ?>>
<span id="el_contagempf_pc_varFasesRoteiro" class="control-group">
<span<?php echo $contagempf->pc_varFasesRoteiro->ViewAttributes() ?>>
<?php echo $contagempf->pc_varFasesRoteiro->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($contagempf->vr_pfFaturamento->Visible) { // vr_pfFaturamento ?>
		<tr id="r_vr_pfFaturamento">
			<td><?php echo $contagempf->vr_pfFaturamento->FldCaption() ?></td>
			<td<?php echo $contagempf->vr_pfFaturamento->CellAttributes() ?>>
<span id="el_contagempf_vr_pfFaturamento" class="control-group">
<span<?php echo $contagempf->vr_pfFaturamento->ViewAttributes() ?>>
<?php echo $contagempf->vr_pfFaturamento->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
