//UPDATE ORDER CODE
jQuery(document).ready(function () {
    /* grab important elements */
    var _token = jQuery('input[type=hidden][name=_token]').val();
    var databaseTable = jQuery('input[type=hidden][name=databaseTable]').val();
    var primaryKey = jQuery('input[type=hidden][name=primaryKey]').val();
    var sortInput = jQuery('input[type=hidden][name=sortorder]');
    var list = jQuery('.draggable-column');
    /* create requesting function to avoid duplicate code */
    var request = function () {
        jQuery.ajax({
            beforeSend: function () {
                jQuery('.alert').removeClass('hidden');
                jQuery('.alert span').html('Salvando...');
            },
            data: '_token='+_token+'&databaseTable='+databaseTable+'&primaryKey='+primaryKey+'&sortorder='+sortInput[0].value,
            type: 'post',
            url: '/admin/update-order',
            complete: function (success) {
                jQuery('.alert span').html('Ordem editada com sucesso!');
            }
        });
    };
    /* worker function */
    var fnSubmit = function () {
        var sortOrder = [];
        $('.draggable-item').each(function () {
            sortOrder.push(jQuery(this).data('id'));
        });
        sortInput.val(sortOrder.join(','));
        request();
    };
    /* store values */
    $('.draggable-item').each(function () {
        var li = jQuery(this);
        li.data('id', li.attr('title')).attr('title', '');
    });
    /* sortables */
    list.sortable({
        opacity: 0.7,
        update: function () {
            fnSubmit();
        }
    });
    list.disableSelection();
    /* ajax form submission */
    jQuery('#formOrder').bind('submit', function (e) {
        if (e) e.preventDefault();
        fnSubmit();
    });
});