<?php
require_once('conexao.php');
$con = new PDO('mysql:host=localhost;dbname=metas', 'root', '');

session_start();

$results = $con->query('select * from metas')->fetchAll();

$arraySituacao = [1 => 'Aberta', 2 => 'Em Andamento', 3 => 'Realizada'];

include_once('layout/_header.php');

if(isset($_GET['excluir'])){
    $id = filter_input(INPUT_GET, 'excluir', FILTER_SANITIZE_NUMBER_INT);
    $query = "DELETE FROM metas WHERE id=:id";
     
    $stm=$con->prepare($query);
    $stm->bindParam('id',$id);
    $stm->execute();

    header('Location: http://localhost/seguranca/index.php');
}

if(isset($_SESSION["loginUser"]) && isset($_SESSION["senhaUser"])){
    $loginUser = $_SESSION["loginUser"];
    $senhaUser = $_SESSION["senhaUser"];
    $nomeUser = $_SESSION["nomeUser"];
    
    //$sql = "SELECT * FROM usuario WHERE loginUser = '{$loginUser}' and senhaUser = '{$senhaUser}'";
    $sql = "SELECT * FROM usuario WHERE loginUser = :loginUser and senhaUser = :senhaUser";
    $rs = $con->prepare($sql);
    $rs->execute([
        ':loginUser' => $loginUser, 
        ':senhaUser' => $senhaUser
    ]);
        $dados = $rs->fetch(PDO::FETCH_ASSOC);
        $linha = $rs->rowCount();

        if($linha == 0){
            session_unset();
            session_destroy();
            header('Location: http://localhost/seguranca/index.php');
            exit;
        }
}else{
    /*header('Location: login.php');
    exit;*/
}

?>


<div class="card mt-4">
    <div class="navbar-nav w-100 justify-content-end">
        <a href="logout.php" class="nav-link">
            <i class="bi -person"></i>
            <!--<?=$nomeUser?> Sair <i class="bi bi-box-arrow-right"></i>-->
        </a>
    </div>
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Minhas Tarefas</h5>
        <a class="btn btn-success" href="cadastro.php">Adicionar</a>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($results as $item):?>
                    <tr>
                        <td><?= $item['descricao']?></td>
                        <td><?=$arraySituacao[$item['situacao']]?></td>
                        <td>
                            <a class="btn btn-sm btn-primary" href="cadastro.php?id=<?= $item['id']?>">Editar</a>
                            <a class="btn btn-sm btn-danger" href="?excluir=<?=$item['id']?>">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once('layout/footer.php')?>