<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

$select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id'") or die('query failed');
      $row = mysqli_fetch_assoc($select_users);

if(isset($_POST['update_order'])){

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
   $message[] = 'payment status has been updated!';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_orders.php');
}

?>
<!DOCTYPE html>
<html lang="pt">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pedidos</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- CSS ADMIN -->
   <link rel="stylesheet" href="css/admin_style.css">


</head>

<body>

<?php include 'admin_header.php'; ?>

<?php if ($row['user_type'] == 'admin') { ?>

<section class="orders">

   <h1 class="title">Pedidos Realizados</h1>

   <div class="box-container">

      <?php

      $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');

      if(mysqli_num_rows($select_orders) > 0){

         while($fetch_orders = mysqli_fetch_assoc($select_orders)){

      ?>

      <div class="box">

         <p>ID do Usuário <span><?php echo $fetch_orders['user_id']; ?></span></p>

         <p>Data do Pedido <span><?php echo $fetch_orders['placed_on']; ?></span></p>

         <p>Nome do Cliente <span><?php echo $fetch_orders['name']; ?></span></p>

         <p>Telefone <span><?php echo $fetch_orders['number']; ?></span></p>

         <p>Email <span><?php echo $fetch_orders['email']; ?></span></p>

         <p>Endereço <span><?php echo $fetch_orders['address']; ?></span></p>

         <p>Total de Produtos <span><?php echo $fetch_orders['total_products']; ?></span></p>

         <p>Preço Total <span>kz<?php echo $fetch_orders['total_price']; ?></span></p>

         <p>Método de Pagamento <span><?php echo $fetch_orders['method']; ?></span></p>


      </div>

      <?php
         }
      }else{
         echo '<p class="empty">Nenhum pedido realizado ainda!</p>';
      }
      ?>

   </div>

</section>

<?php } else { ?>

<section class="orders">

   <h1 class="title">Meus Pedidos</h1>

   <div class="box-container">

      <?php

      $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE admin_id = '$admin_id' ") or die('query failed');

      if(mysqli_num_rows($select_orders) > 0){

         while($fetch_orders = mysqli_fetch_assoc($select_orders)){

      ?>

      <div class="box">

         <p>ID do Usuário <span><?php echo $fetch_orders['user_id']; ?></span></p>

         <p>Data do Pedido <span><?php echo $fetch_orders['placed_on']; ?></span></p>

         <p>Nome do Cliente <span><?php echo $fetch_orders['name']; ?></span></p>

         <p>Telefone <span><?php echo $fetch_orders['number']; ?></span></p>

         <p>Email <span><?php echo $fetch_orders['email']; ?></span></p>

         <p>Endereço <span><?php echo $fetch_orders['address']; ?></span></p>

         <p>Total de Produtos <span><?php echo $fetch_orders['total_products']; ?></span></p>

         <p>Preço Total <span>kz<?php echo $fetch_orders['total_price']; ?></span></p>

         <p>Método de Pagamento <span><?php echo $fetch_orders['method']; ?></span></p>

         
         <form action="" method="post">

            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">

            <select name="update_payment">
               <option value="pending">Pendente</option>
               <option value="completed">Concluído</option>
            </select>

            <input type="submit" value="Atualizar Pedido" name="update_order" class="option-btn">


            <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Deseja eliminar este pedido?');" class="delete-btn">
               Eliminar Pedido
            </a>  
            <input type="file" name="comprovativo">
         </form>

      </div>

      <?php
         }
      }else{
         echo '<p class="empty">Nenhum pedido realizado ainda!</p>';
      }
      ?>

   </div>

</section>

<?php } ?>

</body>
</html>

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
   --borda:#e2e8f0;
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
   PEDIDOS
========================= */

.orders{
   width:100%;
   display:flex;
   flex-direction:column;
   align-items:center;
   padding:25px 20px;
}

/* TÍTULO */

