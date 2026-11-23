<?php
session_start();
include 'config/koneksi.php';

if(isset($_POST['register'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $cek = mysqli_query($conn,
        "SELECT * FROM customer WHERE username='$username'");

    if(mysqli_num_rows($cek) > 0){

        $error = "Username sudah digunakan!";

    } else {

        mysqli_query($conn,
            "INSERT INTO customer(username,password)
             VALUES('$username','$password')");

        header("Location: customer_login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Customer - Spotly</title>

    <style>
    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family: Georgia, serif;
    }

    body{
        min-height:100vh;
        display:flex;
        justify-content:center;
        align-items:center;
        background:#F5EFE6;
    }

    .card{
        width:400px;
        background:white;
        padding:40px;
        border-radius:20px;
        box-shadow:0 10px 25px rgba(0,0,0,.15);
    }

    h2{
        text-align:center;
        color:#6F4E37;
        margin-bottom:20px;
    }

    input{
        width:100%;
        padding:12px;
        margin-bottom:15px;
        border:1px solid #ddd;
        border-radius:10px;
    }

    button{
        width:100%;
        padding:12px;
        border:none;
        border-radius:10px;
        background:#6F4E37;
        color:white;
        cursor:pointer;
    }

    .error{
        color:red;
        margin-bottom:10px;
        text-align:center;
    }

    .link{
        text-align:center;
        margin-top:15px;
    }
    </style>
</head>
<body>

<div class="card">

    <h2>Register Customer</h2>

    <?php if(isset($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">

        <input type="text" name="username"
               placeholder="Username" required>

        <input type="password" name="password"
               placeholder="Password" required>

        <button type="submit" name="register">
            Register
        </button>

    </form>

    <div class="link">
        Sudah punya akun?
        <a href="customer_login.php">Login</a>
    </div>

</div>

</body>
</html>