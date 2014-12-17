<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['userTableHeaders']         = array(
    'firstName'                             => 'First Name',
    'lastName'                              => 'Last Name',
    'userName'                              => 'User Name',
    'workEmailAddress'                      => 'Work Email',
    'homePhoneNumber'                       => 'Home Number'
	);

$config['moduleTableHeaders']       = array(
    'moduleName'                            => 'Module Name',
    'moduleKey'                             => 'Module Key',
    'moduleShortDescription'                => 'Short Description'
	);
$config['modulePermissionHeaders']  = array(
    'userName'                              => 'User Name',
    'create'                                => 'Create',
    'read'                                  => 'Read',
    'update'                                => 'Update',
    'delete'                                => 'Delete'
    );

$config['warehouseTableHeaders']    = array(
    'name'                                  => 'Warehouse Name',
    'shortDescription'                      => 'Short Description'
	);
$config['cityTableHeaders']         = array(
    'cityName'                              => 'City',
    'provinceName'                          => 'Prov./State',
    'countryName'                           => 'Country',
    'regionName'                            => 'Region'
    );
$config['groupTableHeaders']        = array(
    'groupName'                             => 'Group Name',
    'groupDescription'                      => 'Group Description'
    );
$config['userGroupHeaders']         = array(
    'groupName'                             => 'Group Name'
    );
$config['warehouseAccessHeaders']   = array(
    'userName'                              => 'User Name',
    'create'                                => 'Create',
    'read'                                  => 'Read',
    'update'                                => 'Update',
    'delete'                                => 'Delete',
    //'userName'                              => 'User Name',
    //'firstName'                             => 'First Name',
    //'lastName'                              => 'Last Name',
    //'name'                                  => 'Warehouse Name'
    
    );
$config['binTableHeaders']          = array(
    'x'                                     => 'Row',
    'y'                                     => 'Level',
    'z'                                     => 'Position',
    'binComment'                            => 'Comment'
    );
$config['productListTableHeaders']  = array(
    'listName'         => 'Name',
    'warehouseName'    => 'Warehouse',
    'shortDescription' => 'Description'
    );
$config['productsTableHeaders'] = array(
    'ipc'              => 'IPC/SKU#',
    'productUpc'       => 'UPC#',
    'model'            => 'Model', 
    'shortDescription' => 'Description'
    );
