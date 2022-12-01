$().ready(function () {
    jQuery.extend(jQuery.validator.messages, {
        required: "Este campo é obrigatório",
        pattern: "O formato está incorreto",
        email: "Entre com uma conta de email válida",
        equalTo: "Os dados precisam ser iguais",
        maxlength: jQuery.validator.format("O número máximo de caracteres é de {0}."),
        minlength: jQuery.validator.format("O número mínimo de caracteres é de {0}."),
        rangelength: jQuery.validator.format("O valor precisa estar entre {0} e {1} caracteres."),
        range: jQuery.validator.format("Please enter a value between {0} and {1}."),
        max: jQuery.validator.format("O valor tem que ser menor que {0}."),
        min: jQuery.validator.format("não são permitidos valores menores que {0}.")
    })
})