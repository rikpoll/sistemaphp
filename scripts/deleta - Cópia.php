<?php
include_once '../config.php';
include_once '../banco.php';
$pag_atual="Delete Artigos";
include '../template/header.php';

if (!isset($_COOKIE['login'])) : 
  
  log1($connect, '', "Erro", $pag_atual, "Erro de acesso.");

?>

<div class="geral">
  <div class="erro">
    <h2>Faça login.</h2>
    <p>Você não tem autorização para ver esse conteúdo.</p>
    <p>Faça <a href="../login/login.php">login</a> para aceder.<p>
    <p>Obrigado.</p>
  <div>
</div> 

<?php die();
endif; 

log1($connect, $_COOKIE['login'], "Acesso", $pag_atual, "");

?>

<div class="deleta_container">
  <div class="pesquisa">
    <h2>Apagar Produtos</h2>
    <h3>Apagar produtos em todas as bases, caso seja possível.</h3>
    <hr />  
    <form method="POST">
        <label for="freferencia">Referência:</label>
        <input type="text" id="referencia" name="freferencia" onkeyup="this.value=this.value.toUpperCase()" autofocus>
        <input type="submit" class="botao" value="Buscar" name="busca_ref"><br />
    </form>
  </div><!--pesquisa-->

  <?php
    if (isset($_POST["busca_ref"])) {
      $ref=$_POST["freferencia"];
          
    
  ?>
  <h2>Apagar referencia <?php echo $ref; ?></h2>
  <form method="POST">
    <input type="hidden" name="ref" value=<?php echo $ref; ?>>
    <input type="submit" class="botao" value="Apagar" name="deleta">
  </form>

  <?php
    }
    if (isset($_POST["deleta"])) {
      $ref=$_POST["ref"];
      try {
        // Criar conexões com os bancos de dados

        //define("USER_SIA", "SYSDBA");
        //define("PASS_SIA", "2t6rXhgX");

        $LL = new PDO("firebird:dbname=192.168.6.15:D:\Alidata\Leirilis\Database\GIA.FDB", FB_USER, FB_PASS);
        //$VA = new PDO("firebird:dbname=192.168.6.13:c:\Alidata\Varidauto\Database\GIA.FDB", FB_USER, FB_PASS);
        //$EM = new PDO("firebird:dbname=192.168.6.13:c:\Alidata\Emporio\Database\GIA.FDB", FB_USER, FB_PASS);
        //$BP = new PDO("firebird:dbname=192.168.6.13:c:\Alidata\Biapecas\Database\GIA.FDB", FB_USER, FB_PASS);
        $UNO = new PDO("firebird:dbname=192.168.6.15:d:\dp\database\uno.fdb", FB_USER, FB_PASS);

        //$wms = new PDO("sqlsrv:Server=192.168.6.13,1435;Database=dp-wms", "svcportais", "RrfnjQx6yLh9ZT");
        //$portal = new PDO("sqlsrv:Server=ns3213222.ip-141-95-99.eu;Database=dp-store", "svcportais", "RrfnjQx6yLh9ZT");

        // Configurar o gerenciador
        $deleter = new MultiDatabaseDeleter();
        $deleter->addConnection($UNO, 'UNO');
        $deleter->addConnection($LL, 'LL');
        //$deleter->addConnection($db2, 'db2');
        //$deleter->addConnection($db3, 'db3');

        // Executar exclusão atômica
        $table = 'artigo';
        $condition = 'referencia = :ref';
        $params = [':ref' => $ref];

        $result = $deleter->deleteFromAllDatabases($table, $condition, $params);

        if ($result) {
            echo "Dado excluído com sucesso em todas as bases de dados!";
        } else {
            echo "Falha ao excluir o dado. Nenhuma alteração foi persistida.";
        }

      } catch (PDOException $e) {
        die("Erro de conexão com o banco de dados: " . $e->getMessage());
      }
    }
  
  ?>


<?php
class MultiDatabaseDeleter {
  private $connections = [];
  private $transactionActive = false;

  /**
   * Adiciona uma conexão de banco de dados ao gerenciador
   * @param PDO $connection Conexão PDO com o banco de dados
   * @param string $name Nome identificador da conexão
   */
  public function addConnection(PDO $connection, string $name) {
    $this->connections[$name] = $connection;
  }

