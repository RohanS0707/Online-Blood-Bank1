<!DOCTYPE html>
<html>
<head>
    <title>Payment Page</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: pink;
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
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }
        .error {
            color: red;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Payment Page</h2>
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root"; // Your MySQL username
        $password = ""; // Your MySQL password
        $dbname = "bbdms"; // Your database name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'];
            $blood_type = $_POST['blood_type'];
            $units = $_POST['units'];
            $payment_method = $_POST['payment_method'];
            $total_amount = $units * 500;

            // Insert payment details into the payments table
            $sql_insert = "INSERT INTO payments (name, blood_type, units, amount, payment_method) 
                           VALUES ('$name', '$blood_type', '$units', '$total_amount', '$payment_method')";
            if ($conn->query($sql_insert) === TRUE) {
                echo "<p>Payment successful!</p>";

                // Update blood_stock table to decrease the purchased units
                $sql_update = "UPDATE blood_stock SET units = units - '$units' WHERE blood_type = '$blood_type'";
                if ($conn->query($sql_update) === TRUE) {
                    echo "<p>Blood   purchess successfully!</p>";
                } else {
                    echo "Error updating blood stock: " . $conn->error;
                }
            } else {
                echo "Error: " . $sql_insert . "<br>" . $conn->error;
            }
        }

        $conn->close();
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>
            <label for="blood_type">Blood Type:</label>
            <input type="text" id="blood_type" name="blood_type" required><br>
            <label for="units">Units:</label>
            <input type="number" id="units" name="units" min="1" required onchange="updateTotalAmount()"><br>
            <label for="total_amount">Total Amount (INR):</label>
            <input type="text" id="total_amount" name="total_amount" readonly><br>
            <label for="payment_method">Payment Method:</label>
            <select id="payment_method" name="payment_method" onchange="togglePaymentFields(this.value)" required>
                <option value="">Select Payment Method</option>
                <option value="card">Credit/Debit Card</option>
                <option value="upi">UPI</option>
                <option value="cod">Cash on Delivery</option>
            </select><br>
            <div id="card_fields" class="card-fields">
                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="card_number"><br>
                <label for="expiry_date">Expiry Date:</label>
                <input type="text" id="expiry_date" name="expiry_date"><br>
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv"><br>
            </div>
            <div id="upi_fields" class="upi-fields">
                <label for="upi_id">UPI ID:</label>
                <input type="text" id="upi_id" name="upi_id"><br>
            </div>
            <button type="submit">Purchase</button>
        </form>
    </div>

    <script>
        function updateTotalAmount() {
            var units = document.getElementById("units").value;
            var totalAmount = units * 500;
            document.getElementById("total_amount").value = totalAmount;
        }

        function togglePaymentFields(paymentMethod) {
            var cardFields = document.getElementById("card_fields");
            var upiFields = document.getElementById("upi_fields");

            if (paymentMethod === "card") {
                cardFields.style.display = "block";
                upiFields.style.display = "none";
            } else if (paymentMethod === "upi") {
                cardFields.style.display = "none";
                upiFields.style.display = "block";
            } else {
                cardFields.style.display = "none";
                upiFields.style.display = "none";
            }
        }
    </script>