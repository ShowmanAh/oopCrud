<?php
require 'User.php';
/*
session_start();
if (isset($_SESSION['id'])){
    echo '<p> welcome' . $_SESSION['email'] . '<a href="logout.php">logout</a></p>';
}else{
    header("Location:/login.php");
    exit();
}
**/

  $user = new User();
  //$user->connect();
  $users = $user->getUsers();
  var_dump($users);
  if(is_array($users)){
      echo "arr" ;
     return 0;
  }else{
      echo "not ass ociative";
  }



   if (isset($_GET['search'])){
       $users = $user->searchUsers($_GET['search']);

   }

?>
<html>
    <head>
          <title>List Users</title>
        <form method="get">
            <input type="text" name="search" placeholder="enter {name} or {email}">
            <input type="submit" value="search">

        </form>
    </head>
    <body>
         <table>
             <thead>
               <tr>
                   <td>ID</td>
                   <td>NAME</td>
                   <td>EMAIL</td>
                   <td>ADMIN</td>
                   <td>CREATED_AT</td>
                   <td>UPDATED_AT</td>
                   <td>Image</td>
                   <td>ACTION</td>
               </tr>
             </thead>
             <tbody>
             <?php 
             foreach($users as $row) {


                 ?>
                 <tr>
                     <td><?= $row['id'] ?></td>
                     <td><?= $row['name'] ?></td>
                     <td><?= $row['email'] ?></td>
                     <td><?= ($row['admin']) ? 'yes' : 'no' ?></td>

                     <td><?= $row['created_at'] ?></td>
                     <td><?= $row['updated_at'] ?></td>
                     <td><a href="edit.php?id=<?= $row['id'] ?>">Edit</a> | <a href="delete.php?id=<?= $row['id'] ?>">Delete</a>
                     </td>
                 </tr>
                 <?php
             }
                 ?>


             </tbody>
             <tfoot>
                 <tr>
                     <td colspan="2" style="text-align:center"><?= count($users) ?> Users</td>
                     <td colspan="3" style="text-align:center"><a href="add.php"> Add New User</a></td>
                 </tr>
             </tfoot>
         </table>
    </body>
</html>
<?php 

?>