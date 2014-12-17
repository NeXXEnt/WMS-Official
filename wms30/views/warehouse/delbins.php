<div class="background"></div>
<div class="modal modal-form" id="new-module">
	<div class="close-modal">
		<a class="button negative" href="/warehouses/bins"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>Remove Bins</h1>
	<h3>From Warehouse: <?= $warehouseName ?></h3>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('warehouses/bins/-1'); ?>
		<div style="display: inline-flex;">
			<?= form_fieldset('Remove Bins Between'); ?>
				<p>
					<?= form_label('Rows', 'startXCoord', array('class' => 'required')); ?>
					<?= form_input('startXCoord', set_value('startXCoord'), 'class="number five" maxlength="5"'); ?>
					<?= form_label('And', 'endXCoord'); ?>
					<?= form_input('endXCoord', set_value('endXCoord'), 'class="number five" maxlength="5"'); ?>					

				</p>
				
				<p>
					<?= form_label('Levels', 'startYCoord'); ?>
					<?= form_input('startYCoord', set_value('startYCoord'), 'class="number five" maxlength="5"'); ?>
					<?= form_label('And', 'endYCoord'); ?>
					<?= form_input('endYCoord', set_value('endYCoord'), 'class="number five" maxlength="5"'); ?>					

				</p>

				<p>
					<?= form_label('Bins', 'startZCoord'); ?>
					<?= form_input('startZCoord', set_value('startZCoord'), 'class="number five" maxlength="5"'); ?>
					<?= form_label('And', 'endZCoord'); ?>
					<?= form_input('endZCoord', set_value('endZCoord'), 'class="number five" maxlength="5"'); ?>					

				</p>
				<p>A row must be selected at a minimum, then a level, then a bin.<br>
				Leaving empty fields implies "All".
				</p>
			
		
			
			<?= form_fieldset_close(); ?>
		</div>
		<div class="last">
			<input type="reset" class="button negative" value="Reset">
			<input type="submit" class="button positive" value="Submit">
		</div>
		  
	<?= form_close(); ?>
</div>