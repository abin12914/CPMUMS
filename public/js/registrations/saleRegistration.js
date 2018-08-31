$(function () {
    siblingsHandling();
    var ajaxAccountDetailUrl    = '/ajax/account/details/';
    var ajaxOldSaleByBranchUrl  = '/ajax/last/sale'

    //customer details
    $('body').on("change", "#branch_id", function (evt) {
        var branchId = $(this).val();

        if(branchId) {
            $.ajax({
                url: ajaxOldSaleByBranchUrl,
                method: "get",
                data: {
                    paramName : 'branch_id',
                    paramValue : branchId,
                },
                success: function(result) {
                    
                    if(result && result.flag) {
                        var employeeId = result.sale.loadingEmployeeId;
                        console.log(employeeId);
                        $('#loading_employee_id').val(employeeId);
                        $('#loading_employee_id').trigger("change");
                    } else {
                        $('#loading_employee_id').val('');
                        $('#loading_employee_id').trigger("change");
                    }
                },
                error: function (err) {
                    $('#loading_employee_id').val('');
                    $('#loading_employee_id').trigger("change");
                }
            });
        } else {
            $('#loading_employee_id').val('');
            $('#loading_employee_id').trigger("change");
        }
    });

    $('body').on("click", "#sale_submit_button", function (evt) {
        evt.preventDefault();
        var taxFlag         = false;
        var cutomTitle      = 'Are you sure about the tax option?';
        var customButton    = 'Yes, Save it!';


        taxFlag = $('#tax_invoice_flag').is(':checked');

        if(taxFlag) {
            cutomTitle      = 'Are you sure to save this sale as Tax Invoice ?';
        } else {
            customButton    = 'Yes, Save without Tax Invoice';
        }
        swal({
          title: cutomTitle,
          type: 'warning',
          showCancelButton: true,
          focusCancel : true,
          confirmButtonColor: '#d33',
          confirmButtonText: customButton
        }).then((result) => {
          if (result.value) {
            $(this).attr('disabled', true);
            //submit delete form on confirmation
            $(this).parents('form:first').submit();
          }
        })
    });

    //customer details
    $('body').on("change", "#customer_account_id", function (evt) {
        var customerAccountId   = $(this).val();

        if(customerAccountId && customerAccountId != -1) {
            var selectedOption = $(this).find(':selected');
            
            $.ajax({
                url: ajaxAccountDetailUrl + customerAccountId,
                method: "get",
                data: {},
                success: function(result) {
                    
                    if(result && result.flag) {
                        var account = result.account;
                        if(account.type == 3) {
                            $('#customer_name').val(account.name);
                            $('#customer_phone').val(account.phone);
                            $('#customer_address').val(account.address);
                            $('#customer_gstin').val(account.gstin);
                        }
                    } else {
                        $('#customer_name').val('');
                        $('#customer_phone').val('');
                        $('#customer_address').val('');
                        $('#customer_gstin').val('');
                    }
                },
                error: function (err) {
                    $('#customer_name').val('');
                    $('#customer_phone').val('');
                    $('#customer_address').val('');
                    $('#customer_gstin').val('');
                }
            });
        } else {
            $('#customer_name').val('');
            $('#customer_phone').val('');
            $('#customer_address').val('');
            $('#customer_gstin').val('');
        }
    });

    //
    $('body').on("keyup", "#customer_phone", function (evt) {
        var input       = $(this).val();
        var accountId   = $('#customer_account_id').val();

        if(input.length > 9 && accountId != -1) {
            accountId = $('#customer_account_id').find(`[data-phone='${input}']`).val();
            if(accountId && accountId > 0) {
                if(confirm("Found an account related with the entered phone number. Do you want to change the 'Sale To' field?")) {
                    $('#customer_account_id').val(accountId);
                    $('#customer_account_id').trigger('change');
                    $('#customer_address').focus();
                }
            }
        }
    });

    //product change event
    $('body').on("change", ".products_combo", function (evt) {
        var fieldValue  = $(this).val();
        var rowId       = $(this).data('index-no');

        if(fieldValue && fieldValue != '' && fieldValue != 'undefined') {
            var rate = $(this).find(':selected').data('rate');

            //enabling quantity & rate in same column
            $(this).closest('tr').find('.sale_quantity').attr('disabled', false);
            $(this).closest('tr').find('.sale_rate').attr('disabled', false);
            
            //setting rate for selected product
            $('#sale_rate_'+rowId).val(rate);

            //enabling next combo box
            $('#product__row_'+(rowId+1)).find('.products_combo').attr('disabled', false);
        } else {
            //disabling quantity & rate in same column
            $(this).closest('tr').find('.sale_quantity').attr('disabled', true);
            $(this).closest('tr').find('.sale_rate').attr('disabled', true);
            
            //setting empty values for deselected product
            $('#sale_quantity_'+rowId).val('');
            $('#sale_rate_'+rowId).val('');

            $('#product__row_'+(rowId+1)).find('.products_combo').val('');
            //disabling next combo box
            $('#product__row_'+(rowId+1)).find('.products_combo').trigger('change');
            $('#product__row_'+(rowId+1)).find('.products_combo').attr('disabled', true);
        }

        //disabiling same value selection in 2 product combo boxes
        siblingsHandling();
        initializeSelect2();
        //calculate total sale bill
        calculateTotalPurchaseBill();

    });

    //purchase quantity event actions
    $('body').on("change keyup", ".sale_quantity", function (evt) {
        //calculate total sale bill
        calculateTotalPurchaseBill();
    });

    //purchase rate event actions
    $('body').on("change keyup", ".sale_rate", function (evt) {
        //calculate total sale bill
        calculateTotalPurchaseBill();
    });

    //purchase rate event actions
    $('body').on("change keyup", "#discount", function (evt) {
        //calculate total sale bill
        calculateTotalPurchaseBill();
    });
});

