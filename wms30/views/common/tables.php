<?php 
/*
 *Table View.
 *
 *Will Draw out as many Tables as provided for in the $tables array
 *
 *Required:
 *
 * array $tables(array firstTable(array tableHeaders[header,header], array tableRow1[field, field, field], array tableRow2))
 * string $page['id'] = a short string to identify the page
 *
 *Optional:
 * view $editRegion
 * html $buttons
 * html $limit
 * array $page['links'] = array ('title' => 'Remove', 'link' => '/admin/rmuser', 'img' => '/pics/rm-user.png')
 *
*/
$class = '';

?>

<div class="content append-6 ">
	<?php if(isset($editRegion)) echo $editRegion; ?>
	<div class="last">
		<?php if(isset($select)) foreach($select as $drop) echo $drop; ?>
	</div>
	<div class="last">
		<?php if(isset($buttons)) foreach($buttons as $button) echo $button; ?>
	</div>
	
	
	<div id="<?= $page['id'] ?>">
		<?php foreach($tables as $caption => $table): ?>
			<?php if(isset($boolrg)) if($boolrg) $class .= 'boolrg ' ?>
			<table class="tablesorter <?=$class?>">
				<caption>
					<?= $caption ?>
					<?php if(isset($limit)) echo $limit; ?>
				</caption>			
				<thead>
					<tr>
						<?php foreach($table['0'] as $field): ?>
							<th><?= $field ?></th>
						<?php endforeach; ?>
						
						<th style="min-width:10em;">Actions</th>
					</tr>
				</thead>
				<tbody class='offset'>
					<?php foreach($table as $key => $row): ?>
							<?php if(! $key) continue; ?>
							<tr>
								<?php foreach($row as $field): ?>
									<td><?= $field ?></td>
								<?php endforeach; ?>
								<td style="text-align:right;">
									<?php if(isset($page['links']) && $key != 'NULL'): ?>
										<?php foreach($page['links'][$caption] as $link): ?>
											<?php if(!isset($link['class'])) $link['class'] = ''; ?>
												<a href="<?=$link['link'].$key?>" class="<?=$link['class']?>" title="<?=$link['title']?>"><img src="<?=$link['img']?>" alt="<?=$link['title']?>"></a>
										<?php endforeach; ?>
									<?php else: ?>
										None
									<?php endif; ?>	
								</td>
							</tr>
					<?php endforeach; ?>
				</tbody>						
			</table>
		<?php endforeach; ?>
	</div>	
</div>