.orders .title{
   width:100%;
   max-width:1100px;
   background:#fff;
   padding:18px 22px;
   border-radius:14px;
   border:1px solid var(--borda);
   box-shadow:0 5px 15px rgba(0,0,0,.05);
   margin-bottom:25px;
   font-size:24px;
   font-weight:700;
   color:var(--azul-escuro);
   text-align:center;
   position:relative;
}

.orders .title::before{
   content:'';
   position:absolute;
   left:0;
   top:0;
   width:5px;
   height:100%;
   background:var(--azul);
   border-radius:14px 0 0 14px;
}

/* CONTAINER */

.orders .box-container{
   width:100%;
   max-width:1100px;
   display:grid;
   grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));
   gap:20px;
}

/* CARD */

.orders .box-container .box{
   background:#fff;
   border-radius:18px;
   padding:20px;
   border:1px solid var(--borda);
   box-shadow:0 6px 18px rgba(0,0,0,.05);
   transition:.3s;
   position:relative;
}

.orders .box-container .box::before{
   content:'';
   position:absolute;
   top:0;
   left:0;
   width:100%;
   height:4px;
   background:linear-gradient(to right,#2563eb,#60a5fa);
   border-radius:18px 18px 0 0;
}

.orders .box-container .box:hover{
   transform:translateY(-4px);
   box-shadow:0 12px 22px rgba(0,0,0,.08);
}

/* TEXTOS */

.orders .box-container .box p{
   display:flex;
   justify-content:space-between;
   align-items:center;
   gap:10px;
   padding:10px 0;
   border-bottom:1px solid #f1f5f9;
   font-size:13px;
   color:#475569;
   font-weight:500;
}

.orders .box-container .box p:last-child{
   border-bottom:none;
}

.orders .box-container .box p span{
   color:var(--texto);
   font-weight:600;
   text-align:right;
   max-width:58%;
   word-break:break-word;
}

/* FORM */

.orders .box-container .box form{
   margin-top:18px;
   display:flex;
   flex-direction:column;
   gap:12px;
}

/* SELECT */

.orders .box-container .box form select{
   width:100%;
   height:45px;
   border-radius:12px;
   border:1px solid #cbd5e1;
   padding:0 12px;
   font-size:14px;
   outline:none;
}

.orders .box-container .box form select:focus{
   border-color:#2563eb;
   box-shadow:0 0 0 3px rgba(37,99,235,.08);
}

/* BOTÕES */

.option-btn,
.delete-btn{
   width:100%;
   height:45px;
   border:none;
   border-radius:12px;
   display:flex;
   align-items:center;
   justify-content:center;
   text-decoration:none;
   font-size:14px;
   font-weight:600;
   cursor:pointer;
   transition:.2s;
}

/* BOTÃO ATUALIZAR */

.option-btn{
   background:#2563eb;
   color:#fff;
}

.option-btn:hover{
   background:#1d4ed8;
}

/* BOTÃO ELIMINAR */

.delete-btn{
   background:#ef4444;
   color:#fff;
}

.delete-btn:hover{
   background:#dc2626;
}

/* SEM PEDIDOS */

.empty{
   width:100%;
   max-width:600px;
   background:#fff;
   padding:25px;
   border-radius:14px;
   text-align:center;
   font-size:15px;
   font-weight:600;
   color:var(--cinza);
   border:1px dashed #cbd5e1;
}

/* RESPONSIVO */

@media(max-width:768px){

   .orders{
      padding:20px 15px;
   }

   .orders .title{
      font-size:20px;
      padding:15px;
   }

   .orders .box-container{
      grid-template-columns:1fr;
   }

   .orders .box-container .box{
      padding:18px;
   }

   .orders .box-container .box p{
      flex-direction:column;
      align-items:flex-start;
      font-size:13px;
   }

   .orders .box-container .box p span{
      max-width:100%;
      text-align:left;
   }

}

</style>
</html>








<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>