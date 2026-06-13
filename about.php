<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sobre Nós - DivExpress</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>Sobre Nós</h3>
   <p><a href="home.php">Início</a> / Sobre</p>
</div>

<section class="about">

   <div class="flex">

      <div class="image">
      <img src="images/ChatGPT Image 24_04_2026, 16_15_02.png" alt="">
      </div>

      <div class="content">
         <h3>Por que escolher-nos?</h3>

         <p>
            A DivExpress é uma plataforma moderna de vendas online que conecta vendedores e clientes de forma rápida e segura.
         </p>

         <p>
            O nosso objetivo é facilitar a divulgação de produtos e proporcionar uma experiência de compra simples, eficiente e profissional.
         </p>

         <a href="contact.php" class="btn">Contactar</a>
      </div>

   </div>

</section>


</body>
</html>


<!-- custom js file link  -->
<script src="js/script.js"></script>
<?php include 'footer.php'; ?>
</body>
   <style>
   @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

   :root{
      --primary:#2563eb;
      --dark:#0f172a;
      --gray:#64748b;
      --light:#f8fafc;
      --white:#fff;
      --shadow:0 15px 40px rgba(0,0,0,.08);
      --radius:20px;
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

   /* HEADER TITLE */
   .heading{
      text-align:center;
      padding:80px 20px 40px;
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

   /* ABOUT */
   .about{
      padding:80px 8%;
   }

   .about .flex{
      display:flex;
      gap:50px;
      align-items:center;
      flex-wrap:wrap;
   }

   .about .image{
      flex:1 1 400px;
   }

   .about .image img{
      width:100%;
      border-radius:25px;
      box-shadow:var(--shadow);
   }

   .about .content{
      flex:1 1 400px;
   }

   .about .content h3{
      font-size:34px;
      font-weight:800;
      margin-bottom:15px;
   }

   .about .content p{
      color:var(--gray);
      line-height:1.8;
      margin-bottom:15px;
      font-size:15px;
   }

   .btn{
      display:inline-block;
      padding:12px 25px;
      background:var(--primary);
      color:#fff;
      border-radius:12px;
      font-weight:600;
      transition:.3s;
   }

   .btn:hover{
      transform:translateY(-3px);
   }

   /* SECTIONS */
   .title{
      text-align:center;
      font-size:36px;
      font-weight:800;
      margin-bottom:40px;
   }

   /* REVIEWS */
   .reviews{
      padding:80px 8%;
      background:#fff;
   }

   .reviews .box-container{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
      gap:25px;
   }

   .reviews .box{
      background:#fff;
      padding:20px;
      border-radius:18px;
      box-shadow:var(--shadow);
      text-align:center;
      transition:.3s;
   }

   .reviews .box:hover{
      transform:translateY(-6px);
   }

   .reviews img{
      width:70px;
      height:70px;
      border-radius:50%;
      margin-bottom:10px;
   }

   .stars i{
      color:#fbbf24;
   }

   .reviews h3{
      margin-top:10px;
      font-size:16px;
   }

   /* AUTHORS */
   .authors{
      padding:80px 8%;
      background:var(--light);
   }

   .authors .box-container{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
      gap:25px;
   }

   .authors .box{
      background:#fff;
      padding:20px;
      border-radius:18px;
      box-shadow:var(--shadow);
      text-align:center;
      transition:.3s;
   }

   .authors .box:hover{
      transform:translateY(-6px);
   }

   .authors img{
      width:100%;
      height:220px;
      object-fit:cover;
      border-radius:15px;
      margin-bottom:15px;
   }

   .share a{
      display:inline-block;
      margin:5px;
      color:var(--primary);
      font-size:16px;
   }

   .share a:hover{
      color:#1d4ed8;
   }

   .authors h3{
      margin-top:10px;
   }

   /* RESPONSIVE */
   @media(max-width:768px){
      .heading h3{
         font-size:30px;
      }

      .about .content h3{
         font-size:26px;
      }
   }
   </style>
</html>