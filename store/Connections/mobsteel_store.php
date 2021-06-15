<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_mobsteel_store = "mysql127.secureserver.net";
$database_mobsteel_store = "newmobsteel";
$username_mobsteel_store = "newmobsteel";
$password_mobsteel_store = "Mobsteel411";
$mobsteel_store = mysql_pconnect($hostname_mobsteel_store, $username_mobsteel_store, $password_mobsteel_store) or trigger_error(mysql_error(),E_USER_ERROR); 
?>