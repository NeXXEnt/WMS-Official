<?= form_open('features/edit/'.$feature->feature_id); ?>
<div class="background"></div>
<div class="modal modal-form" id="new-feature">
	<div class='last'>
		<input type="submit" class="button positive right" value="Submit">
		<input type="reset" class="button negative right" value="Reset">
		<input type="button" class="button" value="Cancel" onClick="location.href='/features'">
		
	</div>
	<br>
	<h1 class="modalcustom">Change a feature</h1>
	
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
		<div>
			<?= form_fieldset('Info') ?>
				<p>
					<?php 
						$priorities = array(
						"1" => "1-Error or Essential Feature", 
						"2" => "2-Unexpected behavior or confusing",
						"3" => "3-Cosmetic or Nice to have");
					?>
					<?= form_label('Priority', 'priority'); ?><br>
					<?= form_dropdown('priority', $priorities, $feature->priority); ?>
				</p>
				<p>
					<?php
						$types = array(
							"0"            => "Type",
							"404"          => "404, Page not found",
							"PHP"          => "PHP error",
							"MySQL"        => "Database Error",
							"Cosmetic"     => "Cosmetic",
							"Logical"      => "Logical error",
							"Feature"      => "Feature request",
							"Bug"          => "Bug",
							"Disply Order" => "Display Order"
							);

					?>
					<?= form_label('Type', 'type'); ?><br>
					<?= form_dropdown('type', $types, $feature->type); ?>
				</p>
				<p>
					<?= form_label('Link (ie. shipping/take/-1)', 'link'); ?><br>
					<?= form_input('link', $feature->link); ?>
				</p>
				<p>
					<?php
						$status = array(
							"Proposed"   => "Proposed",
							"Accepted"   => "Accepted",
							"Rejected"	 => "Rejected",
							"Developing" => "Developing",
							"Testing"    => "Testing",
							"Completed"  => "Completed",
							"Abondoned"	 => "Abandoned"

							);
					?>
					<?= form_label('Status', 'status'); ?><br>
					<?= form_dropdown('status', $status, $feature->status); ?>
				</p>
				<p>
					<?= form_label('Description including file names and line numbers', 'description'); ?><br>
					<?= form_textarea('description', $feature->description); ?>
				</p>

			<?= form_fieldset_close(); ?>
		</div>
		
		<div class='last'>
			<input type="submit" class="button positive right" value="Submit">
			<input type="reset" class="button negative right" value="Reset">
			<input type="button" class="button" value="Cancel" onClick="location.href='/features'">
		</div>
		  
	<?= form_close(); ?>
</div>