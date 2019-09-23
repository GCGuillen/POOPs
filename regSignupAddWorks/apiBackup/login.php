<?php
   require 'dbh.php';

//   $mailuid = $_POST['mailuid'];
//   $password = $_POST['pwd'];

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
      // ? to run prepared statement for security purposes
      //$sql = "SELECT username FROM Login WHERE username=?";
      //$stmt = mysqli_stmt_init($conn);

    //   if(!mysqli_stmt_prepare($stmt, $sql))
    //   {
    //     //  header("Location: ../index.php?error=sqlerror");
    //     returnWithError("SQL Error");
    //      exit();
    //   }
      //else
      //{
         //mysqli_stmt_bind_param($stmt, "s", $username);
         //$result = $conn->query($stmt);
         //mysqli_stmt_execute($stmt);
         //$result = mysqli_stmt_get_result($stmt);
         
         $sql = "SELECT username FROM Login WHERE username=" . $username . "";
         $result = $conn->query($sql);
         
         //returnWithInfo
         if(/*$row = mysqli_fetch_assoc($result)*/ $result->num_rows > 0)
         {
            // Make sure entered password when hashed is correct
            $pwdCheck = password_verify($password, $row['password']);

            if ($pwdCheck == false)
            {
               returnWithError("Incorrect password.");
               $conn->close();
               exit();
            }
            else if($pwdCheck == true)
            {
               //session_start();

               // Change these to columns in database
               //$_SESSION['userId'] = $row['idUsers'];
               //$_SESSION['userUid'] = $row['uidUsers'];

               returnWithInfo($row['userId']);
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
            returnWithError("No user");
            exit();
         }
      //}
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
		$retValue = '{"id":' . $id . '}';
		sendResultInfoAsJson( $retValue );
	}


?>
