(function($) {
    $('.addCart').click(function(event){
        event.preventDefault();
        $.get($(this).attr('href'), {}, function(data){
            if(data.error){
                alert(data.message);
            }else{
                alert(data.message);
                if(confirm(data.message + '. Voulez vous consulter votre panier ?')){
                    location.href = 'cart.php';
                }else{
                    $('#total').empty().append(data.total);
                    $('#count').empty().append(data.count);
                }
            }
        },'json');
    return false;
    });

})(jQuery);
