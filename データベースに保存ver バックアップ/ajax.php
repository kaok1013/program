<?php
    //データベース接続
        $server = "localhost";
        $userName = "brute-force";
        $password = "brute-force16";
        $dbName = "rakurakupg";

        $mysqli = new mysqli($server,$userName,$password,$dbName);

        if($mysqli->connect_error){
            echo $mysqli->connect_error;
            exit();
        }else{
            $mysqli->set_charset("utf-8");
        }

        $sql = "SELECT * FROM detail_module_0";
        
        $result = $mysqli -> query($sql);
        
        //クエリー失敗
        if(!$result) {
            echo $mysqli->error;
            exit();
        }
        
        //レコード件数
        $row_count = $result->num_rows;
        
        //連想配列で取得
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $rows[] = $row;
        }
        
        //結果セットを解放
        $result->free();
        
        // データベース切断
        $mysqli->close();
        
?>

<!DOCTYPE html>
    <?php
    foreach($rows as $row){
    ?>
    <tr> 
        <td><?php echo htmlspecialchars($row['String'],ENT_QUOTES,'UTF-8'); ?></td>
        <br>
    </tr> 
    <?php
    }  
    ?>
