<?php 
ini_set('display_errors',1);
error_reporting(E_ALL);
ini_set('log_errors',1);
ini_set('error_log','/');
$con = mysqli_connect('localhost','root','','epaper') or die('connection failed');
