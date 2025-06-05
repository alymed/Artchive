<?php
session_start();

$servername = "localhost";
$username = "root";
$password = ""; // Caso haja uma senha para o banco, modifique aqui.
$database = "loja";

// Criar conexão com MySQL
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $passe = $_POST['passe'];

    // Consulta SQL para verificar o usuário
    $sql = "SELECT id, nome, passe FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // Protege contra SQL Injection
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Verificar se a senha corresponde ao hash da senha armazenado no banco de dados
        if (password_verify($passe, $row['passe'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_nome'] = $row['nome'];
            header("Location: app.php");
            exit();
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        $erro = "Usuário não encontrado!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>LOGIN</h1>

    <?php if (isset($erro)) { echo "<p style='color:red;'>$erro</p>"; } ?> <!-- Exibir erro se houver -->

    <form method="post">
        <label for="email">Email</label>
        <input type="email" name="email" required><br/>
        <label for="passe" >Palavra-passe</label>
        <input type="password" name="passe" required><br/>

        <button type="submit" name="login">Entrar</button><br/>
    </form>
    <p>Ainda não tem conta? <a href="registo.php">Registre-se aqui</a></p>

</body>
