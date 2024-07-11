

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .logincontainer{
            width:400px;
            height:300px;
            border:7px solid;
            display:block;
            margin:auto;
            margin-top:70px;
            border-radius:15px;
            text-align:center;
        }
        .logincontainer h1{
            font-size:40px;
        }
        .logincontainer form{
            display:flex;
            flex-direction:column;
        }
        .logincontainer form input{
            height:30px;
            text-align:center;
            margin:10px;
            border:2px solid;
            border-radius:15px;
            font-size:15px;
        }
    </style>
</head>
<body>
    <div class="logincontainer">
        <h1>Login</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="user" id="user" placeholder="Enter your user name">
            <input type="password" name="pass" id="pass" placeholder="Enter password">
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>

<?php
    session_start();
    include("db.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['user']) && isset($_POST['pass'])) {
            $user = $_POST['user'];
            $pass = $_POST['pass'];

            $un = "SELECT * FROM `user_data` WHERE `user` = '$user'";
            $result1 = mysqli_query($conn, $un);
            $noofrows = mysqli_num_rows($result1);

            if ($noofrows == 1) {
                $row = mysqli_fetch_assoc($result1);
                if ($pass == $row['password']) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user'] = $user;
                    echo "<script>window.location='http://localhost/Chat/chat.php?user=$user'</script>";
                    exit;
                } else {
                    echo "<script>alert('Incorrect password')</script>";
                }
            } else {
                echo "<script>alert('User not found')</script>";
            }
        }
    }
?>

<!-- HTML remains unchanged -->
