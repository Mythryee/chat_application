<?php
    include("db.php");
    session_start();
    
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("location: login.php");
        exit;
    }

    $username = $_SESSION['user'];
    $showchatbox = false;

    if (isset($_GET['user'])) {
        $selecteduser = $_GET['user'];
        $selecteduser = mysqli_real_escape_string($conn, $selecteduser);
        $showchatbox = true;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            margin: 20px auto;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 800px;
        }

        .logout {
            float: right;
            text-decoration: none;
            padding: 8px 16px;
            background-color: #ff6347;
            color: #fff;
            border-radius: 3px;
            transition: background-color 0.3s;
        }

        .logout:hover {
            background-color: #e74c3c;
        }

        .account-info {
            margin-top: 20px;
            display: flex;
            flex-direction:column;
            justify-content: space-between;
            align-items: center;
        }

        .welcome {
            flex: 1;
        }

        .user-list {
            flex: 1;
        }

        .user-list h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .user-list ul {
            list-style-type: none;
            padding: 0;
        }

        .user-list ul li {
            margin-bottom: 5px;
        }

        .user-list ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        .user-list ul li a:hover {
            color: #ff6347;
        }

        .chat-box {
            background-color: #fff;
            border: 1px solid #ccc;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }

        .chat-box-header {
            background-color: #f0f0f0;
            padding: 10px 20px;
            border-bottom: 1px solid #ccc;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        .chat-box-header h2 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }

        .close-btn {
            background-color: #ccc;
            border: none;
            color: #333;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 3px;
            transition: background-color 0.3s;
        }

        .close-btn:hover {
            background-color: #999;
            color: #fff;
        }

        .chat-box-body {
            height: 200px;
            overflow-y: auto;
            padding: 10px 20px;
        }

        .message {
            margin-bottom: 10px;
        }

        .message strong {
            font-weight: bold;
            color: #333;
        }

        .chat-form {
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #ccc;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        .chat-form input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-right: 10px;
            font-size: 16px;
        }

        .chat-form button {
            background-color: #ff6347;
            border: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .chat-form button:hover {
            background-color: #e74c3c;
        }
    </style>
    </style>
</head>
<body>
    <div class="container">
        <h1>My Account</h1>
        <a href="logout.php" class="logout">Logout</a>
    </div>
    <div class="account-info">
        <div class="welcome">
            <h1>Welcome, <?php echo $_SESSION['user']?></h1>
        </div>
        <div class="user-list">
            <h2>Select a user to chat with:</h2>
            <ul>
            <?php
                include("db.php");
                $username = $_SESSION['user'];
                $query = "SELECT `user` FROM `user_data` WHERE `user` != '$username'";
                $result = mysqli_query($conn, $query);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $user = $row['user'];
                        echo '<li><a href="chat.php?user='.$user.'">'.$user.'</a></li>';
                    }
                }
            ?>

            </ul>
        </div>

        <div class="chat-box" id="chat-box">
            <div class="chat-box-header">
                <h2><?php echo $_GET['user'] ?></h2>
                <button class="close-btn" onclick="toggleChatBox()">X</button>
            </div>
            <div class="chat-box-body" id="chat-box-body">
                <!-- Messages will be dynamically loaded here -->
            </div>
            <form action="" class="chat-form" id="chat-form">
                <input type="hidden" id="sender" value="<?php echo $_SESSION['user']?>">
                <input type="hidden" id="receiver" value="<?php echo $_GET['user']?>">
                <input type="text" name="message" id="message" placeholder="Enter your message" required>
                <button type="submit">Send</button>
            </form>
        </div>
    </div>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>   
    <script>
        function toggleChatBox() {
            var chatBox = document.getElementById('chat-box');
            if (chatBox.style.display === "none") {
                chatBox.style.display = "block";
            } else {
                chatBox.style.display = "none";
            }
        }

        function fetchMessage() {
            var sender = $('#sender').val();
            var receiver = $('#receiver').val();
            
            $.ajax({
                url: "fetch_message.php",
                type: "POST",
                data: { sender: sender, receiver: receiver },
                success: function(data) {
                    $('#chat-box-body').html(data); // Update chat box with fetched messages
                    scrollChatToBottom();
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching messages:', error);
                }
            });
        }


        function scrollChatToBottom() {
            var chatBox = $('#chat-box-body');
            chatBox.scrollTop(chatBox.prop("scrollHeight"));
        }

        $(document).ready(function() {
            fetchMessage(); // Fetch messages initially when the page loads
            setInterval(fetchMessage, 1000); // Poll every 1 second for new messages

            $('#chat-form').submit(function(e) {
                e.preventDefault();
                var sender = $('#sender').val();
                var receiver = $('#receiver').val();
                var message = $('#message').val();

                $.ajax({
                    url: 'submit_message.php',
                    type: 'POST',
                    data: {sender: sender, receiver: receiver, message: message},
                    success: function() {
                        $('#message').val(''); // Clear message input after sending
                        fetchMessage(); // Fetch messages again after sending
                    },
                    error: function(xhr, status, error) {
                        console.error('Error sending message:', error);
                    }
                });
            });
        });
    </script>
</body>
</html>
