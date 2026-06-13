<?php
include 'config.php';
session_start();

/* RECEBER CATEGORIA */
$cat = $_GET['cat'];

/* TITULOS DAS CATEGORIAS */
$titulos = [
   'electronico' => 'Produtos Electrónicos',
   'roupas' => 'Moda & Roupas',
   'calcados' => 'Calçados',
   'acessorios' => 'Acessórios',
   'mobilias' => 'Mobilias',
   'veiculos' => 'Veiculos',
];

/* DEFINIR TITULO */
$titulo = isset($titulos[$cat]) ? $titulos[$cat] : 'Categorias';
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $titulo; ?></title>

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

/* HEADER */
.header{
   background:linear-gradient(135deg,#1e3c72,#2a5298);
   color:#fff;
   text-align:center;
   padding:60px 20px;
}

.header h1{
   font-size:42px;
   margin-bottom:10px;
}

.header p{
   font-size:18px;
   opacity:.9;
}

/* BOTAO VOLTAR */
.back-btn{
   display:inline-block;
   margin-top:20px;
   padding:12px 25px;
   background:#fff;
   color:#1e3c72;
   border-radius:30px;
   font-weight:bold;
   transition:.3s;
}

.back-btn:hover{
   background:#dfe6e9;
}

/* CONTAINER */
.container{
   width:90%;
   max-width:1300px;
   margin:50px auto;
}

/* GRID */
.grid{
   display:grid;
   grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
   gap:30px;
}

/* CARD */
.card{
   background:#fff;
   border-radius:20px;
   overflow:hidden;
   box-shadow:0 8px 20px rgba(0,0,0,.08);
   transition:.4s;
   position:relative;
}

.card:hover{
   transform:translateY(-10px);
   box-shadow:0 15px 35px rgba(0,0,0,.15);
}

.card img{
   width:100%;
   height:260px;
   object-fit:cover;
   transition:.4s;
}

.card:hover img{
   transform:scale(1.05);
}

/* CONTEUDO */
.content{
   padding:25px;
   text-align:center;
}

.content h3{
   font-size:24px;
   color:#222;
   margin-bottom:12px;
}

.price{
   font-size:24px;
   color:#2a5298;
   font-weight:700;
   margin-bottom:20px;
}

/* BOTOES */
.btn-group{
   display:flex;
   justify-content:center;
   gap:10px;
   flex-wrap:wrap;
}

.btn{
   display:inline-block;
   padding:12px 25px;
   background:linear-gradient(135deg,#1e3c72,#2a5298);
   color:#fff;
   border-radius:30px;
   transition:.3s;
   font-size:15px;
}

.btn:hover{
   transform:scale(1.05);
}

.view-btn{
   background:linear-gradient(135deg,#27ae60,#2ecc71);
}

/* MENSAGEM */
.empty{
   width:100%;
   text-align:center;
   padding:60px;
   background:#fff;
   border-radius:15px;
   font-size:22px;
   color:#777;
}

/* RESPONSIVO */
@media(max-width:768px){

   .header h1{
      font-size:32px;
   }

   .grid{
      grid-template-columns:1fr;
   }

}

</style>
</head>
<body>

<!-- HEADER -->
<section class="header">

   <h1><?php echo $titulo; ?></h1>

   <p>Explore os melhores produtos disponíveis nesta categoria</p>

   <a href="home.php" class="back-btn">
      <i class="fas fa-arrow-left"></i> Voltar
   </a>

</section>

<!-- PRODUTOS -->
<div class="container">

   <div class="grid">

      <?php

      /* BUSCAR PRODUTOS */
      $select_products = mysqli_query($conn,
      "SELECT * FROM products WHERE category='$cat'")
      or die('Erro na consulta!');

      /* VERIFICAR PRODUTOS */
      if(mysqli_num_rows($select_products) > 0){

         while($fetch_products = mysqli_fetch_assoc($select_products)){

      ?>

      <!-- CARD -->
      <div class="card">

         <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">

         <div class="content">

            <h3>
               <?php echo $fetch_products['name']; ?>
            </h3>

            <div class="price">
               <?php echo $fetch_products['price']; ?> kz
            </div>

            <div class="btn-group">

               <!-- VER PRODUTO -->
               <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="btn view-btn">
                  <i class="fas fa-eye"></i> Ver Produto
               </a>

            </div>

         </div>

      </div>

      <?php
         }

      }else{

         echo '<p class="empty">Nenhum produto encontrado nesta categoria.</p>';

      }
      ?>

   </div>

</div>

</body>
</html>