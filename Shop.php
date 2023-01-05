<style>
  .col-md-2 {
    margin: 0;
    padding: 0;
  }

  .col-md-10 {
    margin: 0;
    padding: 0;
  }
</style>
<div class="row border">
  <div class="col-lg-2 col-md-3">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md bg-light navbar-light bg-white">
      <!-- Toggle button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Brand -->
      <a class="text-decoration-none text-black ps-3" href="index.php">
        <h3>NDQ STORE</h3>
      </a>
    </nav>
    <!-- Navbar -->

    <!-- Sidebar -->
    <nav id="sidebarMenu" class="collapse d-md-block bg-white">
      <div class="position-sticky">
        <div class="list-group list-group-flush">
          <a href="?page=shop" class="list-group-item list-group-item-action py-2">All</a>
          <?php
          $result = mysqli_query($conn, "SELECT c.CatID, c.CatName, c.Cat_image , COUNT(c.CatID), SUM(Qty)
                                        FROM orderdetail o, product p, category c
                                        WHERE o.ProID = p.ProID AND p.CatID = c.CatID
                                        GROUP BY c.CatID
                                        ORDER BY SUM(Qty)
                                        DESC
                                        LIMIT 6");

          if (!$result) {
            die('Invalid query: ' . mysqli_error($conn));
          }

          while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          ?>
            <a href="?page=shop&id=<?php echo $row['CatID'] ?>" class="list-group-item list-group-item-action py-2"><?php echo $row['CatName'] ?></a>
          <?php
          }
          ?>
        </div>
      </div>
    </nav>
    <!-- Sidebar -->
  </div>
  <div class="col-lg-10 col-md-9 col-12">
    <div id="carouselExampleSlidesOnly" class="carousel slide mb-3 mt-2" data-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img class="d-block w-100" src="Image/Fashion-ads-3.jpg" alt="First slide">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="Image/Fashion-ads-2.png" alt="Second slide">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="Image/Fashion-ads-1.jpg" alt="Third slide">
        </div>
      </div>
    </div>
    <section>
      <div class="row">
        <?php
        $per_page_record = 12;
        if (isset($_GET["pagep"])) {
          $page  = $_GET["pagep"];
        } else {
          $page = 1;
        }
        $start_from = ($page - 1) * $per_page_record;
        /*Show by Brand*/
        if (isset($_GET["id"])) {
          $id = $_GET["id"];

          $result = mysqli_query($conn, "SELECT * FROM product WHERE CatID = '$id' LIMIT $start_from, $per_page_record");

          if (!$result) {
            die('Invalid query: ' . mysqli_error($conn));
          }
          $query = "SELECT COUNT(*) FROM product WHERE CatID = '$id'";
          $rs_result = mysqli_query($conn, $query);
          $row = mysqli_fetch_row($rs_result);
          $total_records = $row[0];
          echo "</br>";
          $total_pages = ceil($total_records / $per_page_record);
        } elseif (isset($_POST['btnSearch'])) {
          /*Searching*/
          $searching = $_POST['txtSearch'];

          $keywords = explode(' ', $searching);
          $searchTermKeywords = array();

          foreach ($keywords as $word) {
            $searchTermKeywords[] = "ProName LIKE '%$word%'";
          }

          $result = mysqli_query($conn, "SELECT * FROM product WHERE " . implode('AND LIMIT $start_from, $per_page_record AND ', $searchTermKeywords) . "");

          if (!$result) {
            die('Invalid query: ' . mysqli_error($conn));
          }
          $query = "SELECT COUNT(*) FROM product WHERE " . implode('AND ', $searchTermKeywords) . "";
          $rs_result = mysqli_query($conn, $query);
          $row = mysqli_fetch_row($rs_result);
          $total_records = $row[0];
          echo "</br>";
          $total_pages = ceil($total_records / $per_page_record);
        } else {
          /*Show all*/
          $result = mysqli_query($conn, "SELECT * FROM product LIMIT $start_from, $per_page_record");

          if (!$result) {
            die('Invalid query: ' . mysqli_error($conn));
          }
          $query = "SELECT COUNT(*) FROM product";
          $rs_result = mysqli_query($conn, $query);
          $row = mysqli_fetch_row($rs_result);
          $total_records = $row[0];
          echo "</br>";
          $total_pages = ceil($total_records / $per_page_record);
        }
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        ?>
          <div class="col-12 col-lg-4 col-xl-3 col-md-6 col-sm-6 mb-xl-4">
            <div class="product-grid1">
              <div class="product-image1">
                <a href="?page=viewdetail&id=<?php echo $row['ProID'] ?>" class="image1">
                  <img class="img" src="Product/<?php echo $row['Pro_image'] ?>">
                </a>
                <!-- <ul class="product-links1">
                    <li><a href="#" data-tip="Add to Wishlist"><i class="bi bi-heart"></i></a></li>
                    <li><a href="#" data-tip="Compare"><i class="bi bi-shuffle"></i></i></a></li>
                    <li><a href="?page=viewdetail&id=<?php echo $row['ProID'] ?>" data-tip="Quick View"><i class="bi bi-eye-fill"></i></a></li>
                  </ul> -->
              </div>
              <div class="product-content1">
                <div class="title1 mb-2">
                  <a href="?page=viewdetail&id=<?php echo $row['ProID'] ?>">
                    <strong><?php echo $row['ProName'] ?></strong>
                  </a>
                </div>
                <div class="price1">$<?php echo $row['ProPrice'] ?></div>
                <form action="?page=cart" method="POST" class="d-flex">
                  <input type="hidden" name="quantity" class="text-center" value="1">
                  <input type="submit" name="addcart" class="add-to-cart" value="Add to cart">
                  <input type="hidden" name="proid" value="<?php echo $row['ProID'] ?>">
                  <input type="hidden" name="proname" value="<?php echo $row['ProName'] ?>">
                  <input type="hidden" name="shortdesc" value="<?php echo $row['SmallDesc'] ?>">
                  <input type="hidden" name="image" value="<?php echo $row['Pro_image'] ?>">
                  <input type="hidden" name="price" value="<?php echo $row['ProPrice'] ?>">
                </form>
              </div>
            </div>
          </div>
        <?php
        }
        ?>
      </div>
    </section>
    <nav aria-label="Search results pages">
      <ul class="pagination justify-content-center">
        <?php
        $pagLink = "";
        if ($page >= 2) {
          echo "<li class='page-item'>
                    <a class='page-link' href='?page=shop&&pagep=" . ($page - 1) . "'>Previous</a>
                </li>";
        }
        for ($i = 1; $i <= $total_pages; $i++) {
          if ($i == $page) {
            $pagLink .= "<li class='page-item active'>
                            <a class='page-link' href='?page=shop&&pagep=" . $i . "'>" . $i . "</a>
                        </li>";
          } else {
            $pagLink .= "<li class='page-item'>
                            <a class='page-link' href='?page=shop&&pagep=" . $i . "'>" . $i . "</a>
                          </li>";
          }
        };
        echo $pagLink;
        if ($page < $total_pages) {
          echo "<li class='page-item'>
                    <a class='page-link' href='?page=shop&&pagep=" . ($page + 1) . "'>Next</a>
                  </li>";
        }
        ?>
      </ul>
    </nav>
  </div>
</div>