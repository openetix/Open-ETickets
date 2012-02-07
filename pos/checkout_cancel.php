<?php
define('ft_check','pos');
require_once('../includes/classes/class.router.php');
router::draw('/cancel', 'pos/checkout');
?>