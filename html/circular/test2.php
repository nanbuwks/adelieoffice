<?php
// session_start();
// session_regenerate_id();
// setcookie('TEST', 'VALUEi2');

header('Set-Cookie: TEST2=ThisWork?');
echo $_COOKIE['TEST2'];
?>

