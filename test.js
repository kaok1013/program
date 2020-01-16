$(function() {
  $('#sortable').sortable({
    connectWith: '#sort',
    helper: 'clone',
    start: function(event, ui) {
      $(ui.item).show();
      clone = $(ui.item).clone();
      before = $(ui.item).prev();
      parent = $(ui.item).parent();
    },
    remove: function(event, ui) {
      if (before.length) before.after(clone);
      else parent.prepend(clone);
    },
  });
  $('#sort').sortable({});
});
