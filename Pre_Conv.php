<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>変換準備</title>
</head>
<body>

<?php

function Pre_Conv($module, $string){
/* 
    引数    ：（識別子, 条件式）
    戻り値  ：Send_Data使える配列2つ
    処理    ：引数配列をSend_Dataで使える形に変換
*/

    global $Array_module;
    global $Array_string;
    $flag = 0;
    $Stack_i = array();

    for($i = 0, $j = 0 ; ; $i++){
        
        if(empty($module[$i][$j])){
            continue;
        }

        if($module[$i][$j] == 8){
            $Stack_i[] = $i;
        }

        if($module[$i][$j] == 11){
            $start_i = array_pop($Stack_i);
            /*test--
            print("pc<br>");
            --test*/
            $flag = Pre_Conv_branch($start_i, $j+1, $flag+1, $i, $module, $string);
            if($flag == 999){
                return;
            }
        }

        $Array_module[$j][] = $module[$i][$j];
        $Array_string[$j][] = $string[$i][$j];

        if($module[$i][$j] == 2){
            break;
        }
        
    }

    return [$Array_module, $Array_string];
}

function Pre_Conv_branch($start_i, $start_j, $flag, $end_i, $module, $string){
    /* 
    再帰関数
    引数    ：（参照開始位置(縦), 参照開始位置（横）, flag, 参照終了位置（縦）, 識別子, 条件式）
    戻り値  ：flag
    処理    ：分岐の処理
    ※elseifにおいて参照終了位置まで見て分岐収束が無ければスタックを一つ捨てる
    */
    global $Array_module;
    global $Array_string;

//    $flag = $start_j;
    $Stack_i = array();

    /*test--
    print("end_i : ".$end_i." , module[".$start_i."][".$start_j."] : ");
    print($module[$start_i][$start_j]."<br>");
    --test*/

    for($i = 0 ; $i <= 10 ;$i++){    //nullの間$start_jを+1（10回探索してもない場合はERR）
        if(!empty($module[$start_i][$start_j])){
            break;
        }
        $start_j++;

        if($i == 10){
            print($flag.", 分岐先なし<br>");
            return --$flag;
        }
    }

    if($module[$start_i][$start_j] != 9){       //分岐先がelseifでない場合
       /* test  print("分岐先がelseifでない<br>"); */
        return --$flag;
    }

    for($i = $start_i, $j = $start_j ; $module[$i][$j] != 2 && $end_i != $i ; $i++){
        /*test--
        print($i." : ".$j."<br>");
        --test*/

        if(empty($module[$i][$j])){
            continue;
        }

        if($module[$i][$j] == 8 || $module[$i][$j] == 9){
            /*test--
            print($module[$i][$j]."<br>");
            --test*/
            $Stack_i[] = $i;
        }

        $Array_module[$flag][] = $module[$i][$j];
        $Array_string[$flag][] = $string[$i][$j];

        if($module[$i][$j] == 11){
            /* test-- 
            print("test:".$module[$i][$j]."<br>");
            --test */
            $start_i = array_pop($Stack_i);
                        /*test--
                        print("pcb<br>");
                        --test*/
            $flag = Pre_Conv_branch($start_i, $j+1, $flag+1, $i, $module, $string);
        }
        
    }

    return $flag;
}

