<div class="background"></div>
<div class="span-13 modal notice">
	<div class="close-modal">
		<a class="button negative" href="/warehouses/manage"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h3>Are you sure you want to remove the Warehouse: <strong><u><?= $rmWarehouse->name ?></u></strong>? </h3>
	<form action="/warehouses/rmwarehouse/<?= $rmWarehouse->warehouse_id ?>" method="post">
		<input type="hidden" name="confirmed" value="1">
		<input type="submit" class="button positive" value="Yes">
	</form>
</div>