<?php
include 'config.php';
session_start();

/* =========================
   BUSCAR PEDIDO
========================= */
if (!isset($_GET['id'])) {
    echo "Pedido inválido";
    exit();
}

$order_id = mysqli_real_escape_string($conn, $_GET['id']);

$order_query = mysqli_query($conn, "
   SELECT * FROM orders WHERE id='$order_id'
");

if (!$order_query || mysqli_num_rows($order_query) == 0) {
    echo "Pedido não encontrado";
    exit();
}

$order = mysqli_fetch_assoc($order_query);

/* =========================
   BUSCAR VENDEDOR
========================= */
$vendor_id = $order['admin_id'];

$vendor_query = mysqli_query($conn, "
   SELECT name
   FROM users
   WHERE id='$vendor_id'
");

$vendor = mysqli_fetch_assoc($vendor_query);

/* =========================
   ENVIAR EMAIL AO CLIENTE
========================= */
if(isset($_POST['send_email'])){

    $to = $order['email'];
    $subject = "Fatura do pedido #$order_id";

    $message = "
FATURA DO PEDIDO

Cliente: {$order['name']}
Vendedor: {$vendor['name']}
Produtos: {$order['total_products']}
Total: {$order['total_price']} Kz
Método: {$order['method']}
Data: {$order['placed_on']}
";

    $headers = "From: noreply@loja.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    mail($to, $subject, $message, $headers);

    echo "<script>alert('Fatura enviada com sucesso!');</script>";
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Fatura</title>

<style>
.box{
    width:60%;
    margin:40px auto;
    padding:20px;
    border:1px solid #ddd;
    font-family:Arial;
}

h2{text-align:center;}

.total{
    font-size:22px;
    font-weight:bold;
}

.btn{
    padding:10px 15px;
    border:none;
    cursor:pointer;
    color:white;
    margin:5px;
}

.print{background:green;}
.download{background:blue;}
.send{background:orange;}
</style>
</head>

<body>

<div class="box" id="fatura">

    <h2>📄 Comprovativo</h2>

    <!-- CLIENTE -->
    <p><b>Cliente:</b> <?= $order['name'] ?></p>
    <p><b>Email:</b> <?= $order['email'] ?></p>
    <p><b>Telefone:</b> <?= $order['number'] ?></p>
    <p><b>Endereço:</b> <?= $order['address'] ?></p>
    <p><b>Data:</b> <?= $order['placed_on'] ?></p>

    <hr>

    <!-- VENDEDOR -->
    <p><b>Vendedor:</b> <?= $vendor['name'] ?></p>

    <hr>

    <!-- PRODUTOS -->
    <p><b>Produtos:</b> <?= $order['total_products'] ?></p>

    <div class="total">
        Total: <?= $order['total_price'] ?> Kz
    </div>

    <hr>

    <!-- MÉTODO -->
    <p><b>Método de Pagamento:</b> <?= $order['method'] ?></p>

    <!-- BOTÕES -->
    <div style="margin-top:20px;">

        <button class="btn print" onclick="window.print()">🖨 Imprimir</button>

        <button class="btn download" onclick="baixarFatura()">📥 Baixar</button>

        <form method="POST" style="display:inline;">
            <button type="submit" name="send_email" class="btn send">
                📤 Enviar Fatura
            </button>
        </form>

    </div>

</div>

<script>
function baixarFatura(){
    const conteudo = document.getElementById('fatura').outerHTML;
    const blob = new Blob([conteudo], {type:"text/html"});

    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "fatura_<?= $order['id'] ?>.html";
    link.click();
}
</script>

</body>
</html>