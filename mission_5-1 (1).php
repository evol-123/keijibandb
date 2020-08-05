<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    
<?php
    $dsn="データベース名";
    $user="ユーザー名";
    $password="パスワード";
    $pdo= new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    
    
    //投稿機能
	if(!empty($_POST["name"])&&!empty($_POST["comm"])&&!empty($_POST["pass1"])){
        $name = $_POST["name"];
        $comm = $_POST["comm"];
        $pass = $_POST["pass1"];
        
        
	     //編集実行
    	 if(!empty($_POST["edn"])){
            $id =$_POST["edn"]; //変更する投稿番号
    	    $sql = 'UPDATE db5 SET name=:name,comment=:comment,pass=:pass,time=cast(now() as datetime) WHERE id=:id';
    	    $stmt = $pdo->prepare($sql);
    	    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    	    $stmt->bindParam(':comment', $comm, PDO::PARAM_STR);
    	    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
    	    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    	    $stmt->execute();
            }
         
         //新規投稿
         else{
              //INSERT文でデータを入力
              $sql = $pdo -> prepare("INSERT INTO db5 (name, comment, pass, time) VALUES (:name, :comment, :pass, cast(now() as datetime))");  
	          $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	          $sql -> bindParam(':comment', $comm, PDO::PARAM_STR);
	          $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	          $sql -> execute();
         }
	}
	
	
	//削除機能
	if(!empty($_POST["num"])&&!empty($_POST["pass2"])){
	    $sql = 'SELECT * FROM db5';
	    $stmt = $pdo->query($sql);
	    $results = $stmt->fetchAll();
	    foreach ($results as $row){
	        if($row["pass"]==$_POST["pass2"]){
                $id =$_POST["num"];
        	    $sql = 'delete from db5 where id=:id';
        	    $stmt = $pdo->prepare($sql);
        	    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        	    $stmt->execute();
	        }
	    }
    }
    
    //編集選択
    if(!empty($_POST["edit"])&&!empty($_POST["pass3"])){
        //編集したい番号
        $edit=$_POST["edit"];
        //データレコード抽出
        $sql = 'SELECT * FROM db5';
        $stmt = $pdo->query($sql);
        //全件配列で取得
	    $results = $stmt->fetchAll();
	    foreach ($results as $row){
	        if($row['id']==$edit&&$row["pass"]==$_POST["pass3"]){
	            $editname=$row['name'];
	            $editcomm=$row['comment'];
	            $editnumber=$row['id'];
	        }
	    }
    }
?>

<form action="mission_5-1.php" method="post">
        <input type="text" name="name" placeholder="名前" value="<?php echo $editname;?>"><br>
        <input type="text" name="comm" placeholder="コメント" value="<?php echo $editcomm;?>">
        <input type="hidden" name="edn" value="<?php echo $editnumber;?>"><br>
        <input type="text" name="pass1" placeholder="パスワード">
        <input type="submit" name="submit" value="送信">
</form>
<br>
<form action="mission_5-1.php" method="post">
        <input type="number" name="num" placeholder="削除番号"><br>
        <input type="text" name="pass2" placeholder="パスワード">
        <input type="submit" name="subm" value="削除">
</form>
<br>
<form action="mission_5-1.php" method="post">
        <input type="number" name="edit" placeholder="編集番号"><br>
        <input type="text" name="pass3" placeholder="パスワード">
        <input type="submit" name="sub" value="編集">
</form>

<?php
    //SELECT文でデータレコード抽出
    $sql = 'SELECT * FROM db5';      
	$stmt = $pdo->query($sql);
	//fetchAllで結果を全件配列で取得
	$results = $stmt->fetchAll();       
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['time'].'<br>';
	echo "<hr>";
	}
?>

</body>
</html>