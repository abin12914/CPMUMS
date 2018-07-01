$(function () {
    //append to main registratin number textbox
    $('body').on("click", ".voucher_type", function (evt) {
        if($('#voucher_type_credit').is(':checked')) {console.log('in');
            $('#account_label').html('Reciever');
        } else {console.log('else');
            $('#account_label').html('Giver');
        }
    });
});