<footer class="footer bg-cor-branca shadow-sm">
    <div class="container-fluid">
        <div class="d-flex justify-content-end">
            <div class="row text-end">
                <p class="mb-0 text-muted small">
                    <?= config::get(["nome_instituicao"]) ?> &nbsp;|&nbsp;
                    <a href="https://paramosoftware.com.br/documentacao" target="_blank">Páramo v<?= config::get(["versao"]) ?></a>
                </p>
                <p class="mb-0 text-muted small">
                    Tempo de carregamento da página: <?= (microtime(true) - $_GET["start_time"]); ?>s <br>
                    Tempo de execução das queries: <?= $_GET["queries_execution_time"]; ?>s <br>
                    Número de queries: <?= $_GET["num_queries"]; ?>
                </p>
            </div>
        </div>
    </div>
</footer>
