$(function () {
    calculateTotalPurchaseBill();
    //purchase quantity event actions
    $('body').on("change keyup", "#purchase_quantity", function (evt) {
        //calculate total purchase bill
        calculateTotalPurchaseBill();
    });

    //purchase rate event actions
    $('body').on("change keyup", "#purchase_rate", function (evt) {
        //calculate total purchase bill
        calculateTotalPurchaseBill();
    });

    //purchase rate event actions
    $('body').on("change keyup", "#purchase_discount", function (evt) {
        //calculate total purchase bill
        calculateTotalPurchaseBill();
    });
});

//method for total bill calculation of purchase
function calculateTotalPurchaseBill() {
    var quantity    = ($('#purchase_quantity').val() > 0 ? $('#purchase_quantity').val() : 0 );
    var rate        = ($('#purchase_rate').val() > 0 ? $('#purchase_rate').val() : 0 );
    var discount    = ($('#purchase_discount').val() > 0 ? $('#purchase_discount').val() : 0 );
    var bill        = 0;
    var totalBill   = 0;
    
    bill  = quantity * rate;
    if(bill > 0) {
        $('#purchase_bill').val(bill);
        if((bill - discount) > 0) {
            totalBill = bill - discount;
            $('#purchase_total_bill').val(totalBill);
        } else {
            $('#purchase_discount').val(0);
            $('#purchase_total_bill').val(bill);
        }

    } else {
        $('#purchase_bill').val(0);
        $('#purchase_discount').val(0);
        $('#purchase_total_bill').val(0);
    }
}