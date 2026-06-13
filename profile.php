<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user_id'])){
   header('location:login.php');
   exit();
}

$user_id = $_SESSION['user_id'];

/* BUSCAR USER */
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);

if(!$user){
   die("Utilizador não encontrado.");
}

$image = (!empty($user['image'])) ? $user['image'] : 'default.png';

/* UPDATE PERFIL */
if(isset($_POST['update_profile'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);

   $update_image = "";

   /* VERIFICAR IMAGEM */
   if(!empty($_FILES['image']['name'])){

      $image_name = time().'_'.$_FILES['image']['name'];
      $image_tmp = $_FILES['image']['tmp_name'];
      $folder = "uploaded_img/".$image_name;

      if(move_uploaded_file($image_tmp, $folder)){
         $update_image = ", image='$image_name'";
      }
   }

   /* UPDATE SEGURO */
   $query = "UPDATE users 
             SET name='$name', email='$email' $update_image 
             WHERE id='$user_id'";

   $run = mysqli_query($conn, $query);

   if($run){

      /* ATUALIZAR SESSÃO */
      $_SESSION['user_name'] = $name;
      $_SESSION['user_email'] = $email;

      header("location:profile.php?success=1");
      exit();

   }else{
      die("Erro ao atualizar perfil: ".mysqli_error($conn));
   }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Editar Perfil</title>

<style>

body{
   margin:0;
   font-family:'Poppins', sans-serif;
   background:linear-gradient(135deg,#0f172a,#1e3a8a);
   min-height:100vh;
   display:flex;
   align-items:center;
   justify-content:center;
}

/* CARD MODERNO */
.card{
   width:420px;
   background:rgba(255,255,255,.95);
   backdrop-filter:blur(10px);
   border-radius:25px;
   padding:35px;
   box-shadow:0 25px 60px rgba(0,0,0,.25);
   text-align:center;
   animation:fade .4s ease;
}

/* TITULO */
.card h2{
   color:#1e3a8a;
   margin-bottom:20px;
   font-size:28px;
}

/* IMAGEM */
.avatar{
   width:130px;
   height:130px;
   border-radius:50%;
   object-fit:cover;
   border:4px solid #2563eb;
   margin-bottom:15px;
}

/* INPUTS */
input{
   width:100%;
   padding:14px;
   margin:10px 0;
   border-radius:12px;
   border:1px solid #ddd;
   font-size:15px;
   outline:none;
}

input:focus{
   border-color:#2563eb;
   box-shadow:0 0 0 3px rgba(37,99,235,.15);
}

/* BOTÃO */
.btn{
   width:100%;
   padding:14px;
   border:none;
   border-radius:12px;
   background:linear-gradient(135deg,#2563eb,#3b82f6);
   color:#fff;
   font-weight:700;
   cursor:pointer;
   transition:.3s;
}

.btn:hover{
   transform:translateY(-3px);
}

/* BACK */
.back{
   display:block;
   margin-top:15px;
   color:#2563eb;
   text-decoration:none;
   font-weight:600;
}

.back:hover{
   text-decoration:underline;
}

/* SUCCESS MESSAGE */
.success{
   background:#22c55e;
   color:#fff;
   padding:10px;
   border-radius:10px;
   margin-bottom:10px;
   font-size:14px;
}

/* ANIMATION */
@keyframes fade{
   from{opacity:0; transform:translateY(15px);}
   to{opacity:1; transform:translateY(0);}
}

@media(max-width:500px){
   .card{width:90%;}
}

</style>
</head>

<body>

<div class="card">

   <h2>Editar Perfil</h2>

   <?php if(isset($_GET['success'])): ?>
      <div class="success">Perfil atualizado com sucesso!</div>
   <?php endif; ?>

   <img class="avatar" src="uploaded_img/<?php echo $image; ?>">

   <form method="post" enctype="multipart/form-data">

      <input type="text" name="name"
      value="<?php echo $user['name']; ?>" required>

      <input type="email" name="email"
      value="<?php echo $user['email']; ?>" required>

      <input type="file" name="image">

      <button type="submit" name="update_profile" class="btn">
         Guardar Alterações
      </button>

   </form>

   <a href="home.php" class="back">← Voltar</a>

</div>

</body>
</html>