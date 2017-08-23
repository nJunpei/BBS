<?php
session_start();
require('dbconnect.php');
if(isset($_SESSION['id'])){
  $id=$_REQUEST['id'];
   $sql=sprintf('SELECT * FROM posts WHERE id=%d',
               mysqli_real_escape_string($db,$id)
               );
                
               $record=mysqli_query($db,$sql) or die(mysql_error());
               $table=mysqli_fetch_assoc($record);
          if($table['member_id']==$_SESSION['id']){
                 //削除
             mysqli_query($db,'DELETE FROM posts WHERE id=' .mysqli_real_escape_string($db,$id)) or die(mysql_error());
              }
              }header('Location: index.php');
              exit();
               ?>