(function($){
    $(document).ready(function(){

        setInterval(function(){
            refreshCsrf();
        }, 60 * 1000 * 5)

        $(window).focus(function(){
            refreshCsrf();
        })

    });

    function refreshCsrf(){
        $.ajax({
            url: config.endpoints.refresh_csrf,
            type: 'get',
        }).then(function (d) {
            $('meta[name="csrf-token"]').attr('content', d);
            $('[name="_token"]').val(d);
        });
    }
})(jQuery)
