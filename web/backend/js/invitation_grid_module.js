var invitation_grid_module = function($) {

    var gridPjaxContainer = $('#invitation-grid-pjax');

    var initApproveAllControl = function() {
        var btnElement = $('#approve-all');
        var link = btnElement.data('link');

        btnElement.click(function (e) {
            processBulkAction(link);
        })
    };

    var initDeclineAllControl = function() {
        var btnElement = $('#decline-all');
        var link = btnElement.data('link');

        btnElement.click(function (e) {
            processBulkAction(link);
        })
    };

    var processBulkAction = function (link) {
        var selectedIds = $('#invitation-grid').yiiGridView('getSelectedRows');
        $.ajax({
            url: link,
            type: "POST",
            dataType: 'json',
            data: {
                ids: selectedIds
            },
            success: function(data) {
                if (data == true) {
                    $.pjax.reload({container: gridPjaxContainer});
                }
            }
        });
        return false;
    };

    return {

        init: function() {
            if (!jQuery().yiiGridView) {
                console.log('"yiiGridView" is required!');
                return;
            }

            initApproveAllControl();
            initDeclineAllControl();
        },

        status: function(url, id) {
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: {
                    id: id
                },
                success: function(data) {
                    if (data == true) {
                        $.pjax.reload({container: gridPjaxContainer});
                    }
                }
            });
            return false;
        }
    }
}(jQuery);