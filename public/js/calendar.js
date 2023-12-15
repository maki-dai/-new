$(function () {

  $('.delete-modal-open').on('click', function () {
    $('.js-modal').fadeIn();
    var getDate = $(this).attr('date');
    var getPart = $(this).attr('part');

    $('.modal_delete_date').p(getDate);
    $('.modal_delete_part').p(getPart);
    // $('.delete-modal-hidden').val(getDate);
    // $('.delete-modal-hidden').val(getPart);
    return false;
  });
  $('.js-modal-close').on('click', function () {
    $('.js-modal').fadeOut();
    return false;
  });

});
