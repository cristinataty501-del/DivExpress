<?php

include 'config.php';
session_start();



$user_id = $_SESSION['admin_id'];

/* =========================
   BUSCAR DADOS DO VENDEDOR
========================= */
$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");

if(!$query || mysqli_num_rows($query) == 0){
   die("Usuário não encontrado.");
}

$admin_id= mysqli_fetch_assoc($query);

/* =========================
   ATUALIZAR DADOS
========================= */
if(isset($_POST['update'])){

   $iban = mysqli_real_escape_string($conn, $_POST['iban']);
   $express = mysqli_real_escape_string($conn, $_POST['express']);

   if(strlen($iban) < 10){
      echo "<script>alert('IBAN inválido');</script>";
   } else {

      $update = mysqli_query($conn, "
         UPDATE users 
         SET iban='$iban', express='$express' 
         WHERE id='$user_id'
      ");

      if($update){
         echo "<script>alert('Dados atualizados com sucesso!'); window.location.href='profile_vendor.php';</script>";
      } else {
         echo "<script>alert('Erro ao atualizar dados');</script>";
      }
   }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Perfil do Vendedor</title>

<style>

body{
   font-family: Arial;
   background:#f4f6f9;
   margin:0;
   padding:0;
}

.container{
   width:40%;
   margin:50px auto;
   background:#fff;
   padding:20px;
   border-radius:10px;
   box-shadow:0 10px 25px rgba(0,0,0,.1);
}

h2{
   text-align:center;
   margin-bottom:20px;
}

.box{
   margin-bottom:15px;
}

label{
   font-weight:bold;
}

input{
   width:100%;
   padding:10px;
   margin-top:5px;
   border:1px solid #ccc;
   border-radius:6px;
}

.btn{
   width:100%;
   padding:12px;
   background:#2563eb;
   color:#fff;
   border:none;
   border-radius:6px;
   cursor:pointer;
   font-size:16px;
}

.btn:hover{
   background:#1d4ed8;
}

.info{
   background:#f1f5f9;
   padding:10px;
   border-radius:6px;
   margin-bottom:15px;
}

</style>
</head>

<body>

<div class="container">

   <h2>👤 Perfil do Vendedor</h2>

   <div class="info">
      <p><b>Nome:</b> <?= $admin_id['name'] ?></p>
      <p><b>Email:</b> <?= $admin_id['email'] ?></p>
      <p><b>Tipo:</b> <?= $admin_id['user_type'] ?></p>
   </div>

   <form method="POST">

      <div class="box">
         <label>IBAN</label>
         <input type="text" name="iban" value="<?= $admin_id['iban'] ?? '' ?>" placeholder="Insira seu IBAN">
      </div>

      <div class="box">
         <label>Multicaixa Express</label>
         <input type="text" name="express" value="<?= $admin_id['express'] ?? '' ?>" placeholder="Número Multicaixa Express">
      </div>

      <button type="submit" name="update" class="btn">
         💾 Guardar Dados
      </button>

   </form>

</div>

</body>
</html>