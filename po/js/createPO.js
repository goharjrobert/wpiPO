$(document).ready(function(e){
    var i=1;
    var id = 0;
    var maxRows= 20;
    //var totalPrice = 0;
    ///Add rows
    $("#add").click(function(e){
        document.getElementById('poHeader').removeAttribute('style');
        var groups = "<p/><div><div class=\"form-row\" id=\"formRow\">\n" + "<div class=\"col-2\">\n" +
            "                    <input type=\"text\" class=\"form-control\" name=\"job[]\" id=\childjob\" placeholder=\"Job Number/Office\">\n" +
            "                    </div>\n" +
            "                    <div class=\"col-5\">\n" +
            "                        <input type=\"text\" name=\"item[]\" id=\"childitem\" class=\"form-control\" placeholder=\"Item\">\n" +
            "                    </div>\n" +
            "                    <div class=\"col-1\">\n" +
            "                        <input type=\"number\" value=\"1\" class=\"form-control qty"+id+"\" name=\"quantity[]\" max=\"50\" id=\"childquantity\" placeholder=\"Quantity\" oninput=\"calculate("+id+");\">\n" +
            "                    </div>\n" +
            "                    <div class=\"col-2\">\n" +
            "                        <input type=\"number\" value=\"0.00\" step=\"0.01\" data-number-to-fixed=\"2\" data-number-stepfactor=\"100\" class=\"form-control currency updatePrice thisPrice"+id+"\"  name=\"price[]\" id=\"childprice\" min=\"0\" oninput=\"calculate("+id+");\" \">\n" +
            "                    </div><div class=\"col-1\"><input  class=\"form-control text-right\" type=\"text\" id=\"totalEach"+id+"\" name=\"totalPriceArray[]\" disabled></div>" +
            "                    <div class=\"col-1 text-center\"><a href=\"#\" class=\"btn btn-danger\" name=\""+id+"\" id=\"remove\" value=\""+id+"\">X</a></div>" +
            "                    </div>";
        if(i <= maxRows) {
            $("#formGroup").append(groups);
            i++;
            id++;
        }
        else{

            alert("Exceeded item limit. 20");
        }

    });

    ///Remove
    $("#formGroup").on('click','#remove', function(e){
        $(this).parent('div').parent('div').remove();
        i--;

        removeFromTotal(this);
        if(i === 1){
            document.getElementById('poHeader').style.display = 'none';
        }
    });

    //Set date for today
    let today = new Date().toISOString().substr(0, 10);
    document.querySelector("#today").value = today;

});