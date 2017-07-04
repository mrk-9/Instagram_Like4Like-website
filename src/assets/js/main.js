$(document).ready(function() {
  $('.mobile-trigger').on('click', function(){
    $('.mobile-overlay').fadeIn();
    $('header nav').animate({right:'0' });
  });

  $('.mobile-overlay').on('click', function(){
    $('.mobile-overlay').fadeOut();
    $('header nav').animate({right:'-200' });
  });

  $('.accordion .item .title').on('click', function(){
    $(this).toggleClass('active');
    $(this).next().slideToggle();
  });

  //tabs

  //Default Action
  $(".options").hide(); //Hide all content
  $(".radio-holder input:first-child").addClass("active").show().find("label input:radio").attr("checked", "");
  $(".options:first").show();
  //On Click Event
  $(".radio-holder input").on('click', function() {
      $(".radio-holder input").removeClass("active");
      $(".radio-holder input").find("label input:radio").attr("checked", "");
      $(this).addClass("active").find("label input:radio").attr("checked", "checked");
      $(".options").hide(); //Hide all tab content
      var activeTab = $(this).val();
      $('#' + activeTab).show();
  });
});
