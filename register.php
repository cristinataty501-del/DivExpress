<?php
session_start();
include 'config.php';

$message = array();

if(isset($_POST['submit'])){
   
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, $_POST['password']);
   $cpass = mysqli_real_escape_string($conn, $_POST['cpassword']);
   $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
   
   // Validação do nome
   if (strlen($name) >= 3 && preg_match("/^[A-ZÀ-Ý][a-zà-ÿ]+(\s[A-ZÀ-Ý][a-zà-ÿ]+)+$/", $name)) {
       
       // Validação do email
       if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
           
           // Validação da senha
           if (strlen($pass) >= 8) {
               
               // Verificar se usuário já existe
               $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed: ' . mysqli_error($conn));
               
               if(mysqli_num_rows($select_users) > 0){
                  $message[] = 'Usuário já existe!';
               } else {
                  // Verificar se as senhas coincidem
                  if($pass != $cpass){
                     $message[] = 'As senhas não coincidem!';
                  } else {
                     // Criptografar a senha
                     $hashed_pass = md5($pass); // Ou use password_hash() para mais segurança
                     
                     // Query corrigida - apenas 4 campos (name, email, password, user_type)
                     $insert_query = "INSERT INTO `users` (name, email, password, user_type) VALUES ('$name', '$email', '$hashed_pass', '$user_type')";
                     
                     if(mysqli_query($conn, $insert_query)){
                        $message[] = 'Registrado com sucesso!';
                        header('location:login.php');
                        exit();
                     } else {
                        $message[] = 'Erro ao registrar: ' . mysqli_error($conn);
                     }
                  }
               }
               
           } else {
               $message[] = "Senha deve ter pelo menos 8 caracteres.";
           }
           
       } else {
           $message[] = "Email inválido.";
       }
       
   } else {
       $message[] = 'Nome inválido. Use nome e sobrenome (ex: João Silva)';
   }
   
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registrar - Loja Online</title>
   
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
      max-width:470px;
   }
   
   .form-container form{
      background: rgba(255,255,255,0.95);
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
      font-size:32px;
      color:var(--dark);
      margin-bottom:30px;
      font-weight:700;
   }
   
   /* ===================================== */
   /*               INPUTS                  */
   /* ===================================== */
   
   .form-container form .box{
      width:100%;
      padding:14px 18px;
      margin:10px 0;
      border-radius:16px;
      background:#f8fafc;
      border:2px solid var(--border);
      font-size:14px;
      color:var(--dark);
      transition:.3s ease;
      outline: none;
   }
   
   .form-container form .box:focus{
      border-color:var(--primary);
      background:var(--white);
   }
   
   /* Inputs com erro */
   .form-container form .box.error{
      border-color: #dc2626;
      background: #fef2f2;
   }
   
   /* ===================================== */
   /*               SELECT                  */
   /* ===================================== */
   
   select.box{
      cursor:pointer;
      appearance:none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 18px center;
      background-size: 20px;
   }
   
   /* ===================================== */
   /*              BUTTON                   */
   /* ===================================== */
   
   .form-container form .btn{
      width:100%;
      padding:14px;
      margin-top:20px;
      border:none;
      border-radius:16px;
      background: linear-gradient(135deg, #4f46e5, #7c3aed);
      color:#fff;
      font-size:16px;
      font-weight:600;
      cursor:pointer;
      transition:.3s ease;
      box-shadow:0 15px 30px rgba(37,99,235,.18);
   }
   
   .form-container form .btn:hover{
      transform:translateY(-3px);
      box-shadow:0 20px 35px rgba(37,99,235,.25);
   }
   
   .form-container form .btn:active{
      transform:translateY(0);
   }
   
   /* ===================================== */
   /*               TEXT                    */
   /* ===================================== */
   
   .form-container form p{
      text-align:center;
      margin-top:25px;
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
   /*              MESSAGE                  */
   /* ===================================== */
   
   .message{
      position:fixed;
      top:20px;
      right:20px;
      background:#fff;
      padding:14px 20px;
      border-radius:12px;
      box-shadow:0 10px 25px rgba(0,0,0,.1);
      display:flex;
      align-items:center;
      gap:15px;
      z-index:1000;
      border-left:4px solid var(--primary);
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
      transition: opacity 0.3s;
   }
   
   .message i:hover{
      opacity:0.7;
   }
   
   /* Mensagem de erro */
   .message.error{
      border-left-color: #dc2626;
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
         font-size:28px;
      }
   }
   </style>
</head>
<body>

<?php
if(isset($message) && !empty($message)){
   foreach($message as $msg){
      $error_class = (strpos($msg, 'sucesso') !== false || strpos($msg, 'success') !== false) ? '' : 'error';
      echo '
      <div class="message ' . $error_class . '">
         <span>' . htmlspecialchars($msg) . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
   
<div class="form-container">
   <form id="form" action="" method="post">
      <h3>📝 Cadastra-se</h3>
      
      <input type="text" name="name" placeholder="Nome completo" required class="box" id="nome">
      <span class="error-message" id="nomeError" style="color: #dc2626; font-size: 12px; display: none; margin-top: -5px; margin-bottom: 5px;"></span>
      
      <input type="email" name="email" placeholder="Seu melhor email" required class="box" id="email">
      <span class="error-message" id="emailError" style="color: #dc2626; font-size: 12px; display: none; margin-top: -5px; margin-bottom: 5px;"></span>
      
      <input type="password" name="password" placeholder="Senha (mínimo 8 caracteres)" required class="box" id="senha">
      <span class="error-message" id="senhaError" style="color: #dc2626; font-size: 12px; display: none; margin-top: -5px; margin-bottom: 5px;"></span>
      
      <input type="password" name="cpassword" placeholder="Confirmar senha" required class="box" id="csenha">
      <span class="error-message" id="csenhaError" style="color: #dc2626; font-size: 12px; display: none; margin-top: -5px; margin-bottom: 5px;"></span>
      
      <select name="user_type" class="box" required>
         <option value="user">👤 Cliente</option>
         <option value="vendf">🏪 Vendedor Físico</option>
         <option value="vendemp">🏢 Empresa</option>
      </select>
      
      <input type="submit" name="submit" value="Cadastrar" class="btn">
      
      <p>Já tens uma conta? <a href="login.php">Fazer Login</a></p>
   </form>
</div>

<script>
// Validação em tempo real
const nomeInput = document.getElementById('nome');
const emailInput = document.getElementById('email');
const senhaInput = document.getElementById('senha');
const csenhaInput = document.getElementById('csenha');

const nomeError = document.getElementById('nomeError');
const emailError = document.getElementById('emailError');
const senhaError = document.getElementById('senhaError');
const csenhaError = document.getElementById('csenhaError');

// Regex para nome (nome e sobrenome)
const nomeRegex = /^[A-ZÀ-Ý][a-zà-ÿ]+(\s[A-ZÀ-Ý][a-zà-ÿ]+)+$/;

// Validar nome
function validarNome() {
    const nome = nomeInput.value.trim();
    if (nome.length < 3 || !nomeRegex.test(nome)) {
        nomeError.textContent = 'Nome inválido. Use nome e sobrenome (ex: João Silva)';
        nomeError.style.display = 'block';
        nomeInput.classList.add('error');
        return false;
    } else {
        nomeError.style.display = 'none';
        nomeInput.classList.remove('error');
        return true;
    }
}

// Validar email
function validarEmail() {
    const email = emailInput.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        emailError.textContent = 'Email inválido. Ex: nome@dominio.com';
        emailError.style.display = 'block';
        emailInput.classList.add('error');
        return false;
    } else {
        emailError.style.display = 'none';
        emailInput.classList.remove('error');
        return true;
    }
}

// Validar senha
function validarSenha() {
    const senha = senhaInput.value;
    if (senha.length < 8) {
        senhaError.textContent = 'A senha deve ter pelo menos 8 caracteres';
        senhaError.style.display = 'block';
        senhaInput.classList.add('error');
        return false;
    } else {
        senhaError.style.display = 'none';
        senhaInput.classList.remove('error');
        validarConfirmacaoSenha();
        return true;
    }
}

// Validar confirmação de senha
function validarConfirmacaoSenha() {
    const senha = senhaInput.value;
    const csenha = csenhaInput.value;
    if (senha !== csenha) {
        csenhaError.textContent = 'As senhas não coincidem';
        csenhaError.style.display = 'block';
        csenhaInput.classList.add('error');
        return false;
    } else if (csenha.length > 0 && senha.length >= 8) {
        csenhaError.style.display = 'none';
        csenhaInput.classList.remove('error');
        return true;
    }
    return true;
}

// Eventos em tempo real
nomeInput.addEventListener('input', validarNome);
emailInput.addEventListener('input', validarEmail);
senhaInput.addEventListener('input', validarSenha);
csenhaInput.addEventListener('input', validarConfirmacaoSenha);

// Validação antes de enviar o formulário
document.getElementById('form').addEventListener('submit', function(e) {
    const isNomeValido = validarNome();
    const isEmailValido = validarEmail();
    const isSenhaValida = validarSenha();
    const isConfirmacaoValida = validarConfirmacaoSenha();
    
    if (!isNomeValido || !isEmailValido || !isSenhaValida || !isConfirmacaoValida) {
        e.preventDefault();
        alert('Por favor, corrija os erros no formulário antes de enviar.');
    }
});
</script>

<script src="js/script.js"></script>
</body>
</html>