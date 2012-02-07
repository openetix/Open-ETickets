<?php
//Load JSON handler
define('ft_check','remote');

require_once('includes/classes/class.router.php');
router::draw('json', 'web/json'); //, true)

?>