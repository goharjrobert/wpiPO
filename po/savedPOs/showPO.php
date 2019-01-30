<?php
/**
 * Created by PhpStorm.
 * User: GRobert
 * Date: 12/6/2018
 * Time: 2:05 PM
 */

    if(isset($_POST['viewPO'])){
        $nameOfPO = $_POST['nameOfPO'];
        if(isset($nameOfPO) && file_exists($nameOfPO.'.pdf')) { ?>
            <html>
            <title>
                <?php echo $nameOfPO ?>
            </title>

            <div class="row">
                <object class="showPO" data="<?php echo $nameOfPO ?>.pdf" type="application/pdf" style="width: 100%; height: 100%;">
                    <embed src="<?php echo $nameOfPO ?>.pdf" type="application/pdf"/>
                </object>
            </div>
            </html>
            <?php

        }
        else{
            $_SESSION['error'] = "This PO doesn't have a proof of purchase";
            header('Location: allPOs.php');
        }


    }

    else if(isset($_POST['removePO'])){
        $nameOfPO = $_POST['nameOfPO'];
        $initials = $_POST['initials'];
        $input = fopen('../Log.csv', 'r');
        $output = fopen('../temp.csv', 'w');

        while (($data = fgetcsv($input)) !== FALSE) {

            if ($data[1] === $nameOfPO && $data[0] === $initials) {
                $data[1] = substr($nameOfPO, 0, 6);
                $data[2] = "";
                $data[3] = "";
                $data[4] = "";
                $data[5] = "";
                $data[6] = "";
            }
            fputcsv( $output, $data);
        }
        fclose($output);
        fclose($input);
        unlink("../Log.csv");
        rename("../temp.csv", "../Log.csv");

        //Remove the PO pdf
        unlink($nameOfPO.".pdf");
        session_unset();
        header('Location: allPOs.php');


    }

    else if(isset($_POST['returnPO'])) {
        $nameOfPO = $_POST['nameOfPO'];
        $initials = $_POST['initials'];
        $input = fopen('../Log.csv', 'r');
        $output = fopen('../temp.csv', 'w');

        while (($data = fgetcsv($input)) !== FALSE) {

            if ($data[1] === $nameOfPO && $data[0] === $initials) {
                $data[1] = $data[1].'(RETURNED)';
            }
            fputcsv( $output, $data);
        }
        fclose($output);
        fclose($input);
        unlink("../Log.csv");
        rename("../temp.csv", "../Log.csv");
        session_unset();
        header('Location: allPOs.php');

    }

    else if(isset($_POST['addDocs'])){
        echo "<script>alert('Testing')</script>";

        include('../session/sessions.php');
        $nameOfPO= $_POST['nameOfPO'];
        $_SESSION['po'] = $nameOfPO;
        //echo $_SESSION['po'];
        $_SESSION['addDocs'] = 'set';
        $files = glob('../createPDF/uploadedFiles/*'); //get all file names
        foreach($files as $file){
            if(is_file($file))
                unlink($file); //delete file
        }
        if(file_exists($_SESSION['po'].'.pdf')) {
            copy($_SESSION['po'] . '.pdf', '../createPDF/uploadedFiles/' . $_SESSION['po'] . '.pdf');
        }
        header('Location: ../createPDF/index.php');
        //echo "<script>alert('Testing')</script>";
    }

	?>