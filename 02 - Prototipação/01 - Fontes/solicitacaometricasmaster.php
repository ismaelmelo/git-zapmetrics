<?php

// nu_solMetricas
// nu_tpSolicitacao
// nu_projeto
// ic_stSolicitacao
// nu_usuarioAlterou
// dt_stSolicitacao
// qt_pfTotal
// vr_pfContForn

?>
<?php if ($solicitacaoMetricas->Visible) { ?>
<table cellspacing="0" id="t_solicitacaoMetricas" class="ewGrid"><tr><td>
<table id="tbl_solicitacaoMetricasmaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($solicitacaoMetricas->nu_solMetricas->Visible) { // nu_solMetricas ?>
		<tr id="r_nu_solMetricas">
			<td><?php echo $solicitacaoMetricas->nu_solMetricas->FldCaption() ?></td>
			<td<?php echo $solicitacaoMetricas->nu_solMetricas->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_nu_solMetricas" class="control-group">
<span<?php echo $solicitacaoMetricas->nu_solMetricas->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_solMetricas->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->nu_tpSolicitacao->Visible) { // nu_tpSolicitacao ?>
		<tr id="r_nu_tpSolicitacao">
			<td><?php echo $solicitacaoMetricas->nu_tpSolicitacao->FldCaption() ?></td>
			<td<?php echo $solicitacaoMetricas->nu_tpSolicitacao->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_nu_tpSolicitacao" class="control-group">
<span<?php echo $solicitacaoMetricas->nu_tpSolicitacao->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_tpSolicitacao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->nu_projeto->Visible) { // nu_projeto ?>
		<tr id="r_nu_projeto">
			<td><?php echo $solicitacaoMetricas->nu_projeto->FldCaption() ?></td>
			<td<?php echo $solicitacaoMetricas->nu_projeto->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_nu_projeto" class="control-group">
<span<?php echo $solicitacaoMetricas->nu_projeto->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_projeto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->ic_stSolicitacao->Visible) { // ic_stSolicitacao ?>
		<tr id="r_ic_stSolicitacao">
			<td><?php echo $solicitacaoMetricas->ic_stSolicitacao->FldCaption() ?></td>
			<td<?php echo $solicitacaoMetricas->ic_stSolicitacao->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_ic_stSolicitacao" class="control-group">
<span<?php echo $solicitacaoMetricas->ic_stSolicitacao->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->ic_stSolicitacao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->nu_usuarioAlterou->Visible) { // nu_usuarioAlterou ?>
		<tr id="r_nu_usuarioAlterou">
			<td><?php echo $solicitacaoMetricas->nu_usuarioAlterou->FldCaption() ?></td>
			<td<?php echo $solicitacaoMetricas->nu_usuarioAlterou->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_nu_usuarioAlterou" class="control-group">
<span<?php echo $solicitacaoMetricas->nu_usuarioAlterou->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_usuarioAlterou->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->dt_stSolicitacao->Visible) { // dt_stSolicitacao ?>
		<tr id="r_dt_stSolicitacao">
			<td><?php echo $solicitacaoMetricas->dt_stSolicitacao->FldCaption() ?></td>
			<td<?php echo $solicitacaoMetricas->dt_stSolicitacao->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_dt_stSolicitacao" class="control-group">
<span<?php echo $solicitacaoMetricas->dt_stSolicitacao->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->dt_stSolicitacao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->qt_pfTotal->Visible) { // qt_pfTotal ?>
		<tr id="r_qt_pfTotal">
			<td><?php echo $solicitacaoMetricas->qt_pfTotal->FldCaption() ?></td>
			<td<?php echo $solicitacaoMetricas->qt_pfTotal->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_qt_pfTotal" class="control-group">
<span<?php echo $solicitacaoMetricas->qt_pfTotal->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->qt_pfTotal->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->vr_pfContForn->Visible) { // vr_pfContForn ?>
		<tr id="r_vr_pfContForn">
			<td><?php echo $solicitacaoMetricas->vr_pfContForn->FldCaption() ?></td>
			<td<?php echo $solicitacaoMetricas->vr_pfContForn->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_vr_pfContForn" class="control-group">
<span<?php echo $solicitacaoMetricas->vr_pfContForn->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->vr_pfContForn->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
