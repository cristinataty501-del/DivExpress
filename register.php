<?php
include 'config.php';

// Initialize variables
$message = array();

if(isset($_POST['submit'])){
   
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, $_POST['password']);
   $cpass = mysqli_real_escape_string($conn, $_POST['cpassword']);
   $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
   
   // Validation
   $errors = array();
   
   // Validate name (at least 2 words, each starting with capital letter)
   if (strlen($name) < 3 || !preg_match("/^[A-ZÀ-Ý][a-zà-ÿ]+(\s[A-ZÀ-Ý][a-zà-ÿ]+)+$/", $name)) {
      $errors['name'] = 'Nome inválido. Use nome completo com letras maiúsculas.';
   }
   
   // Validate email
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Email inválido.';
   }
   
   // Check if email already exists
   $check_email = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'");
   if(mysqli_num_rows($check_email) > 0){
      $errors['email_exists'] = 'Este email já está registrado!';
   }
   
   // Validate password
   if (strlen($pass) < 8) {
      $errors['password'] = 'Senha deve ter pelo menos 8 caracteres.';
   }
   
   // Check if passwords match
   if($pass != $cpass){
      $errors['cpassword'] = 'Confirmar senha não corresponde!';
   }
   
   // If no errors, proceed with registration
   if(empty($errors)){
      // Hash the password (md5 is weak, but keeping for compatibility)
      $hashed_pass = md5($pass);
      
      // Fixed INSERT query - only 4 columns (id is auto_increment)
      $insert_query = "INSERT INTO `users` (name, email, password, user_type) 
                       VALUES ('$name', '$email', '$hashed_pass', '$user_type')";
      
      if(mysqli_query($conn, $insert_query)){
         $message[] = 'Registrado com sucesso! Redirecionando...';
         echo '<script>
            setTimeout(function() {
               window.location.href = "login.php";
            }, 2000);
         </script>';
      } else {
         $message[] = 'Erro ao registrar: ' . mysqli_error($conn);
      }
   } else {
      // Collect all errors
      foreach($errors as $error){
         $message[] = $error;
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
   <title>Registrar - Admin Panel</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
      --error:#dc2626;
      --success:#10b981;
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
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding:20px;
   }

   /* ===================================== */
   /*             FORM BOX                  */
   /* ===================================== */

   .form-container{
      width:100%;
      max-width:500px;
   }

   .form-container form{
      background: rgba(255,255,255,0.98);
      backdrop-filter:blur(18px);
      border-radius:var(--radius);
      padding:45px;
      box-shadow:var(--shadow);
      border:1px solid rgba(255,255,255,.4);
      animation:fadeIn .5s ease;
   }

   /* ===================================== */
   /*               TITLE                   */
   /* ===================================== */

   .form-container form h3{
      text-align:center;
      font-size:32px;
      color:var(--dark);
      margin-bottom:30px;
      font-weight:800;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
   }

   /* ===================================== */
   /*               INPUTS                  */
   /* ===================================== */

   .form-container form .box{
      width:100%;
      padding:14px 18px;
      margin:8px 0;
      border-radius:12px;
      background:#f8fafc;
      border:2px solid #e2e8f0;
      font-size:14px;
      color:var(--dark);
      transition:.3s ease;
   }

   .form-container form .box:focus{
      border-color:var(--primary);
      outline:none;
      box-shadow:0 0 0 3px rgba(37,99,235,.1);
   }

   /* ===================================== */
   /*               SELECT                  */
   /* ===================================== */

   select.box{
      cursor:pointer;
      appearance:none;
      background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right 1rem center;
      background-size: 1em;
   }

   /* ===================================== */
   /*              BUTTON                   */
   /* ===================================== */

   .form-container form .btn{
      width:100%;
      padding:14px;
      margin-top:20px;
      border:none;
      border-radius:12px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color:#fff;
      font-size:16px;
      font-weight:600;
      cursor:pointer;
      transition:.3s ease;
   }

   .form-container form .btn:hover{
      transform:translateY(-2px);
      box-shadow:0 10px 25px rgba(102, 126, 234, 0.3);
   }

   /* ===================================== */
   /*               TEXT                    */
   /* ===================================== */

   .form-container form p{
      text-align:center;
      margin-top:20px;
      color:var(--gray);
      font-size:14px;
   }

   .form-container form p a{
      color:var(--primary);
      font-weight:600;
      text-decoration:none;
   }

   .form-container form p a:hover{
      text-decoration:underline;
   }

   /* ===================================== */
   /*             VALIDATION STYLES         */
   /* ===================================== */
   
   .box.error {
      border-color: var(--error) !important;
      background-color: #fef2f2;
   }
   
   .box.success {
      border-color: var(--success) !important;
      background-color: #f0fdf4;
   }
   
   .span-required {
      font-size: 12px;
      margin-top: -5px;
      margin-bottom: 8px;
      display: none;
   }

   /* ===================================== */
   /*              MESSAGE                  */
   /* ===================================== */

   .message{
      position:fixed;
      top:20px;
      right:20px;
      background:#fff;
      padding:14px 20px;
      border-radius:12px;
      box-shadow:0 10px 30px rgba(0,0,0,.15);
      display:flex;
      align-items:center;
      gap:12px;
      z-index:1000;
      border-left:4px solid var(--primary);
      animation:slideDown .4s ease;
      max-width:350px;
   }

   .message span{
      color:var(--dark);
      font-size:14px;
      flex:1;
   }

   .message i{
      cursor:pointer;
      color:#ef4444;
      font-size:16px;
      transition: opacity 0.3s;
   }

   .message i:hover{
      opacity:0.7;
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
         padding:30px 25px;
      }

      .form-container form h3{
         font-size:28px;
      }
      
      .message{
         left:20px;
         right:20px;
         max-width:none;
      }
   }
   </style>
