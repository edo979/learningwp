(function($) {
  "use strict";

  $(function() {
    // Get secret key
    var security = $('div#slides-order-key').text();
    
    $('table#admin-slides-sort').sortable({
      items: 'tr.list-items',
      revert: true,
      opacity: 0.6,
      cursor: 'move',
      update: function() {
        var order = $(this).sortable('serialize') + '&action=esse_fpb_slides_order' + '&security=' + security;
        $.post(ajaxurl, order, function(response) {
          console.log(response);
        });
      }
    });

  });

}(jQuery));