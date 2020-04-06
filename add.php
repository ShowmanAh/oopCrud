
<?php
require 'User.php';
$error_fields = array();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(!(isset($_POST['name']) && !empty($_POST['name']))){
        $error_fields = "name";
    }
    if(!(isset($_POST['email']) && filter_input(INPUT_POST,'email', FILTER_VALIDATE_EMAIL))){
        $error_fields = "email";
    }
    if(!(isset($_POST['password']) && $_POST['password'] > 5)){
        $error_fields = "password";
    }
    if(!$error_fields){
        $user = new User();
       $link = $user->connect();
        // escape any special characters to avoid sql injection
        $name = mysqli_escape_string($link,$_POST['name']);
        $email = mysqli_escape_string( $link,$_POST['email']);
        $password = sha1($_POST['password']);
        $admin = (isset($_POST['admin'])) ? 1 : 0;
        $user_data = [$name,$email,$password,$admin];


        $user->addUser($user_data);
    }
}
?>
<html>
<head>
    <title> add new user </title>
</head>
 <body>
 <form method="post" enctype="multipart/form-data">
     <label for="name">Name</label>
     <input type="text" name="name" id="name" value="<?= (isset($_POST['name'])) ? $_POST['name'] : '' ?>"/>
     <?php if(in_array('name',$error_fields)) echo "* please enter your name"?>
     <label for="email">Email</label>
     <input type="email" name="email" id="email" value="<?= (isset($_POST['email'])) ? $_POST['email'] : ''?>"/>
     <?php if(in_array('email', $error_fields)) echo "* please enter your email" ?>
     <label for="password">Password</label>
     <input type="password" name="password" id="password" value="<?= (isset($_POST['password'])) ? $_POST['password'] : ''?>"/>
     <?php if(in_array('password', $error_fields)) echo "* please enter your password at least 6 characters" ?>
     <input type="file" name="avatar" id="avatar">
     <label for="admin">Admin</label>
     <input type="checkbox" name="admin" <?= (isset($_POST['admin'])) ? 'checked' : '' ?> />
     <br>
     <input type="submit" name="submit" value="Add User">
 </form>
 </body>
</html>







