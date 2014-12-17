<div class="background"></div>
<div class="span-13 modal notice">
	<div class="close-modal">
		<a class="button negative" href="/admin/module_permissions"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h3>Are you sure you want to remove the<br>Module Permission: <strong><u><?= $rmModulePermission->module->moduleName.' - '.$rmModulePermission->user->userName ?></u></strong>? </h3>
	<form action="/admin/rmmodule_permission/<?= $rmModulePermission->module_permission_id ?>" method="post">
		<input type="hidden" name="confirmed" value="1">
		<input type="submit" class="button positive" value="Yes">
	</form>
</div>