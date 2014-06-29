<?php

// nu_indicador
// nu_versao
// ic_periodicidadeGeracao
// ic_reponsavelColetaCtrl
// dh_versao

?>
<?php if ($indicadorversao->Visible) { ?>
<table cellspacing="0" id="t_indicadorversao" class="ewGrid"><tr><td>
<table id="tbl_indicadorversaomaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($indicadorversao->nu_indicador->Visible) { // nu_indicador ?>
		<tr id="r_nu_indicador">
			<td><?php echo $indicadorversao->nu_indicador->FldCaption() ?></td>
			<td<?php echo $indicadorversao->nu_indicador->CellAttributes() ?>>
<span id="el_indicadorversao_nu_indicador" class="control-group">
<span<?php echo $indicadorversao->nu_indicador->ViewAttributes() ?>>
<?php echo $indicadorversao->nu_indicador->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($indicadorversao->nu_versao->Visible) { // nu_versao ?>
		<tr id="r_nu_versao">
			<td><?php echo $indicadorversao->nu_versao->FldCaption() ?></td>
			<td<?php echo $indicadorversao->nu_versao->CellAttributes() ?>>
<span id="el_indicadorversao_nu_versao" class="control-group">
<span<?php echo $indicadorversao->nu_versao->ViewAttributes() ?>>
<?php echo $indicadorversao->nu_versao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($indicadorversao->ic_periodicidadeGeracao->Visible) { // ic_periodicidadeGeracao ?>
		<tr id="r_ic_periodicidadeGeracao">
			<td><?php echo $indicadorversao->ic_periodicidadeGeracao->FldCaption() ?></td>
			<td<?php echo $indicadorversao->ic_periodicidadeGeracao->CellAttributes() ?>>
<span id="el_indicadorversao_ic_periodicidadeGeracao" class="control-group">
<span<?php echo $indicadorversao->ic_periodicidadeGeracao->ViewAttributes() ?>>
<?php echo $indicadorversao->ic_periodicidadeGeracao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($indicadorversao->ic_reponsavelColetaCtrl->Visible) { // ic_reponsavelColetaCtrl ?>
		<tr id="r_ic_reponsavelColetaCtrl">
			<td><?php echo $indicadorversao->ic_reponsavelColetaCtrl->FldCaption() ?></td>
			<td<?php echo $indicadorversao->ic_reponsavelColetaCtrl->CellAttributes() ?>>
<span id="el_indicadorversao_ic_reponsavelColetaCtrl" class="control-group">
<span<?php echo $indicadorversao->ic_reponsavelColetaCtrl->ViewAttributes() ?>>
<?php echo $indicadorversao->ic_reponsavelColetaCtrl->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($indicadorversao->dh_versao->Visible) { // dh_versao ?>
		<tr id="r_dh_versao">
			<td><?php echo $indicadorversao->dh_versao->FldCaption() ?></td>
			<td<?php echo $indicadorversao->dh_versao->CellAttributes() ?>>
<span id="el_indicadorversao_dh_versao" class="control-group">
<span<?php echo $indicadorversao->dh_versao->ViewAttributes() ?>>
<?php echo $indicadorversao->dh_versao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
