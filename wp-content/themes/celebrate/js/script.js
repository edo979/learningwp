jQuery(document).ready(function($) {
 
    var $container = $('.blog .loop, .archive .loop');
   
    $container.imagesLoaded( function(){
      $container.masonry({
        itemSelector : '.blog .hentry, .archive .hentry'
      });
    });
    
  });