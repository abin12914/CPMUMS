$(function () {
    //append to main registratin number textbox
    $('body').on("change", ".transaction_type", function (evt) {
        if($('#transaction_type_credit').is(':checked')) {
            $('#supplier_account_id').prop('disabled', false);
            $('#supplier_div').show();
        } else {
            $('#supplier_account_id').prop('disabled', true);
            $('#supplier_div').hide();
        }
    });
});