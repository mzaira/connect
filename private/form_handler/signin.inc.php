<?php
//IF: User is not loggedIn
if (!Login::isLoggedIn()) {
    //IF: 'signin' button is pressed
    if (isset($_POST['signin'])) {

        $emailUsername = strip_tags($_POST['emailUsername']); //to remove HTML tags
        $emailUsername = str_replace(" ", "", $emailUsername); //to replace space to empty
        $_SESSION['emailUsername'] = '';

        if (isset($_POST['remember']) && $_POST['remember'] == 'true') {
            $_SESSION['emailUsername'] = $emailUsername; //to store the value into the SESSION
        }

        //IF: inputted email or username does not exist
        if (!DB::query('SELECT email, username FROM users WHERE email=:email OR username=:username', array(':email' => $emailUsername, ':username' => $emailUsername))) {
            //ERROR ARRAY
            array_push($error_array, "Email doesn't exist2");
        }

        $password = strip_tags($_POST['password']); //to remove HTML tags

        //IF: inputted password is correct
        if (password_verify($password, DB::query('SELECT password FROM users WHERE email=:email OR username=:username', array(':email' => $emailUsername, ':username' => $emailUsername))[0]['password'])) {

            //DATE: get users.id, users.active
            $user_id = DB::query('SELECT id FROM users WHERE email=:email OR username=:username', array(':email' => $emailUsername, ':username' => $emailUsername))[0]['id'];
            $active = DB::query('SELECT active FROM users WHERE id=:id', array(':id' => $user_id))[0]['active'];

            //IF: the user is still active
            if ($active == True) {

                //VARIABLE: encoded token
                $cstrong = True;
                $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));

                //DATA: insert token and users.id
                DB::query('INSERT INTO login_token VALUES("", :token, :user_id)', array(':token' => sha1($token), ':user_id' => $user_id));

                //COOKIE
                //1st COOKIE: to last for a week
                setcookie("SNID", $token, time() + 60 * 50 * 24 * 7, '/', NULL, NULL, TRUE);
                //2nd COOKIE: to last for 3 days
                setcookie("SNID_", '1', time() + 60 * 50 * 24 * 3, '/', NULL, NULL, TRUE);

                $_SESSION['id'] = $user_id;

                //SCRIPT: to index.php
                echo "<script> window.location.assign('../connect'); </script>";
            } else {
                //DATA: updating the active to true
                DB::query('UPDATE users SET active=:active WHERE id=:id', array(':id' => $user_id, ':active' => True));

                //NOTE// The same as the if statement //
                $cstrong = True;
                $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));

                DB::query('INSERT INTO login_token VALUES("", :token, :user_id)', array(':token' => sha1($token), ':user_id' => $user_id));

                setcookie("SNID", $token, time() + 60 * 50 * 24 * 7, '/', NULL, NULL, TRUE);
                setcookie("SNID_", '1', time() + 60 * 50 * 24 * 3, '/', NULL, NULL, TRUE);

                $_SESSION['id'] = $user_id;
                
                echo "<script> window.location.assign('../connect'); </script>";
            }
        } else {
            //ERROR ARRAY
            array_push($error_array, "Incorrect password");
        }
    }
} else {
    //SCRIPT: to index.php
    echo "<script> window.location.assign('../connect'); </script>";
}

?>
