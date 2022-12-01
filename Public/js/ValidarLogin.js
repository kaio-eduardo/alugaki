$().ready(function () {

    var validator_login = $("#form_login").bind("invalid-form.validate", function () {
    }
    ).validate({
        validClass: "green-text success_msg",
        errorClass: "error_msg",
        errorElement: "span",
        rules: {
            username: {
                required: true,
                rangelength: [5, 32],
                pattern: /^[a-zA-Z0-9_. ]+$/
            },
            password: {
                required: true,
                rangelength: [8, 64]
            }
        },
        messages: {
            username: {
                pattern: 'Username não pode conter espaços ou caracteres especiais'
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    })
});