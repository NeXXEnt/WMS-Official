<div class="background"></div>
<div class="modal draggable modal-form" id="take-select-infinite">
	
	<div class="close-modal">
		<a class="button negative" href="/shipping/items"><img src="/pics/icons/cross.png">Close</a>
	</div>
	

	<h1>Create New From</h1>
	<h2>From: <?= $warehouse->name ?></h2>
	<hr>
	

	<?= form_open("/shipping/items/-1/4"); ?>
		<div>
			<?= form_hidden('method', 'ipc') ?>
			<?= form_hidden('item_id', '0') ?>
			<?= form_hidden('product_id', $product->product_id) ?>
			<?= form_fieldset('Step 3'); ?>
				<?php foreach(Bins::get_infinite_bins($warehouse->warehouse_id) as $bin) : ?>
					<?= form_radio(array('name' => 'bin_id', 'value' => $bin->bin_id)) ?> <?= $bin->binAddress ?><br>
				<?php endforeach ?>
				<?= form_input(array('name' => 'qty', 'value' => $product->productDim->piecesPerPallet)) ?><br>
				<?= form_input(array('name' => 'comment', 'placeholder' => 'Comment')) ?><br>
				<?= form_input(array('name' => 'poNumber', 'placeholder' => 'PO Number')) ?>
			<?= form_fieldset_close(); ?>
		</div>
		
			<div>
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive right" value="Next">
				<input type="button" class="button negative right" value="Back" onClick="history.go(-1);return true;">
			</div>
		  
	<?= form_close(); ?>
</div>