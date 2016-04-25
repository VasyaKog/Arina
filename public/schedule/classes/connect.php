<?php

class Connect
{
    public function __construct($userName, $password, $dbName = "khpk")
    {
        $host = "localhost";
        $db = mysql_connect($host, $userName, $password) or die(mysql_error("Oooops.. Try to refresh the page :-)"));
        mysql_select_db($dbName);
        mysql_query("SET NAMES `utf8`");
    }
}
