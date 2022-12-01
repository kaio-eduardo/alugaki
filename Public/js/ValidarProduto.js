$().ready(function () {

    $("#form_produto").validate({
        validClass: "green-text success_msg validate",
        errorClass: "error_msg invalid",
        errorElement: "span",
        rules: {
            nome: {
                required: true,
                rangelength: [5, 60],
            },
            valor: {
                required: true,
                number: true,
                min: 15,
                max: 40000
            },
            categoria: {
                required: true
            },
            estoque: {
                required: true,
                digits: true,
                min: 1,
                max: 999
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    })

});