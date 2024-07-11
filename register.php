
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <style>
        .signupcontainer{
            width:400px;
            height:400px;
            border:7px solid;
            display:block;
            margin:auto;
            margin-top:70px;
            border-radius:15px;
            text-align:center;
        }
        .signupcontainer h1{
            font-size:40px;
        }
        .signupcontainer form{
            display:flex;
            flex-direction:column;
        }
        .signupcontainer form input{
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
    <div class="signupcontainer">
        <h1>Signup</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="user" id="user" placeholder="Enter your name">
            <input type="password" name="pass" id="pass" placeholder="Enter password">
            <input type="password" name="cpass" id="cpass" placeholder="confirm password">
            <input type="submit" value="Submit">
            <p>Already have account? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>
</html>

<?php
    session_start();
    include("db.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['cpass'])) {
            $user = $_POST['user'];
            $pass = $_POST['pass'];
            $cpass = $_POST['cpass'];

            $un = "SELECT * FROM `user_data` WHERE `user` = '$user'";
            $result1 = mysqli_query($conn, $un);
            $rows = mysqli_num_rows($result1);

            if ($rows == 0) {
                if ($pass == $cpass) {
                    $on = "INSERT INTO `user_data` (`user`, `password`) VALUES ('$user', '$pass')";
                    $result2 = mysqli_query($conn, $on);
                    if ($result2) {
                        echo "<script>window.location='http://localhost/Chat/login.php'</script>";
                    } else {
                        echo "<script>alert('Failed to insert data')</script>";
                    }
                } else {
                    echo "<script>alert('Passwords do not match')</script>";
                }
            } else {
                echo "<script>alert('Username is already taken')</script>";
            }
        }
    }
?>

<!-- HTML remains unchanged -->
