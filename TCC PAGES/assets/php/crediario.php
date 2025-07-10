<?php
session_start();

// Verificar se há produtos na sessão
if (isset($_SESSION['produtos']) && count($_SESSION['produtos']) > 0) {
  $produtos = $_SESSION['produtos'];
} else {
  // Caso não haja produtos, redireciona para a página 'caixa.php'
  header("Location: caixa.php");
  exit;  // Garante que o código abaixo não será executado
}

// Conexão com o banco de dados
$host = "localhost";
$username = "root";
$password = "";
$dbname = "padaria"; // Altere para o nome correto do seu banco de dados

try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Consultar os clientes
  $stmt = $conn->prepare("SELECT cod_cliente, nome, cpf, cnpj, st_devedor FROM cad_clientes");
  $stmt->execute();
  $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
  exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SOFT BAKERY - Crediário</title>
  <link rel="stylesheet" href="../css/crediario.css">
</head>

<body>
  <a href="./caixa.php">
    <img src="../img/seta_voltar.png" alt="Voltar" class="voltar">
  </a>

  <h2>Venda no Crediário</h2>

  <div class="tble2">
    <table class="tbl1">
      <thead>
        <tr>
          <th class="td1">Código</th>
          <th class="td1 des">Descrição</th>
          <th class="td1">Quantidade</th>
          <th class="td1 UM">UM</th>
          <th class="td1">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $total = 0;
        foreach ($produtos as $produto) {
          echo "<tr>
                <td class='td1'>{$produto['codigo']}</td>
                <td class='td1'>{$produto['descricao']}</td>
                <td class='td1'>{$produto['quantidade']}</td>
                <td class='td1'>{$produto['unidade_medida']}</td>
                <td class='td1'>R$ {$produto['subtotal']}</td>
              </tr>";
          $total += floatval(str_replace(',', '.', $produto['subtotal']));
        }
        ?>
      </tbody>
    </table>
  </div>


  <h3>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></h3>

  <h2 class="h1">Escolha o Cliente</h2>
  <div class="tble">
    <table class="tbl">
      <thead>
        <tr>
          <th>Cód Cliente</th>
          <th>Nome</th>
          <th>CPF</th>
          <th>CNPJ</th>
          <th>Status</th>
          <th>Ação</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($clientes as $cliente): ?>
          <tr>
            <td><?php echo $cliente['cod_cliente']; ?></td>
            <td><?php echo $cliente['nome']; ?></td>
            <td><?php echo $cliente['cpf']; ?></td>
            <td><?php echo $cliente['cnpj']; ?></td>
            <td><?php echo $cliente['st_devedor']; ?></td>
            <td>
              <?php
              // Verificar se o cliente está em débito (st_devedor = 'sim')
              if ($cliente['st_devedor'] != 'sim'): ?>
                <!-- Exibir o botão apenas se o status não for 'sim' -->
                <form method="POST" action="processar_crediario.php">
                  <input type="hidden" name="cod_cliente" value="<?php echo $cliente['cod_cliente']; ?>" />
                  <input type="hidden" name="cpf" value="<?php echo $cliente['cpf']; ?>" />
                  <input type="hidden" name="cnpj" value="<?php echo $cliente['cnpj']; ?>" />
                  <input type="submit" class="btn-selecionar" value="Selecionar Cliente" />
                </form>
              <?php else: ?>
                <!-- Caso o status seja 'sim', não exibe o botão -->
                <span>Cliente com débito</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>

</html>