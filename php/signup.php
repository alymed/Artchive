<?php
    $servername = "localhost";
    $username = "root"; 
    $password = ""; 
    $database = "loja"; 
    
    // Criar conexão com MySQL
    $conn = new mysqli($servername, $username, $password, $database);
    
    // Verificar conexão
    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signUp'])) {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $password = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    
        $sql = "INSERT INTO users (nome, email, senha) VALUES ('$nome', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Utilizador criado com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao criar utilizador: " . $conn->error . "');</script>";
        }        
    }
    ?>

<!DOCTYPE html>
<html>
<body>
    <h1>REGISTO</h1>

    <form method="post">
        <label for="nome">Nome</label>
        <input type="text" name="nome" required><br/>
        <label for="email">Email</label>
        <input type="email" name="email" required><br/>
        <label for="passe" >Palavra-passe</label>
        <input type="password" name="passe" required><br/>

        <button type="submit"name="signUp">Registar</button><br/>
    </form>
</body>
</html>

<?php $conn->close() ?>