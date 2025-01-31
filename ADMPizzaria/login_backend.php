<?php
session_start(); // Inicia a sessão para armazenar dados

// Conexão com o banco de dados
require_once 'includes/conexao.php'; // Substitua com seu arquivo de conexão

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Validar os dados de login
    $query = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar se o usuário existe e se a senha está correta
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Salvar as informações do usuário na sessão
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];

        // Redirecionar para a página principal
        header('Location: admin.php');
        exit;
    } else {
        // Se falhar, redirecionar para o login com mensagem de erro
        header('Location: login.php?erro=1');
        exit;
    }
}
?>
