<?php

// nu_metaneg
// nu_periodoPei
// nu_necessidade
// ic_perspectiva
// no_metaneg
// ic_situacao

?>
<?php if ($metaneg->Visible) { ?>
<table cellspacing="0" id="t_metaneg" class="ewGrid"><tr><td>
<table id="tbl_metanegmaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($metaneg->nu_metaneg->Visible) { // nu_metaneg ?>
		<tr id="r_nu_metaneg">
			<td><?php echo $metaneg->nu_metaneg->FldCaption() ?></td>
			<td<?php echo $metaneg->nu_metaneg->CellAttributes() ?>>
<span id="el_metaneg_nu_metaneg" class="control-group">
<span<?php echo $metaneg->nu_metaneg->ViewAttributes() ?>>
<?php echo $metaneg->nu_metaneg->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($metaneg->nu_periodoPei->Visible) { // nu_periodoPei ?>
		<tr id="r_nu_periodoPei">
			<td><?php echo $metaneg->nu_periodoPei->FldCaption() ?></td>
			<td<?php echo $metaneg->nu_periodoPei->CellAttributes() ?>>
<span id="el_metaneg_nu_periodoPei" class="control-group">
<span<?php echo $metaneg->nu_periodoPei->ViewAttributes() ?>>
<?php echo $metaneg->nu_periodoPei->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($metaneg->nu_necessidade->Visible) { // nu_necessidade ?>
		<tr id="r_nu_necessidade">
			<td><?php echo $metaneg->nu_necessidade->FldCaption() ?></td>
			<td<?php echo $metaneg->nu_necessidade->CellAttributes() ?>>
<span id="el_metaneg_nu_necessidade" class="control-group">
<span<?php echo $metaneg->nu_necessidade->ViewAttributes() ?>>
<?php echo $metaneg->nu_necessidade->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($metaneg->ic_perspectiva->Visible) { // ic_perspectiva ?>
		<tr id="r_ic_perspectiva">
			<td><?php echo $metaneg->ic_perspectiva->FldCaption() ?></td>
			<td<?php echo $metaneg->ic_perspectiva->CellAttributes() ?>>
<span id="el_metaneg_ic_perspectiva" class="control-group">
<span<?php echo $metaneg->ic_perspectiva->ViewAttributes() ?>>
<?php echo $metaneg->ic_perspectiva->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($metaneg->no_metaneg->Visible) { // no_metaneg ?>
		<tr id="r_no_metaneg">
			<td><?php echo $metaneg->no_metaneg->FldCaption() ?></td>
			<td<?php echo $metaneg->no_metaneg->CellAttributes() ?>>
<span id="el_metaneg_no_metaneg" class="control-group">
<span<?php echo $metaneg->no_metaneg->ViewAttributes() ?>>
<?php echo $metaneg->no_metaneg->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($metaneg->ic_situacao->Visible) { // ic_situacao ?>
		<tr id="r_ic_situacao">
			<td><?php echo $metaneg->ic_situacao->FldCaption() ?></td>
			<td<?php echo $metaneg->ic_situacao->CellAttributes() ?>>
<span id="el_metaneg_ic_situacao" class="control-group">
<span<?php echo $metaneg->ic_situacao->ViewAttributes() ?>>
<?php echo $metaneg->ic_situacao->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
