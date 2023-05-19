<?php
$errLogin = "";
if (isset($_POST["btnLogin"])) {
  $us = $_POST["txtUsername"];
  $pa = $_POST["txtPassword"];

  $us = mysqli_real_escape_string($conn, $us);
  $password = md5($pa);
  $sq = "SELECT Username, Password, CustName, CustPhone, CustMail, CustAddress, State FROM customer WHERE (Username = '$us' OR CustMail = '$us') AND Password = '$password'";
  $res = mysqli_query($conn, $sq) or die(mysqli_error($conn));

  if (mysqli_num_rows($res) == 1) {
    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

    $_SESSION["us"] = $row["Username"];
    $_SESSION["cname"] = $row["CustName"];
    $_SESSION["phone"] = $row["CustPhone"];
    $_SESSION["address"] = $row["CustAddress"];
    $_SESSION["email"] = $row["CustMail"];
    $_SESSION["admin"] = $row["State"];
    echo '<meta http-equiv="refresh" content = "0; URL=index.php"/>';
  } else {
    // echo "<script>alert('Username or Password incorrect')</script>";
    $errLogin = "Username or Password incorrect";
  }
}
?>
<section class="d-flex justify-content-center align-items-center border my-2">
  <div class="row my-4 mx-2">
    <div class="col-md-6 d-flex align-items-center">
      <img src="Image/log.png" class="img-fluid mt-4" alt="Phone image" />
    </div>
    <div class="col-md-6">
      <h2 class="text-center mt-5 d-none d-md-block">
        <strong>Login</strong>
      </h2>
      <div class="text-center">
        <span class="text-danger"><?= $errLogin == "" ? "" : $errLogin; ?></span>
      </div>
      <form id="formLogin" name="formLogin" method="POST" onsubmit="return formValid()">
        <!-- Fill in Username -->
        <div class="form-outline">
          <label class="form-label" for="formUsername" style="font-weight: 700;">Username</label>
          <input type="text" name="txtUsername" id="formUsername" class="form-control form-control-lg" style="font-size: medium;" value='<?php echo isset($_POST["txtUsername"]) ? ($_POST["txtUsername"]) : ""; ?>' />
          <span class="text-danger" id="errorUsername"></span>
        </div>

        <!-- Fill in Password -->
        <div class="form-outline my-3">
          <label class="form-label fw-bold" for="formPassword">Password</label>
          <input type="password" name="txtPassword" id="formPassword" class="form-control form-control-lg" style="font-size: medium;" />
          <span class="text-danger" id="errorPassword"></span>
        </div>

        <div class="d-flex justify-content-around align-items-center mb-3">
          <!-- Checkbox -->
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="checkBox" />
            <label class="form-check-label" for="checkBox">Remember me
            </label>
          </div>
          <a href="#!">Forgot password?</a>
        </div>

        <!-- Sign in button -->
        <button type="submit" name="btnLogin" id="btnLogin" class="btn btn-primary btn-block mb-3">
          Sign in
        </button>
        <div class="text-center mb-3">
          <p>Don’t have an account? <a href="?page=register"> Sign up </a> </p>
        </div>
      </form>
    </div>
  </div>
</section>
<script>
  function formValid() {
    f = document.formLogin
    const errorUsername = document.getElementById('errorUsername')
    const errorPassword = document.getElementById('errorPassword')

    if (f.txtUsername.value == "") {
      errorUsername.innerHTML = "Username can't be empty, please enter again"
      f.txtUsername.focus();
      return false;
    } else {
      errorUsername.innerHTML = ""
    }

    if (f.txtPassword.value == "") {
      errorPassword.innerHTML = "Password can't be empty, please enter again"
      f.txtPassword.focus();
      return false;
    } else {
      errorPassword.innerHTML = ""
    }

    return true;
  }
</script>