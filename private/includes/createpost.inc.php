<?php


//check if the user is logged in
if (Login::isLoggedIn()) {

    $userid = Login::isLoggedIn();
    $page = basename($_SERVER['REQUEST_URI']);

    //get the profile_pic and full name of the user
    $profile_pic = User::profileImage($userid);
    $profileName = User::name($userid);
    
} else { //if the user is not logged in
    header('Location: register.php');
}
?>


<div id="createModal">
    <!---Click the overlay to close the modal-->
    <div id="overlay" onclick="closeCreate()"></div>
    <div class="container">
        <div class="headerCreate">
            <h1>Create Post</h1>
            <button onclick="closeCreate()"><i class="fas fa-times-circle"></i></button>
        </div>
        <div class="createForm">
            <div class="create-header">
                <figure><img src="<?php echo $profile_pic; ?>"></figure>
                <div class="name">
                    <a href="<?php echo "profile.php?id=$userid" ?>"><?php echo "$profileName"; ?></a>
                </div>
            </div>
            <form action="<?php if($page == 'connect' || $page == 'index.php'){ echo "index.php"; } else{ echo "profile.php?id=$userid"; } ?>" 
            method="post" enctype="multipart/form-data">
                <textarea name="postbody" placeholder="What's on your mind, <?php echo $profileName; ?>?"></textarea>
                <div class="addtoPost">
                    <p>Add to Post</p>
                    <div class="addButtons">
                        <input type="file" name="postImage">
                    </div>
                </div>
                <input type="submit" name="post" value="Post" class="modal-submit">
            </form>
        </div>
    </div>
</div>