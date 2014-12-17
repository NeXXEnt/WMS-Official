<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ADMIN Controller
$config['userTH']             = array(
    'firstName'                   => 'First Name',
    'lastName'                    => 'Last Name',
    'userName'                    => 'User Name',
    'workEmailAddress'            => 'Work Email',
    'homePhoneNumber'             => 'Home Number'                           
    );
$config['moduleTH']           = array(
    'moduleName'                  => 'Module Name',
    'moduleKey'                   => 'Module Key',
    'moduleShortDescription'      => 'Short Description'
    );
$config['modulePermissionTH'] = array(
    'userName'                    => 'User Name',
    'create'                      => 'Create',
    'read'                        => 'Read',
    'update'                      => 'Update',
    'delete'                      => 'Delete'
    );
$config['groupTH']            = array(
    'groupName'                   => 'Group Name',
    'groupDescription'            => 'Group Description'
    );
$config['userGroupTH']        = array(
    'groupName'                   => 'Group Name'
    );
$config['groupUsersTH']       = array(
    'userName'                    => 'User Name'
    );
$config['cityTH']             = array(
    'cityName'                    => 'City',
    'provinceName'                => 'Prov./State',
    'countryName'                 => 'Country',
    'regionName'                  => 'Region'
    );
    
// WAREHOUSE Controller
$config['warehouseTH']        = array(
    'name'                        => 'Warehouse Name',
    'shortDescription'            => 'Short Description'
    );
$config['binTH']              = array(
    //'x'                           => 'Row',
    //'y'                           => 'Level',
    //'z'                           => 'Position',
    'binAddress'                  => 'Address',
    'binIsAUserBasket'            => 'User Basket',
    'binIsInfinite'               => 'Infinite Bin',
    'binComment'                  => 'Comment'
    );
$config['warehouseAccessTH']  = array(
    'userName'                    => 'User Name',
    'create'                      => 'Create',
    'read'                        => 'Read',
    'update'                      => 'Update',
    'delete'                      => 'Delete',
    //'firstName'                             => 'First Name'
    //'lastName'                              => 'Last Name',
    //'name'                                  => 'Warehouse Name'
    
    );

// PRODUCTS Controller
$config['productListTH']       = array(
    'listName'                     => 'Name',
    'warehouseName'                => 'Warehouse',
    'shortDescription'             => 'Description'
    );
$config['productsTH']          = array(
    'ipc'                          => 'IPC/SKU#',
    'upc'                          => 'UPC#',
    'model'                        => 'Model', 
    'shortDescription'             => 'Description'
    );
$config['productListAccessTH'] = array(
    'userName'                     => 'User Name',
    'create'                       => 'Create',
    'read'                         => 'Read',
    'update'                       => 'Update',
    'delete'                       => 'Delete'
    );
$config['productDimTH']        = array(
    'name'                         => 'Name',
    'shortDescription'             => 'Short Description',
    'pieceLength'                  => 'Piece Length',
    'pieceWidth'                   => 'Piece Width',
    'pieceHeight'                  => 'Piece Height',
    'pieceWeight'                  => 'Piece Weight',
    'piecesPerBox'                 => 'Pieces/ Box',
    'sqPerPiece'                   => 'SqFT/ Piece'
    );

// SHIPPING Controller
$config['itemsTH'] = array(
    'binAddress' => 'Bin Address',
    'ipc' => 'IPC/SKU#',
    'qty' => 'Qty',
    'upc' => 'UPC#',
    'model' => 'Model',
    'productListName' => 'Product List',
    );

// Features Controller
$config['featuresTH'] = array(
    'type'        => 'Type',
    'status'      => 'Status',
    'priority'    => 'Priority',
    'link'        => 'Link',
    'description' => 'Description',
    'userName'    => 'User Name'
    );