<?php

require_once 'services/ApiClient.php'; // Incluir o arquivo de API
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Médicos - Visualizar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <?php include('navbar.php'); ?>
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>Visualizar médico
                <a href="index.php" class="btn btn-danger float-end">Voltar</a>
              </h4>
            </div>
            <div class="card-body">
                <?php
              if (isset($_GET['id'])) {
                  $medicos_id = mysqli_real_escape_string($conexao, $_GET['id']);
                  $sql = "SELECT * FROM medicos WHERE id='$medicos_id'";
                  $query = mysqli_query($conexao, $sql);
                if (mysqli_num_rows($query) > 0) {
                  $medicos = mysqli_fetch_array($query);
                ?>
                <div class="mb-3">
                  <label>Nome</label>
                  <p class="form-control">
                    <?=$medicos['nome'];?>
                  </p>
                </div>
                <div class="mb-3">
                  <label>Email</label>
                  <p class="form-control">
                    <?=$medicos['email'];?>
                  </p>
                </div>
                <div class="mb-3">
                  <label>Data Nascimento</label>
                  <p class="form-control">
                    <?=date('d/m/Y', strtotime($medicos['data_nascimento']));?>
                  </p>
                </div>
                <?php
                } else {
                  echo "<h5>Usuário não encontrado</h5>";
                }
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>