<?php

$servername = "localhost";
$dBUsername = "sean";
$dBPassword = "sean123123";
$dBName = "ContactMan";

$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);



if (!$conn)
{
  die("Connection failed: ".mysqli_connect_error());
}
