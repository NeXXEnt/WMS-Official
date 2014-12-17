<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
*
*  ADMIN Controller
*
*/
$config['usersTA']              = array(
	array(
		'title' => 'Add to Group',
		'link'  => '/admin/addtogroup/',
		'img'   => ICON_ROOT.'/group_add.png'
		),
	array(
		'title' => 'View Associated Groups',
		'link'  => '/admin/usergroup/',
		'img'   => ICON_ROOT.'/group_link.png'
		),
	array(
		'title' => 'Edit User',
		'link'  => '/admin/user/',
		'img'   => ICON_ROOT.'/user-properties.png'
		),
	array(
		'title' => 'Remove User',
		'link'  => '/admin/rmuser/',
		'img'   => ICON_ROOT.'/user-delete-3.png',
		'class' => 'confirmClick'
		)
	);
$config['groupsTA']             = array(
	array(
		'title' => 'Add Users',
		'link'  => '/admin/addtogroup/',
		'img'   => ICON_ROOT.'/group_add.png'
		),
	array(
		'title' => 'View Associated Users',
		'link'  => '/admin/groupusers/',
		'img'   => ICON_ROOT.'/group_link.png'
		),
	array(
		'title' => 'Edit Group',
		'link'  => '/admin/group/',
		'img'   => ICON_ROOT.'/user-properties.png'
		),
	array(
		'title' => 'Remove Group',
		'link'  => '/admin/rmgroup/',
		'img'   => ICON_ROOT.'/user-delete-3.png',
		'class' => 'confirmClick'
		)
	);
$config['modulesTA']            = array(
	array(
		'title' => 'Edit',
		'link'  => '/admin/module/',
		'img'   => ICON_ROOT.'/plugin_edit.png'
		),
	array(
		'title' => 'Remove',
		'link'  => '/admin/rmmodule/',
		'img'   => ICON_ROOT.'/plugin_delete.png',
		'class' => 'confirmClick'
		)
	);
$config['modulePermissionTA']   = array(
	array(
		'title' => 'Edit',
		'link'  => '/admin/module_permissions/',
		'img'   => ICON_ROOT.'/textfield_key.png'
		),
	array(
		'title' => 'Remove',
		'link'  => '/admin/rmmodule_permissions/',
		'img'   => ICON_ROOT.'/key_delete.png',
		'class' => 'confirmClick'
		)
	);
$config['citiesTA']             = array(
	array(
		'title' => 'Edit',
		'link'  => '/admin/city/',
		'img'   => ICON_ROOT.'/user-properties.png'
		),
	array(
		'title' => 'Remove',
		'link'  => '/admin/rmcity/',
		'img'   => ICON_ROOT.'/user-delete-3.png',
		'class' => 'confirmClick'
		)
	);
$config['userGroupTA']          = array(
	array(
		'title' => 'Remove From Group',
		'link'  => '/admin/rmusergroup/',
		'img'   => ICON_ROOT.'/group_delete.png',
		'class' => 'confirmClick'
		)
	);

/**
*
* Warehouse Controller
*
*/
$config['warehouseTA-delete']   = array(
	array(
		'title' => 'Remove Warehouse',
		'link'  => '/warehouses/rmwarehouse/',
		'img'   => ICON_ROOT.'/building_delete.png',
		'class' => 'confirmClick'
		)
	);
$config['warehouseTA-update']   = array(
	array(
		'title' => 'View Warehouse Access',
		'link'  => '/warehouses/access/',
		'img'   => ICON_ROOT.'/building_key.png'
		),
	array(
		'title' => 'Add User Access',
		'link'  => '/warehouses/addaccess/',
		'img'   => ICON_ROOT.'/user_add.png'
		),
	array(
		'title' => 'Edit Warehouse Info',
		'link'  => '/warehouses/manage/',
		'img'   => ICON_ROOT.'/building_edit.png'
		)
	);
