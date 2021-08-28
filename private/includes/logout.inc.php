<?php

if (isset($_POST['logout'])) {

    DB::query('DELETE FROM login_token WHERE token=:token', array(':token' => sha1($_COOKIE['SNID'])));

    setcookie("SNID", "", time() - 3600);
    setcookie("SNID_", "", time() - 3600);
    
    header('Location: register.php');
}

?>