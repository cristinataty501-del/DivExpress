<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

      $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id'") or die('query failed');
      $row = mysqli_fetch_assoc($select_users);
   


if(!isset($admin_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel Administrativo</title>

<!-- FONT AWESOME -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body>

<?php include 'admin_header.php'; ?>

<section class="dashboard">

<h1 class="title">Visão Geral do Painel Administrativo</h1>

<!-- LINHA 1 -->
<div class="row">

   <div class="card pending">

      <div class="icon">
         <i class="fas fa-clock"></i>
      </div>

      <div class="content">

      <?php
         $total_pendings = 0;

         if($row['user_type']=='admin'){

            $q=mysqli_query($conn,"SELECT total_price_admin FROM orders WHERE payment_status='pending'");

            while($f=mysqli_fetch_assoc($q)){
               $total_pendings += $f['total_price_admin'];
            }

         }else{

            $q=mysqli_query($conn,"SELECT total_price_vend FROM orders WHERE payment_status='pending' AND admin_id=$admin_id");

            while($f=mysqli_fetch_assoc($q)){
               $total_pendings += $f['total_price_vend'];
            }

         }
      ?>

      <h3>kz<?php echo $total_pendings; ?></h3>
      <p>Pagamentos Pendentes</p>

      </div>

   </div>

   <div class="card completed">

      <div class="icon">
         <i class="fas fa-check-circle"></i>
      </div>

      <div class="content">

      <?php

         $total_completed = 0;

         if($row['user_type']=='admin'){

            $q=mysqli_query($conn,"SELECT total_price_admin FROM orders WHERE payment_status='completed'");

            while($f=mysqli_fetch_assoc($q)){
               $total_completed += $f['total_price_admin'];
            }

         }else{

            $q=mysqli_query($conn,"SELECT total_price_vend FROM orders WHERE payment_status='completed' AND admin_id=$admin_id");

            while($f=mysqli_fetch_assoc($q)){
               $total_completed += $f['total_price_vend'];
            }

         }

      ?>

      <h3>kz<?php echo $total_completed; ?></h3>
      <p>Pagamentos Concluídos</p>

      </div>

   </div>

</div>

<!-- LINHA 2 -->

<div class="row">

   <div class="card orders">

      <div class="icon">
         <i class="fas fa-shopping-cart"></i>
      </div>

      <div class="content">

      <?php

         if($row['user_type']=='admin'){

            $q=mysqli_query($conn,"SELECT * FROM orders");

         }else{

            $q=mysqli_query($conn,"SELECT * FROM orders WHERE admin_id='$admin_id'");

         }

      ?>

      <h3><?php echo mysqli_num_rows($q); ?></h3>
      <p>Total de Pedidos</p>

      </div>

   </div>

   <div class="card products">

      <div class="icon">
         <i class="fas fa-box"></i>
      </div>

      <div class="content">

      <?php

         if($row['user_type']=='admin'){

            $q=mysqli_query($conn,"SELECT * FROM products");

         }else{

            $q=mysqli_query($conn,"SELECT * FROM products WHERE admin_id='$admin_id'");

         }

      ?>

      <h3><?php echo mysqli_num_rows($q); ?></h3>
      <p>Total de Produtos</p>

      </div>

   </div>

</div>

<!-- LINHA 3 -->

<?php if($row['user_type']=='admin'){ ?>

<div class="row">

   <div class="card users">

      <div class="icon">
         <i class="fas fa-user"></i>
      </div>

      <div class="content">

         <?php
            $q=mysqli_query($conn,"SELECT * FROM users WHERE user_type='user'");
         ?>

         <h3><?php echo mysqli_num_rows($q); ?></h3>
         <p>Usuários</p>

      </div>

   </div>

   <?php if($row['id']==1){ ?>

   <div class="card admins">

      <div class="icon">
         <i class="fas fa-user-shield"></i>
      </div>

      <div class="content">

         <?php
            $q=mysqli_query($conn,"SELECT * FROM users WHERE user_type='admin'");
         ?>

         <h3><?php echo mysqli_num_rows($q); ?></h3>
         <p>Administradores</p>

      </div>

   </div>

   <?php } ?>

   <div class="card accounts">

      <div class="icon">
         <i class="fas fa-users"></i>
      </div>

      <div class="content">

         <?php
            $q=mysqli_query($conn,"SELECT * FROM users");
         ?>

         <h3><?php echo mysqli_num_rows($q); ?></h3>
         <p>Total de Contas</p>

      </div>

   </div>

</div>

<?php } ?>

</section>

<!-- JS -->
<script src="js/admin_script.js"></script>

</body>
</html>

<!-- admin dashboard section ends -->









<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

:root{
   --azul:#2563eb;
   --azul-escuro:#1e3a8a;
   --bg:#f1f5f9;
   --card:#ffffff;
   --texto:#0f172a;
   --cinza:#64748b;
   --border:#e2e8f0;
   --shadow:0 8px 20px rgba(0,0,0,.05);
   --radius:16px;
}

*{
   margin:0;
   padding:0;
   box-sizing:border-box;
   font-family:'Poppins',sans-serif;
}

body{
   background:var(--bg);
}

/* =========================
   DASHBOARD
========================= */

.dashboard{
   width:100%;
   display:flex;
   flex-direction:column;
   align-items:center;
   padding:30px 20px;
}

/* TÍTULO */

.title{
   width:100%;
   max-width:1100px;
   background:#fff;
   padding:18px 22px;
   border-radius:14px;
   border:1px solid var(--border);
   box-shadow:var(--shadow);
   margin-bottom:25px;
   font-size:24px;
   font-weight:700;
   color:var(--texto);
   text-align:center;
   position:relative;
}

.title::before{
   content:'';
   position:absolute;
   top:0;
   left:0;
   width:5px;
   height:100%;
   background:var(--azul);
   border-radius:14px 0 0 14px;
}

/* LINHAS */

.row{
   width:100%;
   max-width:1100px;
   display:grid;
   grid-template-columns:repeat(auto-fit, minmax(250px,1fr));
   gap:20px;
   margin-bottom:20px;
}

/* CARD */

.card{
   background:var(--card);
   border-radius:18px;
   padding:20px;
   border:1px solid var(--border);
   box-shadow:var(--shadow);
   display:flex;
   align-items:center;
   gap:15px;
   transition:.3s ease;
   position:relative;
   overflow:hidden;
}

.card::before{
   content:'';
   position:absolute;
   top:0;
   left:0;
   width:100%;
   height:4px;
   background:linear-gradient(to right,#2563eb,#60a5fa);
}

.card:hover{
   transform:translateY(-5px);
   box-shadow:0 12px 25px rgba(0,0,0,.08);
}

/* ÍCONE */

.icon{
   width:55px;
   height:55px;
   border-radius:14px;
   display:flex;
   align-items:center;
   justify-content:center;
   color:#fff;
   font-size:20px;
   flex-shrink:0;
}

/* CONTEÚDO */

.content{
   width:100%;
}

.content h3{
   font-size:24px;
   font-weight:700;
   color:var(--azul-escuro);
   margin-bottom:4px;
}

.content p{
   font-size:13px;
   color:var(--cinza);
   font-weight:500;
}

/* CORES DOS ÍCONES */

.pending .icon{
   background:#f59e0b;
}

.completed .icon{
   background:#16a34a;
}

.orders .icon{
   background:#2563eb;
}

.products .icon{
   background:#3b82f6;
}

.users .icon{
   background:#6366f1;
}

.admins .icon{
   background:#0ea5e9;
}

.accounts .icon{
   background:#0f172a;
}

/* RESPONSIVO */

@media(max-width:768px){

   .dashboard{
      padding:20px 15px;
   }

   .title{
      font-size:20px;
      padding:15px;
   }

   .row{
      grid-template-columns:1fr;
   }

   .card{
      padding:18px;
   }

   .content h3{
      font-size:20px;
   }

}

</style>
</html>