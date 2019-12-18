$(function() {
  // 表作成
  const rend = 8; // 行
  const cend = 5; // 列
  const tableJQ = $('<table cellpadding="15" border="1"> <tbody>');
  for (let r = 1; r <= rend; r++) {
    const trJQr = $('<tr></tr>').appendTo(tableJQ);
    for (let c = 1; c <= cend; c++) {
      $('<td class="table" id="table' + r + '_' + c + '"></td>').appendTo(
        trJQr,
      );
    }
  }
  $('#tableid').append(tableJQ);
});

$(function() {
  // ソート
  $('.table').sortable({
    cursor: 'move',
    opacity: 0.6,
    placeholder: 'ui-state-highlight',
    forcePlaceholderSize: true,
    connectWith: '.table',
    revert: true,
    stop: function() {
      const sortitem = [];
      const rend = 8; // 行
      const cend = 5; // 列
      for (let r = 1; r <= rend; r++) {
        const toarray = [];
        for (let c = 1; c <= cend; c++) {
          const table = 'table' + r + '_' + c;
          toarray.push($('#' + table).sortable('toArray'));
        }
        sortitem.push(toarray);
      }
      console.log(sortitem);
      // $.post("Send_Data.php", { postData: sortitem });
    },
  });

  // ドラッグ
  $('svg').draggable({
    connectToSortable: '.table',
    helper: 'clone',
    containment: 'body',
    start: function(event, ui) {
      newItem = $(this).attr('id');
    },
    stop: function(event, ui) {
      ui.helper.attr('id', newItem);
      ui.helper.addClass('context-menu-one');
    },
  });
  // ゴミ箱エリア
  $('.delete_area').droppable({
    over: function(event, ui) {
      if (confirm('本当に削除しますか？')) {
        ui.draggable.remove();
      }
    },
  });
});
// 右クリックメニュー
$(function() {
  $.contextMenu({
    selector: '.context-menu-one',
    items: {
      edit: {
        name: '条件編集',
        icon: 'edit',
        callback: function(key, opt) {
          // 条件編集のID取得
          const svgid = opt.$trigger.attr('id');
          // 条件入力フォーム
          $('#input_form').dialog({
            modal: true, // モーダル
            title: '入力フォーム(仮)',
            width: 550,
            heighth: 550,
            buttons: {
              確認: function() {
                const conditions = document.forms.input_form.input1.value;
                alert('[条件は]' + conditions + '[id]' + svgid);
                // ここにデータベースを送るスクリプトを書くと思う
                $(this).dialog('close');
              },
              キャンセル: function() {
                $(this).dialog('close');
              },
            },
          });
        },
      },
      // 削除する
      delete: {
        name: '消去',
        icon: 'delete',
        callback: function(key, opt) {
          // 削除するID取得
          if (confirm('本当に削除しますか？')) {
            deleteid = opt.$trigger.attr('id');
            $('#' + deleteid).remove();
            /* 並び順いったん放置
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
            */
          }
        },
      },
      sep1: '---------',
      quit: {
        name: 'キャンセル',
        icon: 'quit',
        callback: function() {
          return;
        },
      },
    },
  });

  $('.context-menu-one').on('click', function(e) {
    console.log('clicked', this);
  });
});
