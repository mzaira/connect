d<?php
    require_once('private/classes/config.php');
    require_once('private/classes/login.php');
    require_once('private/classes/user.php');

    if (Login::isLoggedIn()) {
        $id = Login::isLoggedIn();

        $page = basename($_SERVER['REQUEST_URI']);

        $profileImage = User::profileImage($id);
        $coverImage = User::coverImage($id);
        $fullName = User::name($id);
        $content = "";
        $active = "";

        if ($page = "settings.php") {

            $content = '
        <form action="settings.php" method="post" enctype="multipart/form-data">
            <div class="editimage">
                <div class="profile>
                    <figure><img src="' . $profileImage . '"></figure>
                    <div class="buttons">
                        <h1>' . $fullName . '</h1>
                        <input type="file" name="image" />
                    </div>
                </div>
                <div class="cover>
                    <figure><img src="' . $coverImage . '"></figure>
                    <div class="buttons">
                        <input type="file" name="coverimage" />
                    </div>
                </div>
            </div>
            <div class="name input">
                <h1>Name</h1>
                <input type="text" name="first" placeholder="Name">
            </div>
            <div class="bio input">
                <h1>Bio</h1>
                <input type="text" name="bio" placeholder="Bio">
            </div>
            <div class="location input">
                <h1>City</h1>
                <input type="text" name="city" placeholder="City">
                <h1>Country</h1>
                <input type="text" name="country" placeholder="Country">
            </div>
            <div class="work input">
                <h1>Job Title</h1>
                <input type="text" name="job" placeholder="Job Title">
                <h1>Company Name</h1>
                <input type="text" name="workplace" placeholder="Company Name">
            </div>
            <div class="education input">
                <h1>Graduated at</h1>
                <input type="text" name="university" placeholder="Univeristy Name">
            </div>
            <input type="submit" name="editProfile" value="Submit">
        </form>';
            $active = "edit";

            if (isset($_GET['tab'])) {
                $tab = $_GET['tab'];

                if ($page = "settings.php" && $tab == 'sec') {
                    $content = '<form action="settings.php?tabs=sec" method="post">
                <div class="email input">
                    <h1>Email Address</h1>
                    <input type="text" name="email" placeholder="Email Address">
                </div>
                <div class="password input">
                    <h1>Old Password</h1>
                    <input type="password" name="oldP" placeholder="Old Password">
                    <div class="new">
                        <div class="input">
                            <h1>New Password</h1>
                            <input type="password" name="newP" placeholder="New Password">
                        </div>
                        <div class="input">
                            <h1>Retype Password</h1>
                            <input type="password" name="retypeP" placeholder="Retype Password">
                        </div>
                    </div>
                </div>
                <div class="username input">
                    <h1>Username</h1>
                    <input type="text" name="username" placeholder="Username">
                </div>
                <input type="submit" name="securityPrivacy" value="Submit">
            </form>';
                    $active = "sec";
                }
            }
        } else {
            die(header('Location: error.php'));
        }

        if (isset($_POST['securityPrivacy'])) {
            $email = $_POST['email'];
            $opassword = $_POST['oldP'];
            $npassword = $_POST['newP'];
            $rpassword = $_POST['retypeP'];
            $username = $_POST['username'];

            if ($email == "") {
                if (User::email($id) == NULL) {
                    $first = NULL;
                } else {
                    $email = User::email($id);
                }
            }
            if ($username == "") {
                if (User::username($id) == NULL) {
                    $username = NULL;
                } else {
                    $username = User::username($id);
                }
            }
            if ($opassword == "") {
                if (User::password($id) == NULL) {
                    $opassword = NULL;
                } else {
                    $opassword = User::password($id);
                }
            }

            if ($opassword != $npassword) {
                if (password_verify($opassword, DB::query('SELECT password FROM users WHERE id=:id', array(':id' => Login::isLoggedIn()))[0]['password'])) {
                    if ($npassword == $rpassword) {

                        //DATA: updating the password to new password
                        DB::query('UPDATE users SET password=:newpassword WHERE id=:userid', array(':newpassword' => password_hash($npassword, PASSWORD_BCRYPT), ':userid' => Login::isLoggedIn()));
                    }
                }
            }

            DB::query(
                'UPDATE users SET email=:email, username=:username WHERE id=:userid',
                array(':email' => $email, ':username' => $username, ':userid' => Login::isLoggedIn())
            );
        }

        if (isset($_POST['editProfile'])) {

            $first = $_POST['first'];
            $last = $_POST['last'];
            $bio = $_POST['bio'];
            $city = $_POST['city'];
            $country = $_POST['country'];
            $job = $_POST['job'];
            $workplace = $_POST['workplace'];
            $university = $_POST['university'];

            $location = "$city, $country";


            if (isset($_FILES['image'])) {
                $fileName = $_FILES['image']['name'];
                $fileTmp = $_FILES['image']['tmp_name'];
                $fileSize = $_FILES['image']['size'];
                $fileError = $_FILES['image']['error'];
                $fileType = $_FILES['image']['type'];

                $fileNameNew = "";

                $fileExt = explode('.', $fileName);
                $fileActualExt = strtolower(end($fileExt));

                $allowed = array('jpg', 'jpeg', 'png');

                if (in_array($fileActualExt, $allowed)) {
                    if ($fileError === 0) {
                        if ($fileSize < 1000000) {
                            $fileNameNew = "profile" . $id . "." . $fileActualExt;
                            $fileDestination = 'assets/img/profilePics/' . $fileNameNew;
                            move_uploaded_file($fileTmp, $fileDestination);

                            DB::query('UPDATE profile SET profile_image=:image WHERE user_id=:userid', array(':image' => $fileDestination, ':userid' => $id));
                        }
                    }
                }
            }

            if (isset($_FILES['coverimage'])) {
            $fileName = $_FILES['coverimage']['name'];
            $fileTmp = $_FILES['coverimage']['tmp_name'];
            $fileSize = $_FILES['coverimage']['size'];
            $fileError = $_FILES['coverimage']['error'];
            $fileType = $_FILES['coverimage']['type'];

            $fileNameNew = "";

            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));

            $allowed = array('jpg', 'jpeg', 'png');

            if (in_array($fileActualExt, $allowed)) {
                if ($fileError === 0) {
                    if ($fileSize < 1000000) {
                        $fileNameNew = "profile" . $id . "." . $fileActualExt;
                        $fileDestination = 'assets/img/coverPics/' . $fileNameNew;
                        move_uploaded_file($fileTmp, $fileDestination);

                        DB::query('UPDATE profile SET cover_image=:image WHERE user_id=:userid', array(':image' => $fileDestination, ':userid' => $id));
                    }
                }
            }
        }

            if ($first == "") {
                if (User::name($id) == NULL) {
                    $first = NULL;
                } else {
                    $first = User::name($id);
                }
            }
            if ($bio == "") {
                if (User::bio($id) == NULL) {
                    $bio = NULL;
                } else {
                    $bio = User::bio($id);
                }
            }
            if ($city == "" && $country == "") {
                if (User::location($id) == NULL) {
                    $location = NULL;
                } else {
                    $location = User::location($id);
                }
            }

            if ($job == "") {
                if (User::work($id) == NULL) {
                    $job = NULL;
                } else {
                    $job = User::work($id);
                }
            }
            if ($workplace == "") {
                if (User::workplace($id) == NULL) {
                    $workplace = NULL;
                } else {
                    $workplace = User::workplace($id);
                }
            }
            if ($university == "") {
                if (User::university($id) == NULL) {
                    $university = NULL;
                } else {
                    $university = User::work($id);
                }
            }

            DB::query(
                'UPDATE users SET name=:name WHERE id=:userid',
                array(':name' => $first, ':userid' => $id)
            );

            DB::query(
                'UPDATE profile SET bio=:bio, locations=:location, works=:job, 
        company=:workplace, university=:university WHERE user_id=:userid',
                array(
                    ':bio' => $bio, ':location' => $location, ':job' => $job, ':workplace' => $workplace, ':university' => $university,
                    ':userid' => $id
                )
            );

            header('Location: settings.php');
        }
    } else {
        die(header('Location: register.php'));
    }
