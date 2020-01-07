function database() {
  //ここからajaxの処理です。
  $.ajax({
    //POST通信
    type: "POST",
    //ここでデータの送信先URLを指定します。
    url: "Conversion.php"
  })
    .done(function(response) {
      $("#pro").html(response);
    })
    .fail(function() {});
  setTimeout(function() {
    database();
  }, 5000);
}
database();
