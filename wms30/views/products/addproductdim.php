<div class="background"></div>
<div class="modal modal-form" id="new-product-dim">
	<div class="close-modal">
		<a class="button negative" href="/products/dim"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>Add Product Dimensions</h1>	
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('products/dim/0'); ?>
		<div style="display: inline-flex;">
			<?= form_fieldset('Dimension Info'); ?>
				<p>
					<?= form_label('Dimension Name', 'name', array('class' => 'required')); ?><br>
					<?= form_input('name', set_value('name'), 'class="text" maxlength="100"'); ?>					
				</p>
				<table id="dimension-table" class='modalTable'>
					
					<thead>
						<tr>
							<th>Measurement</th>
							<th>Piece</th>
							<th>Box</th>
							<th>Pallet</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Length</td>
							<td><?= form_input('pieceLength', set_value('pieceLength'), 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('boxLength', set_value('boxLength'), 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('palletLength', set_value('palletLength'), 'class="number" maxlength="11"'); ?></td>
						</tr>
						<tr>
							<td>Width</td>
							<td><?= form_input('pieceWidth', set_value('pieceWidth'), 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('boxWidth', set_value('boxWidth'), 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('palletWidth', set_value('palletWidth'), 'class="number" maxlength="11"'); ?></td>
						</tr>
						<tr>
							<td>Height</td>
							<td><?= form_input('pieceHeight', set_value('pieceHeight'), 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('boxHeight', set_value('boxHeight'), 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('palletHeight', set_value('palletHeight'), 'class="number" maxlength="11"'); ?></td>
						</tr>
						<tr>
							<td>Weight</td>
							<td><?= form_input('pieceWeight', set_value('pieceWeight'), 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('boxWeight', set_value('boxWeight'), 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('palletWeight', set_value('palletWeight'), 'class="number" maxlength="11"'); ?></td>
						</tr>
						<tr>
							<td>Sq. Feet per</td>
							<td><?= form_input('sqPerPiece', set_value('sqPerPiece'), 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('sqPerBox', set_value('sqPerBox'), 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('sqPerPallet', set_value('sqPerPallet'), 'class="number" maxlength="11"'); ?></td>
						</tr>
						<tr>
							<td>Pieces Per</td>
							<td></td>
							<td><?= form_input('piecesPerBox', set_value('piecesPerBox'), 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('piecesPerPallet', set_value('piecesPerPallet'), 'class="number" maxlength="11"'); ?></td>
						</tr>
						<tr>
							<td>Boxes Per</td>
							<td></td>
							<td></td>
							<td><?= form_input('boxesPerPallet', set_value('boxesPerPallet'), 'class="number" maxlength="11"'); ?></td>
						</tr>
					</tbody>
				</table>
				<p>
					<?= form_label('Short Description', 'shortDescription'); ?><br>
					<?= form_input('shortDescription', set_value('shortDescription'), 'class="text" maxlength="100"'); ?>					
				</p>
				<p>
					<?= form_label('Long Description', 'longDescription'); ?><br>
					<?= form_textarea('longDescription', set_value('longDescription'), 'class="text" maxlength="256"'); ?>					
				</p>			
			<?= form_fieldset_close(); ?>
		</div>
		<div class="last">
			<input type="reset" class="button negative" value="Reset">
			<input type="submit" class="button positive" value="Submit">
		</div>
		  
	<?= form_close(); ?>
</div>