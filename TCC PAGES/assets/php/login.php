<?php

// Configurações de conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "padaria";

// Cria conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checa a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$message = ''; // Inicializa a variável de mensagem

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lgn = trim($_POST['lgn']);
    $senha = trim($_POST['senha']);

    // Prepara a consulta para evitar injeção de SQL
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE nome = ? AND senha = ?");
    $stmt->bind_param("ss", $lgn, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se um usuário foi encontrado
    if ($result->num_rows > 0) {
        $message = 'Login bem-sucedido!';
        header('Location: home.php'); // Redireciona para a página de sucesso
        exit();
    } else {
        $message = 'Usuário ou senha incorretos.';
    }

    // Fecha a consulta
    $stmt->close();
}

// Fecha a conexão com o banco de dados
$conn->close();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOFT BAKERY - Login</title>
   
    <link rel="stylesheet" href="../css/login.css">

</head>

<body>
    <div class="mae">
        <form action="login.php" method="post">
            <label>USUÁRIO:</label>
            <br>
            <input type="text" id="lgn" name="lgn" placeholder="Login" required>
            <br>
            <label>SENHA:</label>
            <br>
            <input type="password" id="senha" name="senha" placeholder="Senha" required>
            <br>
            <button type="submit">Entrar</button>
        </form>

        <?php if ($message): ?> <!-- Exibe a mensagem de erro se existir -->
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
    <div class="logo">
        <h1 id="soft">SOFT</h1>
        <h1>BAKERY</h1>
    </div>
</body>

</html>
