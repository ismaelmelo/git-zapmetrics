<?php

// nu_prospecto
// no_prospecto
// nu_area
// nu_categoriaProspecto
// ic_stProspecto
// ic_ativo

?>
<?php if ($prospecto->Visible) { ?>
<table cellspacing="0" id="t_prospecto" class="ewGrid"><tr><td>
<table id="tbl_prospectomaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($prospecto->nu_prospecto->Visible) { // nu_prospecto ?>
		<tr id="r_nu_prospecto">
			<td><?php echo $prospecto->nu_prospecto->FldCaption() ?></td>
			<td<?php echo $prospecto->nu_prospecto->CellAttributes() ?>>
<span id="el_prospecto_nu_prospecto" class="control-group">
<span<?php echo $prospecto->nu_prospecto->ViewAttributes() ?>>
<?php echo $prospecto->nu_prospecto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($prospecto->no_prospecto->Visible) { // no_prospecto ?>
		<tr id="r_no_prospecto">
			<td><?php echo $prospecto->no_prospecto->FldCaption() ?></td>
			<td<?php echo $prospecto->no_prospecto->CellAttributes() ?>>
<span id="el_prospecto_no_prospecto" class="control-group">
<span<?php echo $prospecto->no_prospecto->ViewAttributes() ?>>
<?php echo $prospecto->no_prospecto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($prospecto->nu_area->Visible) { // nu_area ?>
		<tr id="r_nu_area">
			<td><?php echo $prospecto->nu_area->FldCaption() ?></td>
			<td<?php echo $prospecto->nu_area->CellAttributes() ?>>
<span id="el_prospecto_nu_area" class="control-group">
<span<?php echo $prospecto->nu_area->ViewAttributes() ?>>
<?php echo $prospecto->nu_area->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($prospecto->nu_categoriaProspecto->Visible) { // nu_categoriaProspecto ?>
		<tr id="r_nu_categoriaProspecto">
			<td><?php echo $prospecto->nu_categoriaProspecto->FldCaption() ?></td>
			<td<?php echo $prospecto->nu_categoriaProspecto->CellAttributes() ?>>
<span id="el_prospecto_nu_categoriaProspecto" class="control-group">
<span<?php echo $prospecto->nu_categoriaProspecto->ViewAttributes() ?>>
<?php echo $prospecto->nu_categoriaProspecto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($prospecto->ic_stProspecto->Visible) { // ic_stProspecto ?>
		<tr id="r_ic_stProspecto">
			<td><?php echo $prospecto->ic_stProspecto->FldCaption() ?></td>
			<td<?php echo $prospecto->ic_stProspecto->CellAttributes() ?>>
<span id="el_prospecto_ic_stProspecto" class="control-group">
<span<?php echo $prospecto->ic_stProspecto->ViewAttributes() ?>>
<?php echo $prospecto->ic_stProspecto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($prospecto->ic_ativo->Visible) { // ic_ativo ?>
		<tr id="r_ic_ativo">
			<td><?php echo $prospecto->ic_ativo->FldCaption() ?></td>
			<td<?php echo $prospecto->ic_ativo->CellAttributes() ?>>
<span id="el_prospecto_ic_ativo" class="control-group">
<span<?php echo $prospecto->ic_ativo->ViewAttributes() ?>>
<?php echo $prospecto->ic_ativo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
