<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        header("Location: login.php?signup=success");
        exit;
    } else {
        $error = "Username already taken.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - Flower Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'partials/header.html'; ?>

<section class="home" id="home">
  <div class="content">
    <h3 style="font-size: 4rem;">Create your <span> Flower Shop </span> account</h3>
    <form method="POST" class="form-box">
        <input type="text" name="username" placeholder="Username" required class="input-box">
        <input type="password" name="password" placeholder="Password" required class="input-box">
        <button type="submit" class="btn">Sign Up</button>
        <p style="font-size: 1.5rem;">Already have an account? <a href="login.php" class="link">Login</a></p>
    </form>
    <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>
   
  </div>
</section>

<?php include 'partials/footer.html'; ?>
</body>
</html>
