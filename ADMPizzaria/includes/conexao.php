<?php
$host = "localhost";
$user = "";
$pass = "";
$db = "";

try {
    // Criando a conexão PDO
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    
    // Definindo o modo de erro do PDO para exceções
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
   
} catch (PDOException $e) {
    // Em caso de erro na conexão, exibe a mensagem
    die("Falha na conexão: " . $e->getMessage());
}
?>
