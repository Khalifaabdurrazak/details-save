<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = htmlspecialchars($_POST["first_name"]);
    $lastName  = htmlspecialchars($_POST["last_name"]);
    $country   = htmlspecialchars($_POST["country"]);
    $address   = htmlspecialchars($_POST["address"]);
    $amount    = htmlspecialchars($_POST["amount"]);
    $email     = htmlspecialchars($_POST["email"]);
    $phone     = htmlspecialchars($_POST["phone"]);

    $data = "First Name: $firstName | Last Name: $lastName | Country: $country | Address: $address | Amount: $amount | Email: $email | Phone: $phone" . PHP_EOL;

    file_put_contents("users.txt", $data, FILE_APPEND | LOCK_EX);

    echo "<h2>✅ Your details have been saved successfully!</h2>";
} else {
    echo "❌ Invalid request!";
}
?>
