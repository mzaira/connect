<?php
//IF: User is not loggedIn
if (!Login::isLoggedIn()) {
    //IF: token is set
    if (isset($_GET['token'])) {

        //VARIABLE: token
        $token = $_GET['token'];

        //IF: token matches password_token.token
        if (DB::query('SELECT user_id FROM password_token WHERE token=:token', array(':token' => sha1($token)))) {
            //DATA: get userid
            $userid = DB::query('SELECT user_id FROM password_token WHERE token=:token', array(':token' => sha1($token)))[0]['user_id'];

            //IF: 'changePassword' button is pressed
            if (isset($_POST['changePassword'])) {

                //DATA: get existing password
                $oldpassword = DB::query('SELECT password FROM users WHERE id=:id', array(':id' => $userid))[0]['password'];
                //VARIABLE: newpassword, retypepassword, code
                $newpassword = $_POST['newPassword'];
                $retypepassword = $_POST['retypePassword'];
                $code = $_POST['code'];

                //IF: newpassword matches the retypassword
                if ($newpassword == $retypepassword) {
                    //IF: code matches the password_token.codes
                    if (DB::query('SELECT codes FROM password_token WHERE codes=:codes', array(':codes' => $code))) {
                        //IF: newpassword does not macth the oldpassword
                        if ($newpassword != $oldpassword) {
                            //IF: inputted password has a length of 6 to 60 characters
                            if (strlen($newpassword) >= 6 && strlen($newpassword) <= 60) {
                                //IF: inputted password only consists of letters, numbers and other characters
                                if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $password)) {

                                    //DATA: updating the password to new password
                                    DB::query('UPDATE users SET password=:newpassword WHERE id=:userid', array(':newpassword' => password_hash($newpassword, PASSWORD_BCRYPT), ':userid' => $userid));
                                    //DATA: delete the password_token
                                    DB::query('DELETE FROM password_token WHERE token=:token', array(':token' => sha1($token)));

                                    //SCRIPT: to register.php
                                    header('Location: register.php');
                                } else {
                                    //ERROR ARRAY
                                    array_push($error_array, "Password must contain letters and numbers only");
                                }
                            } else {
                                //ERROR ARRAY
                                array_push($error_array, "Password must be between 6 to 60 characters");
                            }
                        } else {
                            //ERROR ARRAY
                            array_push($error_array, "Password must not be the same as your old password");
                        }
                    } else {
                        //ERROR ARRAY
                        array_push($error_array, "Incorrect Code");
                    }
                } else {
                    //ERROR ARRAY
                    array_push($error_array, "Password don't match");
                }
            }
        } else {
            //EXIT: to register.php
            //EXPLANATION: if the token does not exist
            die(header('Location: register.php'));
        }
    }
}  else {
    //SCRIPT: to index.php
    echo "<script> window.location.assign('../connect'); </script>";
}

?>
