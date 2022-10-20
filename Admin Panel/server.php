<?php

// Start session to be able to pull data from other scripts and tabs
session_start();

// Start an error array to keep track of errors happening while script runs, this array is then used by errors.php to display the errors to the user
$errors = array(); 

// Connect to mysql database
$db = mysqli_connect("IP", "Username", "Password", "admin_dashboard");
if (!$db) {
    echo "MySQL Error: " . mysqli_connect_error();
}

// If user registering
if (isset($_POST['reg_user'])) {

    // Get variables from POST request sent by form, mysqli_real_escape_string sanitises values for use with a database
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $pass = mysqli_real_escape_string($db, $_POST['password']);
    $pass2 = mysqli_real_escape_string($db, $_POST['password_repeat']);
    $key = mysqli_real_escape_string($db, $_POST['key']);

    // If passwords don't match tell user and add to error list
    if ($pass != $pass2) {

        // Send error to array to be displayed later
        array_push($errors, "The 2 passwords must match!");
    }

    // Check if user already exists in database, if they exist send message to user and add to errors array
    $user_check_query = "SELECT * FROM users WHERE useremail='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    if ($result === FALSE) {
        die(mysqli_error($db));
    }

    $user = mysqli_fetch_assoc($result);
    if ($user) {
        array_push($errors, "User already in system!");
    }

    // If key correct make user admin 
    if ($key != "") {
        if ($key != "admin") {
            array_push($errors, "Invalid key!");
        } else {
            $admin = 1;
        }
    } else {
        $admin = 0;
    }

    // If there are no errors in array finish registration
    if (count($errors) == 0) {

        // Hash password with default PHP encryption
        $userpass = password_hash($pass, PASSWORD_DEFAULT);

        // Insert details into database
        $sql = "INSERT INTO users (username,useremail,userpass,admin)
        VALUES ('$name','$email','$userpass','$admin')";
        mysqli_query($db, $sql);

        // Make a session with unique values so user can log into their own area
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['admin'] = $admin;
        $_SESSION['success'] = "You are now logged in";

        // Redirect user to dashboard
        header('location: dashboard.php');
    }
}

// If user logging in
if (isset($_POST['log_user'])) {

    // Log user IP and put in DB
    function logIP($email, $db) {
        // If user using cloudflare log IP as proxy
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $date = date("Y-m-d H:i:s");
        // Write log to database
        $sql = "INSERT INTO login (ip, date, email)
        VALUES ('$ip','$date','$email')";
        mysqli_query($db, $sql);
        // If query errors
        if (!mysqli_query($db, $sql)) {
            die(mysqli_error($db));
        }
        // If there is an item in the database with the same values delete it
        $sql = "DELETE FROM login WHERE ip='$ip' AND date='$date' AND email='$email' LIMIT 1";
        mysqli_query($db, $sql);
    }

    // Get variables from POST request sent by form, mysqli_real_escape_string sanitises values for use with a database
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $pass = mysqli_real_escape_string($db, $_POST['password']);

    // Query to select user with email written in login 
    $sql = "SELECT * FROM users WHERE useremail='$email'";
    $row = mysqli_fetch_assoc(mysqli_query($db, $sql));

    // If there is a row meaning user exists, carry on
    if ($row) {

        // Check that password hashes match meaning that they are the same pasword
        if (password_verify($pass, $row['userpass'])) {
            
            // If there are no errors in array finish login
            if (count($errors) == 0) {
                // Make a session with unique values so user can log into their own area
                // and so data can be used later
                $name = $row['username'];
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $name;
                $_SESSION['admin'] = $row['admin'];

                $_SESSION['success'] = "You are now logged in";
                
                // Redirect user to dashboard
                header('location: dashboard.php');
            }
        } else{

            // If the details are wrong send error to user, errors are kept generic to keep malicious parties from guessing details and doing other social enginnering attacks
            array_push($errors, "Incorrect email/password combination!");
            logIP($email, $db);
        }

    } else {

        // If the user doesnt exist send error to user
        array_push($errors, "Email does not match our records");
        logIP($email, $db);
    }
}

# if user has submitted text, echo it and add to database
if (isset($_POST['post_note'])) {
    $text = $_POST['text'];
    $title = $_POST['title'];
    $username = $_SESSION['name'];
    $date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO cards (title, text, date, username) VALUES ('$title', '$text', '$date', '$username')";
    $result = mysqli_query($db, $sql);
    if ($result === FALSE) {
        die(mysqli_error($db));
    }
    header("Refresh:0");
}

// Delete note
if (isset($_GET['del_note'])) {
    $note_id = $_GET['del_note'];
    $sql = "DELETE FROM cards WHERE id='$note_id'";
    $result = $db->query($sql);
    header("Location: dashboard.php");
  }

// Clear notes
if(isset($_GET['notes']) == "clear") {
    // If no cards in database, do nothing
    $sql = "SELECT * FROM cards";
    $result = mysqli_query($db, $sql);
    if ($result === FALSE) {
        die(mysqli_error($db));
    }
    if (mysqli_num_rows($result) == 0) {
        // Do nothing
    } else {
        $name = $_SESSION['name'];
        $sql = "DELETE FROM cards WHERE username='$name'";
        $result = mysqli_query($db, $sql);
        if ($result === FALSE) {
            die(mysqli_error($db));
        }
    }
    header("dashboard.php");
} 

// Clear logs
if(isset($_GET['logs']) == "clear") {
    // If no cards in database, do nothing
    $sql = "SELECT * FROM login";
    $result = mysqli_query($db, $sql);
    if ($result === FALSE) {
        die(mysqli_error($db));
    }
    if (mysqli_num_rows($result) == 0) {
        // Do nothing
    } else {
        $sql = "TRUNCATE TABLE login";
        $result = mysqli_query($db, $sql);
        if ($result === FALSE) {
            die(mysqli_error($db));
        }
    }
    header("dashboard.php");
} 

// Other functions used throughout site
function dynamicBar($variableChecked, $barID) {
    // Dynamic colour progress bar
    if ($variableChecked < 50) {
        echo "<style> #" . $barID . " {background-color: #28a745;} </style>"; // Green
    } else if ($variableChecked < 75) {
        echo "<style> #" . $barID . " {background-color: #ffc107;} </style>"; // Orange
    } else {
        echo "<style> #" . $barID . " {background-color: #dc3545;} </style>"; // Red
    }
}
?>