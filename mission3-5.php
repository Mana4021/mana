<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
</head>
<body>
    
    <?php
    //ファイルからデータの読み取り
        $filename="mission_3-5.txt";
        
        //削除
        if($_POST["delete"] && $_POST["delpass"]){
            $delete=$_POST["delete"];
            $delpass=$_POST["delpass"];
            $delcon=file($filename);
            $fp=fopen($filename,"w");
            for($i=0; $i < count($delcon); $i++){
                $deldata=explode("<>",$delcon[$i]);
                if($delete !== $deldata[0] && $delpass !=$deldata[4]){
                    fwrite($fp,$delcon[$i]);
                }else{
                    fwrite($fp,"");
                }
            }
            fclose($fp);
        }
        
        //編集
        //$editが空ではない場合
        if(!empty($_POST["edit"]) && !empty($_POST["editpass"])){
            $edit=$_POST["edit"];
            $editpass=$_POST["editpass"];
            $lines=file($filename);
            foreach($lines as $line){
                $editdata=explode("<>",$line);
                    if($edit == $editdata[0] && $editpass=$editdata[4]){
                        $editnumber=$editdata[0];
                        $editname=$editdata[1];
                        $editstr=$editdata[2];
                    }
            }
        }
        
        //投稿機能
        if(!empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["pass"])){
            $name=$_POST["name"];
            $str=$_POST["str"];
            $pass=$_POST["pass"];
            $date=date("Y/m/d H:i:s");
            //editNOがないときは新規投稿、ある場合は編集
            if(empty($_POST["editNO"])){
                //以下、新規登録機能
                if(file_exists($filename)){
                    $num=count(file($filename)) + 1;
                }else{
                    $num = 1;
                }
                
                $fp=fopen($filename,"a");
                fwrite($fp , "$num<>$name<>$str<>$date<>$pass<>" . PHP_EOL);
                fclose($fp);
            }else{
            //以下、編集機能
            $editNO = $_POST["editNO"];
            $lines=file($filename);
            $fp=fopen($filename,"w");
                foreach($lines as $line){
                    $data=explode("<>",$line);
                    if($data[0] == $editNO && $data[4] == $editpass){
                        fwrite($fp , "$editNO<>$name<>$str<>$date<>" . PHP_EOL);
                    }else{
                        fwrite($fp , $line);
                    }
                }
            fclose($fp);
        }
        }
    ?>
    
    <form action="" method="post">
        <input type="text" name="editNO" value="<?php if(!empty($editnumber)) {echo $editnumber;} ?>">
        <br>
        <input type="text" name="name" placeholder="名前" value="<?php if(!empty($editname)) {echo $editname;} ?>">
        <br>
        <input type="text" name="str" placeholder="コメント" value="<?php if(!empty($editstr)) {echo $editstr;} ?>">
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
        if(file_exists($filename)){
            $lines = file($filename,FILE_IGNORE_NEW_LINES);
            foreach($lines as $line){
                $getdata=explode("<>",$line);
                echo $getdata[0] . " " . $getdata[1] . " " . $getdata[2] . " " . $getdata[3] . "<br>";
            }
        }
    ?>
</body>
</html>