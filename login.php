<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);

    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        header("Location: home.html");
        exit;
    } else {
        $error = "Invalid login.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Flower Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'partials/header.html'; ?>

<section class="home" id="home">
  <div class="content">
    <h3 style="font-size: 4rem;">Login to your <span> Flower Shop </span> account</h3>
    <form method="POST" class="form-box">
        <input type="text" name="username" placeholder="Username" required class="input-box">
        <input type="password" name="password" placeholder="Password" required class="input-box">
        <button type="submit" class="btn">Login</button>
        <p style="font-size: 1.5rem;">Don't have an account? <a href="signup.php" class="link">Sign up</a></p>
    </form>
    <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>
   
  </div>
</section>

<?php include 'partials/footer.html'; ?>
</body>
</html>
