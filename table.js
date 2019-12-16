$(function() {
  $(".table").sortable({
    cursor: "move",
    opacity: 0.6,
    placeholder: "ui-state-highlight",
    forcePlaceholderSize: true,
    connectWith: ".table",
    revert: true
  });

  $("svg").draggable({
    connectToSortable: ".table",
    helper: "clone",
    revert: "invalid",
    containment: "body"
  });
  //   $("#tableid").css({ display: "table", "border-collapse": "collapse" });
  //   var tbody = $("<div>")
  //     .addClass("tbody")
  //     .css({ display: "table-row-group" });
  //   Object.keys(Array(4).fill(null)).forEach(function(x) {
  //     var row = $("<div>")
  //       .addClass("row")
  //       .css({ display: "table-row" });
  //     Object.keys(Array(4).fill(null)).forEach(function(y) {
  //       $("<div>")
  //         .addClass("cell")
  //         .attr("id", "td" + (parseInt(x) * 4 + parseInt(y) + 1))
  //         .text(parseInt(x) * 4 + parseInt(y) + 1)
  //         .css({
  //           display: "table-cell",
  //           border: "1px solid #000000",
  //           "text-align": "center"
  //         })
  //         .appendTo(row);
  //     });
  //     row.appendTo(tbody);
  //   });
  //   tbody.appendTo($("#tableid"));
});
