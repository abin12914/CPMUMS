var alertType       = '';
var alertMessage    = '';

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if(alertType && alertMessage) {
        if (window.performance && window.performance.navigation.type == window.performance.navigation.TYPE_BACK_FORWARD) {
            //'Got here using the browser "Back" or "Forward" button
            $('#show_last_message').hide();
            return false;
        }
        showAlert();
    }

    //Initialize Select2 Element for account type select box
    initializeSelect2();

    //datepicker for general datepickers
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    //Date picker for registrations
    $('.datepicker_reg').datepicker({
        todayHighlight: true,
        endDate: '+0d',
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    if(typeof defaultDate !== 'undefined') {
        //setting user setting date as selected
        $('.datepicker_reg').datepicker('setDate', defaultDate);

    } else {
        //setting current date as selected
        $('.datepicker_reg').datepicker('setDate', 'now');
    }

    //prevent user from entering data
    $('body').on("click", "#show_last_message", function (evt) {
        //show sweet alert on clicking the message icon
        if(alertType && alertMessage) {
            showAlert();
        }
    });

    //default value setting in account registering
    $('body').on("change", "#financial_status", function () {
        financialStatus = this.value;
        if(financialStatus == 0) {
            $('#opening_balance').val('0');
            $('#opening_balance').prop("readonly",true);
        } else {
            $('#opening_balance').val('');
            $('#opening_balance').prop("readonly",false);
        }
    });

    //prevent user from entering data
    $('body').on("keydown", ".prevent-edit", function (evt) {
        return false;
    });
    
    // for checking if the pressed key is a number
    $('body').on("keypress", ".number_only", function (evt) {
        var fieldValue  = $(this).val();
        var elementId   = $(this).attr("id");
        var charCode = (evt.which) ? evt.which : event.keyCode;

        if(fieldValue.length == 1 && fieldValue == 0) {
            if(charCode != 46) {
                $(this).val('');
            }
        }

        if(elementId == 'phone') {
            if(fieldValue.length == 0 && charCode == 43) {
                return true;
            }
            if(fieldValue.length >= 13) {
                evt.preventDefault();
                $("#phone").data("title", "Phone number must be between 10 and 13 digits!").tooltip("show");
                return false;
            }
        }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            evt.preventDefault();
            $(this).data("title", "Only numbers are allowed!").tooltip("show");
            return false;
        }
        
        $(this).data("title", "");
        return true;
    });

    // for checking if the pressed key is a number or decimal
    $('body').on("keypress", ".decimal_number_only", function (evt) {
        var original = $(this).val();
        // attaching 1 to the end for number like 1.0
        var fieldValue = $(this).val() + '1';
        var charCode = (evt.which) ? evt.which : event.keyCode;

        if(original.length == 1 && original == 0) {
            if(charCode != 46) {
                $(this).val('');
            }
        }
        
        if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57))) {
            evt.preventDefault();
            $(this).data("title", "Only numbers are allowed!").tooltip("show");
            return false;
        }
        if(charCode == 46 && (fieldValue % 1 != 0)) {
            evt.preventDefault();
            $(this).data("title", "Only numbers and decimal point are allowed!").tooltip("show");
            return false;
        }

        $(this).data("title", "");
        return true;
    });

    // for checking if the pressed key is a alphabet
    $('body').on("keypress", ".alpha_only", function (evt) {
        var fieldValue = $(this).val();
        var charCode = (evt.which) ? evt.which : event.keyCode;

        if ((!((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122))) && charCode != 32) {
            evt.preventDefault();
            $(this).data("title", "Only alphabets are allowed!").tooltip("show");
            return false;
        }
        $(this).data("title", "");
        return true;
    });

    // for disabling submit button to prevent multiple submition
    $('body').on("click", ".submit-button", function () {
        $('.submit-button').prop('disabled', true);
        $(this).parents('form:first').submit();
    });

    // for confirming delete
    $('body').on("click", ".update_button", function () {
        swal({
          title: 'Are you sure to update the record?',
          type: 'warning',
          showCancelButton: true,
          focusCancel : true,
          confirmButtonColor: '#d33',
          confirmButtonText: 'Yes, update it!'
        }).then((result) => {
          if (result.value) {
            $(this).attr('disabled', true);
            //submit delete form on confirmation
            $(this).parents('form:first').submit();
          }
        })
    });

    // for confirming delete
    $('body').on("click", ".delete_button", function () {
        swal({
            title: 'Are you sure to delete?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            focusCancel : true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                //submit delete form on confirmation
                $(this).parents('form:first').submit();
            }
        })
    });
});

//method for initializing select2
function initializeSelect2() {
    $(".select2").select2({
        minimumResultsForSearch: 5,
    });
}

//display sweet alert
function showAlert() {
    if(alertType == 'error') {
        messageTitle = "Failed";
    } else {
        messageTitle =  alertType;
    }

    swal({
        title: messageTitle,
        type: alertType,
        text: alertMessage,
        timer: 5000,
        showConfirmButton : false,
    });
}