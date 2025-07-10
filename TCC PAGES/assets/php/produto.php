<?php
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

// Função de marcação como inativo (ao clicar no botão de "excluir")
if (isset($_GET['delete_id'])) {
  $delete_id = $_GET['delete_id'];

  // Atualiza o status do produto para 'inativo'
  $sql_produtos = "UPDATE produtos SET status_prod = 'inativo' WHERE codigo_interno = ?";
  $stmt_produtos = $conn->prepare($sql_produtos);
  $stmt_produtos->bind_param("i", $delete_id);
  $stmt_produtos->execute();

  // Verifica se as atualizações foram bem-sucedidas
  if ($stmt_produtos->affected_rows > 0) {
    echo "<script>alert('Produto marcado como inativo com sucesso!');</script>";
    echo "<script>window.location.href='produto.php';</script>"; // Redireciona para a página de produtos
  } else {
    echo "<script>alert('Erro ao marcar o produto como inativo.');</script>";
    echo "<script>window.location.href='produto.php';</script>";
  }
}

// Consulta SQL para obter os dados de produtos e códigos de barras
$sql = "SELECT p.codigo_interno, p.descricao, p.valor, p.unidade_medida, p.status_prod, cb.cod_barras 
        FROM produtos p
        LEFT JOIN codigo_barras cb ON p.codigo_interno = cb.cod_interno";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SOFT BAKERY - Produtos</title>
  <link rel="stylesheet" href="../css/produto.css">
</head>

<body>
  <div class="icones">
    <div class="home-cad">
      <a href="./home.php">
        <img src="../img/voltar.png" alt="Voltar" class="voltar img">
      </a>
      <a href="./cad_codigo_barras.php">
        <img src="../img/codigo-de-barras.png" alt="Codigo de Barras" class="codigo_barras img"> <!-- sobre estoque minimo e maximo -->
      </a>
    </div>
    <a href="cad_Produto.php">
      <img src="../img/cad_prod.png" alt="Cadastrar" class="cadastro img">
    </a>
  </div>
  <div class="listra"></div>


  <div class="tbl">
    <table>
      <tr class="head">
        <th>Código Interno</th>
        <th>Código De Barras</th>
        <th>Descrição</th>
        <th>Valor</th>
        <th>Unidade de Medida</th>
        <th>Status</th>
        <th><img src="../img/edit.png" alt="editar" class="funcao"></th>
        <th><img src="../img/apagar.png" alt="apagar" class="funcao"></th>
      </tr>

      <?php
      if ($result->num_rows > 0) {
        // Exibe os dados de cada linha
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row["codigo_interno"] . "</td>";
          echo "<td>" . $row["cod_barras"] . "</td>";
          echo "<td>" . $row["descricao"] . "</td>";
          echo "<td>" . number_format($row["valor"], 2, ',', '.') . "</td>"; // Formata o valor
          echo "<td>" . $row["unidade_medida"] . "</td>";
          echo "<td>" . $row["status_prod"] . "</td>";

          // Botão de Editar
          echo "<td><a href='editar_produto.php?id=" . $row["codigo_interno"] . "'><img src='../img/edit.png' alt='editar' class='funcao'></a></td>";

          // Botão de Excluir (na verdade, marcar como inativo)
          echo "<td><a href='?delete_id=" . $row["codigo_interno"] . "' onclick='return confirm(\"Tem certeza que deseja marcar este produto como inativo?\");'><img src='../img/apagar.png' alt='apagar' class='funcao'></a></td>";

          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='7'>Nenhum produto encontrado.</td></tr>";
      }
      ?>

    </table>
  </div>
</body>

</html>

<?php
// Fecha a conexão com o banco de dados
$conn->close();
?>