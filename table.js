$(document).ready(function () {
  //表作成
  var r_end = 8; //行
  var c_end = 5; //列
  var tableJQ = $('<table cellpadding="55" border="1"> <tbody>');
  for (var r = 1; r <= r_end; r++) {
      var trJQ_r = $('<tr></tr>').appendTo(tableJQ);
      for (var c = 1; c <= c_end; c++) {
          $('<td class="table" id="table'+r+'_'+c+'"></td>').appendTo(trJQ_r);
      }
  }
  $('#tableid').append(tableJQ);
});
$(function() {
  //ソート
  $(".table").sortable({
    cursor: "move",
    opacity: 0.6,
    placeholder: "ui-state-highlight",
    forcePlaceholderSize: true,
    connectWith: ".table",
    revert: true,
    stop: function() {
      const sortitem = [];
      var r_end = 8; //行
      var c_end = 5; //列
      for (var r = 1; r <= r_end; r++) {
        const toarray = [];
        for (var c = 1; c <= c_end; c++) {
          const table = 'table'+r+'_'+c;
          toarray.push($('#'+table).sortable("toArray"));         
        }
        sortitem.push(toarray);
      }
      console.log(sortitem);
      // $.post("Send_Data.php", { postData: sortitem });
    },
  });

  //ドラッグ
  $("svg").draggable({
    connectToSortable: ".table",
    helper: "clone",
    containment: "body",
    start: function(event, ui) {
      newItem = $(this).attr("id");
    },
    stop: function(event, ui) {
      ui.helper.attr("id", newItem);
    }
  });
});
