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
   <title>Pedidos - DivExpress</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>Teus Pedidos</h3>
   <p><a href="home.php">Início</a> / Pedidos</p>
</div>

<section class="placed-orders">

   <h1 class="title">Pedidos Realizados</h1>

   <div class="box-container">

      <?php
         $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');

         if(mysqli_num_rows($order_query) > 0){
            while($fetch_orders = mysqli_fetch_assoc($order_query)){
      ?>

      <div class="box">

         <p> Data: <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
         <p> Nome: <span><?php echo $fetch_orders['name']; ?></span> </p>
         <p> Número: <span><?php echo $fetch_orders['number']; ?></span> </p>
         <p> Email: <span><?php echo $fetch_orders['email']; ?></span> </p>
         <p> Endereço: <span><?php echo $fetch_orders['address']; ?></span> </p>
         <p> Pagamento: <span><?php echo $fetch_orders['method']; ?></span> </p>
         <p> Produtos: <span><?php echo $fetch_orders['total_products']; ?></span> </p>
         <p> Total: <span>kz<?php echo $fetch_orders['total_price']; ?></span> </p>

         <p>
            Estado:
            <span class="<?php echo ($fetch_orders['payment_status'] == 'pending') ? 'pending' : 'completed'; ?>">
               <?php echo $fetch_orders['payment_status']; ?>
            </span>
         </p>
         
      </div>

      <?php
            }
         }else{
            echo '<p class="empty">Nenhum pedido encontrado!</p>';
         }
      ?>

   </div>

</section>

</body>
</html>

<!-- custom js file link  -->
<script src="js/script.js"></script>
<?php include 'footer.php'; ?>
</body>
   <style>
   @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

   :root{
      --primary:#2563eb;
      --dark:#0f172a;
      --gray:#64748b;
      --bg:#f1f5f9;
      --white:#ffffff;
      --success:#22c55e;
      --danger:#ef4444;
      --shadow:0 15px 40px rgba(0,0,0,.08);
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

   /* HEADER TITLE */
   .heading{
      text-align:center;
      padding:70px 20px 30px;
      background:linear-gradient(135deg,var(--primary),#1d4ed8);
      color:#fff;
      border-radius:0 0 35px 35px;
   }

   .heading h3{
      font-size:38px;
      font-weight:800;
   }

   .heading p{
      margin-top:10px;
   }

   .heading a{
      color:#fff;
      font-weight:600;
   }

   /* SECTION */
   .placed-orders{
      padding:60px 8%;
   }

   .title{
      text-align:center;
      font-size:36px;
      font-weight:800;
      margin-bottom:40px;
      color:var(--dark);
   }

   /* GRID */
   .box-container{
      display:grid;
      grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));
      gap:25px;
   }

   /* CARD */
   .box{
      background:var(--white);
      padding:22px;
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      transition:.3s;
      border-left:5px solid var(--primary);
      position:relative;
      overflow:hidden;
   }

   .box:hover{
      transform:translateY(-6px);
   }

   /* DECOR */
   .box::before{
      content:"";
      position:absolute;
      top:-40px;
      right:-40px;
      width:120px;
      height:120px;
      background:rgba(37,99,235,.08);
      border-radius:50%;
   }

   /* TEXT */
   .box p{
      font-size:14px;
      color:var(--gray);
      margin:10px 0;
      line-height:1.6;
   }

   .box p span{
      color:var(--dark);
      font-weight:600;
   }

   /* STATUS STYLE */
   .status{
      font-weight:700;
      padding:4px 10px;
      border-radius:8px;
      display:inline-block;
   }

   .pending{
      color:var(--danger);
   }

   .completed{
      color:var(--success);
   }

   /* EMPTY */
   .empty{
      text-align:center;
      padding:30px;
      background:#fff;
      border-radius:15px;
      box-shadow:var(--shadow);
      color:var(--gray);
      font-size:16px;
      grid-column:1/-1;
   }

   /* ICON STYLE */
   .box p::before{
      content:"•";
      color:var(--primary);
      margin-right:6px;
   }

   /* RESPONSIVE */
   @media(max-width:768px){
      .title{
         font-size:28px;
      }
   }

   </style>