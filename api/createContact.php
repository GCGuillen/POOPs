<?php
    require 'dbh.php';
    

    // Read in data from json file
    $inData = getRequestInfo();
    $id = $inData['userid'];
    $email = $inData['cEmail'];
    $firstName = $inData['cFirstName'];
    $lastName = $inData['cLastName'];
    $phoneNumber = $inData['cPhoneNum'];
    $reference = $inData['userId'];
    
    // Prepare sql query to preven sql injection attacks
    $sql = $conn->prepare("INSERT INTO contacts (email, firstName, lastName, phoneNumber, UserId, referenceUser) VALUES (?,?,?,?,?,?)");
    $sql->bind_param("ssssii", $email, $firstName, $lastName, $phoneNumber, $id, $reference);
    $sql->execute();
    
    // If one of the feilds is empty, return an error
    if (empty($firstName) || empty($lastName)|| empty($email) || empty($phoneNumber))
    {
        // Return error
        returnWithError("Please enter all contact information");
        exit();
    }
    
     function returnWithError( $err)
     {
        // Prepare json file to send error
        $retValue = '{"id":0,"error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
     }
     
     function sendResultInfoAsJson( $obj )
	{
	    // Send json file
		header('Content-type: application/json');
		echo $obj;
	}
    
    // Receive user's id and contact's email, phone number, first and last name
    function getRequestInfo()
    {
		return json_decode(file_get_contents('php://input'),true);
    }