<?php
    include "./conexao.php";

    $msg_error = "";
    $dsn = "mysql:host=localhost;dbname=metas";
    $username = "root";
    $password = "";
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

    try {
        $con = new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
        $msg_error = "<div class='alert alert-danger mt-3'>
            <p>Erro ao conectar com o banco de dados: " . $e->getMessage() . "</p>
            </div>";
    }

    if(isset($_POST["submit"])){
        $loginUser = $_POST["loginUser"];
        $senhaUser = hash('sha256',$_POST["senhaUser"]);
        $nomeUser = $_POST["nomeUser"];

        $sql = "SELECT * FROM usuario WHERE loginUser = '{$loginUser}' and senhaUser = '{$senhaUser}' and nomeUser = '{$nomeUser}'";
        $rs = $con->prepare($sql);
        $rs->execute();
        $dados = $rs->fetch(PDO::FETCH_ASSOC);
        $linha = $rs->rowCount();

        if($linha == 0){
            $sql = "INSERT INTO usuario (loginUser, senhaUser, nomeUser) VALUES (:loginUser, :senhaUser, :nomeUser)";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':loginUser', $loginUser);
            $stmt->bindParam(':senhaUser', $senhaUser);
            $stmt->bindParam(':nomeUser', $nomeUser);
            $stmt->execute();
            header('Location: login.php');
        }else{
            $msg_error = "<div class='alert alert-danger mt-3'>
                <p>Usuário ou senha confere.</p>
                </div>
            ";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body class="bg-secondary">
    <div class="container">
        <div class="row vh-100 align-items-center justify-content-center">
            <div class="col-10 col-sm-8 col-md-6 col-lg-4 p-4 bg-white shadow rounded">
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label" for="loginUser">Login</label>
                        <input class="form-control" type="text" name="loginUser" id="loginUser" required>
                    </div>

                    <div class="form-group mt-2">
                        <label class="form-label" for="senhaUser">Senha</label>
                        <input class="form-control" type="password" name="senhaUser" id="senhaUser" required>
                    </div>

                    <div class="form-group mt-2">
                        <label class="form-label" for="nomeUser">Nome</label>
                        <input class="form-control" type="text" name="nomeUser" id="nomeUser" required>
                    </div>
                    <button class="btn btn-success w-100 mt-3" type="submit" name="submit">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>