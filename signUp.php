<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <!-- JQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      $("#importedFooter").load("footer.txt");
      $("#importedNavbar").load("nav.txt");
    });
  </script>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="commonCSS.css">
</head>

<body>
  <div id="importedNavbar"></div>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <main>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="text-center">
              <img class="mb-4" src="Logo.svg" alt="" width="72" height="57">
            </div>
            <h1 class="h3 mb-3 fw-normal">Please Sign Up</h1>

            <div class="form-floating mb-3">
              <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
              <label for="email">Email address</label>
            </div>
            <div class="form-floating mb-3">
              <input type="password" class="form-control" id="password" name="password" placeholder="Password">
              <label for="password">Password</label>
            </div>
            <div class="form-floating mb-3">
              <input type="password" class="form-control" id="passwordconfirm" name="passwordconfirm" placeholder="Confirm Password">
              <label for="passwordconfirm">Confirm Password</label>
            </div>

            <div class="form-check text-start my-3">
              <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
              <label class="form-check-label" for="flexCheckDefault">Remember me</label>
            </div>
            <p>
              By creating an account, you agree to our
              <a href="PrivacyNotice.html">Privacy Notice</a> and
              <a href="TermsofUse.html">Terms of Use</a>.
            </p>
            <button class="btn btn-primary w-100 py-2" type="submit">Sign Up</button>
          </form>
          Have an Account? <a href="signIn.php">Sign In</a>
        </main>
      </div>
    </div>
  </div>

  <div id="importedFooter"></div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
include("Database.php"); // Use the Database class for PDO connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
  $passwordconfirm = filter_input(INPUT_POST, 'passwordconfirm', FILTER_SANITIZE_SPECIAL_CHARS);

  if (empty($email) || empty($password) || empty($passwordconfirm)) {
    echo "<p>Please fill out all fields.</p>";
  } else {
    if ($password !== $passwordconfirm) {
      echo "<p>Password and confirm password do not match.</p>";
    } else {
      // Hash the password
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      try {
        $conn = Database::connect();
        $sql = "INSERT INTO logintable (email, password) VALUES (:email, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
          header("Location: index.html");
          exit;
        } else {
          echo "<p>Registration failed. Please try again later.</p>";
        }
      } catch (PDOException $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
      }
    }
  }
}
?>