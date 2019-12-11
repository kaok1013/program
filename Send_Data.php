<?php

/*やること
・php -> sqlの使い方
・文字列の表示部分
*/

function Send_Data($array){

    $dsn = 'mysql:dbname=rakurakupg;host=localhost';
    $user = 'brute-force';
    $password = 'brute-force16';
    $dbh = new PDO($dsn, $user, $password);
    $Data = array();    //一時的にデータ格納（2次元配列：Data[0:テーブル番号, 1:SQL文]）
    $table = count($array);
    
    for($i = 0 ; $table > $i ; $i++){

        /* create table */
        $sql = "CREATE TABLE 'rakurakupg'.'detail_module_".$i."' ( 'Identifier' INT NOT NULL , 'Module' VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , 'String' VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL , PRIMARY KEY ('Identifier')) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;";

        /* SQL */
        $dbh->query($sql);

        $element = count($array[$i]);

        for($j = 0 ; $element > $j ; $j++){
            /* Data[falg]にデータ代入 */
            if(empty($array[$i][$j+1])){
                $end = ";";
            }
            else{
                $end = ",<br>";
            }
            
            $tmp = $j+1;

            $Data[$i][$j] = "(".$tmp.", '".$array[$i][$j]."', '"."文字列変数"."')".$end;
        }
    }

    /* Data中身 test 
    for($i = 0 ; $table > $i ; $i++){
        $j = 0;
        while(true){
            if(empty($Data[$i][$j]))
                break;
            print($Data[$i][$j]);
            $j++;
            if($j % 5 == 0){
                print("<br>");
            }
        }
        print("<br>");
    }
    */

    try{
        for($i = 0 ; $table > $i ; $i++){
            $element = count($array[$i]);
            $sql = "insert into detail_module_".$i." value";
                for($j = 0 ; $element > $j ; $j++){
                    $sql .= $Data[$i][$j];
                }
            //sql文 test
            print($sql."<br><br>");
            /* SQL(insert) */
        }
    }catch (PDOException $e){
        print('Error:'.$e->getMessage());
        die();
    }

    $dbh = null;

}
?>
