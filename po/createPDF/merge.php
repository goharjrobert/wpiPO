<?php
include('../session/sessions.php');
use setasign\Fpdi;
use setasign\tcpdf;


if(isset($_SESSION['filesArray']) && $_SESSION['po']) {

    require_once 'vendor/autoload.php';
    require_once 'vendor/setasign/tcpdf/tcpdf.php';
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    set_time_limit(2);
    date_default_timezone_set('UTC');
    $start = microtime(true);

    //$pdf = new Fpdi\Fpdi();
    $pdf = new Fpdi\TcpdfFpdi();

    if ($pdf instanceof \TCPDF) {
        //$pdf->SetProtection(array(), null, null, 0, null);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
    }

    $files = $_SESSION['filesArray'];

    foreach ($files as $file) {
        $pageCount = $pdf->setSourceFile($file);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $pdf->AddPage();
            $pageId = $pdf->importPage($pageNo, '/MediaBox');
            //$pageId = $pdf->importPage($pageNo, Fpdi\PdfReader\PageBoundaries::ART_BOX);
            $s = $pdf->useTemplate($pageId, 10, 10, 200);
        }

    }
    $file = $_SESSION['po'] . '.pdf';
    //$saveFile = "C:\wamp64\www\savedPOs\\".$_SESSION['po'] . '.pdf';
    $saveFile = "C:\Bitnami\wampstack-7.1.21-0\apache2\htdocs\po\savedPOs\\".$_SESSION['po'] . '.pdf';
    //$pdf->Output('I', $file);
    $pdf->Output($saveFile, 'F');
    $pdf->Output($file, 'I');
    session_unset();
    //$pdfFiles = glob('uploadedFiles/*'); //get all file names

}
else{
    header('Location: ../index.php');
}
?>
