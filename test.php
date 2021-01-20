<?php

require_once 'lib/Api.php';

use \SnapiFile\Api;

// $_REQUEST['store'] = 'george';
$_REQUEST['store'] = 'test';
$_REQUEST['action'] = 'create';
// $_REQUEST['action'] = 'update';
// $_REQUEST['action'] = 'block';
$_REQUEST['key'] = 'sherlock';
$_REQUEST['value'] = '"holmes"';

$api = new Api();
$api->go();
