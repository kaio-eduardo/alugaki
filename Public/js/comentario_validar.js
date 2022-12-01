$().ready(function () {
    var validator_coment = $("#form_coment").bind("invalid-form.validate", function () {
    }).validate({
        validClass: "green-text success_msg",
        errorClass: "error_msg",
        errorElement: "span",
        rules: {
            comentario: {
                required: true,
                rangelength: [10, 240]
            },
            grade: {
                required: true,
                integer: true,
                range: [1, 5]
            }
        },
        messages: {
            comentario: {
                rangelength: 'Comentario dete ter entre {0} e {1}'
            },
            grade: {
                integer: 'O valor deve ser um numero inteiro',
                range: 'A nota deve ser entre {0} e {1}'
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
});