</head>
<body>

<?php
if(isset($message) && !empty($message)){
   foreach($message as $msg){
      echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
   
<div class="form-container">
   <form id="form" action="" method="post">
      <h3>📝 Cadastra-se</h3>
      
      <input type="text" name="name" placeholder="Nome completo (Ex: João Silva)" required class="box required" oninput="validarNome()">
      <span class="span-required" id="name-error" style="color: #dc2626; display: none;">Nome inválido. Use nome completo com letras maiúsculas.</span>
      
      <input type="email" name="email" placeholder="seu@email.com" required class="box required" oninput="validarEmail()">
      <span class="span-required" id="email-error" style="color: #dc2626; display: none;">Email inválido.</span>
      
      <input type="password" name="password" placeholder="Senha (mínimo 8 caracteres)" required class="box required" oninput="validarSenha()">
      <span class="span-required" id="password-error" style="color: #dc2626; display: none;">Senha deve ter pelo menos 8 caracteres.</span>
      
      <input type="password" name="cpassword" placeholder="Confirmar senha" required class="box required" oninput="compararSenha()">
      <span class="span-required" id="cpassword-error" style="color: #dc2626; display: none;">Senhas não coincidem.</span>
      
      <select name="user_type" class="box">
         <option value="user">Comprador</option>
         <option value="vendemp">Vendedor</option>
      </select>
      
      <input type="submit" name="submit" value="Cadastrar" class="btn">
      <p>Já tens uma conta? <a href="login.php">Login</a></p>
   </form>
</div>

<script>
   const form = document.getElementById('form');
   const campos = document.querySelectorAll('.required');
   const nomeRegex = /^[A-ZÀ-Ý][a-zà-ÿ]+(\s[A-ZÀ-Ý][a-zà-ÿ]+)+$/;
   const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
   
   function erro(index, errorId) {
      campos[index].style.border = '2px solid #dc2626';
      campos[index].style.backgroundColor = '#fef2f2';
      const errorSpan = document.getElementById(errorId);
      if(errorSpan) errorSpan.style.display = 'block';
   }
   
   function valido(index, errorId) {
      campos[index].style.border = '2px solid #10b981';
      campos[index].style.backgroundColor = '#f0fdf4';
      const errorSpan = document.getElementById(errorId);
      if(errorSpan) errorSpan.style.display = 'none';
   }
   
   function validarNome() {
      if (campos[0].value.length >= 3 && nomeRegex.test(campos[0].value)) {
         valido(0, 'name-error');
         return true;
      } else {
         erro(0, 'name-error');
         return false;
      }
   }
   
   function validarEmail() {
      if (emailRegex.test(campos[1].value)) {
         valido(1, 'email-error');
         return true;
      } else {
         erro(1, 'email-error');
         return false;
      }
   }
   
   function validarSenha() {
      if (campos[2].value.length >= 8) {
         valido(2, 'password-error');
         compararSenha();
         return true;
      } else {
         erro(2, 'password-error');
         return false;
      }
   }
   
   function compararSenha() {
      if (campos[2].value == campos[3].value && campos[3].value.length >= 8) {
         valido(3, 'cpassword-error');
         return true;
      } else if (campos[3].value.length > 0) {
         erro(3, 'cpassword-error');
         return false;
      }
      return false;
   }
   
   // Optional: Validate on form submit
   form.addEventListener('submit', function(event) {
      const isNameValid = validarNome();
      const isEmailValid = validarEmail();
      const isPasswordValid = validarSenha();
      const isCpasswordValid = compararSenha();
      
      if (!isNameValid || !isEmailValid || !isPasswordValid || !isCpasswordValid) {
         event.preventDefault();
         alert('Por favor, corrija os erros no formulário antes de enviar.');
      }
   });
</script>

<script src="js/script.js"></script>
</body>
</html>