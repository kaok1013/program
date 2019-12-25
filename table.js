// 保存する二次元配列sortitem=識別子がどこに入っているかconlist=条件式がどこに入っているか
const sortitem = new Array(8).fill(null).map(() => new Array(5).fill(null));
const conlist = new Array(8).fill(null).map(() => new Array(5).fill(null));

$(function() {
  // 表作成
  const rend = 8; // 行
  const cend = 5; // 列
  const tableJQ = $('<table cellpadding="15" border="1"> <tbody>');
  for (let r = 0; r < rend; r++) {
    const trJQr = $('<tr></tr>').appendTo(tableJQ);
    for (let c = 0; c < cend; c++) {
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
    placeholder: 'ui-state-highlight',
    forcePlaceholderSize: true,
    connectWith: '.table',
    revert: true,
    stop: function() {
      const rend = 8; // 行
      const cend = 5; // 列
      for (let r = 0; r < rend; r++) {
        for (let c = 0; c < cend; c++) {
          const table = 'table' + r + '_' + c;
          const sub = $('#' + table).sortable('toArray');
          if (sub[0] != undefined) {
            sortitem[r][c] = sub[0];
          }
        }
      }
      console.log(sortitem); // 識別子がどこに入っているか
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
      ui.helper.attr('title', '条件式が入力されていません。');
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
        callback: function (key, opt) {
          svg = $(this);
          // 条件編集のID取得
          const tableid = opt.$trigger.parent().attr('id');
          // 条件入力フォーム
          $('#input_form').dialog({
            modal: true, // モーダル
            title: '入力フォーム(仮)',
            width: 550,
            heighth: 550,
            buttons: {
              ok: {
                text: '確認',
                id: 'okbtnid',
                click: function(event,ui) {
                  const conditions = document.forms.input_form.input1.value;
                  const rend = 8; // 行
                  const cend = 5; // 列
                  for (let r = 0; r < rend; r++) {
                    for (let c = 0; c < cend; c++) {
                      ret = 'table' + r + '_' + c;
                      if (tableid == ret) {
                        conlist[r][c] = conditions;
                        $(svg).attr('title',conditions);
                      }
                    }
                  }
                  console.log(conlist); // 条件式がどこに入っているか
                  $(this).dialog('close');
                },
              },
              cancel: {
                text: 'キャンセル',
                id: 'cancelbtnid',
                click: function() {
                  $(this).dialog('close');
                },
              },
            },
            open: function() {
              $(document).keydown(function(event) {
                if (event.keyCode == 13) {
                  event.preventDefault();
                  $('#okbtnid').click;
                }
              });
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
            deleteid = opt.$trigger.parent().attr('id');
            $('#' + deleteid).empty();
            for (let r = 0; r < 8; r++) {
              for (let c = 0; c < 5; c++) {
                ret = 'table' + r + '_' + c;
                if (deleteid == ret) {
                  conlist[r][c] = null;
                  sortitem[r][c] = null;
                }
              }
            }
            console.log(conlist);
            console.log(sortitem);
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

// tooltip
$(function () {
  $(".table").tooltip({
    position: {
      my: "left center",
      at: "right center"
    }
  });
});