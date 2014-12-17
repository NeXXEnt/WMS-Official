<?php
/*

$limitArgs = array(
	'link'    => '/products/manage',
	'numRows' => Wms::table_rows('view_ProductsExpanded', $where),
	'limit'   => $limit, 
	'offset'  => $offset
);




*/

$chunks = $numRows / $limit;


?>



<?= form_open($link) ?>
	<?= for($i = 0; $i < $chunks; $i++): ?>
		<a href="<?=$link?>"

<select onchange="this.form.submit()">


</select>