<?php
/**
 * Created by PhpStorm.
 * User: GRobert
 * Date: 9/30/2018
 * Time: 10:37 AM
 */
    //require('assets/index.php');
    include('session/sessions.php');
    require('style/header.php');
    require('po/NewPO.php');

    $newPO = new NewPO();
    //echo $_SESSION['po'];
    if(!isset($_SESSION['po'])) {
        //echo "Hello";
        $_SESSION['po'] = $newPO->getNewPO();
    }
    elseif(isset($_SESSION['poNotAvailable'])){
        echo "<div class='container'><div class='alert-danger'>" . $_SESSION['poNotAvailable'] . "</div></div>";
        unset($_SESSION['poNotAvailable']);
        $_SESSION['po'] = $newPO->getNewPO();
    }

?>

<html>

    <title>Create PO</title>
    <head>
        <meta http-equiv="refresh" content="30"> <!-- Refresh every 30 seconds -->
    </head>
    <body class="text-center">
        <button class="btn btn-outline-secondary allPOButton" type="button" onclick="window.location.href='savedPOs/allPOs.php'">View All PO's</button>
        <div class="container">
            <div class="row">

                <form class="form-signin" method="post" action="po/createPO.php">
                    <h1 class="">Available PO: <?php echo $_SESSION['po']; ?></h1>
                    <label><b>Initials</b></label>
                    <input type="text" id="initials" class="form-control text-center" placeholder="Initials" name="initials" required autofocus>
                    <hr>
                    <button class="btn btn-lg btn-primary btn-block" type="submit" name="createPO">Create PO Now</button>
                    <button class="btn btn-lg btn-danger btn-block" type="submit" name="reservePO">Reserve PO Number</button>
                    <button class="btn btn-lg btn-secondary btn-block" type="submit" name="lookupPO">Look Up My PO's</button>
                    <br>
                    <br>
                </form>

            </div>

        </div>
        <?php

            if(isset($_SESSION['reservedPOs'])): ?>
                <div class="container">
                    <div class="row">
                        <div class="card reservedPOcard" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">PO's for <?php echo $_SESSION['initials'] ?></h5>
                                <?php if (isset($_SESSION['reservedPOs'])): ?>
                                    <form method="post" action="po/createPO.php">
                                        <?php if (sizeof($_SESSION['reservedPOs'])> 0): ?>
                                            <h6 class="card-subtitle mb-2 text-muted">Pick up where you left off</h6>


                                                <?php for($i=0; $i<sizeof($_SESSION['reservedPOs']); $i++){

                                                    ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="selectedReservedPO" id="exampleRadios1" value="<?php echo $_SESSION['reservedPOs'][$i]; ?>" checked>
                                                        <label class="form-check-label" for="exampleRadios1">
                                                            <?php echo $_SESSION['reservedPOs'][$i]; ?>
                                                        </label>
                                                    </div>
                                                    <?php

                                                }
                                                ?>
                                            <br>
                                            <br>
                                            <button type="submit" name="chooseReserved" class="btn btn-outline-success">Choose</button>
                                        <?php else: ?>
                                            <h6 class="card-subtitle mb-2 text-muted">Looks like you don't have any PO's reserved currently</h6>
                                            <h6 class="card-subtitle mb-2 text-muted">Reserve this PO now or just create a new PO to get started</h6>
                                            <br>
                                            <br>
                                        <?php endif; ?>
                                <?php else: ?>
                                    <h6>Error</h6>
                                <?php endif; ?>

                                    <button type="submit" name="closeReserved" class="btn btn-outline-danger">Close</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif(isset($_SESSION['reserved'])): ?>
                <div class="container">
                    <div class="row">
                        <div class="card showReservedPOCard">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $_SESSION['initials'] ?> has reserved PO# <?php echo $_SESSION['reserved'] ?></h5>
                                <form action="po/createPO.php" method="post">
                                    <button type="submit" name="closeShowReservedPO" class="btn btn-outline-danger">Close</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            <?php endif;
                unset($_SESSION['reserved']);
            ?>




    </body>
</html>






