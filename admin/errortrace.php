<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2010
 */
  define('ft_check','langcheck');
  include_once('includes/config/init_common.php');
  header ("content-type: text/xml");
  echo "<?xml version='1.0' encoding='utf-8'?>\n";
  echo "<tracelog>\n";
  echo "<data><![CDATA[";

  if (isset($_POST['traceid'])== md5($_SHOP->secure_ID)) {
    if (file_exists($_SHOP->trace_dir.$_SHOP->trace_name)) {
      echo file_get_contents($_SHOP->trace_dir.$_SHOP->trace_name);
      unlink($_SHOP->trace_dir.$_SHOP->trace_name);
    }
  }
  echo "]]></data>\n</tracelog>\n";
?>