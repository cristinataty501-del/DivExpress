

<?php
session_start();

include 'config.php';


$admin_id = $_SESSION['admin_id'];


      $admin_id = $_SESSION['admin_id']; 
      $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id'") or die('query failed');

      if(mysqli_num_rows($select_users) > 0){

      $row = mysqli_fetch_assoc($select_users);

      }   

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){
   $admin_id = $_POST['admin_id'];
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = $_POST['price'];
   $quantity = $_POST['quantity'];
   $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
   $category = mysqli_real_escape_string($conn, $_POST['category']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

   if(mysqli_num_rows($select_product_name) > 0){
      $message[] = 'product name already added';
   }else{
      $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, admin_id, price, quantity, descricao, category, image) VALUES('$name', $admin_id, '$price', '$quantity', '$descricao', '$category','$image')") or die('query failed');

      if($add_product_query){
         if($image_size > 2000000){
            $message[] = 'image size is too large';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'product added successfully!';
         }
      }else{
         $message[] = 'product could not be added!';
      }
   }
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
}

if(isset($_POST['update_product'])){

   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];

   mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price' WHERE id = '$update_p_id'") or die('query failed');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'image file size is too large';
      }else{
         mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$update_old_image);
      }
   }

   header('location:admin_products.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>

<?php include 'admin_header.php'; ?>
<div class = "form-box">

<h3>Adicionar Produto</h3>

<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="admin_id" value="<?php echo $row['id']; ?>">
         <input type="text" name="name" class="box" placeholder="Nome do produto" required>
         <input type="number" name="price" class="box" placeholder="Preço" required>
         <input type="number" name="quantity" class="box" placeholder="Quantidade" required>
         <input type="text" name="descricao" class="box" placeholder="Descrição" required>
         <select name="category" class = "box">
         <option value="electronico">electrónicos</option>
         <option value="roupas">Roupas</option>
         <option value="calcados">Calçados</option>
         <option value="acessorios">Acessórios</option>
         <option value="mobilias">Mobilias</option>
         <option value="veiculos">Veiculos</option>
         </select>
         <input type="file" name="image" class="box" required>

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

      <input type="text"
      name="update_name"
      value="<?php echo $fetch_update['name']; ?>"
      class="box"
      required>

      <input type="text"
      name="update_descricao"
      value="<?php echo $fetch_update['descricao']; ?>"
      class="box"
      required>

      <input type="number"
      name="update_price"
      value="<?php echo $fetch_update['price']; ?>"
      class="box"
      required>

      <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" width="120" style="margin:10px 0; border-radius:10px;">

      <input type="file" name="update_image" class="box">

      <input type="submit"
      value="Atualizar Produto"
      name="update_product"
      class="btn">

      <a href="admin_products.php"
      style="
      display:block;
      text-align:center;
      margin-top:10px;
      background:#e2e8f0;
      padding:10px;
      border-radius:8px;
      text-decoration:none;
      color:#0f172a;
      font-weight:600;
      ">
      Cancelar
      </a>

   </form>

</div>

<?php
      }
   }
}
?>

   <!-- PRODUTOS EM LINHAS E COLUNAS -->
   <div class="grid">

      <?php
      if ($row['user_type'] == 'admin') {
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
      } else {
         $admin_id = $row['id'];
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
            <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete">Apagar</a>
         </div>

      </div>

      <?php
         }
      } else {
         echo '<p class="empty">Nenhum produto encontrado</p>';
      }
      ?>

   </div>

</div>

</body>
</html>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>
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
      display:grid;
      justify-content:center;
   }

   /* CONTAINER CENTRAL */
   .page{
      width:100%;
      max-width:1100px;
      padding:40px 20px;
   }

   /* TITULO */
   .title{
      text-align:center;
      font-size:28px;
      font-weight:700;
      margin-bottom:25px;
      color:#0f172a;
   }

   /* FORMULÁRIO (COMPACTO E CENTRAL) */
   .form-box{
      background:#fff;
      padding:20px;
      border-radius:14px;
      box-shadow:0 8px 20px rgba(0,0,0,.06);
      width:100%;
      max-width:500px;
      margin:0 auto 35px;
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
   }

   /* GRID DE PRODUTOS (LINHAS E COLUNAS) */
   .grid{
      display:grid;
      grid-template-columns:repeat(3, 1fr);
      gap: 10px;
   }

   /* RESPONSIVO */
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
   }

   .update{
      background:#dbeafe;
      color:#2563eb;
   }

   .delete{
      background:#fee2e2;
      color:#dc2626;
   }

   .empty{
      text-align:center;
      grid-column:1/-1;
      color:#64748b;
      padding:20px;
   }
   </style>