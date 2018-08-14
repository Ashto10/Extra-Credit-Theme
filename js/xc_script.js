function resizeIframes() {
  $("iframe").each(function() {
    var $el = $(this);
    var newWidth = $el.parent().width();
    $el
      .width(newWidth)
      .height(newWidth * $el.attr('aspectRatio'));
  });
}

function initializeIframes() {
  $("iframe").each(function() {
    $(this)
      .attr('aspectRatio', this.height / this.width)
      .removeAttr('height')
      .removeAttr('width');
  });
}

jQuery(document).ready(function( $ ) {

  function sqaureImg () {
    var sizeCap = $('.squareImg:first').width();
    $('.squareImg').height(sizeCap);
  }

  $(window).resize(function() {
    sqaureImg();
    resizeIframes();
  });

  // Run once on page load
  sqaureImg();
  initializeIframes();
  resizeIframes();

});