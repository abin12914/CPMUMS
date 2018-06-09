$(function () {
    //handle link to tabs
    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs-custom a[href="#' + url.split('#')[1] + '"]').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs-custom a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    //append to main registratin number textbox
    $('body').on("click", ".transaction_type", function (evt) {
        if($('#transaction_type_credit').is(':checked')) {
            $('#account_label').html('Reciever / To - Account :');
        } else {
            $('#account_label').html('Giver / From - Account :');
        }
    });
});