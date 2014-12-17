<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$CI =& get_instance();

//conditional rules
//var name should be RULE_FIELD_DESCRIPT
$addBins_user_id_req       = $CI->input->post('binIsAUserBasket')                                                                                                                   ? '|required|is_select' : '';
$addBins_binAddress_req    = $CI->input->post('customAddress')                                                                                                                      ? '|required'           : '';
$addBins_coord_req         = $CI->input->post('endXCoord') != '' || $CI->input->post('endYCoord') != '' || $CI->input->post('endZCoord') != ''                                      ? '|required'           : '';
$delBin_startZCoord_req    = $CI->input->post('endZCoord') != ''                                                                                                                    ? '|required'           : '';
$delBin_startYCoord_req    = $CI->input->post('endYCoord') != '' || $CI->input->post('endZCoord') != ''                                                                             ? '|required'           : '';

$config = array(
	'newUser'              => array(
	array(
	'field'                => 'firstName',
	'label'                => 'First Name',
	'rules'                => 'required|alpha'
	),
	array(
	'field'                => 'lastName',
	'label'                => 'Last Name',
	'rules'                => 'alpha|required'
	),
	array(
	'field'                => 'userName',
	'label'                => 'User Name',
	'rules'                => 'is_unique[Users.username]|required|alpha_numeric'
	),
	array(
	'field'                => 'confPassword',
	'label'                => 'Confirm Password',
	'rules'                => 'matches[userPassword]'
	),
	array(
	'field'                => 'userPassword',
	'label'                => 'Password',
	'rules'                => 'required|min_length[6]'
	),
	array(
	'field'                => 'city_id',
	'label'                => 'City',
	'rules'                => 'is_select'
	),
	array(
	'field'                => 'warehouse_id',
	'label'                => 'Primary Warehouse',
	'rules'                => 'is_select'
	)
	),
	'newGroup'             => array(
	array(
	'field'                => 'groupName',
	'label'                => 'Group Name',
	'rules'                => 'required'
	),
	array(
	'field'                => 'groupDescription',
	'label'                => 'groupDescription',
	'rules'                => 'trim'
	)
	),
	'editUser'             => array(
	array(
	'field'                => 'firstName',
	'label'                => 'First Name',
	'rules'                => 'required|alpha'
	),
	array(
	'field'                => 'lastName',
	'label'                => 'Last Name',
	'rules'                => 'alpha|required'
	),
	array(
	'field'                => 'userName',
	'label'                => 'User Name',
	'rules'                => 'required|alpha_numeric'
	),
	array(
	'field'                => 'city_id',
	'label'                => 'City',
	'rules'                => 'is_select'
	),
	array(
	'field'                => 'userPassword',
	'label'                => 'Password',
	'rules'                => 'min_length[6]'
	)
	),
	'newModule'            => array(
	array(
	'field'                => 'moduleName',
	'label'                => 'Module Name',
	'rules'                => 'alpha|required'
	),
	array(
	'field'                => 'moduleKey',
	'label'                => 'Module Key',
	'rules'                => 'alpha|required|is_unique[Modules.moduleKey]'
	),
	array(
	'field'                => 'moduleShortDescription',
	'label'                => 'Short Description',
	'rules'                => 'max_length[256]'
	),
	array(
	'field'                => 'moduleLongDescription',
	'label'                => 'Long Description',
	'rules'                => 'max_length[256]'
	)
	),
	'editModule'           => array(
	array(
	'field'                => 'moduleName',
	'label'                => 'Module Name',
	'rules'                => 'alpha|required'
	),
	array(
	'field'                => 'moduleKey',
	'label'                => 'Module Key',
	'rules'                => 'alpha|required'
	),
	array(
	'field'                => 'moduleShortDescription',
	'label'                => 'Short Description',
	'rules'                => 'max_length[256]'
	),
	array(
	'field'                => 'moduleLongDescription',
	'label'                => 'Long Description',
	'rules'                => 'max_length[256]'
	)
	),
	'newModulePermission'  => array(
	array(
	'field'                => 'module_id',
	'label'                => 'Module',
	'rules'                => 'is_select'
	),
	array(
	'field'                => 'user_id',
	'label'                => 'User Name',
	'rules'                => 'is_select'
	),
	array(   
	'field'                => 'create',
	'label'                => 'Create',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'read',
	'label'                => 'Read',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'update',
	'label'                => 'Update',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'delete',
	'label'                => 'Delete',
	'rules'                => 'trim'
	)
	),
	'editModulePermission' => array(
	array(
	'field'                => 'module_id',
	'label'                => 'Module',
	'rules'                => 'is_select'
	),
	array(
	'field'                => 'user_id',
	'label'                => 'User Name',
	'rules'                => 'is_select'
	),
	array(   
	'field'                => 'create',
	'label'                => 'Create',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'read',
	'label'                => 'Read',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'update',
	'label'                => 'Update',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'delete',
	'label'                => 'Delete',
	'rules'                => 'trim'
	)
	),
	'addWarehouse'         => array(
	array(
	'field'                => 'name',
	'label'                => 'Name',
	'rules'                => 'required'
	),
	array(
	'field'                => 'city_id',
	'label'                => 'City',
	'rules'                => 'requied|is_select'
	),
	array(
	'field'                => 'address1',
	'label'                => 'Address 1',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'address2',
	'label'                => 'Address 2',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'postalCode',
	'label'                => 'Postal Code',
	'rules'                => 'is_postal'
	),
	array(
	'field'                => 'shortDescription',
	'label'                => 'Short Description',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'longDescription',
	'label'                => 'Long Description',
	'rules'                => 'trim'
	)
	),
	'editWarehouse'        => array(
	array(
	'field'                => 'name',
	'label'                => 'Name',
	'rules'                => 'required'
	),
	array(
	'field'                => 'city_id',
	'label'                => 'City',
	'rules'                => 'requied|is_select'
	),
	array(
	'field'                => 'address1',
	'label'                => 'Address 1',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'address2',
	'label'                => 'Address 2',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'postalCode',
	'label'                => 'Postal Code',
	'rules'                => 'is_postal'
	),
	array(
	'field'                => 'shortDescription',
	'label'                => 'Short Description',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'longDescription',
	'label'                => 'Long Description',
	'rules'                => 'trim'
	)
	),
	'newCity'              => array(
	array(
	'field'                => 'country_id',
	'label'                => 'Country',
	'rules'                => 'is_select'
	),
	array(
	'field'                => 'province_id',
	'label'                => 'Province/State',
	'rules'                => 'is_select'
	),
	array(
	'field'                => 'region_id',
	'label'                => 'Region',
	'rules'                => 'is_select'
	),
	array(
	'field'                => 'name',
	'label'                => 'City',
	'rules'                => 'required|alpha'
	)
	
	),
	'addUserToGroup'       => array(
	array(
	'field'                => 'group_id',
	'label'                => 'Group',
	'rules'                => 'is_select'
	)
	),
	'addWarehouseAccess'   => array(
	array(
	'field'                => 'warehouse_id',
	'label'                => 'Warehouse ID',
	'rules'                => 'required'
	),
	array(
	'field'                => 'user_id',
	'label'                => 'User Name',
	'rules'                => 'is_select'
	),
	array(
	'field'                => 'create',
	'label'                => 'Create',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'read',
	'label'                => 'Read',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'update',
	'label'                => 'Update',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'delete',
	'label'                => 'Delete',
	'rules'                => 'trim'
	)
	),
	'addBins'              => array(
	array(
	'field'                => 'startXCoord',
	'label'                => 'Starting X Coordinate',
	'rules'                => 'max_length[5]|required'
	),
	array(
	'field'                => 'startYCoord',
	'label'                => 'Starting Y Coordinate',
	'rules'                => 'max_length[5]|required'
	),
	array(
	'field'                => 'startZCoord',
	'label'                => 'Starting Z Coordinate',
	'rules'                => 'max_length[5]|required'
	),
	array(
	'field'                => 'endXCoord',
	'label'                => 'Ending X Coordinate',
	'rules'                => 'max_length[5]|trim'.$addBins_coord_req
	),
	array(
	'field'                => 'endYCoord',
	'label'                => 'Ending Y Coordinate',
	'rules'                => 'max_length[5]|trim'.$addBins_coord_req
	),
	array(
	'field'                => 'endZCoord',
	'label'                => 'Ending Z Coordinate',
	'rules'                => 'max_length[5]|trim'.$addBins_coord_req
	),
	array(
	'field'                => 'bin_dim_id',
	'label'                => 'Bin Dimension Profile',
	'rules'                => 'is_select'
	),
	array(
	'field'                => 'binIsInfinite',
	'label'                => 'Infinite Bin',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'binIsAUserBasket',
	'label'                => 'User Basket',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'user_id',
	'label'                => 'User',
	'rules'                => 'trim'.$addBins_user_id_req
	),
	array(
	'field'                => 'customAddress',
	'label'                => 'Custom Address',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'binComment',
	'label'                => 'Bin Comment',
	'rules'                => 'max_length[100]|trim'
	),
	array(
	'field'                => 'binAddress',
	'label'                => 'Custom Bin Address',
	'rules'                => 'trim'.$addBins_binAddress_req.'|max_lenth[15]'//.$addBins_binAddress_notInc
	)
	
	),
	'delBins'              => array(
	array(
	'field'                => 'startXCoord',
	'label'                => 'From Row',
	'rules'                => 'required|trim|max_length[5]'
	),
	array(
	'field'                => 'startYCoord',
	'label'                => 'From Level',
	'rules'                => 'trim|max_length[5]'.$delBin_startYCoord_req
	),
	array(
	'field'                => 'startZCoord',
	'label'                => 'From Bin',
	'rules'                => 'trim|max_length[5]'.$delBin_startZCoord_req
	),
	array(
	'field'                => 'endXCoord',
	'label'                => 'To Row',
	'rules'                => 'trim|max_length[5]'
	),
	array(
	'field'                => 'endYCoord',
	'label'                => 'To Level',
	'rules'                => 'trim|max_length[5]'
	),
	array(
	'field'                => 'endZCoord',
	'label'                => 'To Bin',
	'rules'                => 'trim|max_length[5]'
	)
	),
	'addProductList'       => array(
	array(
	'field'                => 'name',
	'label'                => 'List Name',
	'rules'                => 'required|max_length[25]'
	),
	array(
	'field'                => 'warehoue_id',
	'label'                => 'Warehouse',
	'rules'                => 'is_select'
	),
	array(
	'field'                => 'shortDescription',
	'label'                => 'Short Description',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'longDescription',
	'label'                => 'Long Description',
	'rules'                => 'trim'
	)
	),
	'addAccess'            => array(
	array(
	'field'                => 'user_id',
	'label'                => 'User Name',
	'rules'                => 'is_select'
	),
	array(
	'field'                => 'create',
	'label'                => 'Create',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'read',
	'label'                => 'Read',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'update',
	'label'                => 'Update',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'delete',
	'label'                => 'Delete',
	'rules'                => 'trim'
	)
	),
	'editAccess'           => array(
	array(
	'field'                => 'create',
	'label'                => 'Create',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'read',
	'label'                => 'Read',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'update',
	'label'                => 'Update',
	'rules'                => 'trim'
	),
	array(
	'field'                => 'delete',
	'label'                => 'Delete',
	'rules'                => 'trim'
	)
	),
	'addProducts'          => array(
	array(
	'field'                => 'ipc',
	'label'                => 'IPC/SKU#',
	'rules'                => 'required|is_numeric|max_length[20]'
	),
	array(
	'field'                => 'upc',
	'label'                => 'UPC',
	'rules'                => 'is_numeric|max_length[45]'
	),
	array(
	'field'                => 'model',
	'label'                => 'Model Code',
	'rules'                => 'max_length[45]'
	),
	array(
	'field'                => 'shortDescription',
	'label'                => 'Short Description',
	'rules'                => 'max_length[256]'
	),
	array(
	'field'                => 'longDescription',
	'label'                => 'Long Description',
	'rules'                => 'max_length[256]'
	),
	array(
	'field'                => 'product_dim_id',
	'label'                => 'Product Dimension',
	'rules'                => 'is_select|required'
	)
	
	),
	'editProducts'         => array(
	array(
	'field'                => 'ipc',
	'label'                => 'IPC/SKU#',
	'rules'                => 'required|is_numeric|max_length[20]'
	),
	array(
	'field'                => 'upc',
	'label'                => 'UPC',
	'rules'                => 'is_numeric|max_length[45]'
	),
	array(
	'field'                => 'model',
	'label'                => 'Model Code',
	'rules'                => 'max_length[45]'
	),
	array(
	'field'                => 'shortDescription',
	'label'                => 'Short Description',
	'rules'                => 'max_length[256]'
	),
	array(
	'field'                => 'longDescription',
	'label'                => 'Long Description',
	'rules'                => 'max_length[256]'
	),
	array(
	'field'                => 'product_dim_id',
	'label'                => 'Product Dimension',
	'rules'                => 'is_select|required'
	)
	),
	'editProductDims'       => array(
		array(
			'field' => 'name',
			'label' => 'Name',
			'rules' => 'required'
		),
		array(
			'field' => 'shortDescription',
			'label' => 'Short Description',
			'rules' => 'trim|max_length[100]'
		),
		array(
			'field' => 'longDescription',
			'label' => 'Long Description',
			'rules' => 'trim|max_length[256]'
		),
		array(
			'field' => 'pieceLength',
			'label' => 'Piece Length',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'pieceWidth',
			'label' => 'Piece Width',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'pieceHeight',
			'label' => 'Piece Height',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'pieceWeight',
			'label' => 'Piece Weight',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'boxLength',
			'label' => 'Box Length',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'boxWidth',
			'label' => 'Box Width',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'boxHeight',
			'label' => 'Box Height',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'boxWeight',
			'label' => 'Box Weight',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'palletLength',
			'label' => 'Pallet Length',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'palletWidth',
			'label' => 'Pallet Width',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'palletHeight',
			'label' => 'Pallet Height',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'palletWeight',
			'label' => 'Pallet Weight',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'piecesPerBox',
			'label' => 'Pieces Per Box',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'piecesPerPallet',
			'label' => 'Pieces Per Pallet',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'boxesPerPallet',
			'label' => 'Boxes Per Pallet',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'sqPerPiece',
			'label' => 'SqFeet Per Piece',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'sqPerBox',
			'label' => 'SqFeet Per Box',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'sqPerPallet',
			'label' => 'SqFeet Per Pallet',
			'rules' => 'trim|numeric|max_length[11]'
		)
	),
	'addFeature'       => array(
		array(
			'field' => 'priority',
			'label' => 'Priority',
			'rules' => 'is_select'
		),
		array(
			'field' => 'type',
			'label' => 'Type',
			'rules' => 'trim'
		),
		array(
			'field' => 'link',
			'label' => 'Link',
			'rules' => 'trim'
		),
		array(
			'field' => 'status',
			'label' => 'Status',
			'rules' => 'trim'
		),
		array(
			'field' => 'description',
			'label' => 'Description',
			'rules' => 'trim'
		)
	)
);

	/*
	array(
			'field' => '',
			'label' => '',
			'rules' => ''
		),
	 */
