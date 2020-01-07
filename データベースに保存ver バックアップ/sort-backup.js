$(function() {
  var newItem;
  var i = 1;
  $(".DropArea_class").bind("sortstop", function() {
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
  $(".DropArea_class").sortable({
    connectToSortable: ".DropArea_class"
  });

  $("img").draggable({
    start: function(event, ui) {
      newItem = $(this).attr("id");
    },
        containment: 'body',

    stop: function(event, ui) {
      ui.helper.attr("id", i + "_" + newItem);
      ui.helper.attr("name", "num_data");
      i++;
    },
    helper: "clone",
    connectToSortable: ".DropArea_class",
    revert: "invalid"
  });
});
