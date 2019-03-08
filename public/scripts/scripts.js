var Admin = {
  form : {
    init: function () {
      $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green'
      });

      $('input').on('focus', function () {
        $(this).parent().find('label.error').hide();
      });
    }
  }
};

function readPreviewURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $("img.image-preview[data-id=" + $(input).data('id') + "]").attr('src', e.target.result);
    };

    reader.readAsDataURL(input.files[0]);
  }
}

Admin.form.init();

$('body').on('click', '.alert-delete-button', function (e) {
  e.preventDefault();
  var $this = $(this);

  swal({
    title: $this.attr('data-title'),
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#1ab394",
    confirmButtonText: $this.attr('data-confirm'),
    cancelButtonText: $this.attr('data-cancel'),
    closeOnConfirm: false
  },
  function () {
    window.location.href = $this.attr('href');
  });
});

// $(document).on('ready', function () {
  $("input[type=file].image-preview").on('change', function () {
    console.log('preview');
    readPreviewURL(this);
  });
// });

