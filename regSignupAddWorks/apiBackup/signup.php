<?php
   //require "header.php";
   require 'dbh.php';
    
    //$username = $_POST['uName'];
    //$password = $_POST['pWord'];
    
    $inData = getRequestInfo();
    
    // echo '<script>console.log("error1")</script>';
    // Registration json should include only username and password
    
    $username = $inData["login"];
    $password = $inData["password"];
    
    // $myfile = fopen("testfile.txt", "w") or die("unable to open file");
    // // $txt = ;
    // fwrite($myfile, strval($password));
    // fclose($myfile);
    
   // Make sure none of the fields are empty
  if (empty($username) || empty($password))
  {
      header("Location: ../index.html?error=emptyfields&uid=".$username);
      exit();
  }
   if (!preg_match("/^[a-zA-Z0-9]*$/", $username))
   {
      returnWithError("Invalid username");
      $conn->close();
      exit();
   }
   
   // Used prepared statements instead of $username variable so user cannot insert bad code.
   $sql = "SELECT UserName FROM Login WHERE UserName=?;";
   $stmt = mysqli_stmt_init($conn);

   if (!mysqli_stmt_prepare($stmt, $sql))
   {
      returnWithError("SQL Error");
      $conn->close();
   }
   else
   {
      mysqli_stmt_bind_param($stmt, "s", $username);
      mysqli_stmt_bind_result($id);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      $resultCheck = mysqli_stmt_num_rows($stmt);
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);
      $id = $row['userid'];
      if ($resultCheck > 0)
      {
         returnWithError("Username taken");
         exit();
      }
      else
      {
         $sql = "INSERT INTO Login (UserName, PassWord) VALUES (?, ?)";
         $stmt = mysqli_stmt_init($conn);

         if (!mysqli_stmt_prepare($stmt, $sql))
         {
            returnWithError("SQL Error");
         }
         else
         {
            $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPwd);
            mysqli_stmt_execute($stmt);
            //$result = mysqli_query($conn, $sql);
            //session_start();
            //$row = $result->fetch_assoc();
            // $_SESSION['userId'] = $row['userid'];
            // $_SESSION['userUid'] = $row['username'];
            //$id = $row["userid"];
            
            returnWithInfo($id);
         }
      }
      $conn->close();
   }

   mysqli_stmt_close($stmt);
   mysqli_close($conn);
  
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
// 		$retValue = '{"id":' . $id . ',"error":""}';
        // $retValue = '{"id":' . $id .'}';
        $retValue = '{"id":' . $id . '}';
		sendResultInfoAsJson( $retValue );
  }
  
  function sendResultInfoAsJson( $obj )
  {
		header('Content-type: application/json');
		echo $obj;
  }


   if (isset($_GET['error']))
   {
      if ($_GET['error'] == "emptyfields")
      {
         echo '<p class="signuperror">Fill in both fields!</p>';
      }
      else if ($_GET['error'] == "invaliduid")
      {
         echo '<p class="signuperror">Invalide username!</p>';
      }
      else if ($_GET['error'] == "logininformationtaken")
      {
         echo '<p class="signuperror">Your username is already in use!</p>';
      }
   }
   else if ($_GET['signup'] == "success")
   {
      echo '<p class="signupsuccess">Signup succesful!</p>';
   }
?>
