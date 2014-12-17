<div class="background"></div>
<div class="modal span-19 modal-form" id="new-module">
	<div class="close-modal">
		<a class="button negative" href="/warehouses/manage"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>New Warehouse</h1>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('warehouses/manage/0'); ?>
		<?= form_hidden('warehouse_id', '0'); ?>
		<div class="span-19">
			<?= form_fieldset('General Info'); ?>
				<p class="required">
					<?= form_label('Warehouse Name', 'name'); ?><br>
					<?= form_input('name', set_value('name'), 'class="title"'); ?>
				</p>
				<p class="required">
					<?= form_label('City', 'city_id'); ?><br>
					<?= form_dropdown('city_id', City::city_options(), set_value('city_id')); ?>
				</p>
				<p>
					<?= form_label('Address 1', 'address1'); ?>
					<?= form_input('address1', set_value('address1')); ?><br>
				
					<?= form_label('Address 2', 'address2'); ?>
					<?= form_input('address2', set_value('address2')); ?><br>
				
					<?= form_label('Postal Code', 'postalCode'); ?>
					<?= form_input('postalCode', set_value('postalCode')); ?>
				</p>
				<p>
					<?= form_label('Short Description', 'shortDescription'); ?>
					<?= form_input('shortDescription', set_value('shortDescription')); ?>
				</p>
				<p>
					<?= form_label('Long Description', 'longDescription'); ?><br>
					<?= form_textarea('longDescription', set_value('longDescription'), 'class="span-18"'); ?>
				</p>

			<?= form_fieldset_close(); ?>
		</div>
		
			<div class="span-19 last">
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
		  
	<?= form_close(); ?>
</div>