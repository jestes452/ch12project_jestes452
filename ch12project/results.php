
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>

    <?php
    // Check if the form was submitted using POST.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Get the submitted email address.
        $email = $_POST["email"] ?? "";

        // Remove unnecessary whitespace.
        $email = trim($email);

        // Sanitize the email address.
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Connect to the MySQL database.
        $host = "localhost";
        $username = "root";
        $password = trim(file_get_contents("C:/Ampps/ampps/data/my.conf"));
        $database = "taus_data";

        $conn = mysqli_connect(
            $host,
            $username,
            $password,
            $database
        );

        // Check the database connection.
        if (!$conn) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        // Search for the student email address.
        $sql = "SELECT firstName, lastName, email
                FROM tbl_student
                WHERE email = ?";

        // Prepare the SQL query.
        $stmt = mysqli_prepare($conn, $sql);

        // Bind the submitted email to the query.
        mysqli_stmt_bind_param($stmt, "s", $email);

        // Execute the SQL query.
        mysqli_stmt_execute($stmt);

        // Get the query results.
        $result = mysqli_stmt_get_result($stmt);

        // Check if a matching student was found.
        if (mysqli_num_rows($result) > 0) {

            $student = mysqli_fetch_assoc($result);

            $firstName = htmlspecialchars($student["firstName"]);
            $lastName = htmlspecialchars($student["lastName"]);
            $studentEmail = htmlspecialchars($student["email"]);

            echo '<div class="message">';
            echo "<h2>Student Found</h2>";
            echo "<p>Name: $firstName $lastName</p>";
            echo "<p>Email: $studentEmail</p>";
            echo '</div>';

        } else {

            echo '<div class="message">';
            echo "<h2>Student Not Found</h2>";
            echo "<p>The email address was not found.</p>";
            echo '</div>';

        }

        // Close the statement and database connection.
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

    }
    ?>

    <p><a href="index.php">Return to Search</a></p>

</body>
</html>
