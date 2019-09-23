<?php
    require 'dbh.php';
    

    
    $inData = getRequestInfo();
    
    $id = $inData['userid'];
    $email = $inData['cEmail'];
    $firstName = $inData['cFirstName'];
    $lastName = $inData['cLastName'];
    $phoneNumber = $inData['cPhoneNum'];
    
    $file = fopen("testfile.txt", "w");
    
    //$reference = $inData['cFirstName'] . $indata['cLastName'];
    $reference = $inData['userId'];
    
    fwrite($file, $reference);
    
    $file = fopen("test.txt", "w");
    fwrite($file, "this is the referenceid: " . $reference);
    
    $sql = $conn->prepare("INSERT INTO contacts (email, firstName, lastName, phoneNumber, UserId, referenceUser) VALUES (?,?,?,?,?,?)");
    $sql->bind_param("ssssii", $email, $firstName, $lastName, $phoneNumber, $id, $reference);
    $sql->execute();
    
    if (empty($firstName) || empty($lastName)|| empty($email) || empty($phoneNumber))
    {
        returnWithError("Please enter all contact information");
        exit();
    }
    
     function returnWithError( $err)
     {
        $retValue = '{"id":0,"error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
     }
     
     function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
    
    // Receive user's id and contact's email, phone number, first and last name
    function getRequestInfo()
    {
		return json_decode(file_get_contents('php://input'),true);
    }