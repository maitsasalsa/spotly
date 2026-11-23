<?php
session_start();
include 'config/koneksi.php';

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn,
        "SELECT * FROM customer
         WHERE username='$username'
         AND password='$password'");

    if(mysqli_num_rows($query) > 0){

        $_SESSION['customer_login'] = true;
        $_SESSION['customer_username'] = $username;

        header("Location: /spotly/");
        exit;
    }

    $error = "Username atau Password salah!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Customer - Spotly</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
        background:linear-gradient(135deg,#f5efe6,#e8d8c3);
    }

    .login-card{
        width:420px;
        background:white;
        padding:40px;
        border-radius:25px;
        box-shadow:0 15px 35px rgba(0,0,0,.15);
    }

    .logo{
        text-align:center;
        font-size:42px;
        color:#6F4E37;
        margin-bottom:10px;
    }

    .subtitle{
        text-align:center;
        color:#8B6B4A;
        margin-bottom:30px;
    }

    .error{
        background:#ffe5e5;
        color:#c0392b;
        padding:10px;
        border-radius:10px;
        margin-bottom:15px;
        text-align:center;
    }

    label{
        display:block;
        margin-bottom:8px;
        color:#4B2E2B;
        font-weight:bold;
    }

    input{
        width:100%;
        padding:14px;
        border:1px solid #d8c3a5;
        border-radius:12px;
        margin-bottom:18px;
        font-size:15px;
    }

    input:focus{
        outline:none;
        border-color:#6F4E37;
    }

    .btn{
        width:100%;
        background:#6F4E37;
        color:white;
        border:none;
        padding:14px;
        border-radius:12px;
        cursor:pointer;
        font-size:16px;
        transition:.3s;
    }

    .btn:hover{
        background:#5b3d2a;
    }

    .register-link{
        text-align:center;
        margin-top:18px;
        color:#6F4E37;
    }

    .register-link a{
        color:#8B5E3C;
        font-weight:bold;
        text-decoration:none;
    }

    .register-link a:hover{
        text-decoration:underline;
    }
    </style>
</head>
<body>

<div class="login-card">

    <div class="logo">☕ SPOTLY</div>

    <div class="subtitle">
        Masuk untuk melihat detail cafe favoritmu
    </div>

    <?php if(isset($error)): ?>
        <div class="error">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>

<div style="position:relative;">
    <input type="password" id="password" name="password" required>

    <i id="eye"
       class="fa-solid fa-eye"
       onclick="togglePassword()"
       style="
           position:absolute;
           right:15px;
           top:50%;
           transform:translateY(-50%);
           cursor:pointer;
           color:#6F4E37;
       ">
    </i>
</div>

        <button class="btn" type="submit" name="login">
            Login
        </button>

    </form>

    <div class="register-link">
        Belum punya akun?
        <a href="register.php">Register</a>
    </div>

</div>

<script>
function togglePassword() {
    let password = document.getElementById("password");
    let eye = document.getElementById("eye");

    if(password.type === "password"){
        password.type = "text";
        eye.classList.remove("fa-eye");
        eye.classList.add("fa-eye-slash");
    } else {
        password.type = "password";
        eye.classList.remove("fa-eye-slash");
        eye.classList.add("fa-eye");
    }
}
</script>
</body>
</html>