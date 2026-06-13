<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['send'])){
   $admin_id = $_POST['admin_id'];
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $number = $_POST['number'];
   $msg = mysqli_real_escape_string($conn, $_POST['message']);

   $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

   if(mysqli_num_rows($select_message) > 0){
      $message[] = 'message sent already!';
   }else{
      mysqli_query($conn, "INSERT INTO `message`(user_id, admin_id, name, email, number, message) VALUES('$user_id', '$admin_id', '$name', '$email', '$number', '$msg')") or die('query failed');
      $message[] = 'message sent successfully!';
   }

}

?>
<!DOCTYPE html>
<html lang="pt">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contacto - DivExpress</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>Contacte-Nos</h3>
   <p><a href="home.php">Início</a> / Contacto</p>
</div>

<section class="contact">

<?php   
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      $fetch_cart = mysqli_fetch_assoc($select_cart);
?>

   <form action="" method="post">

      <h3>Diz alguma coisa!</h3>

      <input type="hidden" name="admin_id" value="<?php echo $fetch_cart['admin_id']; ?>">

      <input type="text" name="name" required placeholder="Digite o seu nome" class="box">

      <input type="email" name="email" required placeholder="Digite o seu email" class="box">

      <input type="number" name="number" required placeholder="Digite o seu número" class="box">

      <textarea name="message" class="box" placeholder="Escreva a sua mensagem" cols="30" rows="8"></textarea>

      <input type="submit" value="Enviar Mensagem" name="send" class="btn">

   </form>

</section>

</body>
</html>
<!-- custom js file link  -->
<script src="js/script.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>
   <style>
   @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

   :root{
      --primary:#2563eb;
      --dark:#0f172a;
      --gray:#64748b;
      --bg:#f1f5f9;
      --white:#ffffff;
      --shadow:0 15px 40px rgba(0,0,0,.08);
      --radius:20px;
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
      font-size:14px;
   }

   .heading a{
      color:#fff;
      font-weight:600;
   }

   /* CONTACT SECTION */
   .contact{
      display:flex;
      justify-content:center;
      align-items:center;
      padding:80px 20px;
   }

   form{
      background:#fff;
      padding:35px;
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      width:100%;
      max-width:550px;
      position:relative;
      overflow:hidden;
   }

   form::before{
      content:"";
      position:absolute;
      top:-60px;
      right:-60px;
      width:150px;
      height:150px;
      background:rgba(37,99,235,.08);
      border-radius:50%;
   }

   form h3{
      text-align:center;
      font-size:26px;
      margin-bottom:25px;
      color:var(--dark);
      font-weight:800;
   }

   /* INPUTS */
   .box{
      width:100%;
      padding:14px 15px;
      margin:10px 0;
      border-radius:12px;
      border:1px solid #e2e8f0;
      outline:none;
      font-size:14px;
      transition:.3s;
      background:#f8fafc;
   }

   .box:focus{
      border-color:var(--primary);
      background:#fff;
      box-shadow:0 0 0 3px rgba(37,99,235,.1);
   }

   textarea.box{
      resize:none;
   }

   /* BUTTON */
   .btn{
      width:100%;
      padding:14px;
      border:none;
      border-radius:12px;
      background:linear-gradient(135deg,var(--primary),#1d4ed8);
      color:#fff;
      font-weight:600;
      font-size:15px;
      cursor:pointer;
      margin-top:10px;
      transition:.3s;
   }

   .btn:hover{
      transform:translateY(-3px);
   }

   /* RESPONSIVE */
   @media(max-width:768px){
      .heading h3{
         font-size:28px;
      }

      form{
         padding:25px;
      }
   }

   </style>