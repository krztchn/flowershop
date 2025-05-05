<?php
require 'db.php'; // Your mysqli connection

$message = "";

// Handle Deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Get the image path to delete the file
    $stmt = $conn->prepare("SELECT image FROM flowers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imagePath);
    $stmt->fetch();
    $stmt->close();

    // Delete image file
    if ($imagePath && file_exists($imagePath)) {
        unlink($imagePath);
    }

    // Delete from database
    $stmt = $conn->prepare("DELETE FROM flowers WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "✅ Flower deleted successfully.";
    } else {
        $message = "❌ Failed to delete flower.";
    }
    $stmt->close();
}

// Handle Addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["flower_name"])) {
    $flowerName = $_POST["flower_name"];
    $price = $_POST["price"];
    $image = $_FILES["image"]["name"];
    $imagePath = 'uploads/' . basename($image);

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
        $sql = "INSERT INTO flowers (name, price, image) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sds", $flowerName, $price, $imagePath);

        if ($stmt->execute()) {
            $message = "✅ Flower '$flowerName' added successfully!";
        } else {
            $message = "❌ Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "❌ Image upload failed.";
    }
}

// Fetch flowers to display
$flowers = $conn->query("SELECT * FROM flowers ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Flower - Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f7f7f7;
            padding: 50px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .form-section {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            font-size: 16px;

        }

        .form-section input, .form-section button {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-section input[type="text"], .form-section input[type="number"] {
            width: 100%;
            font-size: 16px;

        }

        .form-section button {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }

        .form-section button:hover {
            background: #218838;
        }

        .form-section label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .message {
            color: green;
            text-align: center;
            margin-top: 20px;
        }

        .message.error {
            color: red;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
            font-size: 16px;

        }

        th {
            background-color: #f0f0f0;
        }

        img {
            max-width: 60px;
            height: auto;
        }

        .delete-link {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        .delete-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<header class="header">
    <a href="#" class="logo"> Flower Shop </a>

    <nav class="navbar">
        <a href="index.html">home</a>
        <a href="product.php">product</a>
        <a href="about.html">about</a>
        <a href="blog.html">blog</a>
        <a href="contact.html">contact</a>
    </nav>

    <div class="icons">
        <div id="menu-btn" class="fas fa-bars"></div>
    </div>
</header>

<section class="form-section">
<h2><span style="color: #ffba43;">Add a</span> <span style="color: #3bb77e;">New Flower</span></h2>
    <?php if (!empty($message)) echo "<p class='message'>" . htmlspecialchars($message) . "</p>"; ?>

    <form action="addflower.php" method="POST" enctype="multipart/form-data">
        <label>Flower Name:</label>
        <input type="text" name="flower_name" required>

        <label>Price:</label>
        <input type="number" name="price" step="0.01" required>

        <label>Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit">Add Flower</button>
    </form>
</section>

<!-- Flower List -->
<section class="form-section">
    <h2>Manage Flowers</h2>
    <?php if ($flowers->num_rows > 0): ?>
        <table>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $flowers->fetch_assoc()): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($row['image']) ?>" alt="Flower Image"></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td>$<?= number_format($row['price'], 2) ?></td>
                    <td><a href="?delete=<?= $row['id'] ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this flower?');">Delete</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No flowers have been added yet.</p>
    <?php endif; ?>
</section>

</body>
</html>
