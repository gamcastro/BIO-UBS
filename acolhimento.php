<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acolhimento de Paciente - BIO-UBS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-person-plus-fill"></i> Acolhimento à Demanda Espontânea</h4>
                </div>
                <div class="card-body">
                    <form action="processa_acolhimento.php" method="POST">

                        <div class="mb-4">
                            <label for="buscaPaciente" class="form-label fs-5"><strong>1. Identificar Paciente</strong></label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-lg" id="buscaPaciente" name="busca_paciente" placeholder="Digite o CPF ou CNS do paciente..." required>
                                <button class="btn btn-outline-secondary" type="button" id="btnBuscar"><i class="bi bi-search"></i> Buscar</button>
                            </div>
                            <div class="form-text">Busque o paciente para carregar os dados automaticamente.</div>
                        </div>

                        <div id="dadosPaciente" class="mb-4 p-3 bg-light border rounded" style="display: none;">
                            <h5 id="nomePaciente"></h5>
                            <p class="mb-0"><strong>Data de Nasc.:</strong> <span id="nascimentoPaciente"></span></p>
                            <input type="hidden" id="pacienteId" name="paciente_id">
                        </div>

                        <div class="mb-4">
                            <label for="queixaPrincipal" class="form-label fs-5"><strong>2. Registrar Queixa Principal</strong></label>
                            <textarea class="form-control" id="queixaPrincipal" name="queixa_principal" rows="3" placeholder="Ex: Dor de cabeça e febre há 2 dias..." required></textarea>
                            <div class="form-text">Descreva de forma breve o motivo da vinda do paciente.</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-arrow-right-circle-fill"></i> Encaminhar Paciente para Triagem
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="#" data-bs-toggle="modal" data-bs-target="#modalCadastroRapido">Paciente não encontrado? Realizar cadastro rápido.</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>