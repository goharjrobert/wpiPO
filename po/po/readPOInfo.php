<?php
/**
 * Created by PhpStorm.
 * User: GRobert
 * Date: 1/28/2019
 * Time: 8:51 AM
 */
//include('../session/sessions.php');
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

    while(($data = fgetcsv($input)) !== FALSE) {

        if ($data[1] === $po) {
            if($data[0] === ""){
                $data[0] = $initials;
            } else {
                $_SESSION['error'] = 'set';
                //header('Location: ../index.php');
            }
        }
        fputcsv( $output, $data);
    }

    fclose($output);
    fclose($input);
    unlink("../Log.csv");
    rename("../temp.csv", "../Log.csv");

}
?>
