<?php
// Establish database connection (replace with your actual credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bbdms";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data
$fullName = $_POST['fullName'];
$email = $_POST['email'];
$bloodType = $_POST['bloodType'];
$units = $_POST['units'];

// Check blood stock availability
$stockCheckSql = "SELECT units FROM blood_stock WHERE blood_type = '$bloodType'";
$result = $conn->query($stockCheckSql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $Units = $row['units'];

    if ($Units >= $units) {
        // Blood stock is available, proceed with the request
        $updateStockSql = "UPDATE blood_stock SET units = units - $units WHERE blood_type = '$bloodType'";
        $conn->query($updateStockSql);

        // Insert data into the database
        $insertRequestSql = "INSERT INTO blood_requests (full_name, email, blood_type, units) VALUES ('$fullName', '$email', '$bloodType', $units)";

        if ($conn->query($insertRequestSql) === TRUE) {
            echo "Request submitted successfully!";
        } else {
            echo "Error: " . $insertRequestSql . "<br>" . $conn->error;
        }
    } else {
        // Blood stock is not sufficient
        echo "Sorry, the requested blood type is not currently available in sufficient quantity.";
    }
} else {
    // Error in checking blood stock
    echo "Error checking blood stock availability.";
}

?>
