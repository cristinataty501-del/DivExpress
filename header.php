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

<header class="header">

   <!-- TOPO -->
   <div class="header-1">
      <div class="flex">

         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>

         <p>
            <a href="login.php">Login</a> |
            <a href="register.php">Cadastro</a>
         </p>

      </div>
   </div>

   <!-- CABEÇALHO -->
   <div class="header-2">
      <div class="flex">

         <!-- LOGO -->
         <a href="home.php" class="logo">
            Div<span>Express</span>
         </a>

         <!-- MENU -->
         <nav class="navbar">
            <a href="home.php">Início</a>
            <a href="about.php">Sobre</a>
            <a href="shop.php">Compras</a>
            <a href="contact.php">Contacto</a>
            <a href="orders.php">Pedidos</a>
            <a href="#categ">Categorias</a>
         </nav>

         <!-- ICONES -->
         <div class="icons">

            <div id="menu-btn" class="fas fa-bars"></div>

            <a href="search_page.php" class="fas fa-search"></a>

            <!-- USER -->
            <div id="user-btn" class="fas fa-user"></div>

            <?php
               $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
               $cart_rows_number = mysqli_num_rows($select_cart_number);
            ?>

            <!-- CARRINHO -->
            <a href="cart.php" class="cart-btn">
               <i class="fas fa-shopping-cart"></i>
               <span><?php echo $cart_rows_number; ?></span>
            </a>

         </div>

         <!-- USER BOX -->
         <div class="user-box">

            <div class="user-profile">

               <img src="<?php echo $_SESSION['user_image'] ?? 'default.jpg'; ?>" class="user-img">

               <div class="user-info">
                  <h4><?php echo $_SESSION['user_name']; ?></h4>
                  <p><?php echo $_SESSION['user_email']; ?></p>
               </div>

            </div>

            <a href="profile.php" class="edit-btn">
               <i class="fas fa-user-edit"></i>
               Editar Perfil
            </a>

            <a href="logout.php" class="delete-btn">
               <i class="fas fa-sign-out-alt"></i>
               Terminar Sessão
            </a>

         </div>

      </div>
   </div>

</header>

<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

:root{
   --primary:#2563eb;
   --primary2:#3b82f6;
   --dark:#0f172a;
   --gray:#64748b;
   --light:#ffffff;
   --shadow:0 10px 30px rgba(0,0,0,.08);
}

*{
   margin:0;
   padding:0;
   box-sizing:border-box;
   font-family:'Poppins', sans-serif;
}

body{
   background:#f8fafc;
}

/* ================== MENSAGEM ================== */

.message{
   position:sticky;
   top:0;
   z-index:2000;
   background:#2563eb;
   color:#fff;
   padding:15px 8%;
   display:flex;
   align-items:center;
   justify-content:space-between;
}

.message i{
   cursor:pointer;
   font-size:18px;
}

/* ================== HEADER ================== */

.header{
   position:sticky;
   top:0;
   left:0;
   width:100%;
   z-index:1000;
}

/* TOPO */

