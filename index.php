<?php
session_start();
require_once 'services/ApiClient.php'; // Inclui a classe ApiClient para interagir com a API

// Exemplo de uso da classe ApiClient
$apiClient = new ApiClient();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LembreFácil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<?php include('navbar.php'); ?>
    <div class="container mt-4">
        <?php include('mensagem.php'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4> Lista de Médicos
                            <a href="medico-create.php" class="btn btn-primary float-end">Adicionar Médico</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Data Nascimento</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Agora você usa os dados que vêm da API
                                if (!empty($medicos)) {
                                    foreach($medicos as $medico) {
                                ?>
                                <tr>
                                    <td><?= $medico['id'] ?></td>
                                    <td><?= $medico['nome'] ?></td>
                                    <td><?= $medico['email'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($medico['data_nascimento'])) ?></td>
                                    <td>
                                        <a href="medico-view.php?id=<?= $medico['id'] ?>" class="btn btn-secondary btn-sm"><span class="bi-eye-fill"></span>&nbsp;Visualizar</a>
                                        <a href="medico-edit.php?id=<?= $medico['id'] ?>" class="btn btn-success btn-sm"><span class="bi-pencil-fill"></span>&nbsp;Editar</a>
                                        <form action="acoes.php" method="POST" class="d-inline">
                                            <button onclick="return confirm('Tem certeza que deseja excluir?')" type="submit" name="delete_medicos" value="<?= $medico['id'] ?>" class="btn btn-danger btn-sm">
                                                <span class="bi-trash3-fill"></span>&nbsp;Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    echo '<h5>Nenhum médico encontrado</h5>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
