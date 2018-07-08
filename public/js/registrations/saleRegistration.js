$(function () {
    siblingsHandling();
    //purchase quantity event actions
    $('body').on("change", ".sale_type", function (evt) {
        if($(this).val() == 1) {
            //account sale
            $('#customer_with_account_div').show();
            $('#customer_with_out_account_div').hide();
        } else {

            //non account sale
            $('#customer_with_out_account_div').show();
            $('#customer_with_account_div').hide();
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