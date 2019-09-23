<?php

   $servername = "localhost";
   $dBUsername = "GCGuillen";
   $dBPassword = "PooptoPoos1.";
   $dBName = "Test";

  $conn = new mysqli($servername, $dBUsername, $dBPassword, $dBName);
    // $conn = new mysqli($servername, $dbUsername, $dBPassword, $dBName);
   if ($conn->connect_error)
   {
      die("Connection failed: ".mysqli_connect_error());
   }

?>
