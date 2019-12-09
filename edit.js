//ドラッグアンドソート
$(function() {
  let ifflag = false;
  let i = 1;
  //ソートエリア
  jQuery(".sort-drop-area").sortable({
    cursor: "move",
    opacity: 0.6,
    placeholder: "ui-state-highlight",
    revert: true,
    //idを送る?
    stop: function() {
      var sortitem = $(".sort-drop-area").sortable("serialize");
      console.log(sortitem);
    }
  });
  //順番idを取得　○_識別子←○の部分1~∞
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

  //ドラックエリアフロウチャートのsvg
  jQuery(".dragArea")
    .find("svg")
    .draggable({
      connectToSortable: ".sort-drop-area",
      helper: "clone",
      revert: "invalid",
      containment: "body",
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

  //ゴミ箱エリア
  jQuery(".delete_area").droppable({
    over: function(event, ui) {
      if (confirm("本当に削除しますか？")) {
        ui.draggable.remove();
      }
    }
  });
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
        icon: "delete",
        callback: function(key, opt) {
          //削除するID取得
          if (confirm("本当に削除しますか？")) {
            deleteid = opt.$trigger.attr("id");
            $("#" + deleteid).remove();
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
        icon: "quit",
        callback: function() {
          return;
        }
      }
    }
  });

  $(".context-menu-one").on("click", function(e) {
    console.log("clicked", this);
  });
});
