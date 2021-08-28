<?php
class User
{
    public static function profileImage($userid)
    {
        $profilePic = DB::query('SELECT profile_image FROM profile WHERE user_id=:user_id', array(':user_id' => $userid))[0]['profile_image'];
        return $profilePic;
    }
    public static function coverImage($userid)
    {
        $profileCov = DB::query('SELECT cover_image FROM profile WHERE user_id=:user_id', array(':user_id' => $userid))[0]['cover_image'];
        return $profileCov;
    }
    public static function name($userid)
    {
        $name = DB::query('SELECT name FROM users WHERE id=:id', array(':id' => $userid))[0]['name'];
        return $name;
    }
    public static function lastName($userid)
    {
        $lastName = DB::query('SELECT lastName FROM users WHERE id=:id', array(':id' => $userid))[0]['lastName'];
        return $lastName;
    }
    public static function numPost($userid)
    {
        $numPost = DB::query('SELECT lastName FROM users WHERE id=:id', array(':id' => $userid))[0]['lastName'];
        return $numPost;
    }
    public static function username($userid)
    {
        $username = DB::query('SELECT username FROM users WHERE id=:id', array(':id' => $userid))[0]['username'];
        return $username;
    }
    public static function email($userid)
    {
        $email = DB::query('SELECT email FROM users WHERE id=:id', array(':id' => $userid))[0]['email'];
        return $email;
    }
    public static function password($userid)
    {
        $password = DB::query('SELECT password FROM users WHERE id=:id', array(':id' => $userid))[0]['password'];
        return $password;
    }
    public static function num_post($userid)
    {
        $countPost = DB::rows('SELECT COUNT(*) FROM posts WHERE user_id=:userid ORDER BY id DESC', array(':userid' => $userid));
        return $countPost;
    }
    public static function active($userid)
    {
        $data = DB::query('SELECT active FROM users WHERE id=:id', array(':id' => $userid))[0]['active'];
        if ($data == 1) {
            $activate = true;
        } else {
            $activate = false;
        }
        return $activate;
    }
    public static function bio($userid)
    {
        $bio = DB::query('SELECT bio FROM profile WHERE user_id=:user_id', array(':user_id' => $userid))[0]['bio'];
        return $bio;
    }
    public static function location($userid)
    {
        $location = DB::query('SELECT locations FROM profile WHERE user_id=:user_id', array(':user_id' => $userid))[0]['locations'];
        return $location;
    }
    public static function work($userid)
    {
        $work = DB::query('SELECT works FROM profile WHERE user_id=:user_id', array(':user_id' => $userid))[0]['works'];
        return $work;
    }
    public static function workplace($userid)
    {
        $workplace = DB::query('SELECT company FROM profile WHERE user_id=:user_id', array(':user_id' => $userid))[0]['company'];
        return $workplace;
    }
    public static function university($userid)
    {
        $university = DB::query('SELECT university FROM profile WHERE user_id=:user_id', array(':user_id' => $userid))[0]['university'];
        return $university;
    }
}
