<?php
 session_start();
 require('dbconnect.php');

 
  if(isset($_SESSION['id']) && $_SESSION['time'] + 3600>time()){
 
  $_SESSION['time']=time();
  
  $sql=sprintf('SELECT * FROM member WHERE id=%d',
   mysqli_real_escape_string($db,$_SESSION['id'])
  );
  
  $record=mysqli_query($db,$sql) or die(mysql_error());
  $member=mysqli_fetch_assoc($record);
  
  }else{
  
  header('Location: Login.php');
  exit();
  
  }
 //投稿する
 if(!empty($_POST)){
  if($_POST['message']!=''){
         $sql=sprintf('INSERT INTO posts SET member_id=%d, message="%s", reply_post_id=%d,created=NOW()',
   mysqli_real_escape_string($db,$member['id']),
   mysqli_real_escape_string($db,$_POST['message']),
   mysqli_real_escape_string($db,$_POST['reply_post_id'])
   );
  mysqli_query($db,$sql) or die(mysql_error);
  
  header('Location: index.php');
  exit();
  
  
  }
 
 }
 //投稿を取得する

 if(!isset($_REQUEST['page'])){
   
   $_REQUEST['page']=1;
}

   $page=$_REQUEST['page'];
   if(!isset($page)){
   $page=1;
   
   }
   $page=max($page,1);
   
   
   //最終ページを取得
   $sql='SELECT COUNT(*) AS cnt FROM posts';
   $recordSet=mysqli_query($db,$sql);
   $table=mysqli_fetch_assoc($recordSet);
   $maxPage=ceil($table['cnt']/5);
   $page=min($page,$maxPage);
   
   $start=($page-1)*5;
   $start=max(0,$start);
   
   
  
   
   
        $sql=sprintf('SELECT m.name,m.picture,p.* FROM member m, posts p WHERE
              m.id=p.member_id ORDER BY p.created DESC LIMIT %d,5',$start);
        $posts=mysqli_query($db,$sql) or die(mysql_error());
              
              
//返信の場合
       if(isset($_REQUEST['res'])){
       $sql=sprintf('SELECT m.name, m.picture, p.* FROM member m,posts p WHERE
                      m.id=p.member_id AND p.id=%d ORDER BY p.created DESC',
                       mysqli_real_escape_string($db,$_REQUEST['res']));
                       
       $record=mysqli_query($db,$sql) or die(mysql_error());
       $table=mysqli_fetch_assoc($record);
       $message='@'.$table['name'] . ' ' .$table['message'];
       
       }
       //
       function h($value){
       return htmlspecialchars($value,ENT_QUOTES,'UTF-8');
       
       }
//URL変換
 function makeLink($value){
 
 return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)",'<a href="\1\2">\1\2</a>',$value);
 
 }
 
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
   <div style="text-align: right"><a href="logout.php">ログアウト</a></div>
    <form action ="" method="post">
     <dl>
      <dt><?php echo htmlspecialchars($member['name']);   ?>メッセージをどうぞ</dt>
       <textarea name="message" cols="50" rows="5"><?php if(!empty($message))echo 
       htmlspecialchars($message,ENT_QUOTES,'UTF-8'); ?></textarea>
       
       <input type="hidden" name="reply_post_id" value="<?php echo htmlspecialchars($_REQUEST['res'],ENT_QUOTES,'UTF-8')?>"/>
      
      </dd>
     </dl>
    <div>
      <input type="submit" value="投稿する" />
     </div>
    </form>
 <?php 
 while($post=mysqli_fetch_Assoc($posts)):
 
 ?>
    <div class="msg">
    <img src="member_picture/<?php echo h($post['picture']);?>" width="48" height="48" 
        alt="<?php echo h($post['name']); ?>"/>
        
        <p> <?php echo makeLink(h($post['message'])); ?><span class="name">(<?php echo
             h($post['name']); ?>)</span></p>
       
             [<a href="index.php?res=<?php echo h($post['id']); ?>">Re</a>]
       
        
       <p class="day"><a href="view.php?id=<?php echo h($post['id']);?>">
       <?php echo h($post['created']); ?></a>
        <?php 
          if($post['reply_post_id']>0):
         ?>
         <a href="view.php?id=<?php echo h($post['reply_post_id']); ?>"> 
            返信元のメッセージ</a>
        <?php
        endif;
        ?>
        </div>
         <?php
         if($_SESSION['id']==$post['member_id']):
         ?>
         
         [<a href="delete.php?id=<?php echo h($post['id']); ?>" style="color: #F33;">削除</a>]
         <?php
        endif;
        ?>
         
         
         
        <?php 
 endwhile;
 
 ?>
        <ul class="pagin">
        <?php
        if($page>1){
        ?>
        <li><a href="index.php?page=<?php print($page-1);?>">前のページに</a></li>
  <?php 
  }else{
  ?>
  <li>前ページへ</li>
  <?php 
  }
  ?>  
   <?php
        if($page<$maxPage){
        ?>
   <li><a href="index.php?page=<?php print($page+1);?>">次のページに</a></li>
  
  <?php
  }else{
  ?>
  
  <li>次のページへ</li>
  <?php 
  }
  ?> 
  </ul>
  
    
   </div>
   <div id="footer"> 
      <p ><img src="images/txt_copyrith.png" width="136" height="15" alt="(c) #20 Space. MYCOM" /></p>
     </div>
    </div>
   </body>
  </html>
