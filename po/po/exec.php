<?php
/**
 * Created by PhpStorm.
 * User: GRobert
 * Date: 10/22/2018
 * Time: 9:25 AM
 */
include('../session/sessions.php');

?>

<?php if(isset($_SESSION['initials'])){ ?>
<html>
    <header>

    <?php include('../session/header.php');
        $_SESSION['po'] = $_POST['poName'];
    ?>

        <title><?php $po = $_SESSION['po']; echo $po; ?></title>
    </header>

    <body>
        <div class="container">
            <div class="row">
                <img class="img" src="../img/logo-large.png" width="340" />
            </div>

            <div>
                <br>
                <h3 class="text-center">Purchase Order: <?php echo $po;?></h3>
                <br>
                <h4 class="text-center"><?php
                    if(isset($_SESSION['vendor']))
                    {echo $_SESSION['vendor'];}
                    else{
                        echo $_POST['vendor'];
                    }
                    ?>
                </h4>
            </div>

            <?php
            if(isset($_POST['save'])) {
                $dateOfRecord = date("F j, Y, g:i a");

                if(isset($_SESSION['date']))
                {$dateOfPO = $_SESSION['date'];}
                else{
                    $dateOfPO = $_POST['date'];
                }
                $usDateOfPO = $dateOfPO;
                $dateOfPO = date("m/d/Y",strtotime($dateOfPO));
                $_SESSION['category'] = $_POST['category'];
                $_SESSION['vendor'] = $_POST['vendor'];
                $costCenter = $_POST['job'];
                $price = $_POST['price'];
                $items = $_POST['item'];
                $vendor = $_SESSION['vendor'];
                $quantity = $_POST['quantity'];
                $itemsString = "";

                $job = "";
                $totalPrice = 0;
                $costCenterSub = [];
                $pricePerItem = [];

                $sessionCostCenter = [];
                $sessionItems = [];
                $sessionQuantity = [];
                $sessionPrices = [];


                if(isset($_SESSION['po'])){
                    $po = $_SESSION['po'];
                    $initials = $_SESSION['initials'];
                }

                else{
                    $initials = $_POST['initials'];

                }

                for ($i = 0; $i < sizeof($price); $i++) {
                    $totalPrice += (float)$price[$i]*$quantity[$i];
                }
                $totalPrice = '$'.$totalPrice;
                //echo "Total Price: " . $totalPrice . "<br>";

                for ($i = 0; $i < sizeof($costCenter); $i++) {
                    $key = array_search($costCenter[$i], $costCenter);
                    //echo "Key of " . $costCenter[0][$i] . ": " . $key . "<br>";

                    //if costCenter[0][i] is not in costCenterSub then add it to that list
                    if(!(in_array(ucfirst(strtolower($costCenter[$i])), $costCenterSub))){
                        array_push($costCenterSub, ucfirst(strtolower($costCenter[$i])));
                    }
                }

                //for every item in costCenterSub, create a grouping of items
                //purchased under that costCenterSub

                for($i=0;$i < sizeof($costCenterSub);$i++){
                    $costCenterName = ucfirst(strtolower($costCenterSub[$i]));
                    array_push($sessionCostCenter, ucfirst(strtolower($costCenterName)));
                    ?>

                    <?php
                    $job .= ", $costCenterName";
                    ?>

                        <div class="row">
                            <div class="col">
                            <?php
                                echo "<h4>$costCenterName</h4><br>";
                            ?>
                            </div>
                        </div>

                        <table class="table table-hover">
                            <tr>
                                <th>
                                    Item
                                </th>
                                <th>
                                    Quantity
                                </th>
                                <th>
                                    Price
                                </th>
                            </tr>
                                <tbody>

                                    <?php
                                    $subItems=[];

                                    $subQuantities=[];
                                    $subPrices=[];
                                    for($j=0;$j < sizeof($costCenter); $j++){

                                        ?>
                                        <tr>
                                        <?php
                                        if(ucfirst(strtolower($costCenterName)) === ucfirst(strtolower($costCenter[$j]))){
                                            ?>

                                            <td>
                                                <?php
                                                echo $items[$j];
                                                array_push($subItems, $items[$j]);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo $quantity[$j];
                                                array_push($subQuantities, $quantity[$j]);
                                                ?>

                                            </td>
                                            <td>
                                                <?php
                                                $totalPriceForItem='$'.(float)$price[$j]*(float)$quantity[$j];
                                                echo $totalPriceForItem;
                                                array_push($subPrices, $totalPriceForItem);
                                                ?>
                                            </td>
                                            </tr>
                                            <?php
                                            $itemsString .= ", $items[$j]";
                                        }

                                    }
                                    array_push($sessionItems, $subItems);
                                    array_push($sessionQuantity, $subQuantities);
                //                    array_push($sessionVendors, $subVendor);
                                    array_push($sessionPrices, $subPrices);

                                    ?>
                                </tbody>
                        </table>

                    <?php
                }
                ?>
                <br>
                <hr>
                <h5 class="col-3 offset-6 text-right">Total Price: <?php echo $totalPrice; ?></h5>
                <?php
                $itemsString = preg_replace('/,/', '', $itemsString, 1);
                $jobString = preg_replace('/,/', '', $job, 1);

                $_SESSION['costCenters'] = $sessionCostCenter;
                $_SESSION['items'] = $sessionItems;
                $_SESSION['prices'] = $sessionPrices;
                $_SESSION['quantity'] = $sessionQuantity;
                $_SESSION['initials'] = $initials;
                $_SESSION['dateOfPO'] = $dateOfPO;
                $_SESSION['usDateOfPO'] = $usDateOfPO;
                $_SESSION['dateOfRecord'] = $dateOfRecord;
                $_SESSION['totalCost'] = $totalPrice;
                $_SESSION['jobString'] = $jobString;
                $_SESSION['itemString'] = $itemsString;
                //$_SESSION['vendorString'] = $vendorString;
            }

            $list  = array($_SESSION['initials'], $_SESSION['po'], $_SESSION['jobString'], $_SESSION['dateOfPO'], $_SESSION['itemString']);
            echo $_SESSION['dateOfPO'];
            ?>


            <div class="row">
                <div class="col-3">
                    <!--            Info Modal-->
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#infoModalLong">
                        <i class="fa fa-info" aria-hidden="true"></i> Info
                    </button>
                </div>
                <div class="offset-9">
                    <form method="post" action="../createPDF/toPdf.php">
                        <button class="btn btn-lg btn-primary align-center" name="createPDF">Next</button>
                    </form>

                </div>
            </div>
            <div class="row">
                <div class="offset-9">
                    <form method="post" action="createPO.php">
                        <input type="text" name="poName" value="<?php echo $po ?>" style="display: none;">
                        <button class="btn btn-lg btn-danger align-center" name="makePOchanges">Back</button>
                    </form>
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
                            Once you proceed from this step, your entries will be locked into the PO log and PDF will be generated.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </body>

</html>

<?php } else{header('Location: index.php');}


?>