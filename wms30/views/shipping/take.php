<div class="background"></div>
<div class="modal draggable modal-form" id="take">
	<div class="close-modal">
		<a class="button negative" href="/shipping/items"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>Take</h1>
	<h2>From: <?= $warehouse->name ?></h2>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open("/shipping/items/-1/2"); ?>
		<div>
			<?= form_fieldset('Step 1'); ?>
				<table>
					<tr>
						<td>
							<?= form_radio(array('name' => 'method', 'value' => 'ipc', 'id' => 'takeRadioIpc')) ?>
						</td>
						<td> 
						    <?= form_dropdown('product_list_id', ProductLists::warehouse_options($warehouse->warehouse_id), ProductLists::get_default($user->user_id, $warehouse->warehouse_id)); ?><br>
							<?= form_input(array('name' => 'ipc', 'id' => 'takeIpc', 'maxlength' => '20', 'placeholder' => 'IPC/SKU#')) ?>
						</td>
					</tr>
					<tr>
						<td>
							<?= form_radio(array('name' => 'method', 'value' => 'bin', 'id' => 'takeRadioBin')) ?> 
						</td>
						<td>
							<?= form_input(array('name'=>'binAddress', 'id' => 'takeBinAddress', 'placeholder' => 'Address', 'maxlength' => '15')) ?>
						</td>
					</tr>
				</table>
				
			<?= form_fieldset_close(); ?>
		</div>
		
			<div>
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive right" value="Next">
				<input type="button" class="button negative right" value="Back" onClick="history.go(-1);return true;">
			</div>
		  
	<?= form_close(); ?>
</div>