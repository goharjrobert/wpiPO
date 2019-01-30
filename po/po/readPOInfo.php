<?php
/**
 * Created by PhpStorm.
 * User: GRobert
 * Date: 1/28/2019
 * Time: 8:51 AM
 */
?>
<?php
function readReserved($initials){
    $input = fopen('../Log.csv', 'r');
    $reservedPOs = [];
    while(($data = fgetcsv($input)) !== FALSE){
        if(($data[0] === $initials && $data[2] === "" && $data[3] === "") || $data[0] === $initials && $data[6] === ""){

            array_push($reservedPOs, $data[1]);
        }

    }
    fclose($input);
    return $reservedPOs;
}

function reserve($initials, $po){
    $input = fopen('../Log.csv', 'r');
    $output = fopen('../temp.csv', 'w');

    while(($data = fgetcsv($input)) !== FALSE){

        if($data[1] === $po && $data[0] === "" ){

            $data[0] = $initials;
//            $data[2] = "";
//            $data[3] = "";
//            $data[4] = "";
//            $data[5] = "";
//            $data[6] = "";
        }

        //else if($data[1] === $po && $data[])



        else if($data[1] === $po && $data[0] !== ""){
            $_SESSION['error'] = "PO ".$_SESSION['po']." no longer available";
            header('Location: index.php');
        }
        fputcsv( $output, $data);
    }

    fclose($output);
    fclose($input);
    unlink("../Log.csv");
    rename("../temp.csv", "../Log.csv");

}
?>
