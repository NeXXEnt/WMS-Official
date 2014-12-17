<?php /*

This view creates a simple select box with a few variables required.

$link    = is where you will be sent upon hitting submit
$hidden  = hidden key for your purposes
$label   = What you want to call your select Box
$key     = your table ID ie. warehouse_id or product_id etc..
$class   = What class do you want to pull the options from ie. Products or Bins
$user_id = will limit the selection to a specific user

$optVars = array (
	'link'    => '', //where to go on submit
	'hidden'  => '', //hidden value if you want
	'label'   => '', //label for the box
	'key'     => '', //option key
	'class'   => '', //which class to call for options
	'funct'   => '', //which function to call from the class
	'para'    => '', //select options with this parameter
	'curVal'  => ''  //which value to set it to
);


*/

 ?>

<?= form_open($link); ?>
	<?= form_hidden($hidden, TRUE); ?>
	<?= form_label($label, $key); ?>
	<?= form_dropdown($key, call_user_func(array($class, $funct), $para), $curVal); ?>
	<input type="submit" class="button positive" value="Submit">
<?= form_close(); ?>