<section id="rod" class="footer">


   

   
         <h3>Links rápidos</h3>
         <a href="pag_inicial.php">Início</a>
         <a href="about.php">Sobre</a>
         <a href="shop.php">Compra</a>
         <a href="contact.php">Contacto</a>
      </div>

      
         <h3>Links extras</h3>
         <a href="login.php">login</a>
         <a href="register.php">Cadastrar</a>
      </div>

      
         <h3>Informações de contacto</h3>
         <p><i class="fas fa-phone"></i> +244 937975019</p>
         <p><i class="fas fa-phone"></i> +244 944046832</p>
         <p><i class="fas fa-envelope"></i> divExpress@gmail.com</p>
         <p><i class="fas fa-map-marker-alt"></i> Luanda, Angola - 400104</p>
      </div>

      
         <h3>Siga-nos</h3>
         <a href="#"><i class="fab fa-facebook-f"></i> Facebook</a>
         <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
         <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
         <a href="#"><i class="fab fa-linkedin"></i> LinkedIn</a>
      </div>

   </div>

   <p class="credit">
      &copy; copyright @ <?php echo date('Y'); ?> by <span>mr. web designer</span>
   </p>

</section>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

.footer{
   background:#0f172a;
   color:#cbd5e1;
   padding:70px 8% 30px;
   font-family:'Poppins', sans-serif;
}

.footer .box-container{
   display:grid;
   grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
   gap:30px;
   margin-bottom:40px;
}

/* BOX */
.footer .box h3{
   color:#ffffff;
   font-size:18px;
   margin-bottom:18px;
   font-weight:700;
   position:relative;
}

.footer .box h3::after{
   content:'';
   width:40px;
   height:3px;
   background:#2563eb;
   position:absolute;
   left:0;
   bottom:-8px;
   border-radius:10px;
}

/* LINKS */
.footer .box a{
   display:block;
   color:#cbd5e1;
   font-size:14px;
   margin:10px 0;
   transition:.3s;
   text-decoration:none;
}

.footer .box a:hover{
   color:#2563eb;
   transform:translateX(5px);
}

/* CONTACT INFO */
.footer .box p{
   font-size:14px;
   margin:10px 0;
   color:#cbd5e1;
}

.footer .box i{
   color:#2563eb;
   margin-right:8px;
}

/* SOCIAL ICONS */
.footer .box a i{
   margin-right:8px;
}

/* CREDIT */
.credit{
   text-align:center;
   padding-top:20px;
   border-top:1px solid rgba(255,255,255,.1);
   font-size:14px;
   color:#94a3b8;
}

.credit span{
   color:#2563eb;
   font-weight:600;
}

/* RESPONSIVO */
@media(max-width:768px){
   .footer{
      text-align:center;
   }

   .footer .box h3::after{
      left:50%;
      transform:translateX(-50%);
   }
}
</style>