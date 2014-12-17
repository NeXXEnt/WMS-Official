<div class="background"></div>
<div class="modal draggable modal-form" id="take-bin">
	
	<div class="close-modal">
		<a class="button negative" href="/shipping/items"><img src="/pics/icons/cross.png">Close</a>
	</div>
	

	<h1>Take</h1>
	<h2>From: <?= $warehouse->name ?></h2>
	<hr>
	

	<?= form_open("/shipping/items/-1/3"); ?>
		<div>
			<?= form_fieldset('Step 2'); ?>
				<?= form_hidden('method', 'bin') ?>
				<?= dump_debug($bin) ?>
				<?= form_radio('bin_id', '0') ?> <?= form_input('binAddress', set_value('binAddress'), 'maxlength="15"');?><br>
				<?php foreach(Bins::get_infinite_bins($warehouse->warehouse_id) as $bin):?>
					<?= form_radio('bin_id', $bin->bin_id); ?><?= $bin->binAddress; ?><br>
				<?php endforeach; ?>
			<?= form_fieldset_close(); ?>
		</div>
		
			<div>
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
		  
	<?= form_close(); ?>
</div>