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
    $descricao = $_POST['descricao'];
    $codigo_barras = $_POST['codigo_barras'];
    $unidade_medida = $_POST['unidade_medida'];
    $valor = $_POST['valor'];

    // Verifica se o código de barras já existe na tabela 'codigo_barras'
    $sql_verifica = "SELECT * FROM codigo_barras WHERE cod_barras = ?";
    $stmt_verifica = $conn->prepare($sql_verifica);
    $stmt_verifica->bind_param("s", $codigo_barras);
    $stmt_verifica->execute();
    $result_verifica = $stmt_verifica->get_result();

    if ($result_verifica->num_rows > 0) {
        echo "<script>alert('Erro: Código de barras já cadastrado.');</script>";
        echo "<script>window.location.href='cad_Produto.php';</script>"; // Redireciona para a página de cadastro
    } else {
        // Insere os dados na tabela de produtos
        $sql = "INSERT INTO produtos (descricao, unidade_medida, valor) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssd", $descricao, $unidade_medida, $valor);

        if ($stmt->execute()) {
            // Recupera o ID do produto inserido
            $codigo_interno = $stmt->insert_id;

            // Insere o código de barras associado
            $sql_barras = "INSERT INTO codigo_barras (cod_interno, cod_barras) VALUES (?, ?)";
            $stmt_barras = $conn->prepare($sql_barras);
            $stmt_barras->bind_param("is", $codigo_interno, $codigo_barras);
            $stmt_barras->execute();

            echo "<script>alert('Produto cadastrado com sucesso!');</script>";
            echo "<script>window.location.href='produto.php';</script>"; // Redireciona para a página 'produto.php'
        } else {
            echo "<script>alert('Erro ao cadastrar o produto.');</script>";
        }
    }

    // Fecha a conexão
    $stmt_verifica->close();
    $stmt->close();
    $stmt_barras->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOFT BAKERY - Cadastro de Produtos</title>
    <link rel="stylesheet" href="../css/cadProduto.css">
</head>

<body>
    <form action="cad_Produto.php" method="POST">
        <div class="titulo">
            <h1>Cadastro De Produtos</h1>
        </div>


        <h2>Dados Gerais Do Produto</h2>
        <div class="dados">
            <input type="text" name="descricao" placeholder="Descrição" class="input des" required>
            <input type="text" name="codigo_barras" placeholder="Código De Barras" class="input cdb" required>
        </div>

        <div class="mdd">
            <h2>Unidade De Medida</h2>
            <div class="slct kg">
                <input type="radio" name="unidade_medida" value="KG" class="radio" required>
                <label>Quilograma</label>
            </div>
            <div class="slct kg">
                <input type="radio" name="unidade_medida" value="UN" class="radio" required>
                <label>Unitário</label>
            </div>
        </div>

        <div class="valor">
            <input type="number" name="valor" step="0.01" placeholder="Valor" class="input vlr" required>
        </div>

        <div class="btns">
            <!-- Botão Cancelar redireciona para produto.php -->
            <input type="button" value="Cancelar" class="input btn cancelar" onclick="window.location.href='produto.php';">
            <input type="submit" value="Finalizar" class="input btn">
        </div>
    </form>
    <div class="logo">
        <h1 id="soft">SOFT</h1>
        <h1>BAKERY</h1>
    </div>
</body>

</html>