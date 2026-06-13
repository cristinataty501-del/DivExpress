<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_cart'])){

   $admin_id = $_POST['admin_id'];
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, admin_id, name, price, quantity, image) VALUES('$user_id', '$admin_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
   }

}

?>
<!DOCTYPE html>
<html lang="pt">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shop - DivExpress</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  
</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>Sua Loja</h3>
   <p><a href="home.php">Início</a> / Loja</p>
</div>

<section class="products">

   <h1 class="title">Produtos Recentes</h1>

   <div class="box-container">

      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');

         if(mysqli_num_rows($select_products) > 0){

            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>

      <form action="" method="post" class="box">

         <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">

         <div class="name">
            <?php echo $fetch_products['name']; ?>
         </div>

         <div class="price">
            KZ<?php echo $fetch_products['price']; ?>
         </div>

         
         <input type="number" min="1" max="<?= $fetch_products['quantity'];?>"  value="1" name="product_quantity" class="qty">  
         <input type="hidden" name="admin_id" value="<?php echo $fetch_products['admin_id']; ?>">
         <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">

         <input type="submit" value="Adicionar ao carrinho" name="add_to_cart" class="btn">

      </form>

      <?php
            }
         }else{
            echo '<p class="empty">Nenhum produto encontrado!</p>';
         }
      ?>

   </div>

</section>
<?php include 'footer.php'; ?>
</body> 
<style>
   @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

   :root{
      --primary:#2563eb;
      --dark:#0f172a;
      --gray:#64748b;
      --light:#f8fafc;
      --white:#fff;
      --shadow:0 15px 40px rgba(0,0,0,.08);
      --radius:18px;
   }

   *{
      margin:0;
      padding:0;
      box-sizing:border-box;
      font-family:'Poppins', sans-serif;
      text-decoration:none;
   }

   body{
      background:var(--light);
      color:var(--dark);
   }

   /* HEADER AREA */
   .heading{
      text-align:center;
      padding:70px 20px 30px;
      background:linear-gradient(135deg,var(--primary),#1d4ed8);
      color:#fff;
      border-radius:0 0 30px 30px;
   }

   .heading h3{
      font-size:38px;
      font-weight:800;
   }

   .heading p{
      margin-top:10px;
      font-size:15px;
   }

   .heading a{
      color:#fff;
      font-weight:600;
   }

   /* SECTION */
   .products{
      padding:70px 8%;
   }

   .title{
      text-align:center;
      font-size:40px;
      margin-bottom:50px;
      font-weight:800;
   }

   /* GRID */
   .box-container{
      display:grid;
      grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));
      gap:25px;
   }

   /* CARD */
   .box{
      background:var(--white);
      border-radius:var(--radius);
      padding:15px;
      box-shadow:var(--shadow);
      transition:.3s;
      text-align:center;
   }

   .box:hover{
      transform:translateY(-6px);
   }

   .image{
      width:100%;
      height:230px;
      object-fit:cover;
      border-radius:14px;
      margin-bottom:15px;
   }

   .name{
      font-size:18px;
      font-weight:600;
      margin-bottom:8px;
   }

   .price{
      font-size:20px;
      color:var(--primary);
      font-weight:700;
      margin-bottom:15px;
   }

   /* INPUT */
   .qty{
      width:100%;
      padding:10px;
      border-radius:10px;
      border:1px solid #e5e7eb;
      margin-bottom:10px;
      font-size:14px;
   }

   /* BUTTON */
   .btn{
      width:100%;
      padding:12px;
      border:none;
      border-radius:12px;
      background:var(--primary);
      color:#fff;
      font-weight:600;
      cursor:pointer;
      transition:.3s;
   }

   .btn:hover{
      background:#1d4ed8;
   }

   /* EMPTY */
   .empty{
      text-align:center;
      font-size:18px;
      color:var(--gray);
      grid-column:1/-1;
   }

   /* RESPONSIVE */
   @media(max-width:768px){
      .heading h3{
         font-size:30px;
      }

      .title{
         font-size:30px;
      }
   }
   </style>

</html>
<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>