<?php
/**
 * Created by PhpStorm.
 * User: GRobert
 * Date: 12/5/2018
 * Time: 11:23 AM
 */
include('../session/sessions.php');

if(isset($_SESSION['po'])){
    if(isset($_POST['uploadFile']) && file_exists('uploadedFiles/'.$_SESSION['po'].'.pdf') || isset($_POST['uploadFile']) && isset($_SESSION['addDocs'])){
    $files = [];
    $allowed= array('application/pdf', 'PDF', 'PDF Document', 'Foxit Reader PDF Document');
    if(file_exists('uploadedFiles/'.$_SESSION['po'].'.pdf')) {
        array_push($files, 'uploadedFiles/' . $_SESSION['po'] . '.pdf');
    }
    if(isset($_FILES['userFile'])) {

        $nameArray = $_FILES['userFile']['name'];
        $fileTempName = $_FILES['userFile']['tmp_name'];
        $typeArray = $_FILES['userFile']['type'];

        for($i=0;$i<sizeof($nameArray);$i++){

            if(in_array($typeArray[$i], $allowed)){
                //echo '<script>console.log("Hello")</script>';
                $destination = "uploadedFiles/".$i.$nameArray[$i];
                move_uploaded_file($fileTempName[$i], $destination);
                array_push($files, $destination);

            }
            else{
                $_SESSION['error'] = "Some of your files were not the correct format";
            }
        }
    }
    if(sizeof($files) > 0){
        $_SESSION['filesArray'] = $files;
        //print_r($files);
        header('Location: merge.php');
    }
    else{
        $_SESSION['error'] = "No files uploaded";
    }
}

?>

<html>

    <header>
        <title><?php echo $_SESSION['po']; ?></title>
        <?php require('../style/header.php'); ?>
        <link rel="stylesheet" href="../style/index.css" type="text/css">
        <script src="../js/createPDF.js" type="text/javascript"></script>
    </header>

    <body>

        <div class="uploadFilesSection">
            <div class="container text-center">
                <h1>Almost Done!</h1>
                <h2><?php echo $_SESSION['po']; ?></h2>
            </div>
            <div class="container">
                <div class="row">

                    <form action="index.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group" id="formGroup">
                            <label>Upload Proof of purchase</label><br>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <button type="button" name="addFiles" id="add" class="btn btn-primary">Add Files</button>
                            </div>
                            <div class="col">
                                <button type="submit" name="uploadFile" class="btn btn-success">Finish</button>
                            </div>
                            <div class="col">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#infoModalLong">
                                    <i class="fa fa-info" aria-hidden="true"></i> Info
                                </button>


                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <object class="showPO" data="uploadedFiles/<?php echo $_SESSION['po'].'.pdf'?>" type="application/pdf">
                        <embed src="uploadedFiles/<?php echo $_SESSION['po'].'.pdf'?>" type="application/pdf" />
                    </object>
                </div>



            </div>

        </div>


    </body>

</html>

<!-- Modal -->
<div class="modal fade" id="infoModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                If your vendor has provided proof of purchase, please upload those PDF's here.
                <p>Currently, only files of PDF formats are accepted</p>
                <p> These files will be appended to your PO so you can save one document and it will contain all the information Angela needs</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div><?php }else{
    header('Location: ../index.php');
}