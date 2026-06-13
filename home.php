<?php

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit();
}

if(isset($_POST['add_to_cart'])){
   $admin_id = $_POST['admin_id'];
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn,
   "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'")
   or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
      mysqli_query($conn,
      "INSERT INTO `cart`(user_id, admin_id, name, price, quantity, image)
      VALUES('$user_id', '$admin_id','$product_name', '$product_price', '$product_quantity', '$product_image')")
      or die('query failed');

      $message[] = 'product added to cart!';
   }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DivExpress</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php include 'header.php'; ?>

</head>

<body>

<section class="home">
   <h1>Seja bem-vindo à DivExpress</h1>
</section>

<!-- ================= CATEGORIAS ================= -->
<section id="categ" class="Categoria">

   <h1 class="title">Categorias</h1>

   <div class="category-container">

      <div class="category-box">
         <img src="images/electronico.jpg">
         <div class="cat-content">
            <h3>Electrónicos</h3>
            <a href="categoria.php?cat=electronico" class="btn">Ver Produtos</a>
         </div>
      </div>

      <div class="category-box">
         <img src="images/roupa.jpg">
         <div class="cat-content">
            <h3>Roupas</h3>
            <a href="categoria.php?cat=roupas" class="btn">Ver Produtos</a>
         </div>
      </div>

      <div class="category-box">
         <img src="images/calcados.jpg">
         <div class="cat-content">
            <h3>Calçados</h3>
            <a href="categoria.php?cat=calcados" class="btn">Ver Produtos</a>
         </div>
      </div>

      <div class="category-box">
         <img src="images/acessorios.jpg">
         <div class="cat-content">
            <h3>Acessórios</h3>
            <a href="categoria.php?cat=acessorios" class="btn">Ver Produtos</a>
            
         </div>
      </div>

      <div class="category-box">
         <img src="images/mobilias.jpg">
         <div class="cat-content">
            <h3>Mobilias</h3>
            <a href="categoria.php?cat=mobilias" class="btn">Ver Produtos</a>
            
         </div>
      </div>

      <div class="category-box">
         <img src="images/Toyota Land Cruiser.jpg">
         <div class="cat-content">
            <h3>Veiculos</h3>
            <a href="categoria.php?cat=veiculos" class="btn">Ver Produtos</a>
            
         </div>
      </div>
      
   </div>

</section>

<!-- ================= PRODUTOS ================= -->
<section class="products">

   <h1 class="title">Produtos Recentes</h1>

   <div class="box-container">

   <?php  
      $select_products = mysqli_query($conn,
      "SELECT * FROM `products` LIMIT 10");

      while($fetch_products = mysqli_fetch_assoc($select_products)){

      $admin_id = $fetch_products['admin_id'];

      $select_users = mysqli_query($conn,
      "SELECT * FROM `users` WHERE id = '$admin_id'");

      $row = mysqli_fetch_assoc($select_users);
   ?>

   <form method="post" class="box">

      <input type="hidden" name="admin_id" value="<?php echo $fetch_products['admin_id']; ?>">

      <!-- 🔥 SELLER + CHAT -->
      <div class="seller-card">

         <div class="seller-avatar">
            <?php echo strtoupper(substr($row['name'],0,1)); ?>
         </div>

         <div>
            <strong><?php echo $row['name']; ?></strong><br>
            <small>Vendedor Oficial</small>
         </div>

        

      </div>

      <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>">

      <div class="name"><?php echo $fetch_products['name']; ?></div>

      <div class="descricao"><?php echo $fetch_products['descricao']; ?></div>

      <div class="price">KZ <?php echo $fetch_products['price']; ?></div>

      <input type="number" name="product_quantity" min="1"
      max="<?php echo $fetch_products['quantity']; ?>"
      value="1" class="qty">

      <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
      <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">

      <button type="submit" name="add_to_cart" class="btn">
         Adicionar ao Carrinho
      </button>

   </form>

   <?php } ?>

   </div>
</section>

<?php include 'footer.php'; ?>

</body>

<style>

/* ===== BASE ===== */
*{
   margin:0;
   padding:0;
   box-sizing:border-box;
   font-family:'Poppins',sans-serif;
}

body{
   background:#f8fafc;
}

/* ===== CHAT BUTTON ===== */
.chat-btn{
   margin-left:auto;
   background:#25d366;
   color:#fff;
   padding:8px 12px;
   border-radius:10px;
   text-decoration:none;
   font-size:13px;
   font-weight:600;
}

/* ===== HOME ===== */
.home{
   min-height:90vh;
   display:flex;
   align-items:center;
   justify-content:center;
   text-align:center;
   background:linear-gradient(rgba(0,0,0,.7),rgba(37,99,235,.7)),url('images/inicio.jpg') center/cover;
}

.home h1{
   color:#fff;
   font-size:42px;
}

/* ===== TITLE ===== */
.title{
   text-align:center;
   font-size:38px;
   margin:50px 0;
   font-weight:800;
}

/* ===== PRODUCTS ===== */
.products .box-container{
   display:grid;
   grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
   gap:25px;
   padding:0 8%;
}

.box{
   background:#fff;
   border-radius:20px;
   padding:20px;
   box-shadow:0 10px 30px rgba(0,0,0,.08);
}

/* IMAGE */
.image{
   width:100%;
   height:230px;
   object-fit:cover;
   border-radius:15px;
}

.name{
   font-size:20px;
   font-weight:700;
   margin:10px 0;
}

.descricao{
   font-size:14px;
   color:#555;
}

.price{
   font-size:22px;
   color:#2563eb;
   font-weight:800;
   margin:10px 0;
}

.qty{
   width:100%;
   padding:10px;
   border-radius:10px;
   border:1px solid #ddd;
   margin:10px 0;
}

.btn{
   width:100%;
   padding:12px;
   background:#2563eb;
   color:#fff;
   border-radius:12px;
   cursor:pointer;
   border:none;
   font-weight:600;
}
.category-container{
   display:grid;
   grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
   gap:25px;
   padding:0 8%;
}

.category-box{
   background:#fff;
   border-radius:20px;
   overflow:hidden;
   box-shadow:0 10px 30px rgba(0,0,0,.08);
   transition:.3s;
}

.category-box:hover{
   transform:translateY(-5px);
}

.category-box img{
   width:100%;
   height:230px;
   object-fit:cover;
}

.cat-content{
   padding:20px;
   text-align:center;
}

.cat-content h3{
   font-size:20px;
   font-weight:700;
   margin-bottom:12px;
}

.cat-content .btn{
   width:100%;
   display:block;
}

/* ===== SELLER ===== */
.seller-card{
   display:flex;
   align-items:center;
   gap:12px;
   padding:12px;
   background:#eff6ff;
   border-radius:15px;
   margin-bottom:12px;
}

.seller-avatar{
   width:45px;
   height:45px;
   border-radius:50%;
   background:#2563eb;
   color:#fff;
   display:flex;
   align-items:center;
   justify-content:center;
   font-weight:800;
}

</style>

</html>