.header .header-1{
   background:linear-gradient(135deg,#0f172a,#1e3a8a);
   padding:12px 8%;
}

.header .header-1 .flex{
   display:flex;
   align-items:center;
   justify-content:space-between;
   flex-wrap:wrap;
   gap:15px;
}

.header .share{
   display:flex;
   gap:10px;
}

.header .share a{
   width:38px;
   height:38px;
   border-radius:50%;
   display:flex;
   align-items:center;
   justify-content:center;
   color:#fff;
   background:rgba(255,255,255,.10);
   transition:.3s;
   text-decoration:none;
}

.header .share a:hover{
   background:var(--primary);
   transform:translateY(-3px);
}

.header .header-1 p{
   color:#fff;
   font-size:14px;
}

.header .header-1 p a{
   color:#fff;
   font-weight:600;
   text-decoration:none;
}

/* HEADER PRINCIPAL */

.header .header-2{
   background:rgba(255,255,255,.96);
   backdrop-filter:blur(10px);
   box-shadow:var(--shadow);
   padding:18px 8%;
}

.header .header-2 .flex{
   display:flex;
   align-items:center;
   justify-content:space-between;
   gap:20px;
   position:relative;
}

/* LOGO */

.logo{
   font-size:30px;
   font-weight:800;
   color:var(--dark);
   text-decoration:none;
}

.logo span{
   color:var(--primary);
}

/* NAVBAR */

.navbar{
   display:flex;
   gap:25px;
}

.navbar a{
   text-decoration:none;
   color:var(--dark);
   font-weight:600;
   transition:.3s;
   position:relative;
}

.navbar a::after{
   content:'';
   position:absolute;
   left:0;
   bottom:-5px;
   width:0%;
   height:2px;
   background:var(--primary);
   transition:.3s;
}

.navbar a:hover{
   color:var(--primary);
}

.navbar a:hover::after{
   width:100%;
}

/* ICONS */

.icons{
   display:flex;
   align-items:center;
   gap:12px;
}

.icons div,
.icons a{
   width:45px;
   height:45px;
   border-radius:50%;
   display:flex;
   align-items:center;
   justify-content:center;
   background:#eff6ff;
   color:var(--dark);
   text-decoration:none;
   cursor:pointer;
   transition:.3s;
   position:relative;
}

.icons div:hover,
.icons a:hover{
   background:linear-gradient(135deg,var(--primary),var(--primary2));
   color:#fff;
   transform:translateY(-3px);
}

/* CART */

.cart-btn span{
   position:absolute;
   top:-5px;
   right:-5px;
   width:20px;
   height:20px;
   border-radius:50%;
   background:#ef4444;
   color:#fff;
   font-size:11px;
   display:flex;
   align-items:center;
   justify-content:center;
   font-weight:700;
}

/* USER BOX */

.user-box{
   position:absolute;
   top:120%;
   right:0;
   width:320px;
   background:#fff;
   border-radius:20px;
   padding:20px;
   box-shadow:0 15px 40px rgba(0,0,0,.12);
   display:none;
   animation:fadeIn .3s ease;
}

.user-box.active{
   display:block;
}

.user-profile{
   display:flex;
   align-items:center;
   gap:15px;
   margin-bottom:18px;
   padding-bottom:15px;
   border-bottom:1px solid #eee;
}

.user-img{
   width:65px;
   height:65px;
   border-radius:50%;
   object-fit:cover;
   border:3px solid var(--primary);
}

.user-info h4{
   color:var(--dark);
   font-size:16px;
}

.user-info p{
   color:var(--gray);
   font-size:13px;
   margin-top:4px;
}

/* BOTÕES */

.edit-btn,
.delete-btn{
   width:100%;
   display:flex;
   align-items:center;
   justify-content:center;
   gap:10px;
   padding:13px;
   border-radius:12px;
   color:#fff;
   text-decoration:none;
   font-weight:600;
   transition:.3s;
   margin-top:10px;
}

.edit-btn{
   background:linear-gradient(135deg,#3b82f6,#2563eb);
}

.delete-btn{
   background:linear-gradient(135deg,#ef4444,#dc2626);
}

.edit-btn:hover,
.delete-btn:hover{
   transform:translateY(-3px);
}

/* MENU BTN */

#menu-btn{
   display:none;
}

/* ANIMAÇÃO */

@keyframes fadeIn{
   from{
      opacity:0;
      transform:translateY(10px);
   }
   to{
      opacity:1;
      transform:translateY(0);
   }
}

/* ================= RESPONSIVO ================= */

@media (max-width:991px){

   .header .header-1,
   .header .header-2{
      padding:15px 4%;
   }

}

@media (max-width:768px){

   #menu-btn{
      display:flex;
   }

   .navbar{
      position:absolute;
      top:115%;
      left:0;
      right:0;
      background:#fff;
      border-radius:15px;
      box-shadow:var(--shadow);
      flex-direction:column;
      padding:15px;
      display:none;
   }

   .navbar.active{
      display:flex;
   }

   .navbar a{
      padding:12px;
      border-radius:10px;
   }

   .navbar a:hover{
      background:#eff6ff;
   }

   .user-box{
      width:100%;
      right:0;
   }

}

@media (max-width:450px){

   .logo{
      font-size:24px;
   }

   .icons{
      gap:8px;
   }

   .icons div,
   .icons a{
      width:40px;
      height:40px;
   }

}

</style>

<script>

let userBtn = document.querySelector('#user-btn');
let userBox = document.querySelector('.user-box');

let menuBtn = document.querySelector('#menu-btn');
let navbar = document.querySelector('.navbar');

/* USER BOX */

userBtn.onclick = () =>{
   userBox.classList.toggle('active');
   navbar.classList.remove('active');
}

/* MENU RESPONSIVO */

menuBtn.onclick = () =>{
   navbar.classList.toggle('active');
   userBox.classList.remove('active');
}

/* FECHAR AO SCROLL */

window.onscroll = () =>{
   userBox.classList.remove('active');
   navbar.classList.remove('active');
}

</script>