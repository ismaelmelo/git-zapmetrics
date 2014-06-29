<?php

// no_prospecto
// nu_categoriaProspecto
// vr_prioridade
// ar_entidade
// nu_area
// ar_nivel
// ds_sistemas
// ic_implicacaoLegal
// ic_risco
// vr_impacto
// vr_alinhamento
// vr_abrangencia
// vr_urgencia
// vr_duracao
// vr_tmpFila
// ic_stProspecto

?>
<?php if ($rprospresumo->Visible) { ?>
<table cellspacing="0" id="t_rprospresumo" class="ewGrid"><tr><td>
<table id="tbl_rprospresumomaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($rprospresumo->no_prospecto->Visible) { // no_prospecto ?>
		<tr id="r_no_prospecto">
			<td><?php echo $rprospresumo->no_prospecto->FldCaption() ?></td>
			<td<?php echo $rprospresumo->no_prospecto->CellAttributes() ?>>
<span id="el_rprospresumo_no_prospecto" class="control-group">
<span<?php echo $rprospresumo->no_prospecto->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($rprospresumo->no_prospecto->ListViewValue()) && $rprospresumo->no_prospecto->LinkAttributes() <> "") { ?>
<a<?php echo $rprospresumo->no_prospecto->LinkAttributes() ?>><?php echo $rprospresumo->no_prospecto->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $rprospresumo->no_prospecto->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($rprospresumo->nu_categoriaProspecto->Visible) { // nu_categoriaProspecto ?>
		<tr id="r_nu_categoriaProspecto">
			<td><?php echo $rprospresumo->nu_categoriaProspecto->FldCaption() ?></td>
			<td<?php echo $rprospresumo->nu_categoriaProspecto->CellAttributes() ?>>
<span id="el_rprospresumo_nu_categoriaProspecto" class="control-group">
<span<?php echo $rprospresumo->nu_categoriaProspecto->ViewAttributes() ?>>
<?php echo $rprospresumo->nu_categoriaProspecto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($rprospresumo->vr_prioridade->Visible) { // vr_prioridade ?>
		<tr id="r_vr_prioridade">
			<td><?php echo $rprospresumo->vr_prioridade->FldCaption() ?></td>
			<td<?php echo $rprospresumo->vr_prioridade->CellAttributes() ?>>
<span id="el_rprospresumo_vr_prioridade" class="control-group">
<span<?php echo $rprospresumo->vr_prioridade->ViewAttributes() ?>>
<?php echo $rprospresumo->vr_prioridade->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($rprospresumo->ar_entidade->Visible) { // ar_entidade ?>
		<tr id="r_ar_entidade">
			<td><?php echo $rprospresumo->ar_entidade->FldCaption() ?></td>
			<td<?php echo $rprospresumo->ar_entidade->CellAttributes() ?>>
<span id="el_rprospresumo_ar_entidade" class="control-group">
<span<?php echo $rprospresumo->ar_entidade->ViewAttributes() ?>>
<?php echo $rprospresumo->ar_entidade->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($rprospresumo->nu_area->Visible) { // nu_area ?>
		<tr id="r_nu_area">
			<td><?php echo $rprospresumo->nu_area->FldCaption() ?></td>
			<td<?php echo $rprospresumo->nu_area->CellAttributes() ?>>
<span id="el_rprospresumo_nu_area" class="control-group">
<span<?php echo $rprospresumo->nu_area->ViewAttributes() ?>>
<?php echo $rprospresumo->nu_area->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($rprospresumo->ar_nivel->Visible) { // ar_nivel ?>
		<tr id="r_ar_nivel">
			<td><?php echo $rprospresumo->ar_nivel->FldCaption() ?></td>
			<td<?php echo $rprospresumo->ar_nivel->CellAttributes() ?>>
<span id="el_rprospresumo_ar_nivel" class="control-group">
<span<?php echo $rprospresumo->ar_nivel->ViewAttributes() ?>>
<?php echo $rprospresumo->ar_nivel->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($rprospresumo->ds_sistemas->Visible) { // ds_sistemas ?>
		<tr id="r_ds_sistemas">
			<td><?php echo $rprospresumo->ds_sistemas->FldCaption() ?></td>
			<td<?php echo $rprospresumo->ds_sistemas->CellAttributes() ?>>
<span id="el_rprospresumo_ds_sistemas" class="control-group">
<span<?php echo $rprospresumo->ds_sistemas->ViewAttributes() ?>>
<?php echo $rprospresumo->ds_sistemas->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($rprospresumo->ic_implicacaoLegal->Visible) { // ic_implicacaoLegal ?>
		<tr id="r_ic_implicacaoLegal">
			<td><?php echo $rprospresumo->ic_implicacaoLegal->FldCaption() ?></td>
			<td<?php echo $rprospresumo->ic_implicacaoLegal->CellAttributes() ?>>
<span id="el_rprospresumo_ic_implicacaoLegal" class="control-group">
<span<?php echo $rprospresumo->ic_implicacaoLegal->ViewAttributes() ?>>
<?php echo $rprospresumo->ic_implicacaoLegal->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($rprospresumo->ic_risco->Visible) { // ic_risco ?>
		<tr id="r_ic_risco">
			<td><?php echo $rprospresumo->ic_risco->FldCaption() ?></td>
			<td<?php echo $rprospresumo->ic_risco->CellAttributes() ?>>
<span id="el_rprospresumo_ic_risco" class="control-group">
<span<?php echo $rprospresumo->ic_risco->ViewAttributes() ?>>
<?php echo $rprospresumo->ic_risco->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($rprospresumo->vr_impacto->Visible) { // vr_impacto ?>
		<tr id="r_vr_impacto">
			<td><?php echo $rprospresumo->vr_impacto->FldCaption() ?></td>
			<td<?php echo $rprospresumo->vr_impacto->CellAttributes() ?>>
<span id="el_rprospresumo_vr_impacto" class="control-group">
<span<?php echo $rprospresumo->vr_impacto->ViewAttributes() ?>>
<?php echo $rprospresumo->vr_impacto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($rprospresumo->vr_alinhamento->Visible) { // vr_alinhamento ?>
		<tr id="r_vr_alinhamento">
			<td><?php echo $rprospresumo->vr_alinhamento->FldCaption() ?></td>
			<td<?php echo $rprospresumo->vr_alinhamento->CellAttributes() ?>>
<span id="el_rprospresumo_vr_alinhamento" class="control-group">
<span<?php echo $rprospresumo->vr_alinhamento->ViewAttributes() ?>>
<?php echo $rprospresumo->vr_alinhamento->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($rprospresumo->vr_abrangencia->Visible) { // vr_abrangencia ?>
		<tr id="r_vr_abrangencia">
			<td><?php echo $rprospresumo->vr_abrangencia->FldCaption() ?></td>
			<td<?php echo $rprospresumo->vr_abrangencia->CellAttributes() ?>>
<span id="el_rprospresumo_vr_abrangencia" class="control-group">
<span<?php echo $rprospresumo->vr_abrangencia->ViewAttributes() ?>>
<?php echo $rprospresumo->vr_abrangencia->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($rprospresumo->vr_urgencia->Visible) { // vr_urgencia ?>
		<tr id="r_vr_urgencia">
			<td><?php echo $rprospresumo->vr_urgencia->FldCaption() ?></td>
			<td<?php echo $rprospresumo->vr_urgencia->CellAttributes() ?>>
<span id="el_rprospresumo_vr_urgencia" class="control-group">
<span<?php echo $rprospresumo->vr_urgencia->ViewAttributes() ?>>
<?php echo $rprospresumo->vr_urgencia->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($rprospresumo->vr_duracao->Visible) { // vr_duracao ?>
		<tr id="r_vr_duracao">
			<td><?php echo $rprospresumo->vr_duracao->FldCaption() ?></td>
			<td<?php echo $rprospresumo->vr_duracao->CellAttributes() ?>>
<span id="el_rprospresumo_vr_duracao" class="control-group">
<span<?php echo $rprospresumo->vr_duracao->ViewAttributes() ?>>
<?php echo $rprospresumo->vr_duracao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($rprospresumo->vr_tmpFila->Visible) { // vr_tmpFila ?>
		<tr id="r_vr_tmpFila">
			<td><?php echo $rprospresumo->vr_tmpFila->FldCaption() ?></td>
			<td<?php echo $rprospresumo->vr_tmpFila->CellAttributes() ?>>
<span id="el_rprospresumo_vr_tmpFila" class="control-group">
<span<?php echo $rprospresumo->vr_tmpFila->ViewAttributes() ?>>
<?php echo $rprospresumo->vr_tmpFila->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($rprospresumo->ic_stProspecto->Visible) { // ic_stProspecto ?>
		<tr id="r_ic_stProspecto">
			<td><?php echo $rprospresumo->ic_stProspecto->FldCaption() ?></td>
			<td<?php echo $rprospresumo->ic_stProspecto->CellAttributes() ?>>
<span id="el_rprospresumo_ic_stProspecto" class="control-group">
<span<?php echo $rprospresumo->ic_stProspecto->ViewAttributes() ?>>
<?php echo $rprospresumo->ic_stProspecto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
