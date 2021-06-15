<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_NewMobsteel = "mysql127.secureserver.net";
$database_NewMobsteel = "newmobsteel";
$username_NewMobsteel = "newmobsteel";
$password_NewMobsteel = "Mobsteel411";
$NewMobsteel = mysql_pconnect($hostname_NewMobsteel, $username_NewMobsteel, $password_NewMobsteel) or trigger_error(mysql_error(),E_USER_ERROR); 
?>