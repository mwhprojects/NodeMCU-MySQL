<?php
	/*
		Description: PHP code for NodeMCU project which inserts the date and two switch statuses into a MySQL database table.
		Create a table in a database with columns: "date" (text), "s1" (boolean), "s2" (boolean).
		
		Author: Matthew W. - www.mwhprojects.com - www.github.com/mwhprojects/NodeMCU-MySQL
	*/
	
	/* This PHP file is used to insert values into the database as well as display them on a webpage for you to view. The following PHP code at the top of this file is to get and insert values into the database, if the parameters are set in the URL. The PHP code later on is to display the values on the page. */
	// Get values.
	$s1 = $_GET['s1'];
	$s2 = $_GET['s2'];
	$pass = $_GET['pass'];
	date_default_timezone_set('America/Toronto');
	$date= date('m-d-Y H:i:s') ;
	
	// Set password. This is just to prevent some random person from inserting values. Must be consistent with YOUR_PASSCODE in Arduino code.
	$passcode = "YOUR_PASSCODE";
	
	// Check if password is right. (If there is no password, assume no data is trying to be entered and skip over this.)
	if(isset($pass) && ($pass == $passcode)){
		// If all values are present, insert it into the MySQL database.
		if(isset($s1)&&isset($s2)){
			// Database credentials
			$servername = "YOUR_HOST";
			$username = "YOUR_DATABASE_USER";
			$dbname = "YOUR_DATABASE_NAME";
			$password = "YOUR_DATABASE_PASSWORD";
			// Create connection.
			$conn = mysqli_connect($servername, $username, $password, $dbname);
			if (!$conn) {
				die("Connection failed: " . mysqli_connect_error());
			}
			
			// Insert values into table. Replace YOUR_TABLE_NAME with your database table name.
			$sql = "INSERT INTO YOUR_TABLE_NAME (date, s1, s2)
			VALUES ('$date', '$s1', '$s2')";
			if (mysqli_query($conn, $sql)) {
				echo "OK";
			} else {
				echo "Fail: " . $sql . "<br/>" . mysqli_error($conn);
			}
			
			// Close connection.
			mysqli_close($conn);
		}
	}
?>

<html>
	<head>
		<title>Switch Statuses</title>
		<style>
		html, body{
			background-color: #F2F2F2;
			font-family: Arial;
			font-size: 1em;
		}
		table{
			border-spacing: 0;
			border-collapse: collapse;
			margin: 0 auto;
		}
		th{
			padding: 8px;
			background-color: #FF837A;
			border: 1px solid #FF837A;
		}
		td{
			padding: 10px;
			background-color: #FFF;
			border: 1px solid #CCC;
		}
		
		div.notes{
			font-family: arial;
			text-align: center;
		}
		
		div.current{
			font-size: 58px;
			font-family: arial black;
			text-align: center;
		}
		</style>
	</head>
	<body>
		<?php	
			// Database credentials.
			$servername = "YOUR_HOST";
			$username = "YOUR_DATABASE_USER";
			$dbname = "YOUR_DATABASE_NAME";
			$password = "YOUR_DATABASE_PASSWORD";
			// Number of entires to display.
			$display = 15;
			// Create connection.
			$conn = mysqli_connect($servername, $username, $password, $dbname);
			if (!$conn) {
				die("Connection failed: " . mysqli_connect_error());
			}
			
			// Get the most recent 10 entries.
			$result = mysqli_query($conn, "SELECT id, date, s1, s2 FROM data ORDER BY id DESC LIMIT " . $display . "");
			while($row = mysqli_fetch_assoc($result)) {
				echo "<table><tr><th>Date</th><th>Sensor 1</th><th>Sensor 2</th><th>Status</th></tr>";
				echo "<tr><td>";
				echo $row["date"];
				echo "</td><td>";
				echo $row["s1"];
				echo "</td><td>";
				echo $row["s2"];
				echo "</td><td>";
				if($row["s1"] == 1) echo "Door is open!";
				else echo "Door is closed.";
				echo "</td></tr>";
				$counter++;
			}
			echo "</table>";
			
			// Print number of entries in the database. Replace YOUR_TABLE_NAME with your database table name.
			$row_cnt = mysqli_num_rows(mysqli_query($conn, "SELECT date FROM YOUR_TABLE_NAME"));
			echo "<div class='notes'>Displaying last " . $display . " entries.<br/>The database table has " . $row_cnt . " total entries.</div>";
			
			// Close connection.
			mysqli_close($conn);
		?>
	</body>
</html>