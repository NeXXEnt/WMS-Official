<div class="background"></div>
<div class="modal draggable modal-form" id="take-infinite-bin">
	
	<div class="close-modal">
		<a class="button negative" href="/shipping/items"><img src="/pics/icons/cross.png">Close</a>
	</div>
	

	<h1>Take</h1>
	<h2>From: <?= $warehouse->name ?></h2>
	<hr>
	

	<?= form_open("/shipping/items/-1/5"); ?>
		<div>
			<?= form_fieldset('Step 4'); ?>
				<?= form_hidden('method', 'bin') ?>
				<?= form_hidden('bin_id', $bin->bin_id) ?>
				<?= form_hidden('product_id', $product->product_id) ?>
				<?= form_hidden('updated_by', $user->user_id) ?>
				<?= form_hidden('created_by', $user->user_id) ?>
				
				Create <?= form_input(array('name' => 'qty', 'maxlength' => '10', 'value' => $product->productDim->piecesPerPallet, 'style' => 'width:3em;')) ?>
				of <?= $product->ipc.' - '.$product->model ?> - From <?= $bin->binAddress ?><br>
				<?= form_input(array('name' => 'comment', 'placeholder' => 'comment')) ?><br>
				<?= form_input(array('name' => 'poNumber', 'placeholder' => 'PO Number')) ?><br>
				
				
						
			<?= form_fieldset_close(); ?>
		</div>
		
			<div>
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
		  
	<?= form_close(); ?>
</div>