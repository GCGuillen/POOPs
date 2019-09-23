<?php

   $servername = "localhost";
   $dBUsername = "GCGuillen";
   $dBPassword = "PooptoPoos1.";
   $dBName = "Test";

  $conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
    // $conn = new mysqli($servername, $dbUsername, $dBPassword, $dBName);
   if (!$conn)
   {
      die("Connection failed: ".mysqli_connect_error());
   }

?>
