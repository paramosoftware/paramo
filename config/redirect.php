<?php ?>
Arquivo de configuração não encontrado. Caso ainda não tenha instalado o sistema, clique no botão abaixo.
<br><br>
<button onclick="redirectInstall()">Instalar</button>
<script>
    function redirectInstall() {
        if (window.location.href.includes("/app/")) {
            let url = window.location.href.replace("/app/", "/install/");
            url = url.substring(0, url.indexOf("/install/") + 9);
            window.location.href = url;
        } else {
            if (window.location.href.endsWith("/")) {
                window.location.href = "install/";
            } else {
                window.location.href = "/install/";
            }
        }
    }
</script>
