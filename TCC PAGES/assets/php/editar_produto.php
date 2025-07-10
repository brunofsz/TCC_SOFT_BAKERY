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

// Verifica se o parâmetro 'id' foi passado via URL
if (isset($_GET['id'])) {
    $codigo_interno = $_GET['id'];

    // Consulta para obter os dados do produto
    $sql = "SELECT * FROM produtos WHERE codigo_interno = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codigo_interno);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o produto existe
    if ($result->num_rows > 0) {
        $produto = $result->fetch_assoc();
    } else {
        echo "Produto não encontrado!";
        exit;
    }
}

// Atualiza o produto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $unidade_medida = $_POST['unidade_medida'];
    $status_prod = $_POST['status_prod']; // Pega o status do produto

    // Atualiza os dados no banco de dados
    $sql_update = "UPDATE produtos SET descricao = ?, valor = ?, unidade_medida = ?, status_prod = ? WHERE codigo_interno = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sdssi", $descricao, $valor, $unidade_medida, $status_prod, $codigo_interno);

    if ($stmt_update->execute()) {
        echo "<script>alert('Produto atualizado com sucesso!');</script>";
        echo "<script>window.location.href='produto.php';</script>"; // Redireciona para a página de produtos
    } else {
        echo "<script>alert('Erro ao atualizar o produto!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOFT BAKERY - Editar Produto</title>
    <!-- Link para o arquivo CSS externo -->
    <link rel="stylesheet" href="../css/editar_produto.css">
</head>

<body>

    <div class="mae">
        <h2>Editar Produto</h2>

        <form method="POST" action="editar_produto.php?id=<?php echo $produto['codigo_interno']; ?>">
            <label for="descricao">Descrição:</label>
            <input type="text" name="descricao" id="descricao" value="<?php echo $produto['descricao']; ?>" required>

            <label for="valor">Valor:</label>
            <input type="number" step="0.01" name="valor" id="valor" value="<?php echo $produto['valor']; ?>" required>

            <label for="unidade_medida">Unidade de Medida:</label>
            <input type="text" name="unidade_medida" id="unidade_medida" value="<?php echo $produto['unidade_medida']; ?>" required>

            <label for="status_prod">Status do Produto:</label>
            <select name="status_prod" id="status_prod" required>
                <option value="ativo" <?php echo $produto['status_prod'] == 'ativo' ? 'selected' : ''; ?>>Ativo</option>
                <option value="inativo" <?php echo $produto['status_prod'] == 'inativo' ? 'selected' : ''; ?>>Inativo</option>
            </select>

            <button type="submit">Salvar Alterações</button>
        </form>

        <a href="produto.php">Voltar para a lista de produtos</a>
    </div>
    <div class="logo">
        <h1 id="soft">SOFT</h1>
        <h1>BAKERY</h1>
    </div>

</body>

</html>
