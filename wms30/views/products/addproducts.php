<div class="background"></div>
<div class="modal modal-form" id="new-product">
	<div class="close-modal">
		<a class="button negative" href="/products/manage"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>Add Product</h1>
	<h3>To: <?= $productList->name ?></h3>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('products/manage/0'); ?>
		<div style="display: inline-flex;">
			<?= form_fieldset('Product Info'); ?>
				<p>
					<?= form_label('IPC/SKU#', 'ipc', array('class' => 'required')); ?><br>
					<?= form_input('ipc', set_value('ipc'), 'class="number" maxlength="20"'); ?><br>					
				</p>
				
				<p>
					<?= form_label('Upc', 'upc'); ?><br>
					<?= form_input('upc', set_value('upc'), 'class="number" maxlength="45"'); ?><br>					
				</p>
				
				<p>
					<?= form_label('Model Code', 'model'); ?><br>
					<?= form_input('model', set_value('model'), 'class="text" maxlength="45"'); ?><br>	
				</p>
				<p class="required">
					<?= form_dropdown('product_dim_id', Product::dim_options(), set_value('product_dim_id')); ?>
				</p>	
				<p>
					<?= form_label('Short Description', 'shortDescription'); ?><br>
					<?= form_input('shortDescription', set_value('shortDescription'), 'class="text" maxlength="256"'); ?><br>	
				</p>
				<p>
					<?= form_label('Long Description', 'longDescription'); ?><br>
					<?= form_textarea('longDescription', set_value('longDescription'), 'class="text" maxlength="256"'); ?><br>
				</p>
				
			
			<?= form_fieldset_close(); ?>
		</div>
		<div class="last">
			<input type="reset" class="button negative" value="Reset">
			<input type="submit" class="button positive" value="Submit">
		</div>
		  
	<?= form_close(); ?>
</div>