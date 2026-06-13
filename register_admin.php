<?php

include 'config.php';

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, $_POST['password']);
   $cpass = mysqli_real_escape_string($conn, $_POST['cpassword']);
   $user_type = $_POST['user_type'];

   if (strlen($name) >= 2 && preg_match("/^[A-ZÀ-Ý][a-zà-ÿ]+(\s[A-ZÀ-Ý][a-zà-ÿ]+)+$/", $name)) {
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
         mysqli_query($conn, "INSERT INTO `users`(name, email, password, user_type) VALUES('$name', '$email', '$cpass', '$user_type')") or die('query failed');
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
   <link rel="stylesheet" href="css/style.css">

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
      <h3>login</h3>
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
      <a href=""></a>
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
</body>
</html>