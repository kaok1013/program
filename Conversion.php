<?php

function Conversion($count, $flag, $dbh){      //変換関数（再帰関数）
    $count_row = 0;                         //現在の行カウント
    $tab = '&nbsp;&nbsp;&nbsp;&nbsp;';      //TABキー変わり

    $sql = 'select * from Detail_Module_';
    $sql .= $flag;
    foreach ($dbh->query($sql) as $row) {

        switch ($row['Module']){        //変換処理
            
            case  1:         //開始
                print("#include&lt;stdio.h&gt;".'<br />');
                print("#include&ltstdlib.h&gt;".'<br />');
                print("#include&lt;string.h&gt;".'<br />');
                print("int main(void)".'<br />');
                print("{".'<br />');
                break;

            case  2:         //終了
                print('<br />');
                for ($j = $count ; $j > 0 ; $j--){
                    print($tab);
			    }
                print("return 0;".'<br />'."}");
                break;

            case  3:         //処理
                for ($j = $count ; $j > 0 ; $j--){
                    print($tab);
                }
                print($row['String'].'<br />');
                break;

            case  4:        //前判定ループ開始
                print('<br />');
                for ($j = $count ; $j > 0 ; $j--){
                    print($tab);
			    }
                print("while(".$row['String']."){".'<br />');
                $count++;
                break;

            case  5:        //前判定ループ終了
                $count--;
                for ($j = $count ; $j > 0 ; $j--){
                    print($tab);
			    }
                print("}".'<br />');
                break;

            case  6:        //後判定ループ開始
                for ($j = $count ; $j > 0 ; $j--){
                    print($tab);
                }
                $count++;
                print("do {".'<br />');
                break;

            case  7:        //後判定ループ終了
                $count--;
                for ($j = $count ; $j > 0 ; $j--){
                    print($tab);
                }
                print("} while{".$row['String']."};".'<br />');
                break;

            case  8:        //if
                for ($j = $count ; $j > 0 ; $j--){
                    print($tab);
                }
                $count++;
                print("if(".$row['String']."){".'<br />');
                break;

            case 9:        //else if
                for ($j = $count ; $j > 0 ; $j--){
                    print($tab);
                }
                $count++;
                print("else if(".$row['String']."){".'<br />');
                break;

            case 10:        //end if
                $count--;
                for ($j = $count ; $j > 0 ; $j--){
                    print($tab);
                }
                print("}".'<br />');
                break;

            case 11:        //分岐終了
                $flag++;
                Conversion($count, $flag, $dbh);
                break;

            case 12:        //出力
                for ($j = $count ; $j > 0 ; $j--){
                    print($tab);
                }
                print("scanf(".$row['String'].");");
                print('<br />');
                break;

            case 13:        //入力
                for ($j = $count ; $j > 0 ; $j--){
                    print($tab);
                }
                print("printf(".$row['String'].");");
                print('<br />');
                break;

            case 14:        //改行
                print('<br />');
                break;

            default :
                print("ERR");
                print('<br />');
        }
        $count_row++;
        
    }
}

/* ここからmain関数 */

$dsn = 'mysql:dbname=rakurakupg;host=localhost';
$user = 'root';
$password = 'ruurei13';
$count = 1;                             //TABキーカウント
$flag = 0;                              //再帰回数(テーブル名にも使用)

try{
    $dbh = new PDO($dsn, $user, $password);
    
    Conversion($count, $flag, $dbh);

}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}

$dbh = null;

/* ここまでmain関数 */

?>

</body>
</html>