<?php

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit();
}

if(isset($_POST['order_btn'])){

   $cart_id = $_POST['cart_id'];
   $admin_id = $_POST['admin_id'];

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = $_POST['number'];
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $method = mysqli_real_escape_string($conn, $_POST['method']);

   $address = $_POST['prov'].' , '.$_POST['muni'].' - '.$_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);

   $placed_on = date('d-M-Y');

   /* VALIDAÇÕES */
   if(strlen($name) < 2){
      echo "Nome inválido";
      exit();
   }

   if(!preg_match("/^(?:\+244|244)?9\d{8}$/", $number)){
      echo "Telefone inválido";
      exit();
   }

   if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      echo "Email inválido";
      exit();
   }

   /* CARRINHO */
   $cart_total = 0;
   $cart_products = [];

   $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");

   if(mysqli_num_rows($cart_query) == 0){
      echo "Carrinho vazio";
      exit();
   }

   while($cart_item = mysqli_fetch_assoc($cart_query)){
      $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].')';
      $cart_total += $cart_item['price'] * $cart_item['quantity'];
   }

   $total_products = implode(', ', $cart_products);

   /* VERIFICAR DUPLICADO */
   $order_query = mysqli_query($conn, "
      SELECT * FROM orders 
      WHERE name='$name' 
      AND number='$number' 
      AND email='$email'
      AND total_price='$cart_total'
   ");

   if(mysqli_num_rows($order_query) > 0){
      echo "Pedido já existe";
      exit();
   }

   /* LUCRO */
   $select_users = mysqli_query($conn, "SELECT * FROM users WHERE id='$admin_id'");
   $row = mysqli_fetch_assoc($select_users);

   if($row['user_type'] == 'admin'){
      $cart_total_admin = $cart_total;
      $cart_total_vend = 0;
   }else{
      $cart_total_admin = $cart_total * 0.25;
      $cart_total_vend = $cart_total - $cart_total_admin;
   }

   /* INSERIR PEDIDO */
   mysqli_query($conn, "
      INSERT INTO orders(
         user_id, admin_id, name, number, email, method,
         address, total_products, total_price,
         total_price_admin, total_price_vend, placed_on
      )
      VALUES(
         '$user_id',
         '$admin_id',
         '$name',
         '$number',
         '$email',
         '$method',
         '$address',
         '$total_products',
         '$cart_total',
         '$cart_total_admin',
         '$cart_total_vend',
         '$placed_on'
      )
   ");

   $order_id = mysqli_insert_id($conn);

   /* LIMPAR CARRINHO */
   mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");
   mysqli_query($conn, "DELETE FROM selectcart WHERE user_id='$user_id'");

   /* REDIRECIONAR PARA FATURA */
   echo "<script>
   window.location.href = 'fatura.php?id=$order_id';
   </script>";
   exit();
}

?>
<!DOCTYPE html>
<html lang="pt">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Finalizar Compra</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>Finalizar Compra</h3>
   <p><a href="home.php">Início</a> / Finalizar Compra</p>
</div>

<section class="display-order">

   <?php  
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `selectcart` WHERE user_id = '$user_id'") or die('falha na consulta');

      if(mysqli_num_rows($select_cart) > 0){
         $fetch_cart = mysqli_fetch_assoc($select_cart);
         $total_price = ($fetch_cart['price'] * $fetch_cart['qty']);
         $grand_total = $total_price;
   ?>

   <p>
      <?php echo $fetch_cart['name']; ?> 
      <span>(<?php echo 'kz'.$fetch_cart['price'].' x '.$fetch_cart['qty']; ?>)</span>
   </p>

   <?php
      }else{
         echo '<p class="empty">Seu carrinho está vazio</p>';
      }
   ?>
   <h1><p>IBAN:AO06 0006 0000 70768961301 75</p></h1>
   <h1><p>Express:928884069</p></h1>
   <div class="grand-total">
      Total Geral : <span><?php echo $grand_total; ?>kz</span>
   </div>

</section>

<section class="checkout">

   <?php   
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('falha na consulta');
      $fetch_cart = mysqli_fetch_assoc($select_cart);
   ?>

   <form action="" method="post" enctype = "multipart/form-data" >

      <h3>Faça o seu pedido</h3>

      <div class="flex">

         <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
         <input type="hidden" name="admin_id" value="<?php echo $fetch_cart['admin_id']; ?>">

         <div class="inputBox">
            <span>Seu nome</span>
            <input type="text" name="name" required placeholder="Digite o seu nome" class="required" oninput="validarNome()" >
            <span class = "span-required" style = "color: red; display: none;">Nome inválido</span>
         </div>

         <div class="inputBox">
            <span>Seu número</span>
            <input type="number" name="number" required placeholder="Digite o seu número" class="required" oninput = "validarTelefone()">
            <span class = "span-required" style = "color: red; display: none;">Número de telefone inválido</span>
         </div>

         <div class="inputBox">
            <span>Seu e-mail</span>
            <input type="email" name="email" required placeholder="Digite o seu e-mail" class="required" oninput = "validaremail()">
            <span class = "span-required" style = "color: red; display: none;">email inválido</span>
         </div>

         <div class="inputBox">
            <span>Método de pagamento</span>
            <select name="method">
               <option value="pagamento_na_entrega">Pagamento na Entrega</option>
               <option value="multicaixa_express">Multicaixa Express</option>
               <option value="transferencia_bancaria">Transferência Bancária</option>
            </select>
         </div>

         <div class="inputBox">
            <span>província :</span>
            <select id="provincia"  class="box" name="prov" required>
            <option value="">Selecione a província</option>
            </select>
         </div>
          <div class="inputBox">
            <span>município :</span>
           <select id="municipio" class="box" name="muni" required>
           <option value="">Selecione o município</option>
           </select> 
         </div>

         <div class="inputBox">
   <span>Código</span>
   <input type="number" name="pin_code" id="pin_code" required readonly>
</div>
       
         </div> 
      <input type="submit" value="Fazer Pedido" class="btn" name="order_btn">

   </form>

</section>


<script>
   const form = document.getElementById('form');
   const campos = document.querySelectorAll('.required');
   const nomeRegex = /^[A-ZÀ-Ý][a-zà-ÿ]+(\s[A-ZÀ-Ý][a-zà-ÿ]+)+$/;
   const emailRegex = /^[^\s]+@[^\s]+\.[^\s]+$/;
   const telefoneRegex = /^(?:\+244|244)?9\d{8}$/;
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
   
   
   function validarTelefone() {
      if (telefoneRegex.test(campos[1].value) && campos[1].value.length >= 9) {
         valido(1);
      } else {
         erro(1);
      }
   }

   
   function validaremail() {
      if (emailRegex.test(campos[2].value)) {
         valido(2);
      } else {
         erro(2); 
      }
   } 

   const angola = {
    "Bengo": ["Ambriz", "Bula Atumba", "Dande", "Dembos", "Nambuangongo", "Pango Aluquém"],
    "Benguela": ["Balombo", "Baía Farta", "Benguela", "Bocoio", "Caimbambo", "Catumbela", "Chongoroi", "Cubal", "Ganda", "Lobito"],
    "Bié": ["Andulo", "Camacupa", "Catabola", "Chinguar", "Chitembo", "Cuemba", "Cunhinga", "Kuito", "Nharea"],
    "Cabinda": ["Belize", "Buco-Zau", "Cabinda", "Cacongo"],
    "Cuando Cubango": ["Calai", "Cuangar", "Cuchi", "Cuito Cuanavale", "Dirico", "Mavinga", "Menongue", "Nancova", "Rivungo"],
    "Cuanza Norte": ["Ambaca", "Banga", "Bolongongo", "Cambambe", "Cazengo", "Golungo Alto", "Lucala", "Ngonguembo", "Quiculungo", "Samba Caju"],
    "Cuanza Sul": ["Amboim", "Cassongue", "Cela", "Conda", "Ebo", "Libolo", "Mussende", "Porto Amboim", "Quibala", "Quilenda", "Seles", "Sumbe"],
    "Cunene": ["Cahama", "Cuanhama", "Curoca", "Cuvelai", "Namacunde", "Ombadja"],
    "Huambo": ["Bailundo", "Caála", "Catchiungo", "Chicala-Cholohanga", "Chinjenje", "Ecunha", "Huambo", "Londuimbali", "Longonjo", "Mungo", "Tchicala-Tcholoanga", "Ucuma"],
    "Huíla": ["Caconda", "Caluquembe", "Chibia", "Chicomba", "Chipindo", "Cuvango", "Humpata", "Jamba", "Lubango", "Matala", "Quilengues"],
    "Luanda": ["Belas", "Cacuaco", "Cazenga", "Ícolo e Bengo", "Luanda", "Quiçama", "Talatona", "Viana"],
    "Lunda Norte": ["Cambulo", "Capenda-Camulemba", "Caungula", "Chitato", "Cuango", "Cuilo", "Lubalo", "Lucapa", "Xá-Muteba"],
    "Lunda Sul": ["Cacolo", "Dala", "Muconda", "Saurimo"],
    "Malanje": ["Cacuso", "Calandula", "Cambundi-Catembo", "Cangandala", "Caombo", "Cuaba Nzogo", "Kalandula", "Luquembo", "Malanje", "Marimba", "Massango", "Mucari", "Quela", "Quirima"],
    "Moxico": ["Alto Zambeze", "Bundas", "Camanongue", "Cameia", "Léua", "Luacano", "Luchazes", "Luena", "Moxico"],
    "Namibe": ["Bibala", "Camucuio", "Moçâmedes", "Tômbwa", "Virei"],
    "Uíge": ["Alto Cauale", "Ambuíla", "Bembe", "Buengas", "Bungo", "Damba", "Maquela do Zombo", "Mucaba", "Negage", "Puri", "Quimbele", "Quitexe", "Sanza Pombo", "Songo", "Uíge", "Zombo"],
    "Zaire": ["Cuimba", "M'banza Congo", "Nóqui", "Nzeto", "Soyo", "Tomboco"]
};

const provinciaSelect = document.getElementById("provincia");
const municipioSelect = document.getElementById("municipio");

// Carregar todas as províncias ao abrir a página
for (let provincia in angola) {
    let option = document.createElement("option");
    option.value = provincia;
    option.text = provincia;
    provinciaSelect.appendChild(option);
}


// Quando selecionar província
provinciaSelect.addEventListener("change", function () {

    municipioSelect.innerHTML = '<option value="">Selecione o município</option>';

    let municipios = angola[this.value];

    if (municipios) {
        municipios.forEach(function (municipio) {
            let option = document.createElement("option");
            option.value = municipio;
            option.text = municipio;
            municipioSelect.appendChild(option);
        });
    }
});

function gerarCodigoPostal() {
   // gera um código de 6 dígitos
   let codigo = Math.floor(100 + Math.random() * 900);
   document.getElementById("pin_code").value = codigo;
}

// gerar ao carregar a página
window.onload = function () {
   gerarCodigoPostal();
};

// opcional: regenerar quando mudar província/município
document.getElementById("provincia").addEventListener("change", gerarCodigoPostal);
document.getElementById("municipio").addEventListener("change", gerarCodigoPostal);


</script>


</body>
</html>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>  
<style>
   @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

   :root{
      --primary:#2563eb;
      --dark:#0f172a;
      --gray:#64748b;
      --bg:#f1f5f9;
      --white:#fff;
      --shadow:0 15px 35px rgba(0,0,0,.08);
      --radius:18px;
      --success:#22c55e;
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

   /* HEADER */
   .heading{
      text-align:center;
      padding:70px 20px 35px;
      background:linear-gradient(135deg,var(--primary),#1d4ed8);
      color:#fff;
      border-radius:0 0 35px 35px;
   }

   .heading h3{
      font-size:40px;
      font-weight:800;
   }

   .heading p{
      margin-top:10px;
   }

   .heading a{
      color:#fff;
      font-weight:600;
   }

   /* DISPLAY ORDER */
   .display-order{
      padding:40px 8%;
      text-align:center;
   }

   .display-order p{
      background:#fff;
      display:inline-block;
      padding:10px 18px;
      margin:8px;
      border-radius:12px;
      box-shadow:var(--shadow);
      font-weight:500;
   }

   .display-order span{
      color:var(--primary);
      font-weight:700;
   }

   .grand-total{
      margin-top:20px;
      font-size:20px;
      font-weight:800;
      color:var(--dark);
   }

   .grand-total span{
      color:var(--success);
   }

   /* CHECKOUT */
   .checkout{
      padding:40px 8% 80px;
   }

   .checkout form{
      background:#fff;
      padding:30px;
      border-radius:20px;
      box-shadow:var(--shadow);
      max-width:1000px;
      margin:auto;
   }

   .checkout h3{
      text-align:center;
      font-size:28px;
      margin-bottom:25px;
      font-weight:800;
      color:var(--dark);
   }

   .flex{
      display:grid;
      grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));
      gap:20px;
   }

   .inputBox{
      display:flex;
      flex-direction:column;
   }

   .inputBox span{
      font-size:14px;
      font-weight:600;
      margin-bottom:6px;
      color:var(--dark);
   }

   .inputBox input,
   .inputBox select{
      padding:12px;
      border-radius:12px;
      border:1px solid #e2e8f0;
      outline:none;
      transition:.3s;
      background:#f8fafc;
   }

   .inputBox input:focus,
   .inputBox select:focus{
      border-color:var(--primary);
      background:#fff;
   }

   /* BUTTON */
   .btn{
      margin-top:25px;
      width:100%;
      padding:14px;
      border:none;
      border-radius:14px;
      background:linear-gradient(135deg,var(--primary),#1d4ed8);
      color:#fff;
      font-size:16px;
      font-weight:700;
      cursor:pointer;
      transition:.3s;
   }

   .btn:hover{
      transform:translateY(-3px);
   }

   /* EMPTY */
   .empty{
      background:#fff;
      padding:20px;
      border-radius:15px;
      box-shadow:var(--shadow);
      display:inline-block;
      margin:10px auto;
      color:var(--gray);
   }

   /* RESPONSIVO */
   @media(max-width:768px){
      .checkout form{
         padding:20px;
      }
   }

   </style>