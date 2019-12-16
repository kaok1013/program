$(document).ready(function () {
  var r_end = 8; 
  var c_end = 8; 
  var tableJQ = $('<table id="table_id1" cellpadding="55" border="1"> <tbody>');
  for (var r = 1; r <= r_end; r++) {
      var trJQ_r = $('<tr></tr>').appendTo(tableJQ);
      for (var c = 1; c <= c_end; c++) {
          $('<td class="table" id = "1"></td>').appendTo(trJQ_r);
      }
  }
  $('#tableid').append(tableJQ);
});
$(function() {
  $(".table").sortable({
    cursor: "move",
    opacity: 0.6,
    placeholder: "ui-state-highlight",
    forcePlaceholderSize: true,
    connectWith: ".table",
    revert: true,
    stop: function() {
      const sortitem = $(".table").serialize();
      console.log(sortitem);
      // $.post("Send_Data.php", { postData: sortitem });
    },
  });

  $("svg").draggable({
    connectToSortable: ".table",
    helper: "clone",
    revert: "invalid",
    containment: "body"
  });
});
