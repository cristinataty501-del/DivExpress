<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_contacts.php');
}

?>
<!DOCTYPE html>
<html lang="pt">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Mensagens</title>

   <!-- FONT AWESOME -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body>

<?php include 'admin_header.php'; ?>

<section class="messages">

   <h1 class="title">
      <i class="fas fa-envelope"></i> Mensagens
   </h1>

   <div class="box-container">

   <?php
      $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE admin_id = $admin_id") or die('query failed');

      if(mysqli_num_rows($select_message) > 0){

         while($fetch_message = mysqli_fetch_assoc($select_message)){
   ?>

   <div class="box">

      <div class="top">
         <span class="id">
            ID: <?php echo $fetch_message['user_id']; ?>
         </span>
      </div>

      <div class="info">

         <p>
            <i class="fas fa-user"></i>
            <strong><?php echo $fetch_message['name']; ?></strong>
         </p>

         <p>
            <i class="fas fa-phone"></i>
            <?php echo $fetch_message['number']; ?>
         </p>

         <p>
            <i class="fas fa-envelope"></i>
            <?php echo $fetch_message['email']; ?>
         </p>

      </div>

      <div class="message">
         <p><?php echo $fetch_message['message']; ?></p>
      </div>

      <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>" 
         onclick="return confirm('Deseja eliminar esta mensagem?');" 
         class="delete-btn">

         <i class="fas fa-trash"></i>
         Eliminar Mensagem

      </a>

   </div>

   <?php
         }

      }else{

         echo '<p class="empty">Nenhuma mensagem encontrada!</p>';

      }
   ?>

   </div>

</section>

</body><style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

:root{
   --azul:#2563eb;
   --azul-escuro:#1d4ed8;
   --bg:#f1f5f9;
   --texto:#0f172a;
   --cinza:#64748b;
   --borda:#e2e8f0;
   --danger:#ef4444;
   --white:#ffffff;
   --shadow:0 8px 20px rgba(0,0,0,.05);
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

/* =====================
   SECTION
===================== */

.messages{
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
   border:1px solid var(--borda);
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
   left:0;
   top:0;
   width:5px;
   height:100%;
   background:var(--azul);
   border-radius:14px 0 0 14px;
}

.title i{
   color:var(--azul);
   margin-right:8px;
}

/* GRID */

.box-container{
   width:100%;
   max-width:1100px;
   display:grid;
   grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));
   gap:20px;
}

/* CARD */

.box{
   background:var(--white);
   border-radius:18px;
   padding:20px;
   border:1px solid var(--borda);
   box-shadow:var(--shadow);
   transition:.3s ease;
   position:relative;
   overflow:hidden;
}

.box::before{
   content:'';
   position:absolute;
   left:0;
   top:0;
   width:100%;
   height:4px;
   background:linear-gradient(to right,#2563eb,#60a5fa);
}

.box:hover{
   transform:translateY(-5px);
   box-shadow:0 12px 25px rgba(0,0,0,.08);
}

/* TOPO */

.top{
   display:flex;
   justify-content:flex-end;
   margin-bottom:15px;
}

.id{
   background:#dbeafe;
   color:var(--azul-escuro);
   padding:5px 12px;
   border-radius:10px;
   font-size:12px;
   font-weight:600;
}

/* INFORMAÇÕES */

.info{
   margin-bottom:15px;
}

.info p{
   margin:10px 0;
   font-size:13px;
   color:#334155;
   display:flex;
   align-items:center;
   gap:8px;
   word-break:break-word;
}

.info i{
   color:var(--azul);
   min-width:16px;
}

/* MENSAGEM */

.message{
   background:#f8fafc;
   border:1px solid #e2e8f0;
   padding:14px;
   border-radius:12px;
   font-size:13px;
   color:var(--texto);
   line-height:1.6;
   min-height:90px;
   word-break:break-word;
}

/* BOTÃO */

.delete-btn{
   width:100%;
   margin-top:18px;
   padding:12px;
   border-radius:12px;
   background:var(--danger);
   color:#fff;
   text-align:center;
   text-decoration:none;
   font-size:13px;
   font-weight:600;
   display:flex;
   align-items:center;
   justify-content:center;
   gap:8px;
   transition:.3s;
}

.delete-btn:hover{
   background:#dc2626;
}

/* SEM MENSAGEM */

.empty{
   width:100%;
   max-width:500px;
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

   .messages{
      padding:20px 15px;
   }

   .title{
      font-size:20px;
      padding:15px;
   }

   .box{
      padding:18px;
   }

}

</style>


</html>