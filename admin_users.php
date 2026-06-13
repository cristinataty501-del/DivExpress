<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_users.php');
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Usuários</title>

   <!-- FONT AWESOME -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

:root{
   --azul:#2563eb;
   --azul-escuro:#1d4ed8;
   --bg:#f1f5f9;
   --texto:#0f172a;
   --cinza:#64748b;
   --danger:#ef4444;
   --white:#ffffff;
   --border:#e2e8f0;
   --shadow:0 8px 20px rgba(0,0,0,0.05);
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

/* ======================
   SECTION
====================== */

.users{
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
   margin-bottom:22px;
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

/* ======================
   TABELA
====================== */

.table{
   width:100%;
   max-width:1100px;
   background:var(--white);
   border-radius:16px;
   overflow:hidden;
   box-shadow:var(--shadow);
   border:1px solid var(--border);
}

/* HEADER */

.table-header{
   display:grid;
   grid-template-columns:70px 1fr 1.2fr 140px 120px;
   background:linear-gradient(to right,#2563eb,#3b82f6);
   color:#fff;
   padding:15px 18px;
   font-size:13px;
   font-weight:600;
   text-align:center;
}

/* LINHAS */

.row{
   display:grid;
   grid-template-columns:70px 1fr 1.2fr 140px 120px;
   align-items:center;
   padding:14px 18px;
   border-bottom:1px solid #e2e8f0;
   transition:.2s ease;
}

.row:last-child{
   border-bottom:none;
}

.row:hover{
   background:#f8fafc;
}

/* COLUNAS */

.col{
   font-size:13px;
   color:var(--cinza);
   text-align:center;
   word-break:break-word;
}

.col strong{
   color:var(--texto);
   font-weight:700;
}

/* BADGE */

.badge{
   display:inline-block;
   padding:5px 12px;
   border-radius:20px;
   background:#dbeafe;
   color:var(--azul-escuro);
   font-size:12px;
   font-weight:600;
}

/* BOTÃO */

.delete-btn{
   background:var(--danger);
   color:#fff;
   padding:9px 12px;
   border-radius:10px;
   text-decoration:none;
   font-size:12px;
   font-weight:600;
   transition:.2s;
   display:inline-block;
}

.delete-btn:hover{
   background:#b91c1c;
}

/* RESPONSIVO */

@media(max-width:850px){

   .table-header{
      display:none;
   }

   .row{
      grid-template-columns:1fr;
      gap:10px;
      text-align:left;
      padding:18px;
   }

   .col{
      text-align:left;
      font-size:13px;
      padding:5px 0;
   }

   .title{
      font-size:20px;
   }

}

</style>

</head>

<body>

<?php include 'admin_header.php'; ?>

<section class="users">

   <h1 class="title">Contas de Usuários</h1>

   <div class="table">

      <!-- CABEÇALHO -->
      <div class="table-header">
         <div>ID</div>
         <div>Nome do Usuário</div>
         <div>Email</div>
         <div>Tipo</div>
         <div>Ação</div>
      </div>

      <!-- DADOS -->
      <?php
         $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');

         while($fetch_users = mysqli_fetch_assoc($select_users)){
      ?>

      <div class="row">

         <div class="col">
            <strong>#<?php echo $fetch_users['id']; ?></strong>
         </div>

         <div class="col">
            <?php echo $fetch_users['name']; ?>
         </div>

         <div class="col">
            <?php echo $fetch_users['email']; ?>
         </div>

         <div class="col">
            <span class="badge">
               <?php echo $fetch_users['user_type']; ?>
            </span>
         </div>

         <div class="col">
            <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>" 
               onclick="return confirm('Deseja eliminar este usuário?');" 
               class="delete-btn">
               Eliminar
            </a>
         </div>

      </div>

      <?php } ?>

   </div>

</section>

<!-- JS -->
<script src="js/admin_script.js"></script>

</body>
</html>