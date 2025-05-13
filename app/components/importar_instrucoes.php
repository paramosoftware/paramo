<?php
function get_import_config_options(): array
    {
        $va_import_config = [];

        $va_import_config["import_debug"] = [
            "name" => "import_debug",
            "label" => "Modo teste",
            "description" => "Nenhuma alteração é feita no banco de dados durante a importação e o relatório da importação é exibido ao final.",
            "value" => true,
            "type" => "checkbox"
        ];

        $va_import_config["import_allow_errors"] = [
            "name" => "import_allow_errors",
            "label" => "Tolerância a erros",
            "description" => "Caso alguma célula possua um valor inválido e não possa ser processada, o item será criado sem o valor da célula.",
            "value" => true,
            "type" => "checkbox"
        ];

        $va_import_config["import_upsert"] = [
            "name" => "import_mode",
            "label" => "Criar e atualizar itens",
            "description" => "Criar novos itens no banco de dados, caso não existam. Atualizar itens existentes no banco de dados, caso existam. O identificador do item deve estar presente no arquivo de importação para atualização.",
            "value" => "upsert",
            "type" => "radio",
            "checked" => "checked"
        ];

        $va_import_config["import_create"] = [
            "name" => "import_mode",
            "label" => "Somente criar itens",
            "description" => "Criar novos itens no banco de dados, caso não existam.",
            "value" => "create",
            "type" => "radio"
        ];

        $va_import_config["import_update"] = [
            "name" => "import_mode",
            "label" => "Somente atualizar itens",
            "description" => "Atualizar itens existentes no banco de dados, caso existam. O identificador do item deve estar presente no arquivo de importação.",
            "value" => "update",
            "type" => "radio"
        ];

        $va_import_config["import_delete"] = [
            "name" => "import_mode",
            "label" => "Apagar e recriar itens",
            "description" => "Caso existam itens no banco de dados, eles serão apagados e recriados com os dados do arquivo de importação. O identificador do item deve estar presente no arquivo de importação.",
            "value" => "delete",
            "type" => "radio"
        ];

        $va_import_config["import_default_value"] = [
            "name" => "import_default_value",
            "description" => "Valor padrão a ser utilizado caso o campo não esteja preenchido no arquivo de importação.",
            "label" => "Valor padrão",
            "type" => "text",
            "value" => "",
            "fields" => []
        ];

        $va_import_config["import_create_related"] = [
            "name" => "import_create_related",
            "label" => "Criar novos itens relacionados",
            "description" => "Caso o item relacionado não exista no banco de dados, será criado.",
            "value" => 1,
            "type" => "checkbox",
            "fields" => [
                "html_autocomplete",
            ]
        ];

        $va_import_config["import_relation_type"] = [
            "name" => "import_relation_type",
            "label" => "Tipo de relação padrão",
            "description" => "Tipo de relação a ser estabelecida entre os itens relacionados.",
            "value" => "",
            "type" => "select",
            "fields" => [
                "html_autocomplete"
            ]
        ];

        $va_import_config["import_separator"] = [
            "name" => "import_separator",
            "label" => "Separador de valores",
            "description" => "Caractere utilizado para separar os valores de um campo.",
            "value" => "",
            "type" => "text",
            "fields" => [
                "autocomplete_input",
                "multi_check_input",
                "multi_fields_input"
            ]
        ];

        $va_import_config["import_separator_subfield"] = [
            "name" => "import_separator_subfield",
            "label" => "Separador de subcampos",
            "description" => "Caractere utilizado para separar os subcampos.",
            "value" => "",
            "type" => "text",
            "fields" => [
                "multi_fields_input",
            ]
        ];

        $va_import_config["import_separator_hierarchy"] = [
            "name" => "import_separator_hierarchy",
            "label" => "Separador de hierarquia",
            "description" => "Caractere utilizado para separar os níveis de hierarquia.",
            "value" => "",
            "type" => "text"
        ];

        return $va_import_config;
    }
    $va_fields_import_options = get_import_config_options();
?>

<div class="mt-4 p-4">
    <h4 class="cil-bold">Opções de importação</h4>
    <table class="table table-bordered bg-light small">
        <thead>
        <tr>
            <th>Opção</th>
            <th>Descrição</th>
            <th>Valor</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($va_fields_import_options as $vs_option_id => $va_option) : ?>
            <?php if (!in_array($va_option["name"], ["import_default_value", "import_relation_type"]) && !isset($va_option["fields"])) : ?>
                <tr>
                    <td><strong><?= $va_option["label"]; ?></strong></td>
                    <td><?= $va_option["description"]; ?></td>
                    <td>
                        <input
                                class="<?= in_array($va_option["type"], ["checkbox", "radio"]) ? "form-check-input" : "form-control-sm form-control" ?>"
                                type="<?= $va_option["type"]; ?>"
                                name="parametros_importacao[<?= $va_option["name"]; ?>]"
                                value="<?= $va_option["value"]; ?>" <?= isset($va_option["checked"]) ? "checked" : ""; ?>
                        >
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>


    <h3>Instruções</h3>
    <ul>
        <li>O arquivo pode estar formato CSV ou XLSX (Excel).</li>
        <li> A primeira linha do arquivo <b>DEVE</b> conter a identificação dos dados.
            Caso utilize exatamente nome dos campos no formulário de cadastro, o sistema irá identificar automaticamente os campos.
            Caso contrário, será necessário selecionar o campo de destino para cada coluna do arquivo.
        </li>
    </ul>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Identificador</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Autoria</th>
            <th>...</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>Nome 1</td>
            <td>Descrição 1</td>
            <td>Autoria 1; Autoria 2</td>
            <td>...</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Nome 2</td>
            <td>Descrição 2</td>
            <td>Autoria 3</td>
            <td>...</td>
        </tr>
        </tbody>
    </table>
</div>