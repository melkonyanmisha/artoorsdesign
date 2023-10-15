$('.slide_section').slick({
    prevArrow: "<img class='a-left lleft control-c prev slick-prev' src='/public/new/img/left.svg'>",
    nextArrow: "<img class='a-right rright control-c next slick-next' src='/public/new/img/right.svg'>",
    dots: true,
    infinite: true,
    arrows: true,
    autoplay: true,
    speed: 700,
    slidesToShow: 1,
    slidesToScroll: 1,
    variableWidth: true,
    swipeToSlide: true,
});
$('.slick-list, .slick-track').css({
    "height": "100%"
  });

  $('.gray_slider').slick({
    dots: false,
    infinite: true,
    speed: 300,
    slidesToShow: 1,
    centerMode: false,
    variableWidth: true
  });

  $(".slick-prev").prepend('<img id="thePrev" src="/public/new/img/left.svg" />')
  $("#thePrev #theNext").css({"width":"39px","height":"39px"});
  $(".slick-next").prepend('<img id="theNext" src="/public/new/img/right.svg" />');
