<!DOCTYPE html>
<html lang = "ja">
<head>
    <meta charset = "UTF-8">
    <title>
        mission5-1
    </title>
</head>
<body>
    <form method = "post" action = "">
        <input type = "text" name = "name" placeholder = "名前"><br>
        <input type = "text" name = "comment" placeholder = "コメント"><br>
        <input type = "text" name = "password" placeholder = "パスワード"><br>
        <input type = "submit" value = "送信"><br>
        <input type = "text" name = "delete" placeholder = "削除対象番号"><br>
        <input type = "text" name = "del_password" placeholder = "パスワード"><br>
        <input type = "submit" value = "削除"><br>
        <input type = "text" name = "editor" placeholder = "編集対象番号"><br>
        <input type = "text" name = "edi_password" placeholder = "パスワード"><br>
        <input type = "submit" value = "編集"><br>
    </form>
    
<?php 
      //データベースへの接続　
      $dsn = 'mysql:dbname=データベース名;host=localhost';
      $user = 'ユーザー名';
      $password = 'PASSWORD';
      $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
      
      
      //データ登録するためのテーブル作成
      $sql = "CREATE TABLE IF NOT EXISTS tbtest"
      ." ("
      . "num INT AUTO_INCREMENT PRIMARY KEY,"
      . "name char(32),"
      . "comment TEXT,"
      . "date char(32),"
      . "password TEXT"
      .");";
      $stmt = $pdo -> query($sql);  
      
      //入力フォームのを受け取り
      $name = $_POST["name"];
      $comment = $_POST["comment"];
      $date = date("Y/m/d H:i:s");
      $password = $_POST["password"];
      $delete = $_POST["delete"];
      $del_password = $_POST["del_password"];
      $editor = $_POST["editor"];
      $edi_password = $_POST["edi_password"];
      
      //登録
      if(!empty($password)){
      $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
      $sql -> bindParam(':name', $name, PDO::PARAM_STR);
      $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
      $sql -> bindParam(':date', $date, PDO::PARAM_STR);
      $sql -> bindParam(':password', $password, PDO::PARAM_STR);
      $sql -> execute();
      }
      
      //表示する準備
      $sql = 'SELECT * FROM tbtest';
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();
      
      //編集
      foreach($results as $row){
          if($row['num'] == $editor && $row['password'] == $edi_password){
              $sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date,password=:password WHERE num=:num';
              $stmt = $pdo->prepare($sql);
              $stmt->bindParam(':name', $name, PDO::PARAM_STR);
              $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
              $stmt->bindParam(':date', $date, PDO::PARAM_STR);
              $stmt->bindParam(':password', $edi_password, PDO::PARAM_STR);
              $stmt->bindParam(':num', $editor, PDO::PARAM_INT);
              $stmt->execute();
              }
      }
       
      //消去
      foreach($results as $row){
          if($row['num'] == $delete && $row['password'] == $del_password){
              $sql = 'delete from tbtest where num=:num';
              $stmt = $pdo->prepare($sql);
              $stmt->bindParam(':num', $delete, PDO::PARAM_INT);
              $stmt->execute();
          }
      }

      //表示
      $sql = 'SELECT * FROM tbtest';
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();
      foreach($results as $row){
          echo $row['num'].',';
          echo $row['name'].',';
          echo $row['comment'].',';
          echo $row['date'].'<br>';
//  　    echo $row['password'].'<br>';
          echo "<hr>";
      }
?>
</body>
</html>
