<?php
// NO HTML OR WHITESPACE BEFORE THIS LINE
if(!isset($_SESSION)) {
    session_start();
}

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
   header('location:login.php');
   exit();
}

$admin_id = $_SESSION['admin_id'];

// Get admin info
$select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id'") or die('falha na consulta');   
if(mysqli_num_rows($select_users) > 0){
   $row = mysqli_fetch_assoc($select_users);
} else {
   header('location:login.php');
   exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel do Administrador</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
/* Your CSS styles here - make sure no HTML before PHP tags */
:root{
   --blue1:#0f172a;
   --blue2:#1d4ed8;
   --blue3:#2563eb;
   --blue4:#3b82f6;
   --bg:#f1f5f9;
   --white:#fff;
   --text:#cbd5e1;
   --shadow:0 20px 50px rgba(0,0,0,.12);
}

*{
   margin:0;
   padding:0;
   box-sizing:border-box;
   font-family:'Poppins', sans-serif;
   text-decoration:none;
}

body{
   display:flex;
   background:var(--bg);
}

.sidebar{
   width:280px;
   height:100vh;
   background:linear-gradient(180deg,var(--blue1),#1e3a8a);
   position:fixed;
   left:0;
   top:0;
   display:flex;
   flex-direction:column;
   padding:25px;
}

.logo{
   display: flex;
   font-size:22px;
   font-weight:800;
   color:#fff;
   margin-bottom:35px;
   justify-content:space-between;
   align-items:center;
}

.logo span{
   color:var(--blue4);
}

.logo .ico i{
   font-size:22px;
   cursor:pointer;
}

.menu{
   flex:1;
}

.menu a{
   display:flex;
   align-items:center;
   gap:10px;
   padding:12px 14px;
   color:var(--text);
   border-radius:10px;
   margin-bottom:10px;
   transition:.3s;
   font-size:14px;
}

.menu a:hover{
   background:rgba(59,130,246,.25);
   color:#fff;
   transform:translateX(6px);
}

.logout{
   margin-top:auto;
   border-top:1px solid rgba(255,255,255,.1);
   padding-top:15px;
}

.logout a{
   display:flex;
   align-items:center;
   gap:10px;
   padding:12px 14px;
   background:#ef4444;
   color:#fff;
   border-radius:10px;
   font-weight:600;
   transition:.3s;
}

.logout a:hover{
   background:#dc2626;
}

.main{
   margin-left:280px;
   width:100%;
}

.account-box{
   position:absolute;
   left:300px;
   top:70px;
   width:320px;
   background:#fff;
   border-radius:16px;
   box-shadow:var(--shadow);
   padding:20px;
   display:none;
   border-top:4px solid var(--blue3);
   z-index:1000;
}

.account-header{
   display:flex;
   align-items:center;
   gap:10px;
   margin-bottom:15px;
   color:var(--blue3);
}

.account-box p{
   margin:10px 0;
   font-size:14px;
   color:#64748b;
}

.account-box span{
   color:#0f172a;
   font-weight:600;
}
</style>
</head>
<body>

<div class="sidebar">
   <div class="logo">
      Admin<span>Painel</span>
      <div class="ico"><i id="user-btn" class="fas fa-user-circle" style="color: white;"></i></div>
   </div>

   <div class="account-box">
      <div class="account-header">
         <i class="fas fa-user-shield"></i>
         <h3>Conta do Administrador</h3>
      </div>
      <p>Nome de usuário: <span><?php echo isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : ''; ?></span></p>
      <p>Email: <span><?php echo isset($_SESSION['admin_email']) ? $_SESSION['admin_email'] : ''; ?></span></p>
      <p>iban: <span>AO06 0006 0000 70768961301 75</span></p>
      <p>Express: <span>928884069</span></p>
      <p>Tipo: <span>
         <?php 
         if(isset($row['user_type'])){
            if($row['user_type'] == 'admin'){ 
               echo 'Administrador';
            } elseif($row['user_type'] == 'vendf'){ 
               echo 'Vendedor físico';
            } elseif($row['user_type'] == 'vendemp'){ 
               echo 'Empresa';
            }
         }
         ?>
      </span></p>
   </div>

   <div class="menu">
      <a href="admin_page.php"><i class="fas fa-home"></i> <span>Início</span></a>
      <a href="admin_products.php"><i class="fas fa-box"></i> <span>Produtos</span></a>
      <a href="admin_orders.php"><i class="fas fa-shopping-cart"></i> <span>Pedidos</span></a>
      <?php if(isset($row['user_type']) && $row['user_type'] == 'admin'){ ?>
         <a href="admin_users.php"><i class="fas fa-users"></i> <span>Usuários</span></a>
      <?php } ?>
      <a href="admin_contacts.php"><i class="fas fa-envelope"></i> <span>Mensagens</span></a>
   </div>

   <div class="logout">
      <a href="logout.php">
         <i class="fas fa-sign-out-alt"></i>
         <span>Sair</span>
      </a>
   </div>
</div>

<script>
let userBtn = document.querySelector('#user-btn');
let accountBox = document.querySelector('.account-box');

if(userBtn){
   userBtn.onclick = () => {
      if(accountBox){
         accountBox.style.display = accountBox.style.display === 'block' ? 'none' : 'block';
      }
   }
}
</script>

</body>
</html>