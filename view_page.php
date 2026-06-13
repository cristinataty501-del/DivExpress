<?php
include 'config.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* USER */
$user_id = $_SESSION['user_id'] ?? 0;

/* VERIFICAR ID */
if(isset($_GET['pid'])){

   $pid = mysqli_real_escape_string($conn, $_GET['pid']);

}else{
   header('location:home.php');
   exit();
}

/* ADICIONAR AO CARRINHO */
if(isset($_POST['add_to_cart'])){

   if($user_id == 0){
      header('location:login.php');
      exit();
   }

   $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
   $product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
   $product_image = mysqli_real_escape_string($conn, $_POST['product_image']);

   $product_quantity = 1;

   /* VERIFICAR SE JA EXISTE */
   $check_cart_numbers = mysqli_query($conn,
   "SELECT * FROM `cart`
   WHERE name = '$product_name'
   AND user_id = '$user_id'")
   or die(mysqli_error($conn));

   if(mysqli_num_rows($check_cart_numbers) > 0){

      $_SESSION['message'] = 'Produto já adicionado ao carrinho!';

   }else{

      mysqli_query($conn,
      "INSERT INTO `cart`
      (user_id, name, price, quantity, image)
      VALUES
      ('$user_id','$product_name','$product_price','$product_quantity','$product_image')")
      or die(mysqli_error($conn));

      $_SESSION['message'] = 'Produto adicionado ao carrinho!';
   }

   header("Location:view_page.php?pid=$pid");
   exit();
}

/* BUSCAR PRODUTO */
$select_product = mysqli_query($conn,
"SELECT * FROM `products` WHERE id = '$pid'")
or die(mysqli_error($conn));

if(mysqli_num_rows($select_product) > 0){

   $fetch_product = mysqli_fetch_assoc($select_product);

}else{
   header('location:home.php');
   exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $fetch_product['name']; ?></title>

<!-- FONT AWESOME -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

*{
   margin:0;
   padding:0;
   box-sizing:border-box;
   font-family:'Poppins', sans-serif;
   text-decoration:none;
}

body{
   background:#f4f6f9;
}

/* ALERTA */
.message{
   position:fixed;
   top:20px;
   right:20px;
   background:#27ae60;
   color:#fff;
   padding:15px 20px;
   border-radius:12px;
   z-index:1000;
   display:flex;
   gap:10px;
   align-items:center;
   box-shadow:0 10px 25px rgba(0,0,0,.15);
}

/* HEADER */
.header{
   width:100%;
   background:linear-gradient(135deg,#1e3c72,#2a5298);
   padding:25px 8%;
   display:flex;
   justify-content:space-between;
   align-items:center;
}

.logo{
   font-size:30px;
   font-weight:700;
   color:#fff;
}

.logo span{ color:#00d2ff; }

.back-btn{
   background:#fff;
   color:#1e3c72;
   padding:10px 22px;
   border-radius:25px;
   font-weight:600;
   transition:.3s;
}

.back-btn:hover{
   transform:scale(1.05);
}

/* CONTAINER */
.container{
   width:90%;
   max-width:1200px;
   margin:40px auto;
}

/* CARD PRINCIPAL */
.product-view{
   display:grid;
   grid-template-columns:1.1fr 1fr;
   gap:40px;
   background:#fff;
   padding:40px;
   border-radius:20px;
   box-shadow:0 10px 30px rgba(0,0,0,.08);
}

/* IMAGEM */
.image-box img{
   width:100%;
   height:520px;
   object-fit:cover;
   border-radius:15px;
   transition:.3s;
}

.image-box img:hover{
   transform:scale(1.03);
}

/* INFO */
.info-box{
   display:flex;
   flex-direction:column;
}

/* CATEGORIA */
.category{
   background:#eaf2ff;
   color:#2a5298;
   padding:6px 14px;
   border-radius:20px;
   width:max-content;
   font-size:13px;
   margin-bottom:12px;
}

/* TITULO */
.product-name{
   font-size:34px;
   color:#222;
   margin-bottom:10px;
}

/* PREÇO */
.price{
   font-size:30px;
   color:#1e3c72;
   font-weight:700;
   margin-bottom:20px;
}

/* DESCRIÇÃO */
.description{
   background:#f8f9fb;
   padding:18px;
   border-radius:12px;
   margin-bottom:20px;
}

.description h3{
   color:#1e3c72;
   margin-bottom:10px;
}

.description p{
   color:#555;
   line-height:1.7;
}

/* DETALHES */
.details{
   display:flex;
   gap:15px;
   margin-bottom:20px;
}

.detail-box{
   flex:1;
   background:#f7f9fc;
   padding:15px;
   border-radius:12px;
   text-align:center;
}

.detail-box i{
   font-size:22px;
   color:#2a5298;
   margin-bottom:8px;
}

/* VENDEDOR (NOVO) */
.seller-box{
   background:#f8f9fb;
   padding:18px;
   border-radius:12px;
   margin-bottom:20px;
   border-left:4px solid #2a5298;
}

.seller-box h3{
   color:#1e3c72;
   margin-bottom:10px;
}

/* BOTÃO */
.btn{
   padding:14px 30px;
   border-radius:30px;
   font-weight:600;
   cursor:pointer;
   border:none;
   transition:.3s;
}

.cart-btn{
   background:#27ae60;
   color:#fff;
}

.cart-btn:hover{
   background:#219150;
   transform:translateY(-2px);
}

/* RESPONSIVO */
@media(max-width:900px){
   .product-view{
      grid-template-columns:1fr;
   }

   .image-box img{
      height:380px;
   }
}

</style>
</head>
<body>

<!-- MENSAGEM -->
<?php
if(isset($_SESSION['message'])){

   echo '
   <div class="message" id="message-box">

      <span>'.$_SESSION['message'].'</span>

      <i class="fas fa-times close-msg"
      onclick="closeMessage()"></i>

   </div>
   ';

   unset($_SESSION['message']);
}
?>

<!-- HEADER -->
<header class="header">

   <div class="logo">
      Div<span>Express</span>
   </div>

   <a href="javascript:history.back()" class="back-btn">
      <i class="fas fa-arrow-left"></i> Voltar
   </a>

</header>

<!-- CONTAINER -->
<section class="container">

   <div class="product-view">

      <!-- IMAGEM -->
      <div class="image-box">

         <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="">

      </div>

      <!-- INFO -->
      <div class="info-box">

         <span class="category">
            <?php echo $fetch_product['category']; ?>
         </span>

         <h1 class="product-name">
            <?php echo $fetch_product['name']; ?>
         </h1>

         <div class="price">
            <?php echo $fetch_product['price']; ?> kz
         </div>

         <!-- DESCRIÇÃO -->
         <div class="description">

            <h3>
            <h3><i class="fas fa-store"></i> Vendedor</h3>
               
            </h3>
<!-- VENDEDOR -->
<div class="seller-box">

   <?php
      $seller_id = $fetch_product['admin_id'];

      $seller = mysqli_fetch_assoc(
         mysqli_query($conn, "SELECT * FROM users WHERE id='$seller_id'")
      );
   ?>

   <p><strong>Nome:</strong> <?php echo $seller['name']; ?></p>
   <p><strong>Email:</strong> <?php echo $seller['email']; ?></p>


   <?php if(!empty($seller['iban'])): ?>
      <p><strong>IBAN:</strong> <?php echo $seller['iban']; ?></p>
   <?php endif; ?>

</div>
            <p>

            <?php

            if(!empty($fetch_product['descricao'])){

               echo nl2br($fetch_product['descricao']);

            }elseif(!empty($fetch_product['descricao'])){

               echo nl2br($fetch_product['descricao']);

            }else{

               echo 'Este produto ainda não possui descrição disponível.';

            }

            ?>

            </p>

         </div>

         <!-- DETALHES -->
         <div class="details">

            <div class="detail-box">
               <i class="fas fa-truck"></i>
               <h4>Entrega</h4>
               <p>Rápida e segura</p>
            </div>

            <div class="detail-box">
               <i class="fas fa-shield-alt"></i>
               <h4>Garantia</h4>
               <p>Produto protegido</p>
            </div>

            <div class="detail-box">
               <i class="fas fa-headset"></i>
               <h4>Suporte</h4>
               <p>24/7 Atendimento</p>
            </div>

         </div>

         <!-- FORM CARRINHO -->
         <form method="POST">

            <input type="hidden" name="product_name"
            value="<?php echo $fetch_product['name']; ?>">

            <input type="hidden" name="product_price"
            value="<?php echo $fetch_product['price']; ?>">

            <input type="hidden" name="product_image"
            value="<?php echo $fetch_product['image']; ?>">

            <div class="buttons">

               <button type="submit" name="add_to_cart" class="btn cart-btn">
                  <i class="fas fa-shopping-cart"></i>
                  Adicionar ao Carrinho
               </button>

            </div>

         </form>

      </div>

   </div>

</section>

<script>

function closeMessage(){

   document.getElementById('message-box').style.display = 'none';

}

/* FECHAR AUTOMATICAMENTE */
setTimeout(() => {

   let msg = document.getElementById('message-box');

   if(msg){
      msg.style.display = 'none';
   }

}, 4000);

</script>

</body>
</html>