<?php

include 'config.php';

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, $_POST['password']);
   $cpass = mysqli_real_escape_string($conn, $_POST['cpassword']);
   $user_type = $_POST['user_type'];

   if (strlen($name) >= 3 && preg_match("/^[A-ZÀ-Ý][a-zà-ÿ]+(\s[A-ZÀ-Ý][a-zà-ÿ]+)+$/", $name)) {
       if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
       if (strlen($pass) >= 8) {

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){
      $message[] = 'user already exist!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $cpass = md5($cpass);
         mysqli_query($conn, "INSERT INTO `users`(name, email, password, user_type) VALUES('$name', '$email', '$cpassword', '$user_type')") or die('query failed');
         $message[] = 'registered successfully!';
         header('location:login.php');
      }
   }
   
  }else{
 $message[] = "Senha deve ter pelo menos 8 caracteres.";
   }
   }else{
 $message[] = "Email errado.";
   }
   }else{
 $message[] = 'Nome inválido.';
   }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

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

   <form id = "form" action="" method="post">
      <h3>Cadastra-se</h3>
      <input type="text" name="name" placeholder="Insira seu nome" required class="box required" oninput = "validarNome()" >
      <span class = "span-required" style = "color: red; display: none;">Nome inválido</span>
      <input type="email" name="email" placeholder="Insira seu email" required class="box required" oninput="validaremail()" >
      <span class = "span-required" style = "color: red; display: none;">email inválido</span>
      <input type="password" name="password" placeholder="Insira sua senha" required class="box required" oninput="validarsenha()" >
      <span class = "span-required" style = "color: red; display: none;">senha inválido</span>
      <input type="password" name="cpassword" placeholder="Confirme sua senha" required class="box required" oninput="compararsenha()" >
      <span class = "span-required" style = "color: red; display: none;">senha não confirmada</span>
      <select name="user_type" class="box">
         <option value="comp">Comprador</option>
         <option value="vendemp">Vendedor</option>
      </select>
      <input type="submit" name="submit" value="Cadastrar" class="btn">
      <p>Já tens uma conta? <a href="login.php">Login </a></p>
      
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
   
   function validarNome() {
      if (campos[0].value.length >= 2 && nomeRegex.test(campos[0].value)) {
         valido(0);
      } else {
         erro(0);
      }
   } 

   
   function validaremail() {
      if (emailRegex.test(campos[1].value)) {
         valido(1);
      } else {
         erro(1); 
      }
   } 

   function validarsenha() {
      if (campos[2].value.length >= 8) {
         valido(2);
         compararsenha();
      } else {
         erro(2);
      }
   }

   function compararsenha(){
      if (campos[2].value == campos[3].value && campos[3].value.length >= 8) {
         valido(3);
      } else {
         erro(3);
      }
   }
</script>

<script src="js/script.js"></script>
</body>
<style>
   /* ===================================== */
/*         REGISTER PAGE STYLE           */
/* ===================================== */

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

:root{
   --primary:#2563eb;
   --primary-light:#3b82f6;
   --dark:#0f172a;
   --gray:#64748b;
   --light:#f8fafc;
   --white:#ffffff;
   --border:#dbeafe;

   --shadow:0 20px 50px rgba(37,99,235,.12);
   --radius:24px;
}

*{
   margin:0;
   padding:0;
   box-sizing:border-box;
   font-family:'Poppins', sans-serif;
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

/* ===================================== */
/*             FORM BOX                  */
/* ===================================== */

.form-container{
   width:100%;
   max-width:470px;
}

.form-container form{
   background: rgba(255,255,255,0.9);
   backdrop-filter:blur(18px);
   border-radius:var(--radius);
   padding:40px;
   box-shadow:var(--shadow);
   border:1px solid rgba(255,255,255,.4);
   animation:fadeIn .5s ease;
}

/* ===================================== */
/*               TITLE                   */
/* ===================================== */

.form-container form h3{
   text-align:center;
   font-size:36px;
   color:var(--dark);
   margin-bottom:30px;
   font-weight:800;
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
}

/* ===================================== */
/*               INPUTS                  */
/* ===================================== */

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

/* ===================================== */
/*               SELECT                  */
/* ===================================== */

select.box{
   cursor:pointer;
   appearance:none;
}

/* ===================================== */
/*              BUTTON                   */
/* ===================================== */

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

/* ===================================== */
/*               TEXT                    */
/* ===================================== */

.form-container form p{
   text-align:center;
   margin-top:25px;
   color:var(--gray);
   font-size:15px;
}

.form-container form p a{
   color:var(--primary);
   font-weight:700;
   text-decoration:none;
}

.form-container form p a:hover{
   text-decoration:underline;
}

/* ===================================== */
/*             VALIDATION                */
/* ===================================== */

/* ===================================== */
/*              MESSAGE                  */
/* ===================================== */

.message{
   position:fixed;
   top:20px;
   right:20px;
   background:#fff;
   padding:16px 20px;
   border-radius:18px;
   box-shadow:var(--shadow);
   display:flex;
   align-items:center;
   gap:15px;
   z-index:1000;
   border-left:5px solid var(--primary);
   animation:slideDown .4s ease;
}

.message span{
   color:var(--dark);
   font-size:14px;
}

.message i{
   cursor:pointer;
   color:#ef4444;
   font-size:16px;
}

/* ===================================== */
/*             ANIMATIONS                */
/* ===================================== */

@keyframes fadeIn{
   from{
      opacity:0;
      transform:translateY(20px);
   }
   to{
      opacity:1;
      transform:translateY(0);
   }
}

@keyframes slideDown{
   from{
      opacity:0;
      transform:translateY(-20px);
   }
   to{
      opacity:1;
      transform:translateY(0);
   }
}

/* ===================================== */
/*             RESPONSIVO                */
/* ===================================== */

@media(max-width:500px){

   .form-container form{
      padding:30px 22px;
   }

   .form-container form h3{
      font-size:30px;
   }
}
</style>
</html>