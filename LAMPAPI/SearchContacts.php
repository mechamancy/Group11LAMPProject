<?php

$inData = getRequestInfo();

$searchResults = "";
$searchCount = 0;

$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
if ($conn->connect_error) 
{
	returnWithError( $conn->connect_error );
} 
else
{
	$firstName = "%" . $inData["firstName"] . "%";
	$lastName = "%" . $inData["lastName"] . "%";
	//$searchName = "%" . $inData["search"] . "%";
	$stmt = $conn->prepare("select FirstName,LastName,Phone,Email,DateCreated,UserID,ID from Contacts where FirstName like ? and LastName like ? and UserID=?");
	$stmt->bind_param("sss", $firstName, $lastName, $inData["userId"]);
	$stmt->execute();

	$result = $stmt->get_result();

	while($row = $result->fetch_assoc())
	{
		if( $searchCount > 0 )
		{
			$searchResults .= ",";
		}
		$searchCount++;
		$searchResults .= '{"firstName":"' . $row["FirstName"] . '","lastName":"' . $row["LastName"] . '","dateCreated":"' . $row["DateCreated"] . '","phone":"' . $row["Phone"] . '","email":"' . $row["Email"] . '", "userId" : "' . $row["UserID"].'", "ID" : "' . $row["ID"]. '"}';
	}

	if( $searchCount == 0 )
	{
		returnWithError( "No Records Found" );
	}
	else
	{
		returnWithInfo( $searchResults );
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
	$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
	sendResultInfoAsJson( $retValue );
}

function returnWithInfo( $searchResults )
{
	$retValue = '{"results":[' . $searchResults . '],"error":""}';
	sendResultInfoAsJson( $retValue );
}

?>
