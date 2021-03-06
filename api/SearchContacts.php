<?php 

    require 'dbh.php';
    
    
    $inData = getRequestInfo();
    
    $searchCount = 0;
    
    $id = $inData['userId'];
    $search = $inData['search'];
    
    // Prepare statement to prevent SQL injection attacks
    $sql = $conn->prepare("SELECT * FROM contacts WHERE (firstName=? OR lastName=?) AND (referenceUser=?)");
    
    $sql->bind_param("ssi", $search, $search, $id);
    $sql->execute();
    $result = $sql->get_result();
    
    // Search came up empty in database
    if($result->num_rows == 0)
    {
        returnWithError("No contacts");
    }
    else
    {
        // While theres another row in search, store it in $row variable
        while($row = $result->fetch_assoc())
		{
			if( $searchCount > 0 )
			{
				$firstNameResults .=",";
				$lastNameRestults .=",";
				$userIdResults .=",";
				$emailResults .=",";
				$phoneNumberRestults .=",";
			}
			$searchCount++;
			$firstNameResults .= '"' . $row["firstName"] . '"';
			$lastNameRestults .= '"' . $row["lastName"] . '"';
			$userIdResults .= '"' . $row["UserId"] . '"';
			$emailResults .= '"' . $row["email"] . '"';
			$phoneNumberRestults .= '"' . $row["phoneNumber"] . '"';
		}
		returnWithInfo( $firstNameResults, $lastNameRestults, $userIdResults, $emailResults, $phoneNumberRestults);
    }
    
    // Prepare json file to send list of contacts
    function returnWithInfo( $firstNameResults, $lastNameRestults, $userIdResults, $emailResults, $phoneNumberRestults)
	{
		$retValue = '{"firstName":[' . $firstNameResults . '], "lastName":[' . $lastNameRestults . '], "userId":[' . $userIdResults . '], "email":[' . $emailResults . '], "phoneNumber":[' . $phoneNumberRestults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
	// Prepare a json file to send an error
	function returnWithError( $err)
    {
      $retValue = '{"id":0,"error":"' . $err . '"}';
      sendResultInfoAsJson($retValue);
    }
    // Send off json
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


