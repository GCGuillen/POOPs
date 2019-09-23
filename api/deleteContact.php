<?php

    $inData = getRequestInfo();
    
    $id = $inData['userid'];
    $tableId = $inData['tableId'];
    
    $sql = $conn->prepare("DELETE FROM contacts WHERE userId=? AND referenceUser=?");
    $sql->bind_param("ii", $tableId, $id);
    $sql->execute();
    
    // Receive user's id and contact's email, phone number, first and last name
    function getRequestInfo()
    {
		return json_decode(file_get_contents('php://input'),true);
    }