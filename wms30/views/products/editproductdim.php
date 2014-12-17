<div class="background"></div>
<div class="modal modal-form" id="edit-product-dim">
	<div class="close-modal">
		<a class="button negative" href="/products/dim"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>Edit Product Dimensions</h1>	
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('products/dim/'.$edit->product_dim_id); ?>
		<div style="display: inline-flex;">
			<?= form_fieldset('Dimension Info'); ?>
				<p>
					<?= form_label('Dimension Name', 'name', array('class' => 'required')); ?><br>
					<?= form_input('name', $edit->name, 'class="text" maxlength="100"'); ?>					
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
							<td><?= form_input('pieceLength', $edit->pieceLength, 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('boxLength', $edit->boxLength, 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('palletLength', $edit->palletLength, 'class="number" maxlength="11"'); ?></td>
						</tr>
						<tr>
							<td>Width</td>
							<td><?= form_input('pieceWidth', $edit->pieceWidth, 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('boxWidth', $edit->boxWidth, 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('palletWidth', $edit->palletWidth, 'class="number" maxlength="11"'); ?></td>
						</tr>
						<tr>
							<td>Height</td>
							<td><?= form_input('pieceHeight', $edit->pieceHeight, 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('boxHeight', $edit->boxHeight, 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('palletHeight', $edit->palletHeight, 'class="number" maxlength="11"'); ?></td>
						</tr>
						<tr>
							<td>Weight</td>
							<td><?= form_input('pieceWeight', $edit->pieceWeight, 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('boxWeight', $edit->boxWeight, 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('palletWeight', $edit->palletWeight, 'class="number" maxlength="11"'); ?></td>
						</tr>
						<tr>
							<td>Sq. Feet per</td>
							<td><?= form_input('sqPerPiece', $edit->sqPerPiece, 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('sqPerBox', $edit->sqPerBox, 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('sqPerPallet', $edit->sqPerPallet, 'class="number" maxlength="11"'); ?></td>
						</tr>
						<tr>
							<td>Pieces Per</td>
							<td></td>
							<td><?= form_input('piecesPerBox', $edit->piecesPerBox, 'class="number" maxlength="11"'); ?></td>
							<td><?= form_input('piecesPerPallet', $edit->piecesPerPallet, 'class="number" maxlength="11"'); ?></td>
						</tr>
						<tr>
							<td>Boxes Per</td>
							<td></td>
							<td></td>
							<td><?= form_input('boxesPerPallet', $edit->boxesPerPallet, 'class="number" maxlength="11"'); ?></td>
						</tr>
					</tbody>
				</table>
				<p>
					<?= form_label('Short Description', 'shortDescription'); ?><br>
					<?= form_input('shortDescription', $edit->shortDescription, 'class="text" maxlength="100"'); ?>					
				</p>
				<p>
					<?= form_label('Long Description', 'longDescription'); ?><br>
					<?= form_textarea('longDescription', $edit->longDescription, 'class="text" maxlength="256"'); ?>					
				</p>			
			<?= form_fieldset_close(); ?>
		</div>
		<div class="last">
			<input type="reset" class="button negative" value="Reset">
			<input type="submit" class="button positive" value="Submit">
		</div>
		  
	<?= form_close(); ?>
</div>