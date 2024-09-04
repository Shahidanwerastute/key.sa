$(function () {
    // crud table
    CarsSellingResponsesTable.init();
});
CarsSellingResponsesTable = {
    init: function () {
        $('#CarsSellingResponsesTable').jtable({
            title: '<h3>Car Selling Responses</h3>',
            sorting: true,
            //paging: true, //Enable paging
            //pageSize: 10, //Set page size (default: 10)
            deleteConfirmation: function (data) {
                //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                data.deleteConfirmMessage = 'Are you sure to delete this ?';
            },
            formCreated: function (event, data) {
                reInitDesignFix(data);
            },
            actions: carsSellingResponsesActions(),
            fields: {
                id: {
                    key: true,
                    create: false,
                    edit: false,
                    list: false
                },
                name: {
                    title: 'Name',
                    width: '10%'
                },
                mobile_no: {
                    title: 'Mobile No',
                    width: '10%'
                },
                email: {
                    title: 'Email',
                    width: '10%'
                },
                car_id: {
                    title: 'Car',
                    width: '10%'
                },
                created_at: {
                    title: 'Received At',
                    width: '10%'
                }
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