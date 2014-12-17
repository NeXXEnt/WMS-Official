<div class="background"></div>
<div class="modal draggable modal-form" id="take-infinite-bin">
	
	<div class="close-modal">
		<a class="button negative" href="/shipping/items"><img src="/pics/icons/cross.png">Close</a>
	</div>
	

	<h1>Take</h1>
	<h2>From: <?= $warehouse->name ?></h2>
	<hr>
	

	<?= form_open("/shipping/items/-1/4"); ?>
		<div>
			<?= form_fieldset('Step 3'); ?>
				<?= form_hidden('method', 'bin') ?>
				<?= form_hidden('bin_id', $bin->bin_id) ?>
				<?= form_dropdown('product_list_id', ProductLists::warehouse_options($warehouse->warehouse_id), ProductLists::get_default($user->user_id, $warehouse->warehouse_id)); ?><br>
				<?= form_label('IPC/SCU#', 'ipc'); ?>
				<?= form_input('ipc', set_value('ipc')); ?>
				
			<?= form_fieldset_close(); ?>
		</div>
		
			<div>
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
		  
	<?= form_close(); ?>
</div>