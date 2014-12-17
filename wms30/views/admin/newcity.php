<div class="background"></div>
<div class="modal modal-form draggable" >
	<div class="close-modal">
		<a class="button negative" href="/admin/city"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1>New City</h1>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('admin/city/0'); ?>
		<?= form_hidden('city_id', '0'); ?>
		<div onload="select()">
			<?= form_fieldset('City Information'); ?>
				<?= form_label('Region', 'region_id'); ?>
				<?= form_dropdown('region_id', Region::region_options(), set_value('region_id'), 'id="region_id"'); ?>
				<a class="positive" onclick="$('#add-region').show();$('#newRegion').val('1');$('#region_id').val('TRUE');" href="#">Add Region</a>
					<div id="add-region" style="display:none;">
						<input type="hidden" name="newRegion" value="0" id="newRegion" />
						<?= form_label('Name', 'regionName'); ?>
						<?= form_input('regionName', set_value('regionName')); ?>
						<br>
						<?= form_label('Direction', 'regionDirection'); ?>
						<?= form_input('regionDirection', set_value('regionDirection')); ?>
						<br>
						<?= form_label('Description', 'regionDescription'); ?>
						<?= form_input('regionDescription', set_value('regionDescription')); ?>
					<br>
					<a class="negative" onclick="$('#add-region').hide();$('#newRegion').val('0');$('#region_id').val('0');" href="#">Cancel</a>
					</div>
				<br>

				<?= form_label('Country', 'country_id'); ?>
				<?= form_dropdown('country_id', array('0' => 'Select Country'), set_value('country_id'), 'id="country" onchange="load_options(this.value, \'province\')"'); ?>
				<a class="positive" onclick="$('#add-country').show();$('#newCountry').val('1');$('#country').val('TRUE');" href="#">Add Country</a>
					<div id="add-country" style="display:none;">
						<input type="hidden" name="newCountry" value="0" id="newCountry" />

					<a class="negative" href="#" onclick="$('#add-country').hide();$('#newCountry').val('0');$('#country').val('0');">Cancel</a>
					</div>
				<br>

				<?= form_label('Province', 'province_id'); ?>
				<?= form_dropdown('province_id', array('' => 'Select Province/State'), set_value('province_id'), 'id="province"'); ?>
				<a class="positive" onclick="$('#add-province').show();" href="#">Add Province/State</a>
				<div id="add-province" style="display:none;">
			
					<a class="negative" href="#" onclick="$('#add-province').hide();">Cancel</a>
					</div>
				<br>

				<?= form_label('City', 'name'); ?>
				<?= form_input('name', set_value('name')); ?>
				<img src="/pics/loader.gif" id="loading" align="absmiddle" style="display:none;"/>

				
			<?= form_fieldset_close(); ?>
		</div>
		
			<div class="last">
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
		  
	<?= form_close(); ?>
</div>