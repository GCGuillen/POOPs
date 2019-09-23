<?php 

    require 'dbh.php';
    
    
    //grab the JSON sent by the javascript
    $inData = getRequestInfo();
    
    $searchCount = 0;
    
    $id = $inData['userId'];

    //Start an api call to the database to select every entry row that has the user defined by JSON in contacts
    
    $sql = $conn->prepare("SELECT * FROM contacts WHERE referenceUser=?");
    
    $sql->bind_param("i", $id);
    $sql->execute();
    $result = $sql->get_result();
    
    //check if there are no users associated with the JSON user
    if($result->num_rows == 0)
    {
        returnWithError("No contacts");
    }
    
    //get the information of every row returned in the result
    else
    {
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
			$emailResults .= '"' . $row["email"] . '"';
			$phoneNumberRestults .= '"' . $row["phoneNumber"] . '"';
			$userIdResults .= '"' . $row["UserId"] . '"';
		}
		
		returnWithInfo( $firstNameResults, $lastNameRestults, $emailResults, $phoneNumberRestults);
    }
    //turn the results into JSON format
     function returnWithInfo( $firstNameResults, $lastNameRestults, $emailResults, $phoneNumberRestults)
	{
		
		$retValue = '{"firstName":[' . $firstNameResults . '], "lastName":[' . $lastNameRestults . '], "userId":[' . $userIdResults . '], "email":[' . $emailResults . '], "phoneNumber":[' . $phoneNumberRestults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
	//return an error if it fails a test case
	function returnWithError( $err)
   {
      $retValue = '{"id":0,"error":"' . $err . '"}';
      sendResultInfoAsJson($retValue);
   }
    //return the XMLHttpRequest a JSON object
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
