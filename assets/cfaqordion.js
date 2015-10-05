$(function() {

  /**
   * Prepare Tabs
   */
  setTimeout(function(){
    $('.tab_content').css({'minHeight' : $('.tab_list').height()});
  }, 200);

  /**
   * Prepare Accordion
   */
  $('.mod_faq_accordion .tab_list .tab_list_item').first().addClass('active');
  $('.mod_faq_accordion .tab_content .tab_content_item').first().addClass('active');

  /**
   * Accordion Click Function
   */
  $('.mod_faq_accordion .tab_list').on('click', '.tab_list_item', function(){
    that = this;
    $('.slider_navigation .move_left').data('slide-nr', 1);
    $('.slider_navigation .move_right').data('slide-nr', 1);
    $('.mod_faq_accordion .tab_content .tab_content_item.active').fadeOut('fast', function() {
      $(this).removeClass('active');
      $('.mod_faq_accordion .tab_content [data-content="'+$(that).data('tab-content')+'"]').fadeIn('fast', function() {
        $(that).parent().find('.active').removeClass('active');
        $(that).addClass('active');
        $(this).addClass('active');
      });
    });
  });

  /**
   * Slider Function
   */
  var slideCount = [];
  var slideWidth = [];
  var slideHeight = [];
  var sliderUlWidth = [];
  $('.mod_faq_accordion .slider').each(function(i, el) {
    var id = i;
    var slider = this;

    /**
     * Prepare slider
     */
    setTimeout(function(slider){
      slideCount[id] = $('.slide', slider).length;
      slideWidth[id] = $('.slide', slider).width();
      slideHeight[id] = $('.slide', slider).height();
      sliderUlWidth[id] = slideCount[id] * slideWidth[id];

      $(slider).css({ width: slideWidth[id], minHeight: slideHeight[id] });
      $('.slider_content', slider).css({ width: sliderUlWidth[id]});
      $('.slide', slider).css({ width: slideWidth[id] });
      $('.slide:last-child', slider).prependTo('.slider_content'. slider);
      $('.slider_navigation .move_left', slider).hide();
    }, 200);

    /**
     * Check the visiblity of nav and set the current page
     */
    function checkVisibility(){
      var count = $('.slider_content', slider).data('slides');
      if ($('.slider_navigation .move_left', slider).data('slide-nr') < 0) {
        $('.slider_navigation .move_left', slider).hide();
      }
      else {
        $('.slider_navigation .move_left', slider).show();
      }
      if ($('.slider_navigation .move_right', slider).data('slide-nr') >= count) {
        $('.slider_navigation .move_right', slider).hide();
      }
      else {
        $('.slider_navigation .move_right', slider).show();
      }
      var current = $('.slider_navigation .move_right', slider).data('slide-nr');
      $('.slider_navigation .status .current', slider).text(current)
    }

    /**
     * move to specific slide
     */
    function moveTo(slide){
      var targetPosition = (slideWidth[id] * slide) * -1;
      $('.slider_content', slider).animate({
        marginLeft: targetPosition
      }, function(){
        $('.slider_navigation .move_left').data('slide-nr', slide - 1);
        $('.slider_navigation .move_right').data('slide-nr', slide + 1);
        checkVisibility();
      });
    };

    /**
     * move one lide left
     */
    function moveLeft(slide){
      var targetPosition = (slideWidth[id] * slide) * -1;
      $('.slider_content', slider).animate({
        marginLeft: targetPosition
      }, function(){
        $('.slider_navigation .move_left').data('slide-nr', slide - 1);
        $('.slider_navigation .move_right').data('slide-nr', slide + 1);
        checkVisibility();
      });
    };

    /**
     * move one lide right
     */
    function moveRight(slide){
      var targetPosition = (slideWidth[id] * slide) * -1;
      $('.slider_content', slider).animate({
        marginLeft: targetPosition
      }, function(){
        $('.slider_navigation .move_left').data('slide-nr', slide - 1);
        $('.slider_navigation .move_right').data('slide-nr', slide + 1);
        checkVisibility();
      });
    };

    /**
     * Slider click functions
     */
    $('.slider_navigation', slider).on('click', 'li', function (e) {
      moveTo($(this).data('slide-nr'));
    });

    $('.slider_navigation', slider).on('click', '.move_left', function (e) {
      moveLeft($(this).data('slide-nr'));
    });

    $('.slider_navigation', slider).on('click', '.move_right', function (e) {
      moveRight($(this).data('slide-nr'));
    });

  });

});