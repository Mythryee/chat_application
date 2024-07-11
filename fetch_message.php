<?php
session_start();
include("db.php");

if (!isset($_SESSION['user'])) {
    exit("You are not logged in");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $sender = mysqli_real_escape_string($conn, $_POST['sender']);
    $receiver = mysqli_real_escape_string($conn, $_POST['receiver']);

    $sql = "SELECT * FROM chat_messages 
            WHERE (sender='$sender' AND receiver='$receiver') 
            OR (sender='$receiver' AND receiver='$sender') 
            ORDER BY created_at";
    
    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="message"><strong>' . htmlspecialchars($row['sender']) . '</strong>: ' . htmlspecialchars($row['message']) . '</div>';
        }
    } else {
        echo "Error fetching messages: " . mysqli_error($conn);
    }
}
?>
