<?php

 # ==================================== CONFIGURAÇÕES PADRÃO DO SISTEMA ==================================== #
 # Para alterar as configurações, crie um arquivo chamado settings.php na pasta config/custom e adicione as configurações que deseja alterar.
 # Exemplo:
 # <?php
 #  return [
 #      "nome_instituicao" => "Meu sistema"
 #  ];
 # Não altere este arquivo diretamente, pois ele poderá ser sobrescrito em atualizações futuras.

return [

    # ================================================ CLASSES ================================================ #

    # Nome da pasta onde estão as classes de negócio do sistema, dentro do caminho src/lib
    "pasta_business" => "default",

    # ================================================ DEPURAÇÃO ================================================ #

    "debug" => false,
    # Nível de log do sistema em arquivo e tela. Valores possíveis: https://www.php.net/manual/en/errorfunc.constants.php
    "error_report" => E_NOTICE,

    # ================================================ PERSONALIZAÇÃO ================================================ #

    "nome_instituicao" => "Páramo",

    # A descrição da instituição é exibida na página de login
    "descricao_instituicao" => "",

    # Tamanho do logo na página de login
    "logo_class" => "w-75",

    # Mensagem que aparece para datas marcadas como "sem data"
    "data_indisponivel" => "[s.d.]",

    # ================================================ MENU LATERAL ================================================ #

    # O menu lateral é composto por 3 seções padrões: institucional, permissões e configurações.
    # Cada seção é composta por um array de classes do sistema.
    "sidebar" => [
        "institucional" => [
            "conjunto_documental", "biblioteca", "instituicao", "tipo_material", "unidade_armazenamento"
        ],
        "permissoes" => [
            "grupo_usuario", "usuario"
        ],
        "configuracoes" => [
            "setor_sistema", "unidade_medida", "tipo_dimensao", "recurso_sistema", "campo_sistema",
            "formato_pagina", "paginas_etiquetas", "visualizacao", "pagina_etiquetas"
        ]
    ],

    # ================================================ UPLOAD  ================================================ #
    # Lista de recursos do sistema que podem ser enviados em lote
    "upload_lote_permitido" => ["livro", "documento", "textual"," iconografico", "periodico", "objeto", "cartografico", "audiovisual"],
    # Salva uma cópia do arquivo original no momento do upload
    "salvar_arquivo_original" => true,
    # Lista de extensões permitidas para upload de arquivos
    "extensoes_permitidas" => [
        "jpeg" => "image/jpeg",
        "jpg" => "image/jpeg",
        "png" => "image/png",
        "gif" => "image/gif",
        "pdf" => "application/pdf"
    ],

    # =============================================== FUNCIONALIDADES =============================================== #
    # São prefixadas com "f_".

    # Habilita a exibição no sidebar do menu para gerar relatório de atividades do usuário na extroversão.
    "f_extroversao_atividades_usuario" => false,
    # Habilita o envio de e-mails pelo sistema. Necessário configurar o SMTP em config/custom/envs.php
    "f_envio_email" => false,
    # Habilita a exibição do botão [Salvar e duplicar] no formulário de cadastro do registro
    "f_exibir_botao_salvar_duplicar" => false,
    # Habilita a seleção da ficha completa no momento da criação de um novo registro
    "f_ficha_completa_novo_registro" => false,
    # Habilita a exibição do botão para gerar etiquetas de listagens.
    "f_geracao_etiquetas" => false,
    # Habilita a integração com o Google Drive. Necessário configurar o Google Drive em config/custom/envs.php
    # Necessário PHP >= 8.0.2
    "f_integracao_google_drive" => false,
    # Permite a utilização de palavras-chave para controle de acesso, com possibilidade de restrições por acervos, espécies documentais e documentos
    "f_keywords" => false,
    # Permite que um usuário administrador de uma instituição administradora possa se logar como uma outra instituição
    "f_logado_como" => false,
    # Permite que um usuário de uma instituição veja os acervos e itens de outras instituições que compartilham a base de dados
    "f_usuario_pode_ver_todas_instituicoes" => true,
    # Habilita abertura do campo representantes digitais por padrão na ficha de cadastro
    "f_abrir_campo_representantes_digitais" => false,
    # Habilita operações em lote de edição e exclusão de registros
    "f_operacoes_lote" => false,
    # Habilita busca pela presença de valores nos filtros de navegação
    "f_filtros_busca_preenchimento_campo" => true,
    # Habilita a abertura do form de cadastro mesmo se o usuário tiver apenas permissão de leitura
    "f_acesso_leitura_form_cadastro" => false,

    # ============================================== CONTROLE DE ACESSO ============================================== #

    "controle_acesso" => [
        "_atributos_" => [
            "instituicao_codigo" => "vn_usuario_logado_instituicao_codigo",
            "acervo_codigo" => "vn_usuario_logado_acervo_codigo"
        ],
        "_combinacao_" => "AND"
    ]
];


