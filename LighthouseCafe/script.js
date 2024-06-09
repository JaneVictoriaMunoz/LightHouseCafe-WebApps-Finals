$(document).ready(function(){
    $(".veen .rgstr-btn button").click(function(){
        $('.veen .wrapper').addClass('move');
        $('.body').css('background','#FED8B1');
        $(".veen .login-btn button").removeClass('active');
        $(this).addClass('active');

    });
    $(".veen .login-btn button").click(function(){
        $('.veen .wrapper').removeClass('move');
        $('.body').css('background','#ECB176');
        $(".veen .rgstr-btn button").removeClass('active');
        $(this).addClass('active');
    });
});
$(document).ready(function() {
    $(".add-to-cart").click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var name = $(this).data('name');
        var price = $(this).data('price');

        $.ajax({
            url: 'ATC.php',
            method: 'POST',
            data: { add_to_cart: true, id: id, name: name, price: price },
            success: function(response) {
                var cartCount = parseInt($("#cart-count").text());
                $("#cart-count").text(cartCount + 1);
            }
        });
    });
});