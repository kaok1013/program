//ドラッグアンドソート
$(function (){
  let i = 1;
  jQuery(".sort-drop-area").sortable({
    cursor: "move",
    opacity: 0.6,
    placeholder: "ui-state-highlight",
    revert: true
  });
  jQuery(".sort-drop-area").on("sortstop", function() {
    // 番号を設定している要素に対しループ処理
    $(this)
      .find('[name="num_data"]')
      .each(function(idx) {
        var str = $(this).attr("id");
        var newid = str.substring(str.length - 1, str.length);
        // タグ内に通し番号を設定（idxは0始まりなので+1する）
        $(this).attr("id", idx + 1 + "_" + newid);
    });
  });
  jQuery(".dragArea")
    .find("svg")
    .draggable({
      connectToSortable: ".sort-drop-area",
      helper: "clone",
      revert: "invalid",
      start: function(event, ui) {
        newItem = $(this).attr("id");
      },
      stop: function(event, ui) {
        ui.helper.attr("id", i + "_" + newItem);
        ui.helper.attr("name", "num_data");
        ui.helper.addClass("context-menu-one");
        i++;
      }
    });

  jQuery(".dragArea").disableSelection();
});
//右クリックメニュー
$(function() {
  $.contextMenu({
    selector: ".context-menu-one",
    items: {
      edit: {
        name: "条件編集",
        icon: "edit",
        callback: function(key, opt) {
          //条件編集のID取得
          var svgid;
          svgid = opt.$trigger.attr("id");
          //条件入力フォーム
          $("#input_form").dialog({
            modal: true, //モーダル
            title: "入力フォーム(仮)",
            width: 550,
            heighth: 550,
            buttons: {
              確認: function() {
                var conditions = document.forms.input_form.input1.value;
                alert("[条件は]" + conditions + "[id]" + svgid);
                //ここにデータベースを送るスクリプトを書くと思う
                $(this).dialog("close");
              },
              キャンセル: function() {
                $(this).dialog("close");
              }
            }
          });
        }
      },
      //削除する
      delete: { 
        name: "消去", 
        icon: "delete" ,
        callback:function(key,opt){
          //削除するID取得
          if(confirm("本当に削除しますか？")){
            deleteid = opt.$trigger.attr("id");
            $('#'+deleteid).remove();
            $(function() {
              $(".sort-drop-area")
                .find('[name="num_data"]')
                .each(function(idx) {
                  var str = $(this).attr("id");
                  var newid = str.substring(str.length - 1, str.length);
                  // タグ内に通し番号を設定（idxは0始まりなので+1する）
                  $(this).attr("id", idx + 1 + "_" + newid);
              });
            });
          }
        }
      },
      sep1: "---------",
      quit: {
        name: "キャンセル",
        icon: function() {
          return "context-menu-icon context-menu-icon-quit";
        }
      }
    }
  });

  $(".context-menu-one").on("click", function(e) {
    console.log("clicked", this);
  });
});
