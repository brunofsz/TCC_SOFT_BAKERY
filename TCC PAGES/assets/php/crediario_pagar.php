<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "padaria";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
  die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o cod_vend foi passado na URL
if (isset($_GET['cod_vend'])) {
  $cod_vend = $_GET['cod_vend'];

  // Consultar dados do cliente com base no cod_vend
  $sql_cliente = "SELECT nome, comentario, valor, cod_vend, st_devedor FROM cad_clientes WHERE cod_vend = $cod_vend";
  $result_cliente = $conn->query($sql_cliente);

  if ($result_cliente === false) {
    die("Erro na consulta do cliente: " . $conn->error); // Se a consulta falhou
  }

  // Verificar se o cliente foi encontrado
  if ($result_cliente->num_rows > 0) {
    $cliente = $result_cliente->fetch_assoc();
  } else {
    die("Cliente não encontrado!"); // Se o cliente não existir
  }

  // Consultar as vendas com base no cod_vend
  $sql_vendas = "SELECT v.datah, v.cod_venda, iv.cod_int, iv.subtotal 
                 FROM vendas v
                 JOIN item_vendas iv ON v.cod_venda = iv.cod_ved
                 WHERE v.cod_venda = $cod_vend";

  // Verificar se a consulta das vendas foi bem-sucedida
  $result_vendas = $conn->query($sql_vendas);

  if ($result_vendas === false) {
    die("Erro na consulta das vendas: " . $conn->error); // Se a consulta falhou
  }
}

// Processar o pagamento quando o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $valor_pago = $_POST['valor_pago'];
  $comentario = $_POST['comentario'];

  // Calcular o valor restante da dívida
  $nova_divida = $cliente['valor'] - $valor_pago;

  // Se a dívida for quitada (valor <= 0), excluir os dados da dívida e atualizar o st_devedor
  if ($nova_divida <= 0) {
    // Excluir os dados da dívida (comentário, valor, cod_vend)
    $sql_update = "UPDATE cad_clientes SET 
                   comentario = NULL, 
                   valor = NULL, 
                   cod_vend = NULL, 
                   st_devedor = 'não' 
                   WHERE cod_vend = $cod_vend";

    if ($conn->query($sql_update) === TRUE) {
      // Redireciona para clientes.php após sucesso
      header('Location: clientes.php');
      exit(); // Garante que o script pare de executar após o redirecionamento
    } else {
      echo "<script>alert('Erro ao atualizar a dívida: " . $conn->error . "');</script>";
    }
  } else {
    // Caso a dívida não tenha sido quitada, atualiza o valor restante e o comentário
    $sql_update = "UPDATE cad_clientes SET valor = $nova_divida, comentario = '$comentario' WHERE cod_vend = $cod_vend";
    if ($conn->query($sql_update) === TRUE) {
      // Redireciona para clientes.php após sucesso
      header('Location: clientes.php');
      exit(); // Garante que o script pare de executar após o redirecionamento
    } else {
      echo "<script>alert('Erro ao atualizar a dívida: " . $conn->error . "');</script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SOFT BAKERY - Pagamento Crediário</title>
  <link rel="stylesheet" href="../css/crediario_pagar.css">
</head>

<body>
  <h1>Pagamento De Crediário</h1>
  <br>
  <h1>Referente ao pagamento do título abaixo listado:</h1>
  <br>

  <!-- Exibe o nome do cliente -->
  <div class="titulo">
    <h2>Nome do Cliente: <?php echo htmlspecialchars($cliente['nome']); ?></h2>
  </div>
  <br>

  <!-- Tabela das vendas -->
  <div class="tbl">
    <table>
      <tr>
        <th>Data Emissão</th>
        <th>Cod Venda</th>
        <th>Item Venda</th>
        <th class="fim">Valor</th>
      </tr>
      <?php
      // Exibe as vendas do cliente
      if ($result_vendas->num_rows > 0) {
        while ($venda = $result_vendas->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $venda['datah'] . "</td>";
          echo "<td>" . $venda['cod_venda'] . "</td>";
          echo "<td>" . $venda['cod_int'] . "</td>"; // Exibindo código do item
          echo "<td>" . $venda['subtotal'] . "</td>"; // Exibindo valor do item
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='4'>Nenhuma venda encontrada para este cliente.</td></tr>";
      }
      ?>
    </table>
  </div>

  <!-- Formulário de pagamento -->
  <form method="POST">
    <div class="inputs">
      <div class="input">
        <h3>Total da Dívida: </h3>
        <p id="totalDivida"><?php echo htmlspecialchars($cliente['valor']); ?> R$</p> <!-- Exibe o valor da dívida -->
      </div>

      <!-- Campo para o valor pago pelo cliente -->
      <div class="input">
        <h3>Valor Pago: </h3>
        <input class="input" type="number" id="valorPago" name="valor_pago" placeholder="Valor Dado Pelo Cliente" required oninput="calcularDividaRestante()">
      </div>

      <!-- Exibe o valor restante da dívida ou "Pago" se a dívida for quitada -->
      <div class="input">
        <h3>Dívida Restante: </h3>
        <input class="input" type="text" id="dividaRestante" value="<?php echo htmlspecialchars($cliente['valor']); ?> R$" readonly>
      </div>

      <!-- Campo para o comentário -->
      <div class="input">
        <h3>Comentário: </h3>
        <textarea name="comentario" placeholder="Comentário sobre o pagamento" required><?php echo htmlspecialchars($cliente['comentario']); ?></textarea>
      </div>

      <!-- Botão de pagamento -->
      <button type="submit" id="finalizarBtn">Concluir Pagamento</button>
    </div>
  </form>

  <script>
    // Função para calcular o valor restante da dívida
    function calcularDividaRestante() {
      let valorPago = parseFloat(document.getElementById("valorPago").value.replace(',', '.'));
      let totalDivida = <?php echo $cliente['valor']; ?>; // Valor da dívida

      // Se o valor pago for maior que 0, subtraímos da dívida
      if (!isNaN(valorPago) && valorPago >= 0) {
        let dividaRestante = totalDivida - valorPago;

        // Atualiza o valor da dívida restante
        if (dividaRestante <= 0) {
          document.getElementById("dividaRestante").value = "Pago";
        } else {
          document.getElementById("dividaRestante").value = dividaRestante.toFixed(2).replace('.', ',') + " R$";
        }
      }
    }
  </script>
</body>

</html>
