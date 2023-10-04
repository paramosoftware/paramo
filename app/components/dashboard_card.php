<?php

$vs_tipo = $va_card["tipo"] ?? "acervo";
$vs_codigo = $va_card["codigo"] ?? "";
$vs_titulo = $va_card["titulo"] ?? "";
$vs_imagem = $va_card["imagem"] ?? "";
$vs_cor = $va_card["cor"] ?? "";
$vs_href = $va_card["href"] ?? "";
$va_itens = $va_card["itens"] ?? [];

?>
<div class="col-md-3 <?php echo $vs_tipo.' '; echo $vs_tipo.'-'.$vs_codigo; ?>">
    <div class="card deashboard-card mb-4">
        <div class="card-header small" <?php echo $vs_cor != "" ? 'style="background-color:' . $vs_cor . ';"' : ''; ?>>
            <a class="btn-tab small link-sem-estilo" id="<?php echo $vs_codigo; ?>" href="<?php echo htmlspecialchars($vs_tipo == "acervo" ? $vs_href : "javascript:void(0);"); ?>">
                <strong><?php echo $vs_titulo; ?></strong>
            </a>
        </div>
        <div class="card-body" style="font-size:12px;">
            <div class="row">
                <?php if ($vs_imagem != ""): ?>
                    <div class="col-6 text-center">
                        <?= utils::get_img_html_element($vs_imagem, "thumb", "img-fluid", null, htmlspecialchars($vs_titulo)); ?>
                    </div>
                <?php endif; ?>
                <div class="<?php echo $vs_imagem != "" ? 'col-6' : 'col-12'; ?>">
                    <?php if ($vs_tipo == "acervo"): ?>
                        <?php foreach ($va_itens as $vs_item): ?>
                            <?php if ($vs_item != ""): ?>
                                <?php echo $vs_item; ?><br>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php foreach ($va_itens as $va_item): ?>
                            <div class="row">
                                <div class="col-12">
                                    <a class="btn-tab small link-sem-estilo" href="<?php echo htmlspecialchars($va_item["link"]); ?>">
                                        <?php echo $va_item["quantidade"] . ' ' . htmlspecialchars($va_item["nome"]); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>