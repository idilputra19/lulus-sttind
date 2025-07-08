<?php
$password = 'wali7a'; // ganti dengan password yang diinginkan
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Hash untuk password '$password' adalah:<br>$hash";
?>
