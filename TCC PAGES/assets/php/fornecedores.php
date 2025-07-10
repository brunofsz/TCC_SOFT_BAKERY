<?php
// Conectar ao banco de dados
$servername = "localhost"; // ou o seu servidor de banco de dados
$username = "root"; // seu usuário do banco de dados
$password = ""; // sua senha do banco de dados
$dbname = "padaria"; // nome do seu banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <!-- Definindo a codificação de caracteres como UTF-8 -->
    <meta charset="UTF-8">

    <!-- Configuração de visualização responsiva para diferentes tamanhos de tela -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Título da página que será exibido na aba do navegador -->
    <title>SOFT BAKERY - Cadastro de Fornecedores</title>

    <!-- Link para o arquivo de estilos CSS externo -->
    <link rel="stylesheet" href="../css/fornecedores.css">
</head>

<body>


    <header class="icones">

        <a href="home.php" class="voltar"> <img src="../img/voltar.png" alt="Cadastrar" class="cadastro"> </a>
        <a href="fornecedores_cadastro.php" class="cadastro"> <img src="../img/cadastro.png" alt="Cadastrar" class="cadastro"> </a> <!-- Título "Cadastro" -->
    </header>
    <div class="listra"></div>

    <!-- Tabela de dados -->
    <div class="tbl">
        <table>
            <thead class="head">
                <tr>
                    <!-- Cabeçalhos da tabela -->
                    <th>Código do Fornecedor</th>
                    <th>Nome Fantasia</th>
                    <th>Razão Social</th>
                    <th>CNPJ</th>
                    <th>Email</th>
                    <th>Telefone</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consulta SQL para buscar os dados
                $sql = "SELECT * FROM cad_fornecedores";
                $result = $conn->query($sql);

                // Verifica se há resultados
                if ($result->num_rows > 0) {
                    // Exibe os dados das linhas retornadas
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>"; // Inicia uma nova linha
                        echo "<td>" . $row["cod_fornecedores"] . "</td>";
                        echo "<td>" . $row["nome_fantasia"] . "</td>";
                        echo "<td>" . $row["razao_social"] . "</td>";
                        echo "<td>" . $row["cnpj"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["telefone"] . "</td>";
                        echo "</tr>"; // Finaliza a linha
                    }
                } else {
                    // Caso não haja resultados, exibe uma mensagem
                    echo "<tr><td colspan='6'>Nenhum fornecedor encontrado</td></tr>";
                }

                // Fecha a conexão
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>