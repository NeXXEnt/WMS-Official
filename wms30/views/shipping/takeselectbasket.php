<div class="background"></div>
<div class="modal draggable modal-form" id="take">
	<div class="close-modal">
		<a class="button negative" href="/shipping/items"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>Take</h1>
	<h2>
		From: <?= $warehouse->name ?>
	</h2>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open("/shipping/items/-1/4"); ?>
		<div>
			<?= form_hidden('method', 'ipc'); ?>
			<?= form_hidden('product_list_id', $productList->product_list_id); ?>
			<?  form_hidden('bin_id', $item->bin_id) ?>
			<?= form_hidden('product_id', $product->product_id) ?>
			<?= form_hidden('item_id', $item->item_id) ?>

			<?= form_fieldset('Step 3'); ?>
				Take: <?= form_input(array('name' => 'qty', 'value' => $item->qty)) ?><br>
				Into the user Basket:<br>
				<?= form_dropdown('basket_id', Warehouse::user_basket_options($warehouse->warehouse_id, $user->user_id)); ?><br>				
			<?= form_fieldset_close(); ?>
		</div>
		
			<div>
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive right" value="Next">
				<input type="button" class="button negative right" value="Back" onClick="history.go(-1);return true;">
			</div>
		  
	<?= form_close(); ?>
</div>