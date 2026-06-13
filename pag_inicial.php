<?php

include 'config.php';



if(isset($_POST['add_to_cart'])){
   
   header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>DivExpress</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

   
</head>
<body>

<header class="header">

   <div class="header-1">
      <p><a href="login.php">login</a> | <a href="register.php">cadastro</a></p>
   </div>

   <div class="header-2">
      <a href="home.php" class="logo">DivExpress.</a>

      <nav class="navbar">
         <a href="#inicio">Início</a>
         <a href="#sobre">Sobre</a>
         <a href="#prod">Loja</a>
         <a href="#contacto">Contactos</a>
         <a href="#categ">Categoria</a>
      </nav>
   </div>

</header>

<section id="inicio" class="home">
   <div class="content">
      <h3>Bem-vindo à DivExpress</h3>
      <p>Plataforma moderna de vendas online com segurança e rapidez.</p>
      <a href="about.php" class="white-btn">Ver mais</a>
   </div>
</section>

<section id="categ" class="Categoria">

   <h1 class="title">Categorias</h1>

   <div class="category-container">

      <div class="category-box">
         <img src="images/electronico.jpg" alt="">
         <h3>Electronicos</h3>
         <a href="categoria.php?cat=electronico" class="btn">Ver Produtos</a>
      </div>

      <div class="category-box">
         <img src="images/roupa.jpg" alt="">
         <h3>Roupas</h3>
         <a href="categoria.php?cat=roupas" class="btn">Ver Produtos</a>
      </div>

      <div class="category-box">
         <img src="images/calcados.jpg" alt="">
         <h3>Calçados</h3>
         <a href="categoria.php?cat=calcados" class="btn">Ver Produtos</a>
      </div>

      <div class="category-box">
         <img src="images/acessorios.jpg" alt="">
         <h3>Acessórios</h3>
         <a href="categoria.php?cat=acessorios" class="btn">Ver Produtos</a>
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
   

</section>

<section id="prod" class="products">

   <h1 class="title">Produtos recentes</h1>

   <div class="box-container">

      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 10") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>

      <form action="" method="post" class="box">
         <img src="uploaded_img/<?php echo $fetch_products['image']; ?>">
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <div class = "descricao"><?php echo $fetch_products['descricao'];?></div>
         <div class="price">KZ<?php echo $fetch_products['price']; ?></div>

         <input type="number" min="1" max="<?= $fetch_products['quantity'];?>"  value="1" name="product_quantity" class="qty">
         <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">

         <input type="submit" value="Adicionar ao Carrinho" name="add_to_cart" class="btn">
      </form>

      <?php }} else { echo '<p class="empty">Nenhum produto encontrado</p>'; } ?>

   </div>

</section>

<section id="sobre" class="about">

   <img src="images/ChatGPT Image 24_04_2026, 16_15_02.png">

   <div class="content">
      <h3>Sobre Nós</h3>
      <p>Somos uma plataforma moderna de vendas online criada para facilitar compras digitais.</p>
   </div>

</section>

<section id="contacto" class="home-contact">
   <h3>Tens uma questão?</h3>
   <p>Contacta-nos para suporte e informações.</p>
   <a href="#rod" class="white-btn">Contactar</a>
</section>


</body>
</html>




<?php include 'footer.php'; ?>
<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
<style>
   @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

   :root{
      --primary:#2563eb;
      --primary-light:#3b82f6;
      --dark:#0f172a;
      --gray:#64748b;
      --light:#f8fafc;
      --white:#ffffff;
      --border:#dbeafe;
      --shadow:0 15px 40px rgba(37,99,235,.10);
      --radius:24px;
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

   /* HEADER */
   .header{
      position:sticky;
      top:0;
      z-index:1000;
      background:#fff;
      box-shadow:var(--shadow);
   }

   .header-1{
      background:var(--dark);
      padding:10px 8%;
      color:#fff;
      display:flex;
      justify-content:space-between;
   }

   .header-1 a{
      color:#fff;
      margin:0 5px;
   }

   .header-2{
      padding:20px 8%;
      display:flex;
      justify-content:space-between;
      align-items:center;
   }

   .logo{
      font-size:28px;
      font-weight:800;
      color:var(--primary);
   }

   .navbar a{
      margin:0 15px;
      color:var(--dark);
      font-weight:500;
   }

   .navbar a:hover{
      color:var(--primary);
   }

   /* HOME */




   .home{
      min-height:90vh;
      display:flex;
      align-items:center;
      justify-content:center;
      text-align:center;
      background:linear-gradient(rgba(0,0,0,.7),rgba(37,99,235,.7)),url('images/inicio.jpg') center/cover;
      padding:40px;
   }

   .home .content h3{
      font-size:48px;
      color:#fff;
      font-weight:800;
   }

   .home .content p{
      color:#dbeafe;
      margin:20px 0;
      font-size:16px;
   }

   /* BUTTONS */
   .btn,.white-btn,.option-btn{
      padding:12px 28px;
      border-radius:12px;
      font-weight:600;
      display:inline-block;
      transition:.3s;
   }

   .btn{
      background:var(--primary);
      color:#fff;
   }

   .btn:hover{
      transform:translateY(-3px);
   }

   .white-btn{
      background:#fff;
      color:var(--primary);
   }

   .white-btn:hover{
      background:var(--primary);
      color:#fff;
   }

   /* PRODUCTS */
   .products{
      padding:80px 8%;
      background:#fff;
   }

   .title{
      text-align:center;
      font-size:36px;
      margin-bottom:40px;
      font-weight:800;
   }

   .box-container{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
      gap:25px;
   }

   .box{
      background:#fff;
      padding:15px;
      border-radius:18px;
      box-shadow:var(--shadow);
      text-align:center;
   }

   .box img{
      width:100%;
      height:220px;
      object-fit:cover;
      border-radius:15px;
   }

   .name{
      font-size:18px;
      margin:10px 0;
      font-weight:600;
   }

   .price{
      color:var(--primary);
      font-size:20px;
      font-weight:700;
      margin-bottom:10px;
   }

   .qty{
      width:100%;
      padding:10px;
      border:1px solid #ddd;
      border-radius:10px;
      margin-bottom:10px;
   }

   /* ABOUT */
   .about{
      padding:80px 8%;
      display:flex;
      gap:40px;
      align-items:center;
   }

   .about img{
      width:100%;
      border-radius:20px;
   }

   .about .content h3{
      font-size:32px;
      margin-bottom:10px;
   }

   .about .content p{
      color:var(--gray);
      line-height:1.7;
   }

   /* CONTACT */
   .home-contact{
      background:linear-gradient(135deg,var(--primary),var(--primary-light));
      text-align:center;
      padding:80px 8%;
      color:#fff;
   }

   .home-contact h3{
      font-size:36px;
      margin-bottom:10px;
   }

   .empty{
      text-align:center;
      padding:20px;
      color:var(--gray);
   }

   /* CATEGORIAS */
.Categoria{
   padding:80px 8%;
   background:var(--light);
}

.category-container{
   display:grid;
   grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
   gap:25px;
}

.category-box{
   background:#fff;
   border-radius:20px;
   overflow:hidden;
   box-shadow:var(--shadow);
   text-align:center;
   transition:.3s;
}

.category-box:hover{
   transform:translateY(-8px);
}

.category-box img{
   width:100%;
   height:220px;
   object-fit:cover;
}

.category-box h3{
   font-size:22px;
   margin:15px 0;
   color:var(--dark);
}

.category-box .btn{
   margin-bottom:20px;
}
   </style>

</html>