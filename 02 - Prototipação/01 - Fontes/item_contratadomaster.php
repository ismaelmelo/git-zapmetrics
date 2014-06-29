<?php

// nu_itemOc
// no_itemContratado
// nu_unidade
// qt_maximo
// vr_maximo
// dt_inclusao

?>
<?php if ($item_contratado->Visible) { ?>
<table cellspacing="0" id="t_item_contratado" class="ewGrid"><tr><td>
<table id="tbl_item_contratadomaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($item_contratado->nu_itemOc->Visible) { // nu_itemOc ?>
		<tr id="r_nu_itemOc">
			<td><?php echo $item_contratado->nu_itemOc->FldCaption() ?></td>
			<td<?php echo $item_contratado->nu_itemOc->CellAttributes() ?>>
<span id="el_item_contratado_nu_itemOc" class="control-group">
<span<?php echo $item_contratado->nu_itemOc->ViewAttributes() ?>>
<?php echo $item_contratado->nu_itemOc->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($item_contratado->no_itemContratado->Visible) { // no_itemContratado ?>
		<tr id="r_no_itemContratado">
			<td><?php echo $item_contratado->no_itemContratado->FldCaption() ?></td>
			<td<?php echo $item_contratado->no_itemContratado->CellAttributes() ?>>
<span id="el_item_contratado_no_itemContratado" class="control-group">
<span<?php echo $item_contratado->no_itemContratado->ViewAttributes() ?>>
<?php echo $item_contratado->no_itemContratado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($item_contratado->nu_unidade->Visible) { // nu_unidade ?>
		<tr id="r_nu_unidade">
			<td><?php echo $item_contratado->nu_unidade->FldCaption() ?></td>
			<td<?php echo $item_contratado->nu_unidade->CellAttributes() ?>>
<span id="el_item_contratado_nu_unidade" class="control-group">
<span<?php echo $item_contratado->nu_unidade->ViewAttributes() ?>>
<?php echo $item_contratado->nu_unidade->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($item_contratado->qt_maximo->Visible) { // qt_maximo ?>
		<tr id="r_qt_maximo">
			<td><?php echo $item_contratado->qt_maximo->FldCaption() ?></td>
			<td<?php echo $item_contratado->qt_maximo->CellAttributes() ?>>
<span id="el_item_contratado_qt_maximo" class="control-group">
<span<?php echo $item_contratado->qt_maximo->ViewAttributes() ?>>
<?php echo $item_contratado->qt_maximo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($item_contratado->vr_maximo->Visible) { // vr_maximo ?>
		<tr id="r_vr_maximo">
			<td><?php echo $item_contratado->vr_maximo->FldCaption() ?></td>
			<td<?php echo $item_contratado->vr_maximo->CellAttributes() ?>>
<span id="el_item_contratado_vr_maximo" class="control-group">
<span<?php echo $item_contratado->vr_maximo->ViewAttributes() ?>>
<?php echo $item_contratado->vr_maximo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($item_contratado->dt_inclusao->Visible) { // dt_inclusao ?>
		<tr id="r_dt_inclusao">
			<td><?php echo $item_contratado->dt_inclusao->FldCaption() ?></td>
			<td<?php echo $item_contratado->dt_inclusao->CellAttributes() ?>>
<span id="el_item_contratado_dt_inclusao" class="control-group">
<span<?php echo $item_contratado->dt_inclusao->ViewAttributes() ?>>
<?php echo $item_contratado->dt_inclusao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
