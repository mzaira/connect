<?php
//IF: User is not loggedIn
if (!Login::isLoggedIn()) {
    //IF: 'signup' button is pressed
    if (isset($_POST['signup'])) {

        $name = strip_tags($_POST['name']); //to remove HTML tags
        $name = str_replace(" ", "", $name); //to replace space to empty
        $name = ucfirst(strtolower($name)); //to uppercase first letter then lowercase

        $email = strip_tags($_POST['email']); //to remove HTML tags
        $email = str_replace(" ", "", $email);

        $username = strip_tags($_POST['username']); //to remove HTML tags
        $username = str_replace(" ", "", $username);

        $password = strip_tags($_POST['password']); //to remove HTML tags

        //IF: inputted name has a length of 3 characters
        if (!strlen($name) >= 3) {
            //ERROR ARRAY
            array_push($error_array, "Name must exceed 3 characters");
        }

        //IF: inputted email is validated
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //IF: inputted email already exists
            if (DB::query('SELECT email FROM users WHERE email=:email', array(':email' => $email))) {
                //ERROR ARRAY
                array_push($error_array, "Email is already registered");
            }
        } else {
            //ERROR ARRAY
            array_push($error_array, "Email input not validated");
        }

        //IF: inputted password only consists of letters amd numbers
        if (preg_match('/[a-zA-Z0-9_]+/', $password)) {
            //IF: inputted password has a length of 6 to 60 characters
            if (strlen($password) >= 6 && strlen($password) <= 60) {

                //DATA: insert email, password, username, name
                DB::query(
                    'INSERT INTO users VALUES ("", :email, :password, :username, :name, NOW(), True )',
                    array(':email' => $email, ':password' => password_hash($password, PASSWORD_BCRYPT), ':username' => $username, ':name' => $name)
                );

                //DATA: get users.id
                $user_id = DB::query('SELECT id FROM users WHERE email=:email', array(':email' => $email))[0]['id'];

                //VARIABLE: profile, cover images
                $profile = "assets/img/profilePics/defaultProfile.png";
                $cover = "assets/img/coverPics/defaultCover.png";

                //DATA: insert user_id, profile, cover
                DB::query(
                    'INSERT INTO profile VALUES ("", :user_id, :profile_image, :cover_image, NULL, NULL, NULL, NULL, NULL)',
                    array(':user_id' => $user_id, ':profile_image' => $profile, ':cover_image' => $cover)
                );

                //NOTE// The same as the signin //
                $cstrong = True;
                $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));

                DB::query('INSERT INTO login_token VALUES("", :token, :user_id)', array(':token' => sha1($token), ':user_id' => $user_id));

                setcookie("SNID", $token, time() + 60 * 50 * 24 * 7, '/', NULL, NULL, TRUE);
                setcookie("SNID_", '1', time() + 60 * 50 * 24 * 3, '/', NULL, NULL, TRUE);

                echo "<script> window.location.assign('../connect'); </script>";
            } else {
                //ERROR ARRAY
                array_push($error_array, "Password must be between 6 to 60 characters");
            }
        } else {
            //ERROR ARRAY
            array_push($error_array, "Password must contain letters and numbers only");
        }
    }
}

?>