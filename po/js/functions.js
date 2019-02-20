$(document).ready(function(){
    $("#myInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

$("#myiFrame").contents().find("a").each(function() {
    $(this).attr("target", "_blank");
});

var totalPriceArray = [];
function calculate(id){

    var thisQty = 'qty'+id;
    var thisPrice = 'thisPrice'+id;
    var totalEach = 'totalEach'+id;
    var myQty = document.getElementsByClassName(thisQty)[0].value;
    var eachPrice = document.getElementsByClassName(thisPrice)[0].value;
    //var result = document.getElementById(totalEach);
    var myResult = parseFloat(myQty).toFixed(2) * parseFloat(eachPrice).toFixed(2);
    myResult = parseFloat(myResult).toFixed(2);
    //console.log("Qty: "+myQty+" price: "+eachPrice+" total: "+myResult);
    document.getElementById(totalEach).value = myResult;
    console.log("myResult: " + myResult);
    totalPriceArray[id] = myResult;
    for(var k = 0; k < totalPriceArray.length; k++){
        console.log("At " + k + " is " + totalPriceArray[k]);
    }
    calculateTotal();

}

function removeFromTotal(i){

    var id = parseInt(i.name);
    console.log(totalPriceArray[id]);
    totalPriceArray[id] = 0.00;
    calculateTotal();
}

function calculateTotal(){
    var totalPrice = 0.00;


    for(var j = 0; j < totalPriceArray.length; j++){
        console.log(totalPriceArray[j]);
         var val = parseFloat(totalPriceArray[j]).toFixed(2);

         totalPrice = eval(totalPrice) + eval(val);

    }
    totalPrice = parseFloat(totalPrice).toFixed(2);
    console.log("total price: " + totalPrice);
    document.getElementById("totalPrice").innerHTML = "Total: $"+totalPrice;
}

