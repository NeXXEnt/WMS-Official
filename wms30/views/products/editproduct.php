<div class="background"></div>
<div class="modal span-19 modal-form" id="edit-product">
	<div class="close-modal">
		<a class="button negative" href="/products/manage"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1><?= $edit->model ?></h1>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('products/manage/'.$edit->product_id); ?>
		<div class="span-19">
			<?= form_fieldset('Product Info'); ?>
				<p>
					<?= form_label('IPC/SKU#', 'ipc', array('class' => 'required')); ?><br>
					<?= form_input('ipc', $edit->ipc, 'class="number" maxlength="20"'); ?><br>					
				</p>
				
				<p>
					<?= form_label('Upc', 'upc'); ?><br>
					<?= form_input('upc', $edit->upc, 'class="number" maxlength="45"'); ?><br>					
				</p>
				
				<p>
					<?= form_label('Model Code', 'model'); ?><br>
					<?= form_input('model', $edit->model, 'class="text" maxlength="45"'); ?><br>	
				</p>
				<p class="required">
					<?= form_dropdown('product_dim_id', Product::dim_options(), $edit->product_dim_id); ?>
				</p>	
				<p>
					<?= form_label('Short Description', 'shortDescription'); ?><br>
					<?= form_input('shortDescription', $edit->shortDescription, 'class="text" maxlength="256"'); ?><br>	
				</p>
				<p>
					<?= form_label('Long Description', 'longDescription'); ?><br>
					<?= form_textarea('longDescription', $edit->longDescription, 'class="text" maxlength="256"'); ?><br>
				</p>

			<?= form_fieldset_close(); ?>
		</div>
		
			<div class="span-19 last">
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
		  
	<?= form_close(); ?>
</div>