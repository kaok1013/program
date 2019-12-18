$(document).ready(function() {
  // 表作成
  const rend = 8; // 行
  const cend = 5; // 列
  const tableJQ = $('<table cellpadding="55" border="1"> <tbody>');
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
    },
  });
});
