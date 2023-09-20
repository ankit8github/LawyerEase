<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract form data
    $selectedUsertype = $_POST["selected_usertype"];
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $gender = $_POST["gender"];
    $password = $_POST["password"];

    // Additional fields for consultants
    $specializations = isset($_POST["specialization"]) ? implode(", ", $_POST["specialization"]) : "";
    $experience = isset($_POST["experience"]) ? $_POST["experience"] : "";


    function connectToDatabase() {
        $dbHost = "localhost"; 
        $dbUser = "LawyerEase"; 
        $dbPass = ""; 
        $dbName = "data"; 

        $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    $conn = connectToDatabase();

    // Check the user type and insert data into the appropriate table
    if ($selectedUsertype === "client") {
        $sql = "INSERT INTO clients (fullname, email, gender, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $fullname, $email, $gender, $password);
    } elseif ($selectedUsertype === "consultant") {
        $sql = "INSERT INTO consultants (fullname, email, gender, password, specialization, experience) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $fullname, $email, $gender, $password, $specializations, $experience);
    } else {
        die("Invalid user type.");
    }

    if ($stmt->execute()) {
        // Registration successful
        echo "Registration successful!";
    } else {
        // Registration failed
        echo "Error: " . $stmt->error;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
