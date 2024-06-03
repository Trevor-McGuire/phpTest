<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

// Get user ID
$username = $_SESSION['username'];
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

// Fetch reversed words for the logged-in user
$sql = "SELECT word, reversed_word, created_at FROM reversed_words WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reversed Words History</title>
</head>
<body>
    <h1>Reversed Words History</h1>
    <a href="reverse.php">Go back to reverse page</a> | <a href="logout.php">Logout</a>
    <h2>History:</h2>
    <ul>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['word']} -> {$row['reversed_word']} ({$row['created_at']})</li>";
        }
        ?>
    </ul>
</body>
</html>
