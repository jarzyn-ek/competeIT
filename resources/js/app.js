require('./bootstrap');

$(document).ready(function () {
    $('.datetimepicker').flatpickr({
        minDate: new Date(),
        enableTime: true
    });

    $('[data-toggle="tooltip"]').tooltip();

    $("[data-launch='modal']").click(function () {
        var id = $(this).data('id');

        $("#record_id").val(id);
        $('#resultsModal').modal();
    });
});
