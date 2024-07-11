<?php
    session_start();
    include("db.php");

    if (!isset($_SESSION['user'])) {
        exit("You are not logged in");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $sender = $_POST['sender'];
        $receiver = $_POST['receiver'];
        $message = $_POST['message'];

        $sql = "INSERT INTO `chat_messages` (`sender`, `receiver`, `message`) VALUES ('$sender', '$receiver', '$message')";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            echo "<script>alert('There was an error submitting the message')</script>";
        }
    }
?>
