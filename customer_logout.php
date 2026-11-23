<?php
session_start();

unset($_SESSION['customer_login']);
unset($_SESSION['customer_username']);

header("Location: /spotly/");
exit;
?>