<?php
 session_start();
 require('dbconnect.php');
 
 if(empty($_REQUEST['id'])){
 header('Location: index.php');
 exit();
 
 }

 //投稿取得
 $sql=sprintf('SELECT m.name,m.picture,p.* FROM member m, posts p WHERE
              m.id=p.member_id ORDER BY p.created DESC',
              mysqli_real_escape_string($db,$_REQUEST['id'])
              );
        $posts=mysqli_query($db,$sql) or die(mysql_error());

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title>ひとこと掲示板</title>
</heder>

<body>
<div id="wrap">
 <div id=header>
   <h1>ひとこと掲示板</h1>
  </div>
   <div id="content">
   <p>&laquo;<a href="index.php">一覧にもどる</p>
   
   <?php
   
   if($post=mysqli_fetch_assoc($posts)):
   
   ?>
   <div class="msg">
   <img src="member_picture/<?php echo htmlspecialchars($post['picture'],ENT_QUOTES,'UTF-8');?>" width="48" height="48" alt="<?php 
         echo htmlspecialchars($post['name'],ENT_QUOTES,'UTF-8'); ?>"/>
     <p> <?php echo htmlspecialchars($post['message'],ENT_QUOTES,'UTF-8'); ?><span class="name">(<?php echo
             htmlspecialchars($post['name'],ENT_QUOTES,'UTF-8'); ?>)</span></p>
       <p class="day"><a href="view.php?id=<?php echo htmlspecialchars($post['id'],ENT_QUOTES,'UTF-8');?>">
       
       
       <?php
       else:
       
       ?>
   
   <p>その投稿は削除されたか、URLが間違えています。</p>
   <?php
   endif;
   ?>
   
   
   
   </div>
      </div>
   <div id="footer"> 
      <p ><img src="images/txt_copyrith.png" width="136" height="15" alt="(c) #20 Space. MYCOM" /></p>
     </div>
    </div>
   </body>
  </html>
   
   
   
   
   