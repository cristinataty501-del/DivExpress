<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
   }

};

?>
<!DOCTYPE html>
<html lang="pt">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pesquisar Produtos</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Pesquisa</h3>
   <p><a href="home.php">Início</a> / Pesquisar</p>
</div>

<section class="search-form">
   <form action="" method="post">
      <input type="text" name="search" placeholder="pesquisar produtos..." class="box">
      <input type="submit" name="submit" value="Pesquisar" class="btn">
   </form>
</section>

<section class="products">

   <div class="box-container">

   <?php
      if(isset($_POST['submit'])){
         $search_item = $_POST['search'];

         $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%{$search_item}%'") or die('falha na consulta');

         if(mysqli_num_rows($select_products) > 0){
            while($fetch_product = mysqli_fetch_assoc($select_products)){
   ?>

   <form action="" method="post" class="box">

      <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" class="image" alt="">

      <div class="name"><?php echo $fetch_product['name']; ?></div>

      <div class="price">kz<?php echo $fetch_product['price']; ?>/-</div>

      <input type="number" class="qty" name="product_quantity" min="1" value="1">

      <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
      <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">

      <input type="submit" class="btn" value="Adicionar ao carrinho" name="add_to_cart">

   </form>

   <?php
            }
         }else{
            echo '<p class="empty">Nenhum resultado encontrado!</p>';
         }
      }else{
         echo '<p class="empty">Pesquise algo!</p>';
      }
   ?>

   </div>

</section>

</body>
</html>
<script src="js/script.js"></script>
<?php include 'footer.php'; ?>
</body>
</html> 
 <style>
   @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

   :root{
      --primary:#2563eb;
      --dark:#0f172a;
      --gray:#64748b;
      --bg:#f1f5f9;
      --white:#fff;
      --shadow:0 15px 35px rgba(0,0,0,.08);
      --radius:18px;
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

   /* HEADER */
   .heading{
      text-align:center;
      padding:70px 20px 35px;
      background:linear-gradient(135deg,var(--primary),#1d4ed8);
      color:#fff;
      border-radius:0 0 35px 35px;
   }

   .heading h3{
      font-size:40px;
      font-weight:800;
   }

   .heading p{
      margin-top:10px;
   }

   .heading a{
      color:#fff;
      font-weight:600;
   }

   /* SEARCH */
   .search-form{
      display:flex;
      justify-content:center;
      padding:50px 20px 20px;
   }

   .search-form form{
      display:flex;
      gap:10px;
      width:100%;
      max-width:650px;
      background:#fff;
      padding:10px;
      border-radius:16px;
      box-shadow:var(--shadow);
   }

   .search-form .box{
      flex:1;
      padding:14px;
      border:none;
      outline:none;
      font-size:15px;
   }

   .search-form .btn{
      padding:12px 22px;
      background:linear-gradient(135deg,var(--primary),#1d4ed8);
      color:#fff;
      border:none;
      border-radius:12px;
      cursor:pointer;
      font-weight:600;
   }

   /* PRODUCTS */
   .products{
      padding:20px 8% 80px;
   }

   .box-container{
      display:grid;
      grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));
      gap:25px;
   }

   /* CARD */
   .box{
      background:#fff;
      border-radius:20px;
      padding:18px;
      box-shadow:var(--shadow);
      transition:.3s;
      border:1px solid #eef2f7;
   }

   .box:hover{
      transform:translateY(-8px);
   }

   /* IMAGE FIX PROFISSIONAL (AQUI ESTÁ A MELHORIA PRINCIPAL) */
   .image{
      width:100%;
      height:230px;
      object-fit:contain; /* evita corte estranho */
      background:#f8fafc; /* fundo neutro estilo loja */
      border-radius:16px;
      padding:15px;
      display:block;
      margin-bottom:15px;
      transition:.3s;
   }

   .box:hover .image{
      transform:scale(1.05);
   }

   /* NAME */
   .name{
      font-size:16px;
      font-weight:700;
      color:var(--dark);
      margin-bottom:6px;
   }

   /* PRICE */
   .price{
      font-size:20px;
      font-weight:800;
      color:var(--primary);
      margin-bottom:10px;
   }

   /* INPUT */
   .qty{
      width:100%;
      padding:10px;
      border-radius:12px;
      border:1px solid #e2e8f0;
      margin:8px 0;
      outline:none;
   }

   /* BUTTON */
   .btn{
      width:100%;
      padding:12px;
      border:none;
      border-radius:12px;
      background:linear-gradient(135deg,var(--primary),#1d4ed8);
      color:#fff;
      font-weight:600;
      cursor:pointer;
      transition:.3s;
   }

   .btn:hover{
      transform:translateY(-3px);
   }

   /* EMPTY */
   .empty{
      text-align:center;
      padding:20px;
      background:#fff;
      border-radius:15px;
      box-shadow:var(--shadow);
      grid-column:1/-1;
      color:var(--gray);
   }

   /* RESPONSIVO */
   @media(max-width:600px){
      .heading h3{
         font-size:28px;
      }

      .search-form form{
         flex-direction:column;
      }

      .image{
         height:200px;
      }
   }

   </style>