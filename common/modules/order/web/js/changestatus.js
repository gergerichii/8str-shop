if (typeof shop == "undefined" || !shop) {
    var shop = {};
}

shop.changestatus = {
    csrf: null,
    csrf_param: null,
    init: function() {
        shop.changestatus.csrf = $('meta[name=csrf-token]').attr("content");
        shop.changestatus.csrf_param = $('meta[name=csrf-param]').attr("content");
        $(document).on('change', ".shop-change-order-status", this.changeStatus);
    },
    changeStatus: function() {
        var link = $(this);
        $(link).css('opacity', '0.2');
        
        data = {};
        data['status'] = $(this).val();
        data['id'] = $(this).data('id');
        data[shop.changestatus.csrf_param] = shop.changestatus.csrf;

        $.post($(this).data('link'), data,
            function(json) {
                if(json.result == 'success') {
                    $(link).css('opacity', '1');
                }
                else {
                    console.log(json.error);
                }

            }, "json");
        
        return false;
    },
};

shop.changestatus.init();
