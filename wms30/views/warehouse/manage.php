<div class="content span-19 ">
	<?= $editRegion ?>
	<?php if($warehousePermissions->create): ?>
		<div class="span-19 last">
			<a class="button positive" href="/warehouses/manage/0"><img src="/pics/icons/tick.png">Add Warehouse</a>
		</div>
	<?php endif; ?>
	<div>
		<table class="d-border">
			<tr class='d-title'>
				<td colspan="<?= (count($warehouseTableHeaders)+2); ?>"><h3>Warehouses</h3></td>
			</tr>
			<tr class="d-header">
				<?php foreach($warehouseTableHeaders as $header): ?>
					<th><?= $header ?></th>
				<?php endforeach; ?>
				<th>Remove</th>
				<th>Edit</th>
			</tr>
			<?php foreach(Warehouse::fetch_all_warehouses_info($warehouseTableHeaders) as $key => $warehouse): ?>
					<tr>
						<?php foreach($warehouse as $field): ?>
							<td>
								<?= $field ?>
							</td>
						<?php endforeach; ?>
						<td><a href="/warehouses/rmwarehouse/<?=$key?>"><img src="/pics/icons/user-delete-3.png"></td>
						<td><a href="/warehouses/manage/<?=$key?>"><img src="/pics/icons/user-properties.png"></td>
					</tr>
			<?php endforeach; ?>
		</table>
	
	</div>
</div>