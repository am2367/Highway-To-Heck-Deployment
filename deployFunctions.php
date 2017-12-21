<?php

global $argv;
//connect to db
$mysqli = new mysqli("127.0.0.1","root","pc329pw","packages");//Your DB info here (localhost, mysql root, mysql pass, database)

//connection status

if ($mysqli->connect_errno) {
	die("Connection failed: " . $mysqli->connect_error);
}
echo "Connected Successfully"."\r\n";

$statement="";

//takes arguments from bash scripts
	if ($argv[1] == "package"){
	$statement = "INSERT INTO packageTable(path, status) VALUES ('$argv[2]', '$argv[3]');";
	//echo $statement."\r\n";
	echo "New record created successfully"."\r\n";
	}
	elseif ($argv[1] == "extract" && $argv[4] == "latest"){
	$statement = "SELECT path FROM packageTable WHERE status='working' ORDER BY version DESC LIMIT 1;";
	//echo $statement."\r\n";
	echo "Extracted latest file"."\r\n";
	}
	elseif ($argv[1] == "extract" && $argv[4] == "QA"){
	$statement = "SELECT path FROM packageTable WHERE status='QA' ORDER BY version DESC LIMIT 1;";
	//echo $statement."\r\n";
	echo "Extracted latest QA file"."\r\n";
	}
	elseif ($argv[1] == "extract" && $argv[4] == "V"){
	$statement = "SELECT path FROM packageTable WHERE version='$argv[5]';";
	}
	elseif ($argv[1] == "rollback"){
	$statement = "SELECT path FROM packageTable WHERE status='working' ORDER BY version DESC LIMIT 1;";
	}
	else {
	echo "Unrecognized Instruction"."\r\n";
	}
//inserts data in to db or quits
$result = $mysqli->query($statement);

if ($result === TRUE) {
	echo "Query sent successfully"."\r\n";
}
elseif ($result->num_rows > 0) {
	echo "Query sent successfully"."\r\n";
	while($row = mysqli_fetch_assoc($result)) {
        	//echo "path: ".$row["path"]."\r\n";
		$pathdir = $row["path"];
		file_put_contents("/home/pc329/bin/latest",$pathdir);

		
	}
}
else {
	echo "Error: " . $statement . "\r\n" . $mysqli->connect_error;
}


//disconnects from db
$mysqli->close();

?>
