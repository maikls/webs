<?php

require ("../../../../wp-config.php");

$price = $_POST["prices"];
update_option('prices',$price);

?>