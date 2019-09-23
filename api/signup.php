<?php
    require 'dbh.php';
    
    $inData = getRequestInfo();
    
    $username = $inData['login'];
    $password = $inData['password'];
    
    // Make sure none of the fields are empty
    if (empty($username) || empty($password))
    {
        header("Location: ../index.html?error=emptyfields&uid=".$username);
        exit();
    }

    // Make sure user doesn't enter a bad username
    if (!preg_match("/^[a-zA-Z0-9]*$/", $username))
    {
      returnWithError("Invalid username");
      $conn->close();
      exit();
    }
    // Used prepared statements instead of $username variable so user cannot insert bad code.
    $sql = $conn->prepare("SELECT * FROM Login WHERE UserName=?");
    
    // Bind param then execute query
    $sql->bind_param("s", $username);
    $sql->execute();
    $result = $sql->get_result();

   if ($result->num_rows > 0)
   {
     returnWithError("Username taken");
     $conn->close();
     exit();
   }
   else
   {
    //Prepare statement to prevent sql injection attacks
    
    $sql = $conn->prepare("INSERT INTO Login (userName, password) VALUES (?, ?)");
    
    // Hash the password
    
    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
    
    // Bind param
    
    $sql->bind_param("ss", $username, $hashedPwd);
    
    // Execute sql query
    
    $sql->execute();
    
    // Select query to get user's id
    
    $sql = $conn->prepare("SELECT userid FROM Login WHERE UserName=?");
    
    $sql->bind_param("s", $username);
    
    // Execute select query to get userid
    
    $sql->execute();
    $result = $sql->get_result();
    $row = $result->fetch_assoc();
    
    // Assign value to id
    $id = $row["userid"];
    returnWithInfo($id);
   }
   $conn->close();


  
  function getRequestInfo()
  {
		return json_decode(file_get_contents('php://input'), true);
  }
  
  function returnWithError( $err)
  {
      $retValue = '{"id":0,"error":"' . $err . '"}';
      sendResultInfoAsJson($retValue);
  }
  
  function returnWithInfo($id)
  {

        $retValue = '{"id":"' . $id . '"}';
		sendResultInfoAsJson( $retValue );
  }
  
  function sendResultInfoAsJson( $obj )
  {
		header('Content-type: application/json');
		echo $obj;
  }



?>
