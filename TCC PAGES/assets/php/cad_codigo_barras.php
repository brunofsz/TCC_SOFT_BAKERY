<?php
// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Configurações de conexão com o banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "padaria";

    // Cria a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Checa a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Recebe os dados do formulário
    $codigo_interno = $_POST['codigo_interno'];
    $codigo_barras = $_POST['codigo_barras'];

    // Verifica se o código de barras já existe para o produto
    $sql_verifica = "SELECT * FROM codigo_barras WHERE cod_barras = ? AND cod_interno = ?";
    $stmt_verifica = $conn->prepare($sql_verifica);
    $stmt_verifica->bind_param("si", $codigo_barras, $codigo_interno);
    $stmt_verifica->execute();
    $result_verifica = $stmt_verifica->get_result();

    if ($result_verifica->num_rows > 0) {
        echo "<script>alert('Erro: Este código de barras já foi associado a este produto.');</script>";
    } else {
        // Insere o código de barras para o produto selecionado
        $sql_barras = "INSERT INTO codigo_barras (cod_interno, cod_barras) VALUES (?, ?)";
        $stmt_barras = $conn->prepare($sql_barras);
        $stmt_barras->bind_param("is", $codigo_interno, $codigo_barras);

        if ($stmt_barras->execute()) {
            echo "<script>alert('Código de barras cadastrado com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar o código de barras.');</script>";
        }
    }

    // Fecha a conexão
    $stmt_verifica->close();
    $stmt_barras->close();
    $conn->close();
}

// Obtém a lista de produtos cadastrados para o dropdown
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "padaria";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$sql_produtos = "SELECT codigo_interno, descricao FROM produtos";
$result_produtos = $conn->query($sql_produtos);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOFT BAKERY - Cadastro de Código de Barras</title>
    <link rel="stylesheet" href="../css/cadProduto.css">
</head>

<body>
    <form action="cad_codigo_barras.php" method="POST">
        <div class="titulo">
            <h1>Cadastro De Código De Barras</h1>
            <h2>Associe um código de barras a um produto existente</h2>
        </div>

        <div class="dados">
            <!-- Seleção de Produto -->
            <label for="produto">Escolha o Produto:</label>
            <select name="codigo_interno" id="produto" required class="input des">
                <option value="">Selecione um Produto</option>
                <?php while ($row = $result_produtos->fetch_assoc()) { ?>
                    <option value="<?php echo $row['codigo_interno']; ?>"><?php echo $row['descricao']; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="dados">
            <!-- Campo para Código de Barras -->
            <label for="codigo_barras">Código De Barras:</label>
            <input type="text" name="codigo_barras" id="codigo_barras" placeholder="Código De Barras" class="input cdb"
                required>
        </div>

        <div class="btns">
            <!-- Botão Cancelar redireciona para produto.php -->
            <input type="button" value="Cancelar" class="input btn" onclick="window.location.href='produto.php';">
            <input type="submit" value="Cadastrar Código de Barras" class="input btn">
        </div>
    </form>
</body>

</html>