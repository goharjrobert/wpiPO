$(document).ready(function(e){
    var i=1;
    var maxRows= 10;
    var totalPrice = 0;
    ///Add rows
    $("#add").click(function(e){
        var groups = "<p /><di" +
            "v><div class=\"form-row\" id=\"formRow\">\n" + "<input name=\"userFile[]\" type=\"file\" required autofocus />" +
            " <a href=\"#\" class=\"btn btn-danger\" name=\"remove\" id=\"remove\">X</a></div>";
        if(i <= maxRows) {
            $("#formGroup").append(groups);
        }
        else{

            alert("Exceeded item limit. 10");
        }
    });

    ///Remove
    $("#formGroup").on('click','#remove', function(e){
        $(this).parent('div').remove();

        i--;

    });

    //Populate values from the first Row

    $("#formGroup").on('dblclick', '#childjob', function(e){
        $(this).val( $('#job').val() );
    });
});