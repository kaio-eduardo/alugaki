function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $("#preview").attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$().ready(function () {

    $('#cpf_input').mask('000.000.000-00');
    $('#tel_input').mask('(00) 00000-0000');
    $('#CEP_input').mask('00000-000');

    function limpa_formulário_cep() {
        // Limpa valores do formulário de cep.
        $("#cidade_input").val("");
        $("#estado_input").val("");
    }


    $("input[name=arqperfil]").change(function () {
        readURL(this);
        $("#preview").show("fast");
    });

    $("#CEP_input").blur(function () {

        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if (validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $("#cidade_input").val("");
                $("#estado_input").val("");

                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#cidade_input").val(dados.localidade);
                        $("#estado_input").val(dados.uf);
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulário_cep();
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    });

    $.validator.addMethod('cep', function (val, element) {

        var cep = val.replace(/\D/g, '');
        validacep = /^[0-9]{8}$/;

        //Valida o formato do CEP.
        if (!validacep.test(cep)) {
            return (this.optional(element) || false);
        }

        return (this.optional(element) || true);

    }, 'Cep invalido');
    jQuery.validator.addMethod('TEL', function (value, element) {
        value = value.replace("(", "");
        value = value.replace(")", "");
        value = value.replace("-", "");
        value = value.replace(" ", "").trim();
        if (value == '0000000000') {
            return (this.optional(element) || false);
        } else if (value == '00000000000') {
            return (this.optional(element) || false);
        }
        if (["00", "01", "02", "03", , "04", , "05", , "06", , "07", , "08", "09", "10"].indexOf(value.substring(0, 2)) != -1) {
            return (this.optional(element) || false);
        }
        if (value.length < 10 || value.length > 11) {
            return (this.optional(element) || false);
        }
        if (["6", "7", "8", "9"].indexOf(value.substring(2, 3)) == -1) {
            return (this.optional(element) || false);
        }
        return (this.optional(element) || true);
    }, 'Informe um celular válido');

    jQuery.validator.addMethod("cpf", function (value, element) {
        value = jQuery.trim(value);

        value = value.replace('.', '');
        value = value.replace('.', '');
        cpf = value.replace('-', '');
        while (cpf.length < 11) cpf = "0" + cpf;
        var expReg = /^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/;
        var a = [];
        var b = new Number;
        var c = 11;
        for (i = 0; i < 11; i++) {
            a[i] = cpf.charAt(i);
            if (i < 9) b += (a[i] * --c);
        }
        if ((x = b % 11) < 2) { a[9] = 0 } else { a[9] = 11 - x }
        b = 0;
        c = 11;
        for (y = 0; y < 10; y++) b += (a[y] * c--);
        if ((x = b % 11) < 2) { a[10] = 0; } else { a[10] = 11 - x; }

        var retorno = true;
        if ((cpf.charAt(9) != a[9]) || (cpf.charAt(10) != a[10]) || cpf.match(expReg)) retorno = false;

        return this.optional(element) || retorno;

    }, "Informe um CPF válido");


    var validator_login = $("#form_atualizarR").bind("invalid-form.validate", function () {
    }
    ).validate({
        validClass: "green-text success_msg validate",
        errorClass: "error_msg invalid",
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
                equalTo: "#password_input"
            },
            dnasc: {
                required: true,
                pattern: /^\d{1,2}\/\d{1,2}\/\d{4}$/
            },
            cep: {
                cep: true
            },
            cpf: {
                cpf: true
            },
            tel: {
                TEL: true
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