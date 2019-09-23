<?php
   require 'dbh.php';

    $inData = getRequestInfo();
    
    $username = $inData["login"];
    $password = $inData["password"];
    
   if (empty($username) || empty($password))
   {
      returnWithError("Enter username and password.");
      $conn->close();
      exit();
   }
   else
   {
     $sql = $conn->prepare("SELECT * FROM Login WHERE UserName=?");
     $sql->bind_param("s", $username);
     $sql->execute();
     $result = $sql->get_result();
     if($result->num_rows > 0)
     {
        // Make sure entered password when hashed is correct
        $row = $result->fetch_assoc();
        $pwdCheck = password_verify($password, $row["password"]);

        if ($pwdCheck == false)
        {
           returnWithError("Incorrect password.");
           $conn->close();
           exit();
        }
        else if($pwdCheck == true)
        {
           returnWithInfo($row["userid"]);
           exit();
        }
        else  // Just incase
        {
           returnWithError("Incorrect password.");
           $conn->close();
           exit();
        }
     }
     else
     {
        returnWithError("No user found");
        exit();
     }
   }
   
   function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"id":0,"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo($id)
	{
		$retValue = '{"id":"' . $id . '"}';
		sendResultInfoAsJson( $retValue );
	}


?>
