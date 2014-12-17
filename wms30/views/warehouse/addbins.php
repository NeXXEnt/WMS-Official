<div class="background"></div>
<div class="modal modal-form" id="new-module">
	<div class="close-modal">
		<a class="button negative" href="/warehouses/bins"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>Add Bins</h1>
	<h3>To Warehouse: <?= $warehouseName ?></h3>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('warehouses/bins/0'); ?>
		<div style="display: inline-flex;">
			<?= form_fieldset('Starting at Bin'); ?>
				<p>
					<?= form_label('X Coordinate', 'startXCoord', array('class' => 'required')); ?><br>
					<?= form_input('startXCoord', set_value('startXCoord'), 'class="number" maxlength="5"'); ?><br>					
					This is the Row letter or number<Br>you want to add.
				</p>
				
				<p>
					<?= form_label('Y Coordinate', 'startYCoord', array('class' => 'required')); ?><br>
					<?= form_input('startYCoord', set_value('startYCoord'), 'class="number" maxlength="5"'); ?><br>
					This is the level or vertical address.					
				</p>
				
				<p>
					<?= form_label('Z Coordinate', 'startZCoord', array('class' => 'required')); ?><br>
					<?= form_input('startZCoord', set_value('startZCoord'), 'class="number" maxlength="5"'); ?><br>
					This would be the depth in the row,<Br> or distance from the start of the level.
				</p>
				
			<?= form_fieldset_close(); ?>
		
			<?= form_fieldset('Ending at Bin'); ?>
				<p>
					<?= form_label('X Coordinate', 'endXCoord'); ?><br>
					<?= form_input('endXCoord', set_value('endXCoord'), 'class="number" maxlength="5"'); ?><br>

				</p>
				<p>
					<?= form_label('Y Coordinate', 'endYCoord'); ?><br>
					<?= form_input('endYCoord', set_value('endYCoord'), 'class="number" maxlength="5"'); ?>
				</p>
				<p>
					<?= form_label('Z Coordinate', 'endZCoord'); ?><br>
					<?= form_input('endZCoord', set_value('endZCoord'), 'class="number" maxlength="5"'); ?>
				</p>
				<p>
					The starting and ending data type<br> must be of the same type, either<br> alphabetical or numerical. Mixing<br> data types isn't supported.<br>
					Numerical values will be zero back<br>filled, ie, start 3 and end 700 <br>will turn 3 into 003
				</p>
			<?= form_fieldset_close(); ?>
		</div>
		<div class="last">
			<?= form_fieldset('Dimensions'); ?>
				<p class="required">
					<?= form_label('Bin Dimension Profile ', 'bin_dim_id'); ?>
					<?= form_dropdown('bin_dim_id', Warehouse::binDim_options(), set_value('bin_dim_id')); ?>
				</p>				
			<?= form_fieldset_close(); ?>
		</div>
		<div class="last">
			<?= form_fieldset('Special Options'); ?>
				<p>
					<?= form_checkbox('binIsInfinite', 1, set_checkbox('binIsInfinite', '1', FALSE)); ?>
					<?= form_label('Infinite Bin', 'binIsInfinite'); ?><br>
					Use this to create an Entrance or Exit to the warehouse.					
				</P>
				<p>
					<?= form_checkbox('binIsAUserBasket', 1, set_checkbox('binIsAUserBasket', '1', FALSE)); ?>
					<?= form_label('This is a basket', 'binIsAUserBasket'); ?>
					<?= form_dropdown('user_id', User::user_options(), set_value('user_id')); ?>
					<br>
					A basket can be a non-physical bin, or a bin that moves like a forklift or a dolly.<br>Must be assigned to a user.	
				</p>
				<p>
					<?= form_checkbox('customAddress', 1, set_checkbox('customAddress', '1', FALSE)); ?>
					<?= form_label('Non-standard Address', 'customAddress'); ?>
					<?= form_input('binAddress', set_value('binAddress'), 'maxlength="15"') ?>
					<br>
					If you do not want to have the address automatically combined ie. XYZ, then <br>enter your custom address here (not recommended).				
				</P>
				<p>
					<?= form_label('Bin Comment', 'binComment'); ?>
					<?= form_input('binComment', set_value('binComment'), 'maxlength="100"'); ?>
				</p>

			<?= form_fieldset_close(); ?>
		</div>
		<div class="last">
			<input type="reset" class="button negative" value="Reset">
			<input type="submit" class="button positive" value="Submit">
		</div>
		  
	<?= form_close(); ?>
</div>