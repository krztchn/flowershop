<?php
require 'db.php'; // make sure db.php exists and connects correctly
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Flower Shop</title>
    <link
      rel="stylesheet"
      href="https://unpkg.com/swiper/swiper-bundle.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    />
    <link rel="stylesheet" href="style.css" />
    <style>
      #searchBar {
        padding: 10px;
        width: 300px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin: 20px auto;
        display: block;
      }
    </style>
  </head>
  <body>
    <header class="header">
      <a href="#" class="logo"> Flower Shop </a>

      <nav class="navbar">
        <a href="home.html">home</a>
        <a href="product.php">product</a>
        <a href="about.html">about</a>
        <a href="blog.html">blog</a>
        <a href="contact.html">contact</a>
      </nav>

      <div class="icons">
        <div id="menu-btn" class="fas fa-bars"></div>
      </div>
    </header>

    <section class="home" id="home">
      <div class="content">
        <h3>buy best <span> fresh flowers </span> online</h3>
      </div>
    </section>

    <section class="product" id="product">
      <h1 class="heading">our <span>products</span></h1>

      <input type="text" id="searchBar" placeholder="Search products..." />

      <div class="box-container">
        <!-- 🧍 Static Products -->
        <div class="box">
          <div class="image"><img src="red.jpg" alt="Rose Bouquet" /></div>
          <div class="content">
            <h3>rose bouquet</h3>
            <div class="price">₱799.00</div>
            <a href="#" class="btn buy-btn" data-name="rose bouquet" data-price="799.00">Buy Now</a>
          </div>
        </div>

        <div class="box">
          <div class="image"><img src="lily.jpg" alt="Lily Arrangement" /></div>
          <div class="content">
            <h3>lily arrangement</h3>
            <div class="price">₱1,199.00</div>
            <a href="#" class="btn buy-btn" data-name="lily arrangement" data-price="1199.00">Buy Now</a>
          </div>
        </div>

        <div class="box">
          <div class="image"><img src="tulip.jpg" alt="Tulip Bouquet" /></div>
          <div class="content">
            <h3>tulip bouquet</h3>
            <div class="price">₱1,499.00</div>
            <a href="#" class="btn buy-btn" data-name="tulip bouquet" data-price="1499.00">Buy Now</a>
          </div>
        </div>

        <div class="box">
          <div class="image"><img src="orc.jpg" alt="Orchid Pot" /></div>
          <div class="content">
            <h3>orchid pot</h3>
            <div class="price">₱2,299.00</div>
            <a href="#" class="btn buy-btn" data-name="orchid pot" data-price="2299.00">Buy Now</a>
          </div>
        </div>

        <!-- 🆕 Dynamically Loaded Products -->
        <?php
        $sql = "SELECT * FROM flowers ORDER BY id DESC";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo '<div class="box">';
            echo '  <div class="image"><img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '"></div>';
            echo '  <div class="content">';
            echo '    <h3>' . htmlspecialchars($row['name']) . '</h3>';
            echo '    <div class="price">₱' . number_format($row['price'], 2) . '</div>';
            echo '    <a href="#" class="btn buy-btn" data-name="' . htmlspecialchars($row['name']) . '" data-price="' . $row['price'] . '">Buy Now</a>';
            echo '  </div>';
            echo '</div>';
          }
        }
        ?>
      </div>
    </section>

    <section class="footer">
      <div class="box-container">
        <div class="box">
          <h3>find us here</h3>
          <p>Irosin Sorsogon</p>
        </div>

        <div class="box">
          <h3>contact us</h3>
          <p>+09 8532 577</p>
          <a href="#" class="link">ShopFloww@gmail.com</a>
        </div>

        <div class="box">
          <h3>localization</h3>
          <p>San Julian, Irosin, <br /> Sorsogon</p>
        </div>
      </div>
    </section>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="script.js"></script>

    <!-- 🛒 Buy Button -->
    <script>
      document.querySelectorAll('.buy-btn').forEach(button => {
        button.addEventListener('click', function (e) {
          e.preventDefault();

          const productName = this.dataset.name;
          const productPrice = this.dataset.price;

          fetch('addtocart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name: productName, price: productPrice }),
          })
          .then(response => response.json())
          .then(data => {
            alert(data.success ? `${productName} added to cart!` : 'Failed to add product.');
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong.');
          });
        });
      });
    </script>

    <!-- 🔍 Search Filter -->
    <script>
      document.getElementById('searchBar').addEventListener('keyup', function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.box-container .box').forEach(product => {
          const name = product.querySelector('h3').textContent.toLowerCase();
          product.style.display = name.includes(query) ? 'block' : 'none';
        });
      });
    </script>
  </body>
</html>
