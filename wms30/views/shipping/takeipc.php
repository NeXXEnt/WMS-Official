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
	<?= form_open("/shipping/items/-1/3"); ?>
		<div>
			<?= form_hidden('method', 'ipc') ?>
			<?= form_hidden('product_id', $product->product_id) ?>

			<?= form_fieldset('Step 2'); ?>
				<table>
					<caption><?= $product->ipc ?> - <?= $product->model ?></caption>
					<tr>
						<th></th>
						<th>Address</th>
						<th>Qty</th>
						<th>Received Date</th>
					</tr>
					<?php foreach(Items::get_product_items($product->product_id) as $item): ?>
						<tr>
							<td>
								<?= form_radio(array('name' => 'item_id', 'value' => $item->item_id)) ?>
							</td>
							<td><?= $item->binAddress ?></td>
							<td><?= $item->qty ?></td>
							<td><?php $date = new DateTime($item->createdDate); echo $date->format('M Y'); ?></td>
						</tr>
					<?php endforeach; ?>
					
					<?php foreach(Bins::get_infinite_bins($warehouse->warehouse_id) as $bin):?>
						<tr>
							<td><?= form_radio('bin_id', $bin->bin_id); ?></td>
							<td><?= $bin->binAddress; ?></td>
							<td style="font-size:150%;">&#8734;</td>
							<td>Today</td>
						</tr>
				
					<?php endforeach; ?>
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