<?= form_open("/warehouses/warehouseUpdate"); ?>
	<?= form_hidden('change_warehouse', TRUE); ?>
	<?= form_label('Warehouse', 'warehouse_id'); ?>
	<?= form_dropdown('warehouse_id', Warehouse::warehouse_options($user_id), set_value('warehouse_id')); ?>
	<input type="submit" class="button positive" value="Submit">
<?= form_close(); ?>