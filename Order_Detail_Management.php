<?php
if (isset($_SESSION['us']) == false) {
    echo "<script>alert('You need to login before accessing this page')</script>";
    echo '<meta http-equiv="refresh" content = "0; URL=?page=login"/>';
} else {
    if (isset($_SESSION['admin']) && $_SESSION['admin'] != 1) {
        echo "<script>alert('You are not administrator to access this page')</script>";
        echo '<meta http-equiv="refresh" content = "0; URL=index.php"/>';
    } else {

?>
        <script>
            function deleteConfirm() {
                if (confirm("Are you sure to delete!")) {
                    return true;
                } else {
                    return false;
                }
            }
        </script>

        <?php
        if (isset($_GET["function"]) == "del") {
            $ido = $_GET["ido"];
            $idp = $_GET["idp"];
            mysqli_query($conn, "DELETE FROM orderdetail WHERE OrderID = '$ido' AND ProID = '$idp'");
        }
        ?>

        <div class="container border my-2">
            <form name="frm" method="post" action="" class="mt-3 mx-md-2">
                <h1 class="text-center mb-4">Order Management</h1>
                <table id="tablecategory" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr class="text-center">
                            <th><strong>No.</strong></th>
                            <th><strong>Product Name</strong></th>
                            <th><strong>Image</strong></th>
                            <th><strong>Price</strong></th>
                            <th><strong>Quantity</strong></th>
                            <th><strong>Total</strong></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if (isset($_GET['id'])) {
                            $id = $_GET['id'];
                            $No = 1;
                            $sq = "SELECT od.OrderID, od.Qty, od.TotalPrice, p.ProName, p.ProPrice, Pro_image
                                FROM orderdetail od, product p
                                WHERE od.ProID = p.ProID AND OrderID = '$id'";
                            $res = mysqli_query($conn, $sq);
                            if (!$res) {
                                die('Invalid query: ' . mysqli_error($conn));
                            }
                            while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
                        ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo $No ?>
                                    </td>
                                    <td class="text-center"><?php echo $row["ProName"]; ?></td>
                                    <td class="text-center">
                                        <img src='Product/<?php echo $row["Pro_image"] ?>' border='0' width="100" />
                                    </td>
                                    <td class="text-center">$<?php echo $row["ProPrice"]; ?></td>
                                    <td class="text-center"><?php echo $row["Qty"]; ?></td>
                                    <td class="text-center fw-bold">$<?php echo $row["TotalPrice"]; ?></td>
                                </tr>
                        <?php
                                $No++;
                            }
                        } else {
                            echo '<meta http-equiv="refresh" content = "0; URL=?page=order_management"/>';
                        }
                        ?>
                    </tbody>
                </table>
                <div class="form-ouline text-center mb-3">
                    <input type="button" class="btn btn-primary" value="Back to order" onclick="window.location='?page=order_management'" />
                </div>
            </form>
        </div>
<?php
    }
}
?>