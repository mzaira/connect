<?php
//IF: User is not loggedIn
if (!Login::isLoggedIn()) {
    //IF: 'resetpassword' button is pressed
    if (isset($_POST['resetpassword'])) {

        $email = strip_tags($_POST['forgot-email']); //to remove HTML tags
        $email = str_replace(" ", "", $email); //to replace space to empty

        //IF: inputted email already exists
        if (DB::query('SELECT email FROM users WHERE email=:email', array(':email' => $email))) {

            //VARIABLE: encoded token, code
            //EXPLAIN "bin2hex" = ASCII string containing the hexadecimal representation of string; and the value will double from bin"2"hex
            $tstrong = True;
            $token = bin2hex(openssl_random_pseudo_bytes(64, $tstrong));
            $cstrong = True;
            $code = bin2hex(openssl_random_pseudo_bytes(3, $cstrong));

            //DATA: get users.id
            $user_id = DB::query('SELECT id FROM users WHERE email=:email', array(':email' => $email))[0]['id'];

            //DATA: insert token, code
            DB::query(
                'INSERT INTO password_token VALUES("", :token, :user_id, :codes)',
                array(':token' => sha1($token), ':user_id' => $user_id, ':codes' => $code)
            );

            //VARIABLE: username
            $username = User::username($user_id);

            //VARIABLE: content of message with inline-css
            $body = '<div style="width:750px; height:350px; background-color:rgb(250, 250, 250); padding:20px 40px; border-radius:50px;">
                <h1 style="text-align:center;">CONNECT | FORGOT PASSWORD</h1>
                <div style="width:90%; height:70%; background-color:#CECECE; border-radius:50px; padding:20px 30px;">
                    <p style="font-size:1.2rem;">Hey <span style="color:#752FFF; width:100%;">' . $username . '</span>,</p>
                    <p style="font-size:1.2rem; width:100%;">Your Connect password can now be reset by clicking the button bellow with a code of <b>' . $code . '</b>.
                    If you did not request a new password, please ignore this email.</p>
                    <a style="font-size:1.3rem; border-radius:30px; width:100px; background-color:#752FFF; color:white; padding:10px 15px; text-decoration:none; margin:0 240px;" href="http://localhost/connect/change-password.php?token=' . $token . '">Reset Password</a>
                </div>
            </div>';

            //SEND EMAIL
            $mail = Mail::sendMail('Forgot Password!', $body, $email);

            if ($mail == 'Message sent') {
                //ERROR ARRAY
                array_push($error_array, "Message sent");
            } else {
                //ERROR ARRAY
                array_push($error_array, "Message could not be sent.");
            }
        } else {
            //ERROR ARRAY
            array_push($error_array, "Email doesn't exist");
        }
    }
}  else {
    //SCRIPT: to index.php
    echo "<script> window.location.assign('../connect'); </script>";
}

?>
