<!DOCTYPE html>
<html>
<head>
    <title>Blood Availability</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 50%;
            margin: 0 auto;
        }
        h2 {
            text-align: center;
        }
        form {
            text-align: center;
        }
        .error {
            color: red;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Blood Availability</h2>
        <?php
        // Database connection
        $servername = "localhost";
        $username = "username";
        $password = "password";
        $dbname = "bbdms";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'];
            $blood_type = $_POST['blood_type'];
            $units = $_POST['units'];

            // Check blood availability
            $sql = "SELECT units FROM blood_stock WHERE blood_type = '$blood_type'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $available_units = $row["units"];

                if ($available_units >= $units) {
                    // Redirect to payment page with user's name
                    header("Location: payment.php?name=$name&blood_type=$blood_type&units=$units");
                    exit();
                } else {
                    echo "<p class='error'>Blood Not Available</p>";
                }
            } else {
                echo "<p class='error'>Blood Not Available</p>";
            }
        }
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="name">Your Name:</label><br>
            <input type="text" id="name" name="name" required><br><br>
            <label for="blood_type">Blood Type:</label><br>
            <input type="text" id="blood_type" name="blood_type" required><br><br>
            <label for="units">Required Units:</label><br>
            <input type="number" id="units" name="units" min="1" required><br><br>
            <button type="submit">Check Availability</button>
        </form>
    </div>
</body>
</html>
