<?php
$password = "12345";   // 🔑 your new password
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Hash for '$password':<br>$hash";
