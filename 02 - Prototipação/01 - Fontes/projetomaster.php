<?php

// nu_tpProjeto
// no_projeto
// ic_passivelContPf

?>
<?php if ($projeto->Visible) { ?>
<table cellspacing="0" id="t_projeto" class="ewGrid"><tr><td>
<table id="tbl_projetomaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($projeto->nu_tpProjeto->Visible) { // nu_tpProjeto ?>
		<tr id="r_nu_tpProjeto">
			<td><?php echo $projeto->nu_tpProjeto->FldCaption() ?></td>
			<td<?php echo $projeto->nu_tpProjeto->CellAttributes() ?>>
<span id="el_projeto_nu_tpProjeto" class="control-group">
<span<?php echo $projeto->nu_tpProjeto->ViewAttributes() ?>>
<?php echo $projeto->nu_tpProjeto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($projeto->no_projeto->Visible) { // no_projeto ?>
		<tr id="r_no_projeto">
			<td><?php echo $projeto->no_projeto->FldCaption() ?></td>
			<td<?php echo $projeto->no_projeto->CellAttributes() ?>>
<span id="el_projeto_no_projeto" class="control-group">
<span<?php echo $projeto->no_projeto->ViewAttributes() ?>>
<?php echo $projeto->no_projeto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($projeto->ic_passivelContPf->Visible) { // ic_passivelContPf ?>
		<tr id="r_ic_passivelContPf">
			<td><?php echo $projeto->ic_passivelContPf->FldCaption() ?></td>
			<td<?php echo $projeto->ic_passivelContPf->CellAttributes() ?>>
<span id="el_projeto_ic_passivelContPf" class="control-group">
<span<?php echo $projeto->ic_passivelContPf->ViewAttributes() ?>>
<?php echo $projeto->ic_passivelContPf->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
