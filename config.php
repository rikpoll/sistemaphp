<?php
//conexao firebird
define("FB_DB_LL","firebird:dbname=192.168.6.13:C:\Alidata\Leirilis\Database\GIA.FDB;charset=UTF8");
define("FB_DB_VA","firebird:dbname=192.168.6.13:c:\Alidata\Varidauto\Database\GIA.FDB;charset=UTF8");
define("FB_DB_EM","firebird:dbname=192.168.6.13:c:\Alidata\Emporio\Database\GIA.FDB;charset=UTF8");
define("FB_DB_BP","firebird:dbname=192.168.6.13:c:\Alidata\Biapecas\Database\GIA.FDB;charset=UTF8");
define("FB_USER", "SYSDBA");
define("FB_PASS", "2t6rXhgX");

$fbConexaoLL = new PDO(FB_DB_LL, FB_USER, FB_PASS, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION) );
$fbConexaoVA = new PDO(FB_DB_VA, FB_USER, FB_PASS, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION) );
$fbConexaoEM = new PDO(FB_DB_EM, FB_USER, FB_PASS, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION) );
$fbConexaoBP = new PDO(FB_DB_BP, FB_USER, FB_PASS, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION) );

//conexão do banco de dados mysql
define("MYDB_PATH","localhost");
define("MYDB_USER", "root");
define("MYDB_PASS", "");
define("MYDB_BANCO", "dp-servicos");

$connect = mysqli_connect(MYDB_PATH,MYDB_USER,MYDB_PASS, MYDB_BANCO);

//conexão mssql
$serverName = "192.168.6.13,1435";
$connectionInfo = array( "Database"=>"dp-wms", "UID"=>"svcportais", "PWD"=>"RrfnjQx6yLh9ZT", "CharacterSet" => "UTF-8");
$connWMS = sqlsrv_connect( $serverName, $connectionInfo);
?>
