<?php
define('ft_check','pos');
$action = '/'.(isset($_REQUEST['action']) and $_REQUEST['action'])?$_REQUEST['action']:'index';
require_once('../includes/classes/class.router.php');
router::draw($action, 'pos/checkout');


?>