<?php

// nu_indicador
// nu_versao
// dh_geracao
// vr_indicadorNumerico
// vr_indicadorTexto

?>
<?php if ($indicadorvalor->Visible) { ?>
<table cellspacing="0" id="t_indicadorvalor" class="ewGrid"><tr><td>
<table id="tbl_indicadorvalormaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($indicadorvalor->nu_indicador->Visible) { // nu_indicador ?>
		<tr id="r_nu_indicador">
			<td><?php echo $indicadorvalor->nu_indicador->FldCaption() ?></td>
			<td<?php echo $indicadorvalor->nu_indicador->CellAttributes() ?>>
<span id="el_indicadorvalor_nu_indicador" class="control-group">
<span<?php echo $indicadorvalor->nu_indicador->ViewAttributes() ?>>
<?php echo $indicadorvalor->nu_indicador->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($indicadorvalor->nu_versao->Visible) { // nu_versao ?>
		<tr id="r_nu_versao">
			<td><?php echo $indicadorvalor->nu_versao->FldCaption() ?></td>
			<td<?php echo $indicadorvalor->nu_versao->CellAttributes() ?>>
<span id="el_indicadorvalor_nu_versao" class="control-group">
<span<?php echo $indicadorvalor->nu_versao->ViewAttributes() ?>>
<?php echo $indicadorvalor->nu_versao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($indicadorvalor->dh_geracao->Visible) { // dh_geracao ?>
		<tr id="r_dh_geracao">
			<td><?php echo $indicadorvalor->dh_geracao->FldCaption() ?></td>
			<td<?php echo $indicadorvalor->dh_geracao->CellAttributes() ?>>
<span id="el_indicadorvalor_dh_geracao" class="control-group">
<span<?php echo $indicadorvalor->dh_geracao->ViewAttributes() ?>>
<?php echo $indicadorvalor->dh_geracao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($indicadorvalor->vr_indicadorNumerico->Visible) { // vr_indicadorNumerico ?>
		<tr id="r_vr_indicadorNumerico">
			<td><?php echo $indicadorvalor->vr_indicadorNumerico->FldCaption() ?></td>
			<td<?php echo $indicadorvalor->vr_indicadorNumerico->CellAttributes() ?>>
<span id="el_indicadorvalor_vr_indicadorNumerico" class="control-group">
<span<?php echo $indicadorvalor->vr_indicadorNumerico->ViewAttributes() ?>>
<?php echo $indicadorvalor->vr_indicadorNumerico->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($indicadorvalor->vr_indicadorTexto->Visible) { // vr_indicadorTexto ?>
		<tr id="r_vr_indicadorTexto">
			<td><?php echo $indicadorvalor->vr_indicadorTexto->FldCaption() ?></td>
			<td<?php echo $indicadorvalor->vr_indicadorTexto->CellAttributes() ?>>
<span id="el_indicadorvalor_vr_indicadorTexto" class="control-group">
<span<?php echo $indicadorvalor->vr_indicadorTexto->ViewAttributes() ?>>
<?php echo $indicadorvalor->vr_indicadorTexto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
