<?php

 $db=mysqli_connect('localhost', 'root' ,'','mini_bbs') or die(mysql_error());
  mysqli_select_db($db,'mini_bbs') or die('mysql_error');
   
  mysqli_query($db,'SET NAMES UTF8');
  
 
  
?>