function Send_Data($Array_module, $Array_string){

    $dsn = 'mysql:dbname=rakurakupg;host=localhost';
    $user = 'root';
    $password = 'ruurei13';
    
    $Data = array();    //一時的にデータ格納（2次元配列：Data[0:テーブル番号, 1:SQL文]）
    $table = count($Array_module);
    
    for($i = 0 ; $table > $i ; $i++){

        /* create table */
        $sql = "CREATE TABLE `rakurakupg`.`detail_module_$i` 
        ( `Identifier` INT NOT NULL , 
        `Module` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , 
        `String` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL , 
        PRIMARY KEY (`Identifier`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;";

        /* SQL(create table) */
        try{
            $dbh = new PDO($dsn, $user, $password);
            /*test--
            print("実行するsql文（create table）<br><br>".$sql."<br><br>");
            --test*/
            $res = $dbh->query($sql);
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            die();
        }
        $dbh = null;

        $element = count($Array_module[$i]);

        for($j = 0 ; $element > $j ; $j++){
            /* Data[falg]にデータ代入 */
            if(empty($Array_module[$i][$j+1])){
                $end = ";";
            }
            else{
                $end = ",";
            }
            
            $tmp = $j+1;

            $Data[$i][$j] = "(".$tmp.", '".$Array_module[$i][$j]."', '".$Array_string[$i][$j]."')".$end;
        }
    }

    /* Data中身 test-- 
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
    --test */

    for($i = 0 ; $table > $i ; $i++){
        $element = count($Array_module[$i]);
        $sql = "insert into `detail_module_$i`(`Identifier`, `Module`, `String`) values";
            for($j = 0 ; $element > $j ; $j++){
                $sql .= $Data[$i][$j];
            }
        try{
            $dbh = new PDO($dsn, $user, $password);
            /*sql文 test--
            print("<br>実行するsql文（insert）<br><br>".$sql."<br><br>");
            --test*/
            /* SQL(insert) */
            $result = $dbh->query($sql);
            if (!$result) {
                die('SELECTクエリーが失敗しました。'.mysqli_error());
            }
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            die();
        }
        $dbh = null;
    }
}

/*test--
$module = [["1","",""], 
["","",""], 
["3","",""], 
["","",""], 
["12","",""], 
["","",""], 
["4","",""], 
["","",""], 
["12","",""], 
["","",""], 
["13","",""], 
["","",""], 
["8","9",""], 
["","",""], 
["3","12",""], 
["","",""], 
["10","10",""], 
["","",""],
["11","",""], 
["","",""], 
["5","",""], 
["","",""], 
["8","9","9"], 
["","",""], 
["12","13","14"], 
["","",""], 
["10","10","10"], 
["","",""],
["","11",""],
["","",""],
["11","",""], 
["","",""], 
["2","",""]
];
$string =[["1","",""], 
["","",""], 
["3","",""], 
["","",""], 
["12","",""], 
["","",""], 
["4","",""], 
["","",""], 
["12","",""], 
["","",""], 
["13","",""], 
["","",""], 
["8","9",""], 
["","",""], 
["3","12",""], 
["","",""], 
["10","10",""], 
["","",""],
["11","",""], 
["","",""], 
["5","",""], 
["","",""], 
["8","9","9"], 
["","",""], 
["12","13","14"], 
["","",""], 
["10","10","10"], 
["","",""],
["","11",""],
["","",""],
["11","",""], 
["","",""], 
["2","",""]
];
--test*/

//ここからmain関数

$Array_module = array();
$Array_string = array();

list($Array_module, $Array_string) = Pre_Conv($module, $string);
Send_Data($Array_module, $Array_string);

/*test--
print("teble1<br>");
for($i = 0, $j = 0 ; ;$j++){
    if(empty($Array_module[$i][$j])){
        break;
    }
    print($Array_module[$i][$j].", ");
}
print("<br>");
for($i = 0, $j = 0 ; ;$j++){
    if(empty($Array_string[$i][$j])){
        break;
    }
    print($Array_string[$i][$j].", ");
}

print("<br><br>teble2<br>");
for($i = 1, $j = 0 ; ;$j++){
    if(empty($Array_module[$i][$j])){
        break;
    }
    print($Array_module[$i][$j].", ");
}
print("<br>");
for($i = 1, $j = 0 ; ;$j++){
    if(empty($Array_string[$i][$j])){
        break;
    }
    print($Array_string[$i][$j].", ");
}

print("<br><br>teble3<br>");
for($i = 2, $j = 0 ; ;$j++){
    if(empty($Array_module[$i][$j])){
        break;
    }
    print($Array_module[$i][$j].", ");
}
print("<br>");
for($i = 2, $j = 0 ; ;$j++){
    if(empty($Array_string[$i][$j])){
        break;
    }
    print($Array_string[$i][$j].", ");
}

print("<br><br>teble4<br>");
for($i = 3, $j = 0 ; ;$j++){
    if(empty($Array_module[$i][$j])){
        break;
    }
    print($Array_module[$i][$j].", ");
}
print("<br>");
for($i = 3, $j = 0 ; ;$j++){
    if(empty($Array_string[$i][$j])){
        break;
    }
    print($Array_string[$i][$j].", ");
}
--test*/

?>
</body>