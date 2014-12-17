<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$config['pageLinksWarehousesDelete'] = array(
	array(
		'title' => 'Remove Warehouse',
		'link' => '/warehouses/rmwarehouse/',
		'img' => ICON_ROOT.'/building_delete.png'
		)
	);
$config['pageLinksWarehousesUpdate'] = array(
	array(
		'title' => 'View Warehouse Access',
		'link' => '/warehouses/access/',
		'img' => ICON_ROOT.'/building_key.png'
		),
	array(
		'title' => 'Add User Access',
		'link' => '/warehouses/addaccess/',
		'img' => ICON_ROOT.'/user_add.png'
		),
	array(
		'title' => 'Edit Warehouse Info',
		'link' => '/warehouses/manage/',
		'img' => ICON_ROOT.'/building_edit.png'
		)

	);
$config['pageLinksBinUpdate'] = array(
	array(
		'title' => "Edit Bin Info",
		'link' => '/warehouses/bins/',
		'img' => ICON_ROOT.'/basket_edit.png'
		)
	);
$config['pageLinksBinDelete'] = array(
	array(
		'title' => "Remove Bin",
		'link' => '/warehouses/bins/-1/',
		'img' => ICON_ROOT.'/basket_delete.png'
		)
	);

$config['pageLinksWarehouseAccess'] = array(
	array(
		'title' => 'Remove Access',
		'link' => '/warehouses/rmaccess/',
		'img' => ICON_ROOT.'/user_delete.png'
		)
	);
$config['tableActionsProductListsDelete'] = array(
	array(
		'title' => 'Remove List',
		'link'  => '/products/lists/-1/',
		'img'   => ICON_ROOT.'/script_delete.png'
		),
	);
$config['tableActionsProductListsUpdate'] = array(
	array(
		'title' => 'View Permissions',
		'link'  => '/products/listAccess/',
		'img'   => ICON_ROOT.'/script_key.png'
		),
	array(
		'title' => 'Add Access',
		'link'  => '/products/listAccess/0',
		'img'   => ICON_ROOT.'/script_add.png'
		),
	array(
		'title' => 'Edit List',
		'link'  => '/products/lists/',
		'img'   => ICON_ROOT.'/script_edit.png'
		)

	);

/*
	array(
		'title' => '',
		'link'  => '',
		'img'   => ''
		)
*/