//method for total bill calculation of purchase
function calculateTotalPurchaseBill() {
    var bill        = 0;
    var totalBill   = 0;
    var discount    = ($('#discount').val() > 0 ? $('#discount').val() : 0 );

    $('.products_combo').each(function(index) {
        var productId   = $(this).val();
        var rowId       = $(this).data('index-no');
        var quantity    = $('#sale_quantity_'+rowId).val();
        var rate        = $('#sale_rate_'+rowId).val();

        if(productId && productId != '' && quantity && quantity != '' && rate && rate != '') {
            $('#sub_bill_'+rowId).val((quantity * rate));
            bill = bill + (quantity * rate);
        } else {
            $('#sub_bill_'+rowId).val('');
        }
    });
    
    if(bill > 0) {
        $('#total_amount').val(bill);
        if((bill - discount) > 0) {
            totalBill = bill - discount;
            $('#total_bill').val(totalBill);
        } else {
            $('#discount').val(0);
            $('#total_bill').val(bill);
        }

    } else {
        $('#total_amount').val(0);
        $('#discount').val(0);
        $('#total_bill').val(0);
    }
}

function siblingsHandling() {
    var selectedOptions = [];

    //getting all selected option values with unique select element index number
    $('.products_combo').each(function() {
        fieldValue = $(this).val();
        indexNo    = $(this).data('index-no');

        if(fieldValue && fieldValue != '') {
            //selectedOptions hold selected option values using select's data-index-no as index
            selectedOptions[parseInt(indexNo)] = parseInt(fieldValue);
        }
    });

    //traversing each selects
    $('.products_combo').each(function() {
        //traversing through every select elements
        $(this).children('option').each(function() {
            optionValue = parseInt($(this).val());
            indexNo     = $(this).parent().data('index-no');
            
            //if current option is in the selectedOptions
            if(selectedOptions.includes(optionValue)) {
                //if index number of the current select and index of the selectedOptions match leave it from disabling
                if(indexNo == selectedOptions.indexOf(optionValue)) {
                    $(this).attr('disabled', false);
                    return;
                }
                //else disable the option
                $(this).attr('disabled', true);
            } else {
                //else enable
                $(this).attr('disabled', false);
            }
        });
    });
}