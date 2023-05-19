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
            $id = $_GET["id"];
            mysqli_query($conn, "DELETE FROM orderdetail WHERE OrderID = '$id'");
            mysqli_query($conn, "DELETE FROM orders WHERE OrderID = '$id'");
        }
        ?>

        <div class="container border my-2">
            <form name="frm" method="post" action="" class="mt-3 mx-md-2">
                <h1 class="text-center mb-4">Order Management</h1>
                <table id="tablecategory" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr class="text-center">
                            <th><strong>No.</strong></th>
                            <th><strong>Order date</strong></th>
                            <!-- <th><strong>Delivery date</strong></th> -->
                            <th><strong>Delivery local</strong></th>
                            <th><strong>Customer Name</strong></th>
                            <th><strong>Delete</strong></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $No = 1;
                        $sq = "SELECT o.OrderID, Orderdate, Deliverydate, Deliverylocal, c.CustName
                                FROM orders o, customer c
                                WHERE o.Username = c.Username ORDER BY Orderdate DESC";
                        $res = mysqli_query($conn, $sq);
                        if (!$res) {
                            die('Invalid query: ' . mysqli_error($conn));
                        }
                        while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
                        ?>
                            <tr>
                                <td class="text-center">
                                    <a href="?page=order_detail_management&id=<?php echo $row["OrderID"] ?>" class="text-decoration-none"><?php echo $No ?></a>
                                </td>
                                <td class="text-center"><?php echo $row["Orderdate"]; ?></td>
                                <!-- <td class="text-center"><?php echo $row["Deliverydate"]; ?></td> -->
                                <td><?php echo $row["Deliverylocal"]; ?></td>
                                <td><?php echo $row["CustName"]; ?></td>
                                <td style='text-align:center'>
                                    <a href="?page=order_management&&function=del&&id=<?php echo $row["OrderID"] ?>" onclick="return deleteConfirm()">
                                        <i class="bi bi-trash-fill" style="color: red;"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php
                            $No++;
                        }
                        ?>
                    </tbody>
                </table>
            </form>
        </div>
<?php
    }
}
?>