<?php

// no_tpMetrica
// ic_tpMetrica
// ic_tpAplicacao
// ic_ativo
// ic_metodoEsforco
// ic_metodoPrazo
// ic_metodoCusto
// ic_metodoRecursos

?>
<?php if ($tpmetrica->Visible) { ?>
<table cellspacing="0" id="t_tpmetrica" class="ewGrid"><tr><td>
<table id="tbl_tpmetricamaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($tpmetrica->no_tpMetrica->Visible) { // no_tpMetrica ?>
		<tr id="r_no_tpMetrica">
			<td><?php echo $tpmetrica->no_tpMetrica->FldCaption() ?></td>
			<td<?php echo $tpmetrica->no_tpMetrica->CellAttributes() ?>>
<span id="el_tpmetrica_no_tpMetrica" class="control-group">
<span<?php echo $tpmetrica->no_tpMetrica->ViewAttributes() ?>>
<?php echo $tpmetrica->no_tpMetrica->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tpmetrica->ic_tpMetrica->Visible) { // ic_tpMetrica ?>
		<tr id="r_ic_tpMetrica">
			<td><?php echo $tpmetrica->ic_tpMetrica->FldCaption() ?></td>
			<td<?php echo $tpmetrica->ic_tpMetrica->CellAttributes() ?>>
<span id="el_tpmetrica_ic_tpMetrica" class="control-group">
<span<?php echo $tpmetrica->ic_tpMetrica->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_tpMetrica->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tpmetrica->ic_tpAplicacao->Visible) { // ic_tpAplicacao ?>
		<tr id="r_ic_tpAplicacao">
			<td><?php echo $tpmetrica->ic_tpAplicacao->FldCaption() ?></td>
			<td<?php echo $tpmetrica->ic_tpAplicacao->CellAttributes() ?>>
<span id="el_tpmetrica_ic_tpAplicacao" class="control-group">
<span<?php echo $tpmetrica->ic_tpAplicacao->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_tpAplicacao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tpmetrica->ic_ativo->Visible) { // ic_ativo ?>
		<tr id="r_ic_ativo">
			<td><?php echo $tpmetrica->ic_ativo->FldCaption() ?></td>
			<td<?php echo $tpmetrica->ic_ativo->CellAttributes() ?>>
<span id="el_tpmetrica_ic_ativo" class="control-group">
<span<?php echo $tpmetrica->ic_ativo->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_ativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tpmetrica->ic_metodoEsforco->Visible) { // ic_metodoEsforco ?>
		<tr id="r_ic_metodoEsforco">
			<td><?php echo $tpmetrica->ic_metodoEsforco->FldCaption() ?></td>
			<td<?php echo $tpmetrica->ic_metodoEsforco->CellAttributes() ?>>
<span id="el_tpmetrica_ic_metodoEsforco" class="control-group">
<span<?php echo $tpmetrica->ic_metodoEsforco->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_metodoEsforco->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tpmetrica->ic_metodoPrazo->Visible) { // ic_metodoPrazo ?>
		<tr id="r_ic_metodoPrazo">
			<td><?php echo $tpmetrica->ic_metodoPrazo->FldCaption() ?></td>
			<td<?php echo $tpmetrica->ic_metodoPrazo->CellAttributes() ?>>
<span id="el_tpmetrica_ic_metodoPrazo" class="control-group">
<span<?php echo $tpmetrica->ic_metodoPrazo->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_metodoPrazo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tpmetrica->ic_metodoCusto->Visible) { // ic_metodoCusto ?>
		<tr id="r_ic_metodoCusto">
			<td><?php echo $tpmetrica->ic_metodoCusto->FldCaption() ?></td>
			<td<?php echo $tpmetrica->ic_metodoCusto->CellAttributes() ?>>
<span id="el_tpmetrica_ic_metodoCusto" class="control-group">
<span<?php echo $tpmetrica->ic_metodoCusto->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_metodoCusto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tpmetrica->ic_metodoRecursos->Visible) { // ic_metodoRecursos ?>
		<tr id="r_ic_metodoRecursos">
			<td><?php echo $tpmetrica->ic_metodoRecursos->FldCaption() ?></td>
			<td<?php echo $tpmetrica->ic_metodoRecursos->CellAttributes() ?>>
<span id="el_tpmetrica_ic_metodoRecursos" class="control-group">
<span<?php echo $tpmetrica->ic_metodoRecursos->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_metodoRecursos->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
