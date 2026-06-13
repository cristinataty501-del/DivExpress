<?php
session_start();
include 'config.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
   header('location:login.php');
   exit();
}

$admin_id = $_SESSION['admin_id'];

// Get admin info
$select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id'") or die('query failed');

if(mysqli_num_rows($select_users) > 0){
   $row = mysqli_fetch_assoc($select_users);
} else {
   header('location:login.php');
   exit();
}

// Create upload directory if it doesn't exist
if(!is_dir('uploaded_img')){
   mkdir('uploaded_img', 0777, true);
}

// Function to generate unique filename
function generateUniqueFilename($originalName) {
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $filename = time() . '_' . uniqid() . '.' . $extension;
    return $filename;
}

// Handle add product
if(isset($_POST['add_product'])){
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = $_POST['price'];
   $quantity = $_POST['quantity'];
   $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
   $category = mysqli_real_escape_string($conn, $_POST['category']);
   
   // Handle image upload with unique filename
   $image_original_name = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   
   // Generate unique filename to avoid special characters and duplicates
   $image = generateUniqueFilename($image_original_name);
   $image_folder = 'uploaded_img/'.$image;

   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

   if(mysqli_num_rows($select_product_name) > 0){
      $message[] = 'Nome do produto já existe';
   } else {
      // Check if file was uploaded successfully
      if($image_size > 0 && $image_size <= 2000000){
         if(move_uploaded_file($image_tmp_name, $image_folder)){
            $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, admin_id, price, quantity, descricao, category, image) VALUES('$name', '$admin_id', '$price', '$quantity', '$descricao', '$category','$image')") or die('query failed');
            
            if($add_product_query){
               $message[] = 'Produto adicionado com sucesso!';
            } else {
               $message[] = 'Erro ao adicionar produto ao banco de dados!';
               // Delete uploaded image if database insert fails
               if(file_exists($image_folder)){
                  unlink($image_folder);
               }
            }
         } else {
            $message[] = 'Erro ao fazer upload da imagem! Verifique as permissões da pasta.';
         }
      } else {
         $message[] = 'Imagem inválida ou muito grande (max 2MB)!';
      }
   }
}

// Handle delete product
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   if($fetch_delete_image && file_exists('uploaded_img/'.$fetch_delete_image['image'])){
      unlink('uploaded_img/'.$fetch_delete_image['image']);
   }
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
   exit();
}

// Handle update product
if(isset($_POST['update_product'])){
   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];
   $update_descricao = $_POST['update_descricao'];

   mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price', descricao = '$update_descricao' WHERE id = '$update_p_id'") or die('query failed');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'Imagem muito grande (max 2MB)';
      } else {
         // Generate unique filename for update
         $new_image_name = generateUniqueFilename($update_image);
         $update_folder = 'uploaded_img/'.$new_image_name;
         
         if(move_uploaded_file($update_image_tmp_name, $update_folder)){
            mysqli_query($conn, "UPDATE `products` SET image = '$new_image_name' WHERE id = '$update_p_id'") or die('query failed');
            if(file_exists('uploaded_img/'.$update_old_image)){
               unlink('uploaded_img/'.$update_old_image);
            }
            $message[] = 'Produto atualizado com sucesso!';
         } else {
            $message[] = 'Erro ao atualizar imagem!';
         }
      }
   }

   header('location:admin_products.php');
   exit();
}

