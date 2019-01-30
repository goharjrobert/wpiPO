<?php
/**
 * Created by PhpStorm.
 * User: GRobert
 * Date: 1/17/2019
 * Time: 1:21 PM
 */
include('../session/header.php');
?>
<html>

    <title>Filter PO's</title>

    <body>
    <?php
    if(isset($_POST['filterNumber']))

    {
        $filterNumber = (int)$_POST['filterNumber'];
        $filterValue = $_POST['filterValue'];
        ?>

        <div class="filterHead">
            <div class="container">
                <div class="row">
                    <!-- Button to Open the Modal -->
                    <button type="button" class="btn btn-outline-danger" onclick="window.location.href='../savedPOs/allPOs.php'"><b><i class="far fa-times-circle"></i> </b>Clear Search</button>

                    <button type="button" class="btn btn-primary filterButton" data-toggle="modal" data-target="#filterModal">
                        <b>Current Search: </b> <?php echo $filterValue ?>
                    </button>
                </div>

                <div class="row">
                    <div class="searchBox">
                        <label><b>Search any field</b></label>
                        <input class="form-control" id="myInput" style="text-align: center;" type="text" placeholder="Search..">
                    </div>
                </div>
            </div>
            <table>
                <thead class="thead-dark" style="text-align: center;">
                <tr>
                    <th>Initials</th>
                    <th>PO Number</th>
                    <th>Vendor</th>
                    <th>Cost Center</th>
                    <th>Date of Purchase</th>
                    <th>Category</th>
                    <th>Cost</th>
                    <th>Items</th>
                    <th>View PO</th>
                </tr>

                </thead>
            </table>

        </div>

        <div class="poList">
            <br>
            <br>
            <table class="table table-hover">

                <tbody id="myTable" style="text-align: center;">
                    <?php

                    //echo gettype($filterNumber);



                        $input = fopen('../Log.csv', 'r');
                        $reservedPOs = [];
                        while(($data = fgetcsv($input)) !== FALSE){

                            if($data[$filterNumber] === $filterValue || stristr($data[$filterNumber], $filterValue)){

                        ?>


                            <tr <?php if(strpos($data[1], '(RETURNED') == true){?> style="background-color: #ffd1004a;" <?php } ?>>
                            <?php

                            for ($i = 0; $i < 8; $i++) {
                                ?>
                                <td>
                                    <?php
                                        echo $data[$i];
                                    ?>
                                </td>
                                <?php
                            }
                            ?>
                                <td>
                                    <form action="../savedPOs/showPO.php" method="post">
                                        <input type="text" value="<?php echo $data[1] ?>" name="nameOfPO" style="display: none">
                                        <input type="text" value="<?php echo $data[0] ?>" name="initials" style="display: none">
                                        <?php if(file_exists('../savedPOs/'.$data[1].'.pdf')){ ?>
                                            <p><button type="submit" class="btn btn-primary" name="viewPO"><i class="far fa-file-alt"></i> View</button></p>
                                        <?php } ?>
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#<?php echo substr($data[1], 0, 6) ?>">
                                            More Options
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="<?php echo substr($data[1], 0, 6) ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel"><?php echo $data[1] ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <?php if(strpos($data[1], 'RETURNED') == false){?>
                                                                <div class="col">
                                                                    <button type="submit" class="btn btn-warning" name="returnPO" title="This will put a 'RETURNED' marker on this PO">Return</button>
                                                                </div>
                                                            <?php } ?>
                                                            <div class="col">
                                                                <button type="submit" class="btn btn-danger" name="removePO" title="This will remove the PO from the log and keep it reserved for you">Remove</button>
                                                            </div>
                                                            <div class="col">
                                                                <button type="submit" class="btn btn-primary" name="addDocs">Add Document</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                    <!-- Modal -->

                                </td>
                            </tr>


                        <?php
                        }}
                        fclose($input);
                    ?>


                </tbody>

            </table>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post" action="index.php">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <select class="form-control form-control-lg" name="filterNumber" required>
                                            <option value="0">Initials</option>
                                            <option value="1">PO Number</option>
                                            <option value="2">Vendor</option>
                                            <option value="3">Cost Center</option>
                                            <option value="4">Date</option>
                                            <option value="5">Category</option>
                                            <option value="7">Items</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <input class="form-control form-control-lg col" type="text" name="filterValue" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-large btn-primary" type="submit">Enter</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php }

    else{
        header('Location: ../savedPOs/allPOs.php');
    }
    ?>
    </body>

</html>
