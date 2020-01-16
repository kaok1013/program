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
    
    for($i = 0, $j = 0 ; $i<100 ; $i++){
            
        if(empty($module[$i][$j])){     //空ならスキップ
            continue;
        }
    
        if($module[$i][$j] == 8){       //ifならスタック（分岐のため）
            $Stack_i[] = $i;
        }
    
        if($module[$i][$j] == 11){      //分岐収束ならスタックから分岐先を探す
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
    
    if($module[$start_i][$start_j] != 9 && $module[$start_i][$start_j] != 15){       //分岐先がelseif || elseでない場合
        /* test  print("分岐先がelseifでない<br>"); */
        return --$flag;
    }
    
    for($i = $start_i, $j = $start_j ; $end_i >= $i ; $i++){
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
        if($module[$i][$j] == 2 ){
            break;
        }    
    }
        return $flag;
}

function Conversion($count, $flag, $Array_module, $Array_string){      //変換関数（再帰関数）
    $tmp_flag = $flag;
    $tab = '&nbsp;&nbsp;&nbsp;&nbsp;';      //TABキー変わり
    $element = count($Array_module[$tmp_flag]);

    for($i = 0 ; $element > $i ; $i++){
        switch ($Array_module[$tmp_flag][$i]){        //変換処理
            
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
                print($Array_string[$tmp_flag][$i].'<br />');
                break;

            case  4:        //前判定ループ開始
                print('<br />');
                for ($j = $count ; $j > 0 ; $j--){
                    print($tab);
			    }
                print("while(".$Array_string[$tmp_flag][$i]."){".'<br />');
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
                print("} while{".$Array_string[$tmp_flag][$i].'<br />');
                break;

            case  8:        //if
                for ($j = $count ; $j > 0 ; $j--){
                    print($tab);
                }
                $count++;
                print("if(".$Array_string[$tmp_flag][$i]."){".'<br />');
                break;

            case  9:        //else if
                for ($j = $count ; $j > 0 ; $j--){
                    print($tab);
                }
                $count++;
                print("else if(".$Array_string[$tmp_flag][$i]."){".'<br />');
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
                Conversion($count, $flag, $Array_module, $Array_string);
                break;
            case 12:        //入力
                for ($j = $count ; $j > 0 ; $j--){
                    print($tab);
                }
                print("scanf(".$Array_string[$tmp_flag][$i].");");
                print('<br />');
                break;
            case 13:        //出力
                for ($j = $count ; $j > 0 ; $j--){
                    print($tab);
                }
                print("printf(".$Array_string[$tmp_flag][$i].");");
                print('<br />');
                break;
    
            case 14:        //改行
                print('<br />');
                break;

            case 15:
                for ($j = $count ; $j > 0 ; $j--){
                    print($tab);
                }
                print("else{<br>");
                $count++;
                break;


            default :
                print("ERR");
                print('<br />');
        }  
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
["12","12","12"], 
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
["3","1212121",""], 
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
//--test*/

//以下main
$Array_module = array();
$Array_string = array();
$module = $_POST['module'];
$string = $_POST['string'];
$count = 1;                             //TABキーカウント
$flag = 0;                              //再帰回数(テーブル名にも使用)

//引数配列

list($Array_module, $Array_string) = Pre_Conv($module, $string);
Conversion($count, $flag, $Array_module, $Array_string);

?>
</body>