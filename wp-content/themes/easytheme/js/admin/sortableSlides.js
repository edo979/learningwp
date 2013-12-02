jQuery(document).ready(function($) {
  $('table#admin-slides-sort').sortable({
    items: 'tr.list-items',
    revert: true,
    opacity: 0.6,
    cursor: 'move',
    update: function() {
      var order = $(this).sortable('serialize') + '&action=easytheme_slides_update';
      $.post(ajaxurl, order, function(response) {
        console.log(response);
      });
    }
  });
});