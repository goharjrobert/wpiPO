<?php
/**
 * Created by PhpStorm.
 * User: GRobert
 * Date: 11/16/2018
 * Time: 12:37 PM
 */

require "../fpdf181/fpdf.php";
include('../session/sessions.php');


class myPDF extends FPDF{

    function header(){

        $this->Image('../img/logo-large.png', 70, 6);

        $this->SetFont('Arial', 'B', 14);
        $this->Ln(35);
        $this->Cell(186,5,'Purchase Order '.$_SESSION['po'],0,1,'C');
        $this->Cell(186,5,'Date of Purchase '.$_SESSION['dateOfPO'],0,1,'C');
        $this->Cell(186, 5, ''.$_SESSION['vendor'], 0, 1, 'C');
    }

    function footer(){
        $this->SetY(-15);
        $this->SetFont('Arial', '', 8);
        $this->Cell(186, 5, $_SESSION['initials']." - ".$_SESSION['po'],0, 1, 'R' );
        $this->Cell(186, 5, 'PO Created: '.$_SESSION['dateOfRecord'],0, 1, 'R' );


    }

    function createTable($i){
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(186,10,''.$_SESSION['costCenters'][$i],0,0);
        $this->Ln();
        $this->SetFont('Times', 'B', 12);

        $this->Cell(124, 6, 'Item', 1, 0, 'C');
        $this->Cell(31, 6, 'Quantity', 1, 0, 'C');
        $this->Cell(31, 6, 'Cost', 1, 0, 'C');
        $this->Ln();
    }

    function insertRows($i, $j){
        $this->SetFont('Times', '', 10);
        $this->Cell(124, 6, ''.$_SESSION['items'][$i][$j], 1, 0, 'C');
        $this->Cell(31, 6, ''.$_SESSION['quantity'][$i][$j], 1, 0, 'C');
        $this->Cell(31, 6, ''.$_SESSION['prices'][$i][$j], 1, 0, 'C');
        $this->Ln(6);
    }

    function totalCost(){
        $this->Ln(5);
        $this->SetFont('Times', 'B', 10);
        $this->Cell(155, 8, 'Total Cost', 0, 0, 'R');
        $this->Cell(31, 8, ''.$_SESSION['totalCost'], 0, 0, 'C');

    }

}

if(isset($_POST['createPDF'])){
    //$list  = array($_SESSION['initials'], $_SESSION['po'], $_SESSION['vendorString'], $_SESSION['jobString'], $_SESSION['dateOfPO'], $_SESSION['itemString']);

    $input = fopen('../Log.csv', 'r');
    $output = fopen('../temp.csv', 'w');

    while(($data = fgetcsv($input)) !== FALSE){

        if($data[1] == $_SESSION['po'] && $data[0] == $_SESSION['initials']){

            $data[2] = $_SESSION['vendor'];
            $data[3] = $_SESSION['jobString'];
            $data[4] = $_SESSION['dateOfPO'];
            $data[5] = $_SESSION['category'];
            $data[6] = $_SESSION['totalCost'];
            $data[7] = $_SESSION['itemString'];
        }

        fputcsv( $output, $data);
    }

    fclose($output);
    fclose($input);
    unlink("../Log.csv");
    rename("../temp.csv", "../Log.csv");
}

$pdf = new myPDF();
$pdf->AliasNbPages();
$pdf->AddPage('P', 'A4', 0);

for($i =0; $i < sizeof($_SESSION['costCenters']); $i++){
    $pdf->createTable($i);
    for($j=0; $j<sizeof($_SESSION['items'][$i]); $j++){
        $pdf->insertRows($i, $j);
    }
}
$pdf->totalCost();
$name = $_SESSION['initials']. '-'. $_SESSION['po'];

unset($_SESSION['reservedPOs']);

//Check uploads folder and delete all files in there
$files = glob('uploadedFiles/*'); //get all file names
foreach($files as $file){
    if(is_file($file))
        unlink($file); //delete file
}

$dir = 'uploadedFiles/'.$_SESSION['po'].'.pdf';
$pdf->Output($dir, 'F');
copy($dir, '../savedPOs/'.$_SESSION['po'].'.pdf');
header('Location: index.php');

?>


