<?php

// no_tpManutencao
// ic_modeloCalculo
// ic_utilizaFaseRoteiroCalculo
// nu_parametro
// ic_ativo

?>
<?php if ($tpmanutencao->Visible) { ?>
<table cellspacing="0" id="t_tpmanutencao" class="ewGrid"><tr><td>
<table id="tbl_tpmanutencaomaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($tpmanutencao->no_tpManutencao->Visible) { // no_tpManutencao ?>
		<tr id="r_no_tpManutencao">
			<td><?php echo $tpmanutencao->no_tpManutencao->FldCaption() ?></td>
			<td<?php echo $tpmanutencao->no_tpManutencao->CellAttributes() ?>>
<span id="el_tpmanutencao_no_tpManutencao" class="control-group">
<span<?php echo $tpmanutencao->no_tpManutencao->ViewAttributes() ?>>
<?php echo $tpmanutencao->no_tpManutencao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tpmanutencao->ic_modeloCalculo->Visible) { // ic_modeloCalculo ?>
		<tr id="r_ic_modeloCalculo">
			<td><?php echo $tpmanutencao->ic_modeloCalculo->FldCaption() ?></td>
			<td<?php echo $tpmanutencao->ic_modeloCalculo->CellAttributes() ?>>
<span id="el_tpmanutencao_ic_modeloCalculo" class="control-group">
<span<?php echo $tpmanutencao->ic_modeloCalculo->ViewAttributes() ?>>
<?php echo $tpmanutencao->ic_modeloCalculo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tpmanutencao->ic_utilizaFaseRoteiroCalculo->Visible) { // ic_utilizaFaseRoteiroCalculo ?>
		<tr id="r_ic_utilizaFaseRoteiroCalculo">
			<td><?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->FldCaption() ?></td>
			<td<?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->CellAttributes() ?>>
<span id="el_tpmanutencao_ic_utilizaFaseRoteiroCalculo" class="control-group">
<span<?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->ViewAttributes() ?>>
<?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tpmanutencao->nu_parametro->Visible) { // nu_parametro ?>
		<tr id="r_nu_parametro">
			<td><?php echo $tpmanutencao->nu_parametro->FldCaption() ?></td>
			<td<?php echo $tpmanutencao->nu_parametro->CellAttributes() ?>>
<span id="el_tpmanutencao_nu_parametro" class="control-group">
<span<?php echo $tpmanutencao->nu_parametro->ViewAttributes() ?>>
<?php echo $tpmanutencao->nu_parametro->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tpmanutencao->ic_ativo->Visible) { // ic_ativo ?>
		<tr id="r_ic_ativo">
			<td><?php echo $tpmanutencao->ic_ativo->FldCaption() ?></td>
			<td<?php echo $tpmanutencao->ic_ativo->CellAttributes() ?>>
<span id="el_tpmanutencao_ic_ativo" class="control-group">
<span<?php echo $tpmanutencao->ic_ativo->ViewAttributes() ?>>
<?php echo $tpmanutencao->ic_ativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
