<?php
    
    $inData = getRequestInfo();

  $firstName = $inData["firstName"];
	$lastName = $inData["lastName"];
  $userId = $inData["userId"];
	$login = $inData["login"];
	$password = $inData["password"];
  $phone = $inData["phone"];
	$email = $inData["email"];

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else {
    $stmt = $conn->prepare("UPDATE Contacts SET FirstName = ?, LastName = ?, Email = ?, Phone = ? WHERE ID = ?");
    $stmt->bind_param("ssssi", $firstName, $lastName, $email, $phone, $userId);
    $stmt->execute();

    if ($result->num_rows > 0) {
        returnWithError("");
    }

    else {
        returnWithError("Error in Finding Contact.");
    }

    $stmt->close();
    $conn->close();
}

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
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

?>
