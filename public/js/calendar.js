$(function () {

  $('.delete-modal-open').on('click', function () {
    $('.js-modal').fadeIn();
    var getDate = $(this).attr('delete_date');
    var getPart = $(this).attr('delete_part');

    $('.modal_delete_date').text(getDate);
    $('.modal_delete_part').text(getPart);
    $('.delete-modal-hidden').val(getDate);
    $('.delete-modal-hidden').val(getPart);
    return false;
  });
  $('.js-modal-close').on('click', function () {
    $('.js-modal').fadeOut();
    return false;
  });

});
