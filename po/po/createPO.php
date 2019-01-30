<?php
/**
 * Created by PhpStorm.
 * User: GRobert
 * Date: 11/19/2018
 * Time: 8:38 AM
 */
include('../session/header.php');
include('../session/sessions.php');
include('readPOInfo.php');

if(isset($_POST['createPO']) || isset($_POST['reservePO'])){
    $initials = strtoupper($_POST['initials']);
    $_SESSION['initials'] = $initials;
    $po = $_SESSION['po'];


    //$list  = array($initials, $po, '','');
    reserve($initials, $po);
    if(isset($_POST['reservePO'])){
        //$_SESSION['reserved'] = 'reserved';
        header('Location: ../index.php');
    }

    unset($_POST['createPO']);
    unset($_POST['reservePO']);

}

else if(isset($_POST['lookupPO'])){
    $_SESSION['initials'] = strtoupper($_POST['initials']);
    $initials = $_SESSION['initials'];

    $reservedPOs = readReserved($initials);
    $_SESSION['reservedPOs'] = $reservedPOs;
    header('Location: ../index.php');
}

else if(isset($_POST['closeReserved'])){
    session_unset();
    header('Location: ../index.php');
}

else if(isset($_POST['chooseReserved'])){
    $checkedValue = $_POST['selectedReservedPO'];

    $_SESSION['po'] = $checkedValue;
    $_SESSION['createPO'] = 'set';
    //$_POST['createPO'] = 'set';
    unset($_SESSION['reservePO']);
    unset($_POST['chooseReserved']);
    header('Location: createPO.php');
}

else if(isset($_POST['closeShowReservedPO'])){
    session_unset();
    header('Location: ../index.php');
}

else if(isset($_POST['makePOchanges'])){
    $_SESSION['po'] = $_POST['poName'];
}

else if(!isset($_SESSION['createPO'])){
    session_unset();
    session_destroy();
    header('Location: ../index.php');
}

?>
<html>

<header>
    <title><?php echo $_SESSION['initials'].' - '. $_SESSION['po']; ?></title>
    <script type="text/javascript" src="../js/createPO.js"></script>
</header>

<body>
<div class="container">
    <h1>
        <?php echo $_SESSION['po']; ?>
    </h1>
    <form method="post" action="exec.php">
        <div class="form-row">
            <div class="col">
                <label>Date of Purchase</label>
                <input class="form-control" type="date" name="date" placeholder="Date of Purchase" id="today" required autofocus>
            </div>
            <div class="col">
                <label>Category</label>
                <input class="form-control" type="text" name="category" placeholder="Category" required autofocus>
            </div>

        </div>

        <div class="form-row">
            <div class="col">
                <label>Vendor</label>
                <input type="text" name="vendor" id="vendor" class="form-control" placeholder="Vendor" required autofocus>
            </div>
        </div>
        <br>
        <div class="form-row text-center" id="poHeader" style="display: none;">
            <div class="col-2"><b>Cost Center</b></div>
            <div class="col-5"><b>Items</b></div>
            <div class="col-1"><b>Quantity</b></div>
            <div class="col-2"><b>Price</b></div>
            <div class="col-1"><b>Total</b></div>
            <div class="col-1"><b>Remove</b></div>
        </div>
        <div class="form-group" id="formGroup">
<!--            This is where the rows populate as user adds items-->
        </div>

        <div class="form-row">
            <div class="col-11">
                <div class="text-right">
                    <b><p id="totalPrice">Total: $0.00</p></b>
                </div>
            </div>
        </div>
        
        <div class="form-row">
<!--            Info Modal-->
            <input name="poName" type="text" value="<?php echo $_SESSION['po'] ?>" style="display: none;">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#infoModalLong">
                <i class="fa fa-info" aria-hidden="true"></i> Info
            </button>
            <div class="col text-center">
                <a href="#" class="btn btn-primary" name="add" id="add"><i class="fas fa-plus"></i> Add Item</a>
            </div>
            <div class="col text-center">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createPOmodal">
                    Next
                </button>

                <!-- create PO Modal -->
                <div class="modal fade" id="createPOmodal" tabindex="-1" role="dialog" aria-labelledby="createPOmodal" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Moving on</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                You will not be able to make changes after this. Please ensure all fields are correct
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="save" class="btn btn-success">Next</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Modal -->
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
                                Every field in this section is required and you have the option of spreading out your PO over multiple
                                cost centers.
                                <p>
                                    Please make sure you spell everything correctly and add all items for this PO before proceeding.

                                </p>
                                <p>
                                    NOTE: Pressing 'Next' will not finalize this PO, it just means you can't come back and edit stuff you have
                                    already entered. You will have to start listing out your items all over (for the same PO number).
                                    This is a dynamically generated form so
                                    the browser does not remember what you put in the fields.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </form>
</div>

</body>
</html>


