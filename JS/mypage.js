/* 共通部分のHTMLファイルの読み込み */
$(function header() {
  $('#header').load('header.html');
});

$.ajax({
  type: 'GET',
  url: 'data.txt',
})
  .done(function(data) {
    console.log('ログイン完了');
    target = document.getElementById('name');
    target.innerHTML = data;
  })
  .fail(function() {
    window.location.href = 'Login.php';
  });
