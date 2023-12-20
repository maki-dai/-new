$(function () {
  $('.search_conditions').click(function () {
    $('.search_conditions_inner').slideToggle();
    $(this).toggleClass("open");
  });



  // 選択科目の登録
  $('.js-subject_edit_btn').click(function () {
    $('.subject_inner').slideToggle();
    $(this).toggleClass("open");
  });
});
