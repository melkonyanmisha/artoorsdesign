$('.prod_mini_img').click( function(){
    let url = $(this).attr("src");
    $('.general_big_img').attr("src", url);
});

$('.star_span').on('mouseover', function() {
    $(this).addClass('star_span_active');
    $(this).prevAll(".star_span").addClass('star_span_active')
});
$('.star_span').on('mouseleave', function() {
    $(this).removeClass('star_span_active');
    $(this).prevAll(".star_span").removeClass('star_span_active');
});
$('.star_span').on('click', function() {
    $(this).addClass('star_span_actives');
    $(this).prevAll(".star_span").addClass('star_span_actives');
    $(this).nextAll(".star_span").removeClass('star_span_actives');
});

$('.description').click( function() {
    $(this).addClass('description_active');
    $('.comments').removeClass('comments_active');
    $('.for_description').addClass('for_description_active');
    $('.for_comment').removeClass('for_comment_active');
    $('._reviews').removeClass('_reviews_active');
    $('.for_reviews').removeClass('for_reviews_active');
});
$('.comments').click( function() {
    $(this).addClass('comments_active');
    $('.description').removeClass('description_active');
    $('.for_description').removeClass('for_description_active');
    $('.for_comment').addClass('for_comment_active');
    $('._reviews').removeClass('_reviews_active');
    $('.for_reviews').removeClass('for_reviews_active');
});
$('._reviews').click( function() {
    $(this).addClass('_reviews_active');
    $('.description').removeClass('description_active');
    $('.comments').removeClass('comments_active');
    $('.for_description').removeClass('for_description_active');
    $('.for_comment').removeClass('for_comment_active');
    $('.for_reviews').addClass('for_reviews_active');
});

$('.close_revo, .cencel_revo').click( function() {
    $('.write_review_popup_block').css("display",'none');
});

$('.write_review').click( function() {
    $('.write_review_popup_block').css("display",'flex');
});

$('.negative_').click(function() {
    $('.negative_').removeClass('positive_');
    $(this).addClass('positive_');
    $('.rate_model').attr('data-like',$(this).attr('data-id'));
});