$config['binTA-update']         = array(
	array(
		'title' => "Edit Bin Info",
		'link'  => '/warehouses/bins/',
		'img'   => ICON_ROOT.'/basket_edit.png'
		)
	);
$config['binTA-delete']         = array(
	array(
		'title' => "Remove Bin",
		'link'  => '/warehouses/bins/-1/',
		'img'   => ICON_ROOT.'/basket_delete.png',
		'class' => 'confirmClick'
		)
	);
$config['warehouseAccessTA']    = array(
	array(
		'title' => 'Remove Access',
		'link'  => '/warehouses/rmaccess/',
		'img'   => ICON_ROOT.'/user_delete.png',
		'class' => 'confirmClick'
		)
	);

/**
*
*  Products Controller
*
*/
$config['productListTA-delete'] = array(
	array(
		'title' => 'Remove List',
		'link'  => '/products/lists/-1/',
		'img'   => ICON_ROOT.'/script_delete.png',
		'class' => 'confirmClick'
		),
	);
$config['productListTA-update'] = array(
	array(
		'title' => 'View Access',
		'link'  => '/products/listAccess/',
		'img'   => ICON_ROOT.'/script_key.png'
		),
	array(
		'title' => 'Add Access',
		'link'  => '/products/listAccess/0/',
		'img'   => ICON_ROOT.'/script_add.png'
		),
	array(
		'title' => 'Edit List',
		'link'  => '/products/lists/',
		'img'   => ICON_ROOT.'/script_edit.png'
		)
	);
$config['productListAccessTA'] = array(
	array(
		'title' => 'Edit Access',
		'link'  => '/products/editlistAccess/',
		'img'   => ICON_ROOT.'/script_edit.png'
		),
	array(
		'title' => 'Delete Access',
		'link'  => '/products/editlistAccess/-1/',
		'img'   => ICON_ROOT.'/script_delete.png',
		'class' => 'confirmClick'
		)
	);
$config['productTA-delete'] = array(
	array(
		'title' => 'Remove Product',
		'link'  => '/products/manage/-1/',
		'img'   => ICON_ROOT.'/brick_delete.png',
		'class' => 'confirmClick'
		)
	);
$config['productTA-update'] = array(
	array(
		'title' => 'Edit Product',
		'link'  => '/products/manage/',
		'img'   => ICON_ROOT.'/brick_edit.png'
		)
	);
$config['productDimTA-delete'] = array(
	array(
		'title' => 'Remove Dimension',
		'link'  => '/products/dim/-1/',
		'img'   => ICON_ROOT.'/map_delete.png',
		'class' => 'confirmClick'
		)
	);
$config['productDimTA-update'] = array(
	array(
		'title' => 'Edit Dimension',
		'link'  => '/products/dim/',
		'img'   => ICON_ROOT.'/map_edit.png'
		)
	);
$config['featuresTA'] = array(
	array(
		'title' => 'Complete',
		'link'  => '/features/complete/',
		'img'   => ICON_ROOT.'/application_add.png'
		),
	array(
		'title' => 'Edit',
		'link'  => '/features/edit/',
		'img'   => ICON_ROOT.'/application_edit.png'
		),
	array(
		'title' => 'Delete',
		'link'  => '/features/delete/',
		'img'   => ICON_ROOT.'/application_delete.png',
		'class' => 'confirmClick'
		)
	);
$config['featuresDeletedTA'] = array(
	array(
		'title' => 'Complete',
		'link'  => '/features/complete/',
		'img'   => ICON_ROOT.'/application_add.png'
		),
	array(
		'title' => 'Edit',
		'link'  => '/features/edit/',
		'img'   => ICON_ROOT.'/application_edit.png'
		),
	array(
		'title' => 'Restore',
		'link'  => '/features/restore/',
		'img'   => ICON_ROOT.'/archive-extract-2.png',
		'class' => 'confirmClick'
		)
	);
/*
	array(
		'title' => '',
		'link'  => '',
		'img'   => ''
		)
*/








