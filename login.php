<?php

include 'config.php';
session_start();

$message = [];

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, trim($_POST['email']));
   $pass  = trim($_POST['password']);

   // Validar email
   if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $message[] = "Email inválido.";
   }

   // Validar senha
   elseif(strlen($pass) < 8){
      $message[] = "A senha deve ter pelo menos 8 caracteres.";
   }

   else{

      $password = md5($pass);

      $select_users = mysqli_query(
         $conn,
         "SELECT * FROM `users` WHERE email = '$email' AND password = '$password'"
      ) or die(mysqli_error($conn));

      if(mysqli_num_rows($select_users) > 0){

         $row = mysqli_fetch_assoc($select_users);

         $user_type = trim($row['user_type']);

         // ADMINISTRADOR E VENDEDORES
         if(in_array($user_type, ['admin', 'vendf', 'vendemp'])){

            $_SESSION['admin_name']  = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id']    = $row['id'];

            header('Location: admin_page.php');
            exit();

         }

         // COMPRADOR
         elseif($user_type === 'comp'){

            $_SESSION['user_name']  = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id']    = $row['id'];

            header('Location: home.php');
            exit();

         }

         else{
            $message[] = "Tipo de utilizador não reconhecido.";
         }

      }else{
         $message[] = "Email ou senha incorretos!";
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->

</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
   
<div class="form-container">

   <form action="" method="post">
      <h3>Login</h3>
      <input type="email" name="email" placeholder="Insira seu email" required class="box required" oninput="validaremail()" >
      <span class = "span-required" style = "color: red; display: none;">email inválido</span>
      <input type="password" name="password" placeholder="Insira sua senha" required class="box required" oninput="validarsenha()" >
      <span class = "span-required" style = "color: red; display: none;">senha inválido</span>
      <input type="submit" name="submit" value="Entrar" class="btn">
      <p>Não tens uma conta? <a href="register.php">Cadastra-se</a></p>

      
   </form>

</div>
<script>
    const form = document.getElementById('form');
   const campos = document.querySelectorAll('.required');
   const nomeRegex = /^[A-ZÀ-Ý][a-zà-ÿ]+(\s[A-ZÀ-Ý][a-zà-ÿ]+)+$/;
   const emailRegex = /^[^\s]+@[^\s]+\.[^\s]+$/;
   const spans = document.querySelectorAll('.span-required');
         

   /*form.addEventListener('submit', (event) => {
    event.preventDefault(); // só bloqueia se estiver inválido
      validarNome();
      validaremail();
      validarsenha();
      compararsenha();
      
   });*/

    function erro(index) {
      campos[index].style.border = '2px solid #e63636';
      spans[index].style.display = 'block';
   }

   function valido(index) {
      campos[index].style.border = '2px solid #4a64d9';
      spans[index].style.display = 'none';
   } 

   
   function validaremail() {
      if (emailRegex.test(campos[0].value)) {
         valido(0);
      } else {
         erro(0); 
      }
   } 

   function validarsenha() {
      if (campos[1].value.length >= 8) {
         valido(1);
      } else {
         erro(1);
      }
   }  
</script>
</body>
<style>
   /* ===== Google Fonts ===== */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

*{
   margin: 0;
   padding: 0;
   box-sizing: border-box;
   font-family: 'Poppins', sans-serif;
}

body{
   min-height: 100vh;
   display: flex;
   align-items: center;
   justify-content: center;
   
   padding: 20px;
}

/* ===== Mensagens ===== */
.message{
   position: fixed;
   top: 20px;
   right: 20px;
   background: #fff;
   color: #333;
   padding: 15px 20px;
   border-left: 5px solid #4f46e5;
   border-radius: 10px;
   box-shadow: 0 5px 15px rgba(0,0,0,0.1);
   display: flex;
   align-items: center;
   gap: 10px;
   z-index: 1000;
}

.back-btn{
   display:inline-block;
   margin-top:20px;
   padding:12px 25px;
   background:#fff;
   color:#1e3c72;
   border-radius:30px;
   font-weight:bold;
   transition:.3s;
   align-items: center;
}

.message i{
   cursor: pointer;
   color: #e11d48;
}

body{
   min-height:100vh;
   display:flex;
   align-items:center;
   justify-content:center;
   background:
   linear-gradient(rgba(15,23,42,.65), rgba(37,99,235,.75)),
   url('../images/bg.jpg') center/cover no-repeat;
   padding:20px;
}

/* ===== Container ===== */
.form-container{
   width: 100%;
   max-width: 420px;
}

/* ===== Formulário ===== */
.form-container form{
   background: rgba(255,255,255,0.9);
   backdrop-filter:blur(18px);
   border-radius: 12px;
   padding:40px;
   box-shadow:var(--shadow);
   border:1px solid rgba(255,255,255,.4);
   animation:fadeIn .5s ease;
}

.form-container form h3{
   text-align:center;
   font-size:36px;
   color:var(--dark);
   margin-bottom:30px;
   font-weight:800;
}

/* ===== Inputs ===== */

.form-container form .box{
   width:100%;
   padding:16px 18px;
   margin:12px 0;
   border-radius:16px;
   background:#f8fafc;
   border:1px solid var(--border);
   font-size:15px;
   color:var(--dark);
   transition:.3s ease;
}

.form-container form .box:focus{
   background:#fff;
   border-color:var(--primary);
   box-shadow:0 0 0 5px rgba(37,99,235,.10);
}


/* ===== Botão ===== */
.form-container form .btn{
   width:100%;
   padding:16px;
   margin-top:18px;
   border:none;
   border-radius:16px;
   background: linear-gradient(135deg, #4f46e5, #7c3aed);
   color:#fff;
   font-size:16px;
   font-weight:700;
   cursor:pointer;
   transition:.3s ease;
   box-shadow:0 15px 30px rgba(37,99,235,.18);
}


.form-container form .btn:hover{
   transform:translateY(-4px);
}


/* ===== Texto ===== */
.form-container form p{
   text-align:center;
   margin-top:25px;
   color:var(--gray);
   font-size:15px;
}

.form-container form p a{
   color:linear-gradient(135deg, #4f46e5, #7c3aed);
   font-weight:700;
   text-decoration:none;
}

.form-container form p a:hover{
   text-decoration:underline;
}

/* ===== Responsivo ===== */
@media(max-width: 480px){
   .form-container form{
      padding: 30px 20px;
   }

   .form-container form h3{
      font-size: 26px;
   }
}
</style>
</html>