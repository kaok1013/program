/* eslint-disable linebreak-style */
// 保存する二次元配列sortitem.識別子conlist.条件式
const sortitem = new Array(15).fill(null).map(() => new Array(5).fill(null));
const conlist = new Array(15).fill(null).map(() => new Array(5).fill(null));

$(function() {
  // 表作成
  const rend = 15; // 行
  const cend = 5; // 列
  const tableJQ = $('<table cellpadding="15" bordercolor="#37a1e5"> <tbody>');
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
  let subcon = null;
  $('.table').sortable({
    placeholder: 'ui-state-highlight',
    forcePlaceholderSize: true,
    connectWith: '.table',
    revert: true,
    update: function() {
      const rend = 15; // 行
      const cend = 5; // 列
      for (let r = 0; r < rend; r++) {
        for (let c = 0; c < cend; c++) {
          const table = 'table' + r + '_' + c;
          const sub = $('#' + table).sortable('toArray');
          if (sub[0] != undefined) {
            sortitem[r][c] = sub[0];
          } else {
            sortitem[r][c] = null;
          }
        }
      }
      $.ajax({
        //POST通信
        type: 'POST',
        data: {
          module: sortitem,
          string: conlist,
        },
        //ここでデータの送信先URLを指定します。
        url: 'New_Conversion.php',
      }).done(function(response) {
        $('#pro').html(response);
      });

      //線を引く
      const leadlist = document.getElementsByClassName('lead-line-list');
      console.log(leadlist);
      if (leadlist.length == 3) {
        removeline.remove();
      }
      for (let i = 0; i < leadlist.length - 1; i++) {
        removeline = new LeaderLine(leadlist[i], leadlist[i + 1]);
      }
    },
    remove: function() {
      const rend = 15; // 行
      const cend = 5; // 列
      const removeid = $(this).attr('id');
      for (let r = 0; r < rend; r++) {
        for (let c = 0; c < cend; c++) {
          const ifid = 'table' + r + '_' + c;
          if (ifid == removeid) {
            subcon = conlist[r][c];
            conlist[r][c] = null;
          }
        }
      }
    },
    receive: function() {
      const con = $(this).attr('id');
      const i = con.substr(5, 1);
      const j = con.substr(7, 1);
      conlist[i][j] = subcon;
    },
  });

  // ドラッグ
  $('#sort').sortable({
    connectWith: '.table',
    containment: 'body',
    helper: 'clone',
    revert: true,
    placeholder: 'ui-state-highlight',
    start: function(event, ui) {
      $(ui.item).show();
      clone = $(ui.item).clone();
      before = $(ui.item).prev();
      parent = $(ui.item).parent();
      newItem = $(ui.item).attr('id');
    },
    remove: function(event, ui) {
      subtitle = $(ui.item).attr('title');
      if (before.length) before.after(clone);
      else {
        parent.prepend(clone);
      }
      ui.item.attr('id', newItem);
      ui.item.addClass('context-menu-one');
      ui.item.addClass('lead-line-list');
      ui.item.attr('title', '条件式が入力されていません。');
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
                click: function(event, ui) {
                  const conditions = document.forms.input_form.input1.value;
                  const rend = 15; // 行
                  const cend = 5; // 列
                  for (let r = 0; r < rend; r++) {
                    for (let c = 0; c < cend; c++) {
                      ret = 'table' + r + '_' + c;
                      if (tableid == ret) {
                        conlist[r][c] = conditions;
                        $(svg).attr('title', conditions);
                      }
                    }
                  }
                  $.ajax({
                    //POST通信
                    type: 'POST',
                    data: {
                      module: sortitem,
                      string: conlist,
                    },
                    //ここでデータの送信先URLを指定します。
                    url: 'New_Conversion.php',
                  }).done(function(response) {
                    $('#pro').html(response);
                  });
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
            for (let r = 0; r < 15; r++) {
              for (let c = 0; c < 5; c++) {
                ret = 'table' + r + '_' + c;
                if (deleteid == ret) {
                  conlist[r][c] = null;
                  sortitem[r][c] = null;
                }
              }
            }
            $.ajax({
              //POST通信
              type: 'POST',
              data: {
                module: sortitem,
                string: conlist,
              },
              //ここでデータの送信先URLを指定します。
              url: 'New_Conversion.php',
            }).done(function(response) {
              $('#pro').html(response);
            });
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
$(function() {
  $('.table').tooltip({
    position: {
      my: 'left center',
      at: 'right center',
    },
  });
});

$(function() {
  $('.toolclass').tooltip({
    position: {
      my: 'left center',
      at: 'right center',
    },
  });
});
