<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SOFT BAKERY - Clientes</title>
  <link rel="stylesheet" href="../css/clientes.css">
</head>

<body>
  <div class="icones">
    <a href="./home.php">
      <img src="../img/voltar.png" alt="Voltar" class="voltar">
    </a>
    <a href="./cadastro_cliente.php">
      <img src="../img/cadastro.png" alt="Cadastrar" class="cadastro">
    </a>
  </div>
  <div class="listra"></div>

  <div class="tbl">
    <table>
      <tr class="head">
        <th>Código do Cliente</th>
        <th>Nome</th>
        <th>Nome Fantasia</th>
        <th>CPF</th>
        <th>CNPJ</th>
        <th>E-mail</th>
        <th>Telefone</th>
        <th class="fim">Inadimplente</th>
        <th class="fim">Ação</th> <!-- Coluna para ação -->
      </tr>
      <!-- Abaixo vai o código PHP para inserir os dados dinamicamente na tabela -->
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

      // Consultar os dados de clientes
      $sql = "SELECT * FROM cad_clientes";
      $result = $conn->query($sql);

      // Verificar se há resultados
      if ($result->num_rows > 0) {
        // Iniciar a construção da tabela com os dados
        while ($row = $result->fetch_assoc()) {
          // Recuperar o código do cliente
          $cod_cliente = $row['cod_cliente'];

          // Recuperar o cod_vend diretamente da tabela cad_clientes
          $cod_vend = $row['cod_vend']; // Aqui você já tem o cod_vend diretamente

          // Exibir os dados na tabela
          echo "<tr>";
          echo "<td>" . $row['cod_cliente'] . "</td>";
          echo "<td>" . $row['nome'] . "</td>";
          echo "<td>" . $row['nome_fantasia'] . "</td>";
          echo "<td>" . $row['cpf'] . "</td>";
          echo "<td>" . $row['cnpj'] . "</td>";
          echo "<td>" . $row['email'] . "</td>";
          echo "<td>" . $row['telefone'] . "</td>";
          echo "<td class='fim'>" . $row['st_devedor'] . "</td>"; // Mostra se é inadimplente
          // echo "<td class='fim'>" . $cod_vend . "</td>"; // Exibe o cod_vend

          // Adiciona o botão de pagamento apenas se o cliente for inadimplente
          // Adiciona o botão de pagamento, passando o cod_vend para a URL
          if ($row['st_devedor'] == "sim") {
            echo "<td class='fim'><a href='crediario_pagar.php?cod_vend=" . $row['cod_vend'] . "'><button>Pagar Dívida</button></a></td>";
          } else {
            echo "<td class='fim'></td>"; // Não mostra o botão para quem não deve
          }
        }
      } else {
        echo "<tr><td colspan='8'>Nenhum cliente encontrado.</td></tr>";
      }

      // Fechar a conexão
      $conn->close();
      ?>

    </table>
  </div>
</body>

</html>