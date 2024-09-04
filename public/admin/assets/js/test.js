$(function () {
    // crud table
    RedeemSetupTable.init();
});
RedeemSetupTable = {
    init: function () {
        $('#RedeemSetupTable').jtable({
            title: '<h3>Car Selling Responses</h3>',
            sorting: true,
            defaultSorting: 'created_at DESC',
            paging: true, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            deleteConfirmation: function (data) {
                //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                data.deleteConfirmMessage = 'Are you sure to delete this ?';
            },
            formCreated: function (event, data) {
                reInitDesignFix(data);
            },
            actions: redeemSetupCarTypeActions(),
            fields: {
                id: {
                    key: true,
                    create: false,
                    edit: false,
                    list: false
                },
                eng_title: {
                    title: 'Contact Name',
                    width: '10%'
                },
                created_at: {
                    title: 'Received At',
                    width: '10%'
                }/*,
                image: {
                    title: 'Car Image',
                    width: '10%',
                    display: function (data) {
                        return '<a href="' + base_url + '/public/uploads/' + data.record.image1 + '" target="_blank" title="Click image to see"><img width="459" src="' + base_url + '/public/uploads/' + data.record.image1 + '"/></a>';
                    }
                },*/
            }
        }).jtable('load');
        // change buttons visual style in ui-dialog
        $('.ui-dialog-buttonset')
            .children('button')
            .attr('class', '')
            .addClass('md-btn md-btn-flat')
            .off('mouseenter focus');
        $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
    }
};