<?php
    $getsort=$_POST['sortitem'];
    
    if($getsort){
    //データベース接続
        $server = "localhost";
        $userName = "root";
        $password = "ruurei13";
        $dbName = "rakurakupg";

        $mysqli = new mysqli($server,$userName,$password,$dbName);

        if($mysqli->connect_error){
            echo $mysqli->connect_error;
            exit();
        }else{
            $mysqli->set_charset("utf-8");
        }

        foreach($getsort as $key => $id){
            $sql="update detail_module_test set Module=".$key."where Identifier=".$id;
        }
        //結果セットを解放
        $result->free();
        
        // データベース切断
        $mysqli->close();
    }
?>