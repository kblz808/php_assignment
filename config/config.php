<?php
ob_start(); // turn on output buffering

session_start();

$timezone = date_default_timezone_set("Europe/London");

$con = mysqli_connect("localhost", "phpmyadmin", "some_pass", "social");

if(mysqli_connect_errno()){
   echo "failed to connect" . mysqli_connect_errno();
}
?>