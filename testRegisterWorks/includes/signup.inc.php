<?php
  if (isset($_POST['signup-submit']))
  {
    require 'dbh.inc.php';

    $username = $_POST['uName'];

    $password = $_POST['pWord'];



    // Make sure none of the fields are empty
    if (empty($username) ||  empty($password))
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
    
    else {
      $sql = "SELECT UserName FROM Login WHERE UserName=?";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql))
      {
        header("Location: ../index.html?error=sqlerror");
        exit();
      }
      else {
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
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    // header("Location: ../index.html");
  }


  else
  {
    header("Location: ../index.html");
  }
