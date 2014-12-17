<div class="background"></div>
<div class="modal draggable modal-form" id="put">
	<div class="close-modal">
		<a class="button negative" href="/shipping/items"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>Put Items</h1>
	<h2>From: <?= $warehouse->name ?></h2>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>


	<?= form_open("/shipping/items/0/2"); ?>
		<div>
			<?= form_fieldset('Step 1'); ?>
				<table>
					<tr>
						<th></th>
						<th>IPC/SKU</th>
						<th>Model</th>
						<th>Qty</th>
						<th>Received Date</th>
						<th>Basket</th>
					</tr>
					<?php $items=Bins::items_in_user_basket($warehouse->warehouse_id, $user->user_id) ?>
					<?php if(empty($items)): ?>
						<tr>
							<td colspan='6' style='text-align:center;'>Basket is empty</td>
						</tr>
					<?php endif ?>
					<?php foreach($items as $item_id => $item): ?>
						<tr>
							<td><?= form_radio(array('name' => 'item_id', 'value' => $item_id)) ?></td>
							<td><?= $item->ipc ?></td>
							<td><?= $item->model ?></td>
							<td><?= $item->qty ?></td>
							<td><?php $date = new DateTime($item->createdDate); echo $date->format('M Y'); ?></td>
							<td><?= $item->binAddress ?></td>
							
						</tr>
					<?php endforeach ?>
				</table>
				<?= form_input(array('name' => 'qty', 'placeholder' => 'Qty')) ?><br>
				<?= form_input(array('name' => 'binAddress', 'placeholder' => 'Address', 'maxlength' => '15')) ?>
				
				
			<?= form_fieldset_close(); ?>
		</div>
		
			<div>
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive right" value="Next">
				<input type="button" class="button negative right" value="Back" onClick="history.go(-1);return true;" disabled>
			</div>
		  
	<?= form_close(); ?>
</div>