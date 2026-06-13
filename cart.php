<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['select_order'])){
   mysqli_query($conn, "DELETE FROM `selectcart` WHERE user_id = '$user_id'") or die('query failed');
   
   $cart_id = $_POST['cart_id'];
   $admin_id = $_POST['admin_id'];
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_quantity = $_POST['product_quantity'];

      mysqli_query($conn, "INSERT INTO `selectcart`(cart_id, user_id, admin_id, name, price, qty) VALUES('$cart_id','$user_id', '$admin_id','$product_name', '$product_price', '$product_quantity')") or die('query failed');
      header('location:checkout.php');
   }

if(isset($_POST['update_cart'])){
   
   $cart_id = $_POST['cart_id'];
   $cart_quantity = $_POST['cart_quantity'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
   $message[] = 'cart quantity updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
   header('location:cart.php');
}

if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Carrinho - DivExpress</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>Carrinho de compra</h3>
   <p><a href="home.php">Início</a> / Carrinho</p>
</div>

<section class="shopping-cart">

   <h1 class="title">Produtos no Carrinho</h1>

   <div class="box-container">

      <?php
         $grand_total = 0;
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');

         if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){   
      ?>

      <div class="box">

         <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>

         <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">

         <div class="name"><?php echo $fetch_cart['name']; ?></div>

         <div class="price">kz<?php echo $fetch_cart['price']; ?>/-</div>

         <form action="" method="post">

            <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
            <input type="hidden" name="admin_id" value="<?php echo $fetch_cart['admin_id']; ?>">
            <input type="hidden" name="product_name" value="<?php echo $fetch_cart['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_cart['price']; ?>">

            <input type="number" min="1" name="product_quantity" value="<?php echo $fetch_cart['quantity']; ?>">

            <input type="submit" name="update_cart" value="Update" class="option-btn">

            <a href="contact.php" class="option-btn">Contactar vendedor</a>

            <input type="submit" value="Pedir produto" name="select_order" class="btn">

         </form>

         <div class="sub-total">
            Sub total: 
            <span>
               kz<?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?>
            </span>
         </div>

      </div>

      <?php
            }
         }else{
            echo '<p class="empty">O teu carrinho está vazio</p>';
         }
      ?>

   </div>

   <div style="margin-top: 2rem; text-align:center;">
      <a href="cart.php?delete_all" class="delete-btn" onclick="return confirm('delete all from cart?');">
         Limpar carrinho
      </a>
   </div>

   <div class="cart-total">
      <a href="shop.php" class="option-btn">Continuar a comprar</a>
   </div>

</section>

</body>
</html>

<!-- custom js file link  -->
<script src="js/script.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>
   <style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

:root{
   --primary:#2563eb;
   --primary-dark:#1d4ed8;
   --dark:#0f172a;
   --gray:#64748b;
   --bg:#f1f5f9;
   --white:#ffffff;
   --danger:#ef4444;
   --success:#22c55e;
   --shadow:0 18px 45px rgba(0,0,0,.10);
   --radius:20px;
}

*{
   margin:0;
   padding:0;
   box-sizing:border-box;
   font-family:'Poppins', sans-serif;
}

body{
   background:var(--bg);
}

/* ================= HEADER ================= */
.heading{
   text-align:center;
   padding:80px 20px 35px;
   background:linear-gradient(135deg,var(--primary),var(--primary-dark));
   color:#fff;
   border-radius:0 0 40px 40px;
}

.heading h3{
   font-size:42px;
   font-weight:800;
   text-transform:capitalize;
}

.heading p{
   margin-top:10px;
   opacity:.9;
}

.heading a{
   color:#fff;
   font-weight:600;
}

/* ================= SECTION ================= */
.shopping-cart{
   padding:70px 8%;
}

.title{
   text-align:center;
   font-size:38px;
   font-weight:800;
   margin-bottom:50px;
   color:var(--dark);
}

/* ================= GRID ================= */
.box-container{
   display:grid;
   grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));
   gap:28px;
}

/* ================= CARD PRODUTO ================= */
.box{
   background:var(--white);
   border-radius:var(--radius);
   padding:18px;
   box-shadow:var(--shadow);
   position:relative;
   transition:.3s ease;
   border:1px solid #e2e8f0;
}

.box:hover{
   transform:translateY(-6px);
}

/* DELETE ICON */
.box .fa-times{
   position:absolute;
   top:14px;
   right:14px;
   font-size:18px;
   color:var(--danger);
   transition:.3s;
}

.box .fa-times:hover{
   transform:scale(1.2);
}

/* ================= IMAGEM PROFISSIONAL ================= */
.box img{
   width:100%;
   height:180px;
   object-fit:contain;
   background:#f8fafc;
   padding:10px;
   border-radius:16px;
   margin-bottom:12px;
}

/* ================= TEXTO ================= */
.name{
   font-size:17px;
   font-weight:700;
   color:var(--dark);
   margin-bottom:6px;
}

.price{
   font-size:20px;
   font-weight:800;
   color:var(--primary);
   margin-bottom:10px;
}

/* ================= INPUT ================= */
input[type="number"]{
   width:100%;
   padding:11px;
   border-radius:12px;
   border:1px solid #e2e8f0;
   background:#f8fafc;
   outline:none;
   margin:8px 0;
   transition:.3s;
}

input[type="number"]:focus{
   border-color:var(--primary);
   background:#fff;
}

/* ================= BOTÕES ================= */
.btn,
.option-btn,
.delete-btn{
   display:inline-block;
   width:100%;
   padding:11px;
   border-radius:12px;
   font-size:14px;
   font-weight:700;
   margin-top:8px;
   text-align:center;
   cursor:pointer;
   transition:.3s;
   text-decoration:none;
}

/* PRIMARY BUTTON */
.btn{
   background:linear-gradient(135deg,var(--primary),var(--primary-dark));
   color:#fff;
}

.btn:hover{
   transform:translateY(-3px);
}

/* SECONDARY BUTTON */
.option-btn{
   background:#e2e8f0;
   color:var(--dark);
}

.option-btn:hover{
   background:#cbd5e1;
}

/* DELETE BUTTON */
.delete-btn{
   background:var(--danger);
   color:#fff;
}

.delete-btn:hover{
   background:#b91c1c;
}

/* ================= SUBTOTAL ================= */
.sub-total{
   margin-top:12px;
   padding-top:10px;
   border-top:1px solid #e2e8f0;
   font-size:15px;
   font-weight:600;
   color:var(--dark);
}

.sub-total span{
   color:var(--success);
   font-weight:800;
}

/* ================= EMPTY ================= */
.empty{
   grid-column:1/-1;
   text-align:center;
   background:#fff;
   padding:25px;
   border-radius:15px;
   box-shadow:var(--shadow);
   color:var(--gray);
}

/* ================= CART ACTIONS ================= */
.cart-total{
   margin-top:40px;
   text-align:center;
}

.cart-total .option-btn{
   max-width:300px;
   margin:auto;
}

/* ================= RESPONSIVO ================= */
@media(max-width:768px){
   .title{
      font-size:28px;
   }

   .heading h3{
      font-size:30px;
   }
}

   </style>