// Display messages
if(isset($message)){
   foreach($message as $msg){
      echo '<div class="message"><span>'.$msg.'</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Products</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <style>
   @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

   *{
      margin:0;
      padding:0;
      box-sizing:border-box;
      font-family:Poppins, sans-serif;
   }

   body{
      background:#f1f5f9;
      margin-left: 280px;
   }

   /* MESSAGES */
   .message{
      position:fixed;
      top:20px;
      right:20px;
      background:#fff;
      padding:15px 20px;
      border-radius:8px;
      box-shadow:0 4px 12px rgba(0,0,0,.15);
      display:flex;
      align-items:center;
      gap:15px;
      z-index:1000;
   }

   /* FORM BOX */
   .form-box{
      background:#fff;
      padding:20px;
      border-radius:14px;
      box-shadow:0 8px 20px rgba(0,0,0,.06);
      width:100%;
      max-width:500px;
      margin:20px auto;
   }

   .form-box h3{
      text-align:center;
      margin-bottom:15px;
      color:#2563eb;
   }

   .box{
      width:100%;
      padding:10px;
      margin:8px 0;
      border:1px solid #e2e8f0;
      border-radius:8px;
      outline:none;
   }

   .btn{
      width:100%;
      padding:12px;
      background:#2563eb;
      color:#fff;
      border:none;
      border-radius:8px;
      cursor:pointer;
      font-weight:600;
      transition: background 0.3s;
   }

   .btn:hover{
      background:#1d4ed8;
   }

   /* GRID DE PRODUTOS */
   .grid{
      display:grid;
      grid-template-columns:repeat(3, 1fr);
      gap: 20px;
      padding:20px;
      max-width:1200px;
      margin:0 auto;
   }

   @media(max-width:900px){
      .grid{ grid-template-columns:repeat(2, 1fr); }
   }

   @media(max-width:600px){
      .grid{ grid-template-columns:1fr; }
   }

   /* CARD */
   .card{
      background:#fff;
      border-radius:14px;
      box-shadow:0 6px 18px rgba(0,0,0,.06);
      padding:12px;
      text-align:center;
      transition: transform 0.3s;
   }

   .card:hover{
      transform: translateY(-5px);
   }

   .card img{
      width:100%;
      height:160px;
      object-fit:cover;
      border-radius:10px;
   }

   .name{
      margin-top:10px;
      font-weight:600;
      color:#0f172a;
   }

   .price{
      color:#2563eb;
      font-weight:700;
      margin:6px 0;
      font-size:18px;
   }

   /* BOTÕES */
   .actions{
      display:flex;
      gap:8px;
      justify-content:center;
      margin-top:10px;
   }

   .actions a{
      flex:1;
      padding:8px;
      border-radius:8px;
      font-size:13px;
      text-decoration:none;
      font-weight:600;
      transition: all 0.3s;
   }

   .update{
      background:#dbeafe;
      color:#2563eb;
   }

   .update:hover{
      background:#2563eb;
      color:#fff;
   }

   .delete{
      background:#fee2e2;
      color:#dc2626;
   }

   .delete:hover{
      background:#dc2626;
      color:#fff;
   }

   .empty{
      text-align:center;
      grid-column:1/-1;
      color:#64748b;
      padding:20px;
   }
   </style>
</head>
<body>

<?php include 'admin_header.php'; ?>

<div class="form-box">
   <h3>Adicionar Produto</h3>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="text" name="name" class="box" placeholder="Nome do produto" required>
      <input type="number" name="price" class="box" placeholder="Preço" required>
      <input type="number" name="quantity" class="box" placeholder="Quantidade" required>
      <input type="text" name="descricao" class="box" placeholder="Descrição" required>
      <select name="category" class="box">
         <option value="electronico">electrónicos</option>
         <option value="roupas">Roupas</option>
         <option value="calcados">Calçados</option>
         <option value="acessorios">Acessórios</option>
         <option value="mobilias">Mobilias</option>
         <option value="veiculos">Veiculos</option>
      </select>
      <input type="file" name="image" class="box" accept="image/*" required>
      <input type="submit" value="Adicionar Produto" name="add_product" class="btn">
   </form>
</div>

<?php
if(isset($_GET['update'])){
   $update_id = $_GET['update'];
   $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
   
   if(mysqli_num_rows($update_query) > 0){
      while($fetch_update = mysqli_fetch_assoc($update_query)){
?>

<div class="form-box">
   <h3>Atualizar Produto</h3>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required>
      <input type="text" name="update_descricao" value="<?php echo $fetch_update['descricao']; ?>" class="box" required>
      <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" class="box" required>
      <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" width="120" style="margin:10px 0; border-radius:10px;">
      <input type="file" name="update_image" class="box" accept="image/*">
      <input type="submit" value="Atualizar Produto" name="update_product" class="btn">
      <a href="admin_products.php" style="display:block; text-align:center; margin-top:10px; background:#e2e8f0; padding:10px; border-radius:8px; text-decoration:none; color:#0f172a; font-weight:600;">Cancelar</a>
   </form>
</div>

<?php
      }
   }
}
?>

<div class="grid">
   <?php
   if($row['user_type'] == 'admin'){
      $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
   } else {
      $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE admin_id = '$admin_id'") or die('query failed');
   }
   
   if(mysqli_num_rows($select_products) > 0){
      while($fetch_products = mysqli_fetch_assoc($select_products)){
   ?>
   
   <div class="card">
      <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
      <div class="name"><?php echo $fetch_products['name']; ?></div>
      <div class="name"><?php echo $fetch_products['descricao']; ?></div>
      <div class="price">Kz<?php echo $fetch_products['price']; ?></div>
      <div class="actions">
         <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="update">Editar</a>
         <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete" onclick="return confirm('Tem certeza que deseja apagar este produto?');">Apagar</a>
      </div>
   </div>
   
   <?php
      }
   } else {
      echo '<p class="empty">Nenhum produto encontrado</p>';
   }
   ?>
</div>

<script>
// Auto-hide messages after 3 seconds
setTimeout(function(){
   const messages = document.querySelectorAll('.message');
   messages.forEach(function(message){
      message.style.display = 'none';
   });
}, 3000);
</script>

</body>
</html>