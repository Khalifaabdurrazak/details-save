<?php
// Set your admin password here
$adminPassword = "myStrongPassword123"; 

// File where details are stored
$filename = "../users.txt";

// If password not submitted yet
if (!isset($_POST['password']) && !isset($_POST['action'])) {
    echo '<h2>🔐 Admin Login</h2>
          <form method="POST">
              <input type="password" name="password" placeholder="Enter Password" required>
              <button type="submit">Login</button>
          </form>';
    exit;
}

// If submitted, check password
if (isset($_POST['password']) && $_POST['password'] !== $adminPassword) {
    echo "<p style='color:red;'>❌ Incorrect password!</p>
          <a href='view.php'>Try again</a>";
    exit;
}

// Handle actions (Delete or Mark)
if (isset($_POST['action']) && isset($_POST['line'])) {
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $lineIndex = intval($_POST['line']);

    if ($_POST['action'] === "delete") {
        unset($lines[$lineIndex]); // remove the line
    } elseif ($_POST['action'] === "mark") {
        $lines[$lineIndex] .= " | Status: ✔ Marked"; // add a status field
    }

    file_put_contents($filename, implode(PHP_EOL, $lines) . PHP_EOL);
}

// Reload lines after any action
$lines = file_exists($filename) ? file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

echo "<h2>📋 Saved User Details</h2>";

if (!empty($lines)) {
    echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse;'>
            <tr style='background:#007bff;color:#fff;'>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Country</th>
                <th>Address</th>
                <th>Amount</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>";

    foreach ($lines as $index => $line) {
        $parts = explode(" | ", $line);

        echo "<tr>";
        $status = "Pending"; // default
        foreach ($parts as $part) {
            $value = explode(": ", $part, 2);
            if (trim($value[0]) === "Status") {
                $status = $value[1];
            } else {
                echo "<td>" . htmlspecialchars($value[1] ?? '') . "</td>";
            }
        }
        echo "<td>" . htmlspecialchars($status) . "</td>";

        // Action buttons
        echo "<td>
                <form method='POST' style='display:inline;'>
                    <input type='hidden' name='line' value='$index'>
                    <input type='hidden' name='action' value='mark'>
                    <button type='submit'>✔ Mark</button>
                </form>
                <form method='POST' style='display:inline;'>
                    <input type='hidden' name='line' value='$index'>
                    <input type='hidden' name='action' value='delete'>
                    <button type='submit' onclick=\"return confirm('Are you sure?');\">🗑 Delete</button>
                </form>
              </td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No user details found.";
}
?>
