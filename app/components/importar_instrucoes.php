<?php

?>

<div class="mt-4">
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
