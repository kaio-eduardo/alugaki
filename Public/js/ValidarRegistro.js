$().ready(function () {

    var validator_registro = $("#form_cadastro").bind("invalid-form.validate", function () {
    }
    ).validate({
        validClass: "green-text success_msg",
        errorClass: "error_msg",
        errorElement: "span",
        rules: {
            firstname: {
                required: true,
                minlength: 2,
                pattern: /^[a-zA-ZÀ-Ÿ\']+$/
            },
            lastname: {
                required: true,
                minlength: 2,
                pattern: /\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+/
            },
            email: {
                required: true,
                email: true
            },
            username: {
                required: true,
                rangelength: [5, 32],
                pattern: /^[a-zA-Z0-9_.]+$/
            },
            password: {
                required: true,
                rangelength: [8, 64]
            },
            cpassword: {
                required: true,
                equalTo: "#input_password"
            },
            dnasc: {
                required: true,
                pattern: /^\d{1,2}\/\d{1,2}\/\d{4}$/
            }
        },
        messages: {
            username: {
                pattern: 'Username não pode conter espaços ou caracteres especiais'
            },
            cpassword: {
                equalTo: 'As senhas não conferem'
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    })
});