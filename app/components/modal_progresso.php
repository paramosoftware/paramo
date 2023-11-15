<div class="modal fade" id="modal-progresso" tabindex="-1" data-coreui-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-progresso-title">
                    Processando
                </h5>
                <div class="col-md-6 text-end">
                    <button id="modal-progresso-cancel"
                            type="button"
                            class="btn btn-primary"
                            onclick="cancelProcessing = true;">
                        Cancelar
                    </button>
                </div>
            </div>


            <div class="modal-body">
                <div id="barra-progresso"
                     class="progress" role="progressbar"
                     aria-valuenow="0"
                     aria-valuemin="0"
                     aria-valuemax="100"
                     style="height: 20px">
                    <div class="progress-bar progress-bar-striped progress-bar-animated text-center" style="width: 0">0%</div>
                </div>
                <div id="modal-progresso-status" class="mt-2"></div>
            </div>

        </div>
    </div>
</div>