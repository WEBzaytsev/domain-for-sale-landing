$(document).ready(function() {


    $("#contactForm").unbind("submit").submit(function(e) {
        e.preventDefault();

        let th = $(this);

        let name = th.find('[name="name"]').val().trim();
        let email = th.find('[name="email"]').val().trim();
        if (name == '') {
            alert('Представьтесь пожалуйста.');
            return false;
        }
        if (email == '') {
            alert('Укажите контактную почту.');
            return false;
        }


        var type_method = 'POST';
        if (th.attr('method'))
            type_method = th.attr('method').toUpperCase();

        let body = null;
        if (type_method.toUpperCase() == 'GET') {
            body = $(th).serialize();

            if (body.indexOf('method_call=') == -1)
                body = body + '&method_call=' + th.attr('id');
        } else {
            body = new FormData(th[0]);
            if (!body.has('method_call'))
                body.append('method_call', th.attr('id'));
        }

        fetch('api.php', {
            method: 'POST',
            body: body
        }).then((response) => {
            if (!response.ok)
                return false;
            let c = response.headers.get('content-type');
            if (c) {
                return c.includes('application/json') ? response.json() : false;
            }
            return false;
        }).then((data) => {
            if (!data) {
                alert('Ошибка, попробуйте позже.');
                return;
            }
            if (data['true']) {
                $(".content__block-form").removeClass("active");
                $(".content__block-thanks").toggleClass("active");


                th.find('[name="name"]').val('');
                th.find('[name="email"]').val('');
            } else if (data['message']) {
                alert(data['message']);
            }
        }).catch(function(e) {
            console.log('Error: ' + e.message);
            console.log(e.response);
            alert('Ошибка, попробуйте позже.');
        });
        return false;
    });


    $(".content__start-link").click(function() {
        $(".content__block-start").hide();
        $(".content__block-game").toggleClass("active");
    });

    $(".content__back").click(function() {
        $(".content__block-game").removeClass("active");
        $(".content__block-start").show();
    });

    $("#content-form").click(function() {
        $(".content__block-start").hide();
        $(".content__block-form").toggleClass("active");
    });

    $("#send-from").click(function() {
        $("#contactForm").submit();
    });

    $("#go-game").click(function() {
        $(".content__block-thanks").removeClass("active");
        $(".content__block-game").toggleClass("active");
    });

    $('.faq__main-header').click(function() {
        $(this).toggleClass('active');
        $(this).next().slideToggle();
    });

    $('.faq__header').click(function() {
        $(this).toggleClass('active');
        $(this).next().slideToggle();
    })
});