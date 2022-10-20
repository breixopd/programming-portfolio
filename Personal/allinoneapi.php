<?php
header("Content-Type: text/html; charset=utf-8");

// API with random tools that may be useful for developing websites

// Generate fake identity with name, address, phone number, email, and date of birth
if (isset($_GET['identity'])) {
    // Generate name
    $firstName = array("John", "Jane", "Mary", "Bob", "Tom", "Jack", "Linda", "Sue", "John", "Jane", "Mary", "Bob", "Tom", "Jack", "Linda", "Sue");
    $lastName = array("Smith", "Johnson", "Williams", "Jones", "Brown", "Davis", "Miller", "Wilson", "Moore", "Taylor", "Anderson", "Thomas", "Jackson", "White", "Harris");
    $name = $firstName[rand(0, count($firstName) - 1)] . " " . $lastName[rand(0, count($lastName) - 1)];
    // Get random address from google maps
    // If status = REQUEST_DENIED then no address found
    $address = "";
    $status = "";
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFMBWY";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $data = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($data, true);
    $status = $data['status'];
    if ($status == "OK") {
        $address = $data['results'][0]['formatted_address'];
    } else {
        $address = "Address not found";
    }
    // Generate phone number
    $phone = "+" . rand(100000000, 999999999);
    // Generate email
    $providers = array("outlook.com", "gmail.com", "yahoo.com", "hotmail.com", "aol.com", "msn.com", "icloud.com", "mail.com", "comcast.net", "verizon.net", "att.net", "sbcglobal.net", "bellsouth.net", "charter.net", "shaw.ca", "rogers.com", "optusnet.com.au", "telus.net", "googlemail.com");
    // Raplace spaces with dots
    $email = str_replace(" ", ".", $name) . "@" . $providers[rand(0, count($providers) - 1)];
    //$email = $name . "@" . $providers[rand(0, count($providers) - 1)];
    // Generate date of birth
    $date = rand(1, 28) . "-" . rand(1, 12) . "-" . rand(1940, 2000);
    // Generate fake identity
    echo $name . " | " . $address . " | " . $phone . " | " . $email . " | " . $date;
}

// Generate random numbers, strings, emails, etc
elseif (isset($_GET['random'])) {
	$action = $_GET['random'];
    // Random number
	if ($action == 'number') {
		$random = rand(1, 100);
		echo $random;
	}
    // Random string
	if ($action == 'string') {
		$random_string = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
		echo $random_string;
	}
    // Random color (hex)
	if ($action == 'color') {
		$random_color = '#' . substr(str_shuffle("0123456789abcdef"), 0, 6);
		echo $random_color;
	}
    // Random hex number
	if ($action == 'hex') {
		$random_hex = substr(str_shuffle("0123456789abcdef"), 0, 6);
		echo $random_hex;
	}
    // Random decimal number
	if ($action == 'float') {
		$random_float = rand(1, 100) / rand(1, 100);
		echo $random_float;
	}
    // Random email address
    if ($action == 'email') {
		$random_email = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10).'@'.substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10).'.com';
		echo $random_email;
	}
    // Random phone number
    if ($action == 'phone') {
        $random_phone = '+'.rand(1, 9).rand(1, 9).rand(1, 9).rand(1, 9).rand(1, 9).rand(1, 9).rand(1, 9).rand(1, 9).rand(1, 9);
        echo $random_phone;
    }
}

// Generate lorem ipsum text
elseif (isset($_GET['lorem'])) {
    $action = $_GET['lorem'];
    // Generate lorem ipsum text
    $words = $_GET['lorem'];
    // Display lorem ipsum text with specified number of words
    echo lorem_ipsum($words);
} else {
    // Output all available actions
    echo '<h1>All-in-one API</h1>';
    echo '<p>This is a simple API that allows you to generate random numbers, strings, emails, etc.</p>';
    echo '<p>The following actions are available:</p>';
    echo '<ul>';
    echo '<li><a href="?random=number">number</a> - Generate a random number between 1 and 100</li>';
    echo '<li><a href="?random=string">string</a> - Generate a random string of 10 characters</li>';
    echo '<li><a href="?random=color">color</a> - Generate a random color (hex)</li>';
    echo '<li><a href="?random=hex">hex</a> - Generate a random hex number</li>';
    echo '<li><a href="?random=float">float</a> - Generate a random decimal number</li>';
    echo '<li><a href="?random=email">email</a> - Generate a random email address</li>';
    echo '<li><a href="?random=phone">phone</a> - Generate a random phone number</li>';
    echo '<li><a href="?identity">identity</a> - Generate fake identity with name, address, phone number, email, and date of birth</li>';
    echo '<li><a href="?lorem=100">lorem</a> - Generate lorem ipsum text with specified number of words (change number at "lorem=" with however many words you want)</li>';
    echo '</ul>';
}

// Lorem ipsum function
function lorem_ipsum($words) {
    // Open lorem.txt file
    $file = fopen("lorem.txt", "r");
    // Read file
    $lorem = fread($file, filesize("lorem.txt"));
    $lorem = explode(" ", $lorem);
    $lorem = array_slice($lorem, 0, $words);
    $lorem = implode(" ", $lorem);
    return $lorem;
}
?>