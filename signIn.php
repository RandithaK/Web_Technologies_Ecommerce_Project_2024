<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In</title>
  <!-- JQuery for dynamic content loading -->
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
            <div class="text-center mb-4">
              <img src="Logo.svg" alt="Logo" width="72" height="57">
            </div>
            <h1 class="h3 mb-3 fw-normal">Please Sign in</h1>

            <div class="form-floating mb-3">
              <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
              <label for="email">Email address</label>
            </div>
            <div class="form-floating mb-3">
              <input type="password" class="form-control" id="password" name="password" placeholder="Password">
              <label for="password">Password</label>
            </div>

            <div class="form-check text-start my-3">
              <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
              <label class="form-check-label" for="flexCheckDefault">Remember me</label>
            </div>
            <button class="btn btn-lg btn-primary w-100" type="submit">Sign In</button>

            <p class="mt-5 mb-3 text-muted text-center">
              Don't Have an Account? <a href="signUp.php">Sign Up</a>
            </p>
          </form>
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
include("Database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

  if (empty($email) || empty($password)) {
    echo "<script>alert('Please fill out all fields.');</script>";
  } else {
    $conn = Database::connect();
    $sql = "SELECT * FROM logintable WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      // Redirecting to homepage after successful login
      session_start();
      $_SESSION['user'] = $user['email']; // Assuming 'email' can be used as user identifier
      header("Location: homepage.php");
      exit;
    } else {
      echo "<script>alert('Login failed, wrong credentials.');</script>";
    }
  }
}
?>