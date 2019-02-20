<?php
/**
 * Created by PhpStorm.
 * User: GRobert
 * Date: 2/19/2019
 * Time: 2:40 PM
 */

class NewPO
{
    private $po;
    function getNewPO()
    {
        $this->po = "";
        $file = 'Log.csv';
        $copy = 'Log-backup.csv';
        copy($file, $copy);
        $input = fopen('Log.csv', 'r');
        while (($data = fgetcsv($input)) !== FALSE) {

            if ($data[0] == "" && $data[1] != "" && $data[2] == "") {
                //echo $data[1];
                $this->po = $data[1];
                break;
            }
        }

        fclose($input);
        return $this->po;
    }

    function checkForAvailablePO()
    {
        $po = $this->po;
        $input = fopen('Log.csv', 'r');
        $output = fopen('temp.csv', 'w');

        while(($data = fgetcsv($input)) !== FALSE) {

            if ($data[1] === $po) {
                if($data[0] === ""){
                    return false;
                }
            }
            fputcsv( $output, $data);
        }

        fclose($output);
        fclose($input);
        unlink("../Log.csv");
        rename("../temp.csv", "../Log.csv");
        return true;
    }
}