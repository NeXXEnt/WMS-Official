<div class="background"></div>
<div class="modal draggable modal-form" id="edit-user">
	<div class="close-modal">
		<a class="button negative" href="/admin/user"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h1><?= $editUser->firstName.' '.$editUser->lastName; ?></h1>
	<hr>
	<?php if(validation_errors()): ?>
		<div class="error">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>
	<?= form_open('/admin/user/'.$editUser->user_id); ?>
		<?= form_hidden('user_id', $editUser->user_id); ?>
			<div style="display:inline-flex;">
				<?= form_fieldset('Personal Info'); ?>
				<p class="required">
					<?= form_label('First Name', 'firstName'); ?><br>
					<?= form_input('firstName', $editUser->firstName); ?>
				</p>
				<p class="required">
					<?= form_label('Last Name', 'lastName'); ?><br>		
					<?= form_input('lastName', $editUser->lastName); ?>
				</p>
				<p class="required">
					<?= form_label('User Name', 'userName'); ?><br>
					<?= form_input('userName', $editUser->userName); ?>
				</p>
				<p>
					<?= form_label('Password', 'userPassword'); ?><br>
					<?= form_password('userPassword') ?><br>
					<a class="negative" id="reset-password" href="/admin/reset_password/<?= $editUser->user_id ?>">Generate Random Password</a>
				</p>
				<p>						
					<?= form_label('Home Number', 'homePhoneNumber'); ?><br>
					<?= form_input('homePhoneNumber', $editUser->homePhoneNumber); ?>
				</p>
				<p>
					<?= form_label('Cell Number', 'cellPhoneNumber'); ?><br>
					<?= form_input('cellPhoneNumber', $editUser->cellPhoneNumber); ?>
				</p>
				<p>
					<?= form_label('Home Email', 'homeEmailAddress'); ?><br>
					<?= form_input('homeEmailAddress', $editUser->homeEmailAddress); ?>
				</p>
				<?= form_fieldset_close(); ?>
			
				<?= form_fieldset('Work Info'); ?>
					<p>
						<?= form_label('Address 1', 'address1'); ?><br>
						<?= form_input('address1', $editUser->address1); ?>
					</p>
					<p>
						<?= form_label('Address 2', 'address2'); ?><br>
						<?= form_input('address2', $editUser->address2); ?>
					</p>
					<p class="required">
						<?= form_label('City', 'city_id'); ?><br>
						<?= form_dropdown('city_id', City::city_options(), $editUser->city_id); ?>
					</p>

					<p>
						<?= form_label('Postal Code', 'postalCode'); ?><br>
						<?= form_input('postalCode', $editUser->postalCode); ?>
					</p>
					<p>
						<?= form_label('Work Email', 'workEmailAddress'); ?><br>
						<?= form_input('workEmailAddress', $editUser->workEmailAddress); ?>
					</p>
					<p>
						<?= form_label('Fax Number', 'faxNumber'); ?><br>
						<?= form_input('faxNumber', $editUser->faxNumber); ?>
					</p>
					<p>
						<?= form_label('Office Number', 'officePhoneNumber'); ?><br>
						<?= form_input('officePhoneNumber', $editUser->officePhoneNumber); ?>
					</p>
				
				<?= form_fieldset_close(); ?>
			
				<?= form_fieldset('Account Info'); ?>
					<p class="required">
						<?= form_label('Primary Warehouse', 'warehouse_id'); ?><br>
						<?= form_dropdown('warehouse_id', Warehouse::options(), $editUser->warehouse_id) ?>
					</p>
					<p>
						<?= form_checkbox('accountEnabled', 1, $editUser->accountEnabled); ?>
						<?= form_label('Account Enabled', 'accountEnabled'); ?>						
					</P>
					<p>
						<?= form_checkbox('admin', 1, $editUser->admin); ?>
						<?= form_label('User is Admin', 'admin'); ?>						
					</p>

				<?= form_fieldset_close(); ?>
			</div>
			<div>
				<input type="reset" class="button negative" value="Reset">
				<input type="submit" class="button positive" value="Submit">
			</div>
		  
	<?= form_close(); ?>
</div>
	