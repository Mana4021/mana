<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-01</title>
</head>
<body>
    <?php
    $name=$_POST["name"];
    $comment=$_POST["comment"];
    $editNO=$_POST["editNO"];
    $pass=$_POST["pass"];
    $delete=$_POST["delete"];
    $delpass=$_POST["delpass"];
    $edit=$_POST["edit"];
    $editpass=$_POST["editpass"];
    $date=date("Y/m/d H:i:s");
    
    // DB接続設定
	$dsn='mysql:dbname=データベース名;host=localhost';
    $user='ユーザー名';
    $password='パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //編集
    //$editが空ではない場合
    if(!empty($_POST["edit"]) && !empty($_POST["editpass"])){
        $sql = 'SELECT * FROM tbtest_52';
	    $stmt = $pdo->query($sql);
	    $results = $stmt->fetchAll();
	    foreach ($results as $row){
	        if($row['id'] == $edit){
		        //$rowの中にはテーブルのカラム名が入る
		        $editname = $row['name'];
		        $editcomment = $row['comment'];
		        $editnumber = $row['id'];
		        $editpass = $row['pass'];
	            }
	        }
        }
    ?>
    <form action="" method="post">
        <input type="text" name="editNO" value="<?php if(!empty($editnumber)) {echo $editnumber;} ?>">
        <br>
        <input type="text" name="name" placeholder="名前" value="<?php if(!empty($editname)) {echo $editname;} ?>">
        <br>
        <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($editcomment)) {echo $editcomment;} ?>">
        <br>
        <input type="text" name="pass" placeholder="パスワード">
        <input type="submit" name="submit">
        <br>
        <br>
        <input type="text" name="delete" placeholder="削除対象番号">
        <br>
        <input type="text" name="delpass" placeholder="パスワード">
        <input type="submit" name="submit" value="削除">
        <br>
        <br>
        <input type="text" name="edit" placeholder="編集対象番号">
        <br>
        <input type="text" name="editpass" placeholder="パスワード">
        <input type="submit" name="submit" value="編集">
    </form>
    
    <?php
    //データベース内にテーブルを作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest_52"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date TEXT,"
	. "pass TEXT"
	.");";
	$stmt = $pdo->query($sql);
	
	//削除機能
	if($_POST["delete"] && $_POST["delpass"]){
	    $sql = 'delete from tbtest_52 where id=:id';
	    $stmt = $pdo->prepare($sql);
	    $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
	    $stmt->execute();
	}
	
	if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){
            //editNOがないときは新規投稿、ある場合は編集
            if(empty($_POST["editNO"])){
                //以下、新規登録機能
                $sql = $pdo -> prepare("INSERT INTO tbtest_52 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
    	        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
	            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	            $name = $_POST["name"];
	            $comment = $_POST["comment"];
	            $pass = $_POST["pass"];
	            $date=date("Y/m/d H:i:s");
	            $sql->execute();
	}else{
                //以下、編集機能
	        $sql = 'UPDATE tbtest_52 SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	        $stmt->bindParam(':id', $editNO, PDO::PARAM_INT);
	        $stmt->bindParam(':date', $date, PDO::PARAM_INT);
	        $stmt->bindParam(':pass', $pass, PDO::PARAM_INT);
	        $stmt->execute();
            }
        }
	
	//表示機能
	$sql = 'SELECT * FROM tbtest_52';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].' ';
		echo $row['name'].' ';
		echo $row['comment'].' ';
		echo $row['date']."<br>";
	    echo "<hr>";
	}

    ?>
    </body>
    </html>