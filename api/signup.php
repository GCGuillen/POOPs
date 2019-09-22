<?php
   require "header.php";
   require 'dbh.php';

   $inData = getRequestInfo();
	$id = 0;
	$firstName = "";
	$lastName = "";
   $username = $_POST['uName'];
   $password = $_POST['pWord'];

	$sql = "SELECT username FROM Users where Login='" . $inData["login"] . "' and Password='" . $inData["password"] . "'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();
		// $firstName = $row["firstName"];
		// $lastName = $row["lastName"];
		// $id = $row["ID"];

		returnWithInfo($firstName, $lastName, $id );
	}
	else
	{
		returnWithError( "No Records Found" );
	}
	$conn->close();

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
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}


   // Make sure none of the fields are empty
   if (empty($username) || empty($password))
   {
      header("Location: ../index.html?error=emptyfields&uid=".$username);
      exit();
   }
   else if (!preg_match("/^[a-zA-Z0-9]*$/", $username))
   {
      header("Location: ../index.html?error=invaliduid");
      exit();
   }

   // Used prepared statements instead of $username variable so user cannot insert bad code.
   $sql = "SELECT UserName FROM Login WHERE UserName=?";
   $stmt = mysqli_stmt_init($conn);

   if (!mysqli_stmt_prepare($stmt, $sql))
   {
      header("Location: ../index.html?error=sqlerror");
      exit();
   }
   else
   {
      mysqli_stmt_bind_param($stmt, "s", $username);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      $resultCheck = mysqli_stmt_num_rows($stmt);

      if ($resultCheck > 0)
      {
         header("Location: ../index.html?error=logininformationtaken");
         exit();
      }
      else
      {
         $sql = "INSERT INTO Login (UserName, PassWord) VALUES (?, ?)";
         $stmt = mysqli_stmt_init($conn);

         if (!mysqli_stmt_prepare($stmt, $sql))
         {
            header("Location: ../signup.php?error=sqlerror");
            exit();
         }
         else
         {
            $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPwd);
            mysqli_stmt_execute($stmt);
            header("Location: ../signup.php?signup=success");
         }
      }
   }

   mysqli_stmt_close($stmt);
   mysqli_close($conn);
   // header("Location: ../index.html");


   if (isset($_GET['error']))
   {
      if ($_GET['error'] == "emptyfields")
      {
         echo '<p class="signuperror">Fill in all fields!</p>';
      }
      else if ($_GET['error'] == "invaliduidmail")
      {
         echo '<p class="signuperror">Invalide username and e-mail!</p>';
      }
      else if ($_GET['error'] == "invaliduid")
      {
         echo '<p class="signuperror">Invalide username!</p>';
      }
      else if ($_GET['error'] == "invalidmail")
      {
         echo '<p class="signuperror">Invalide e-mail!</p>';
      }
      else if ($_GET['error'] == "passwordcheck")
      {
         echo '<p class="signuperror">Your passwords do not match!</p>';
      }
      else if ($_GET['error'] == "logininformationtaken")
      {
         echo '<p class="signuperror">Either your e-mail or username is already in use!</p>';
      }
   }
   else if ($_GET['signup'] == "success")
   {
      echo '<p class="signupsuccess">Signup succesful!</p>';
   }
?>
