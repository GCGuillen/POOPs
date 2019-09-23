<?php 

    require 'dbh.php';
    
    
    $inData = getRequestInfo();
    
    $searchCount = 0;
    
    $id = $inData['userId'];
    $search = $inData['search'];
    $file = fopen("testfile.txt", "w");
    fwrite($file, $search);
    
    $sql = $conn->prepare("SELECT * FROM contacts WHERE (firstName=? OR lastName=?) AND (referenceUser=?)");
    
    $sql->bind_param("ssi", $search, $search, $id);
    $sql->execute();
    $result = $sql->get_result();
    
    fwrite($file,"we here  id: ");
    fwrite($file, $id);
    fwrite($file, "\n aftererrrrr");
    
    // $file = fopen("testfile.txt", "w");
    // fwrite($file,$lastName)
    
    if($result->num_rows == 0)
    {
        returnWithError("No contacts");
    }
    else
    {
        while($row = $result->fetch_assoc())
		{
			if( $searchCount > 0 )
			{
				$searchResults .= ",";
			}
			$searchCount++;
			$searchResults .= '"' . $row["firstName"] . $row["lastName"] . $row["userId"] . '"';
		}
		fwrite($file, $searchResults);
		returnWithInfo($searchResults);
    }
    
    function returnWithInfo( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
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


