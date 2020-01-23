<?php
    $file = "data.txt";

    if(unlink($file)){
        echo $file.'の削除に成功';
    }
    else{
        echo $file.'の削除に失敗';
    }
?>