  /**
   * Executa a exclusão em todas as bases de dados
   * @param string $table Nome da tabela
   * @param string $condition Condição WHERE para a exclusão
   * @param array $params Parâmetros para a query preparada
   * @return bool True se todas as exclusões foram bem-sucedidas, False caso contrário
   */
  public function deleteFromAllDatabases(string $table, string $condition, array $params = []): bool {
    try {
      // Inicia transações em todas as conexões
      $this->beginTransactions();

      // Prepara e executa as exclusões em todas as bases
      $deleteResults = [];
      foreach ($this->connections as $name => $conn) {
        $sql = "DELETE FROM {$table} WHERE codigo_arm='2' and {$condition}";
        $stmt = $conn->prepare($sql);
        
        $success = $stmt->execute($params);
        $deleteResults[$name] = $success;
        
        if (!$success) {
          throw new Exception("Falha ao excluir na base {$name}");
        }
      }

      // Se chegou aqui, todas as exclusões foram bem-sucedidas
      $this->commitAll();
      return true;

    } catch (Exception $e) {
      // Em caso de erro, faz rollback em todas as conexões
      $this->rollbackAll();
      error_log("Erro na exclusão atômica: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Inicia transações em todas as conexões
   */
  private function beginTransactions() {
    foreach ($this->connections as $conn) {
      if (!$conn->beginTransaction()) {
        throw new Exception("Falha ao iniciar transação");
      }
    }
    $this->transactionActive = true;
  }

  /**
   * Confirma transações em todas as conexões
   */
  private function commitAll() {
    if (!$this->transactionActive) return;

    foreach ($this->connections as $conn) {
      $conn->commit();
    }
    $this->transactionActive = false;
  }

  /**
   * Reverte transações em todas as conexões
   */
  private function rollbackAll() {
    if (!$this->transactionActive) return;

    foreach ($this->connections as $conn) {
      $conn->rollBack();
    }
    $this->transactionActive = false;
  }
}


/*try {
  // Criar conexões com os bancos de dados

  //define("USER_SIA", "SYSDBA");
  //define("PASS_SIA", "2t6rXhgX");

  $LL = new PDO("firebird:dbname=192.168.6.15:D:\Alidata\Leirilis\Database\GIA.FDB", FB_USER, FB_PASS);
  $VA = new PDO("firebird:dbname=192.168.6.13:c:\Alidata\Varidauto\Database\GIA.FDB", FB_USER, FB_PASS);
  $EM = new PDO("firebird:dbname=192.168.6.13:c:\Alidata\Emporio\Database\GIA.FDB", FB_USER, FB_PASS);
  $BP = new PDO("firebird:dbname=192.168.6.13:c:\Alidata\Biapecas\Database\GIA.FDB", FB_USER, FB_PASS);
  $UNO = new PDO("firebird:dbname=192.168.6.15:d:\dp\database\uno.fdb", FB_USER, FB_PASS);

  $wms = new PDO("sqlsrv:Server=192.168.6.13,1435;Database=dp-wms", "svcportais", "RrfnjQx6yLh9ZT");
  $portal = new PDO("sqlsrv:Server=ns3213222.ip-141-95-99.eu;Database=dp-store", "svcportais", "RrfnjQx6yLh9ZT");

  // Configurar o gerenciador
  $deleter = new MultiDatabaseDeleter();
  $deleter->addConnection($fbConexaoUNO, 'UNO');
  //$deleter->addConnection($db2, 'db2');
  //$deleter->addConnection($db3, 'db3');

  // Executar exclusão atômica
  $table = 'clientes';
  $condition = 'id = :id';
  $params = [':id' => 123];

  $result = $deleter->deleteFromAllDatabases($table, $condition, $params);

  if ($result) {
      echo "Dado excluído com sucesso em todas as bases de dados!";
  } else {
      echo "Falha ao excluir o dado. Nenhuma alteração foi persistida.";
  }

} catch (PDOException $e) {
  die("Erro de conexão com o banco de dados: " . $e->getMessage());
}*/
?>

</div><!-- deleta_container -->

<?php include '../template/footer.php'; ?>