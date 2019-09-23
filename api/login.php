<?php
   require 'dbh.php';

    $inData = getRequestInfo();
    
    // Assign data to variables from json file we received
    $username = $inData["login"];
    $password = $inData["password"];
    
    // If either field is empty, return error
   if (empty($username) || empty($password))
   {
      returnWithError("Enter username and password.");
      $conn->close();
      exit();
   }
   else
   {
     // Prepare sql statement to prevent SQL Injection attacks
     $sql = $conn->prepare("SELECT * FROM Login WHERE UserName=?");
     $sql->bind_param("s", $username);
     //Execute the query
     $sql->execute();
     $result = $sql->get_result();
     
     // If no rows exist with entered username, account does not exist
     if($result->num_rows > 0)
     {
        // Store row result from query into $row variable
        $row = $result->fetch_assoc();
        
        $pwdCheck = password_verify($password, $row["password"]);
        
        // If hashed passwords dont match, return error
        if ($pwdCheck == false)
        {
           returnWithError("Incorrect password.");
           $conn->close();
           exit();
        }
        // If hashed passwords match, send user's unique id off in json
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
        // Entered username does not exist in database
        returnWithError("No user found");
        exit();
     }
   }
   
   // Decode json file received
   function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}
	
    // Send off json
	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	// Return error in json file
	function returnWithError( $err )
	{
		$retValue = '{"id":0,"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	// Format user's unique id into json file
	function returnWithInfo($id)
	{
		$retValue = '{"id":"' . $id . '"}';
		sendResultInfoAsJson( $retValue );
	}


?>
