<?php
session_start();
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Blood Request Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["blood_request"])) {
    $user_id = $_SESSION['user_id'];
    $blood_type = $_POST["blood_type"];
    $units = $_POST["units"];
    $hospital_name = $_POST["hospital_name"];
    $contact_person = $_POST["contact_person"];
    $contact_number = $_POST["contact_number"];

    $sql = "INSERT INTO blood_requests (user_id, blood_type, units, hospital_name, contact_person, contact_number)
            VALUES ('$user_id', '$blood_type', $units, '$hospital_name', '$contact_person', '$contact_number')";

    if ($conn->query($sql) === TRUE) {
        echo "Blood request submitted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Request Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 400px;
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
        }

        input, button {
            margin-bottom: 10px;
            padding: 10px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Blood Request Form</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="blood_request" value="1"> <!-- To identify the form submission -->

            <label for="bloodType">Blood Type:</label>
            <input type="text" name="blood_type" id="bloodType" required><br>

            <label for="units">Units:</label>
            <input type="number" name="units" id="units" required><br>

            <label for="hospitalName">Hospital Name:</label>
            <input type="text" name="hospital_name" id="hospitalName" required><br>

            <label for="contactPerson">Contact Person:</label>
            <input type="text" name="contact_person" id="contactPerson" required><br>

            <label for="contactNumber">Contact Number:</label>
            <input type="text" name="contact_number" id="contactNumber" required><br>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
