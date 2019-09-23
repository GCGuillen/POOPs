<?php
    require 'dbh.php';

    $inData = getRequestInfo();
    
    //get JSON object from javascript
    
    $id = $inData['UserId'];
    $tableId = $inData['tableId'];

    $send = "Sent";
    
    //prepare a query to mysql to delete contacts from the user that matches an ID
    
    $sql = $conn->prepare("DELETE FROM contacts WHERE (referenceUser=? AND UserId=?)");
    $sql->bind_param("ii", $id, $tableId);
    $sql->execute();
    returnWithInfo();
    
    // Receive user's id and contact's email, phone number, first and last name
    function getRequestInfo()
    {
		return json_decode(file_get_contents('php://input'),true);
    }
    
    function returnWithInfo($send)
	{
	    $retValue = '{"results":[' . $send . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}
    
     function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}