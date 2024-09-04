$(function() {
    // crud table
    altair_crud_table.init();
});

altair_crud_table = {
    init: function() {

        $('#students_crud').jtable({
            title: 'The Student List',
            paging: true, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            addRecordButton: $('#recordAdd'),
            deleteConfirmation: function(data) {
                data.deleteConfirmMessage = 'Are you sure to delete student ' + data.record.Name + '?';
            },
            formCreated: function(event, data) {
                // replace click event on some clickable elements
                // to make icheck label works
                data.form.find('.jtable-option-text-clickable').each(function() {
                    var $thisTarget = $(this).prev().attr('id');
                    $(this)
                        .attr('data-click-target',$thisTarget)
                        .off('click')
                        .on('click',function(e) {
                            e.preventDefault();
                            $('#'+$(this).attr('data-click-target')).iCheck('toggle');
                        })
                });
                // create selectize
                data.form.find('select').each(function() {
                    var $this = $(this);
                    $this.after('<div class="selectize_fix"></div>')
                        .selectize({
                            dropdownParent: 'body',
                            placeholder: 'Click here to select ...',
                            onDropdownOpen: function($dropdown) {
                                $dropdown
                                    .hide()
                                    .velocity('slideDown', {
                                        begin: function() {
                                            $dropdown.css({'margin-top':'0'})
                                        },
                                        duration: 200,
                                        easing: easing_swiftOut
                                    })
                            },
                            onDropdownClose: function($dropdown) {
                                $dropdown
                                    .show()
                                    .velocity('slideUp', {
                                        complete: function() {
                                            $dropdown.css({'margin-top':''})
                                        },
                                        duration: 200,
                                        easing: easing_swiftOut
                                    })
                            }
                        });
                });
                // create icheck
                data.form
                    .find('input[type="checkbox"],input[type="radio"]')
                    .each(function() {
                        var $this = $(this);
                        $this.iCheck({
                                checkboxClass: 'icheckbox_md',
                                radioClass: 'iradio_md',
                                increaseArea: '20%'
                            })
                            .on('ifChecked', function(event){
                                $this.parent('div.icheckbox_md').next('span').text('Active');
                            })
                            .on('ifUnchecked', function(event){
                                $this.parent('div.icheckbox_md').next('span').text('Passive');
                            })
                    });
                // reinitialize inputs
                data.form.find('.jtable-input').children('input[type="text"],input[type="password"],textarea').not('.md-input').each(function() {
                    $(this).addClass('md-input');
                    altair_forms.textarea_autosize();
                });
                altair_md.inputs();
            },
            actions: {
                listAction: 'data/crud_table/studentsActions.php?action=list',
                createAction: 'data/crud_table/studentsActions.php?action=create',
                updateAction: 'data/crud_table/studentsActions.php?action=update',
                deleteAction: 'data/crud_table/studentsActions.php?action=delete'
            },
            fields: {
                StudentId: {
                    key: true,
                    create: false,
                    edit: false,
                    list: false
                },
                Name: {
                    title: 'Name',
                    width: '23%'
                },
                EmailAddress: {
                    title: 'Email address',
                    list: false
                },
                Password: {
                    title: 'User Password',
                    type: 'password',
                    list: false
                },
                Gender: {
                    title: 'Gender',
                    width: '13%',
                    options: {'M': 'Male', 'F': 'Female'}
                },
                CityId: {
                    title: 'City',
                    width: '12%',
                    options: 'data/crud_table/cities.php'
                },
                BirthDate: {
                    title: 'Birth Date',
                    width: '15%',
                    displayFormat: 'dd/mm/yy',
                    type: 'date',
                    input: function(data) {
                        if (data.record) {
                            return '<input class="md-input" type="text" name="BirthDate" value="' + data.value + '" data-uk-datepicker="{format:\'DD/MM/YYYY\'}"/>';
                        } else {
                            return '<input class="md-input" type="text" name="BirthDate"  value="" data-uk-datepicker="{format:\'DD/MM/YYYY\'}"/>';
                        }
                    }
                },
                Education: {
                    title: 'Education',
                    type: 'radiobutton',
                    options: {
                        '1': 'Primary school',
                        '2': 'High school',
                        '3': 'University'
                    }
                },
                About: {
                    title: 'About this person',
                    type: 'textarea',
                    list: false
                },
                IsActive: {
                    title: 'Status',
                    width: '12%',
                    type: 'checkbox',
                    values: { 'false': 'Passive', 'true': 'Active' },
                    defaultValue: 'true'
                },
                RecordDate: {
                    title: 'Record date',
                    width: '15%',
                    type: 'date',
                    displayFormat: 'dd/mm/yy',
                    create: false,
                    edit: false
                }
            }
        }).jtable('load');

        // change buttons visual style in ui-dialog
        $('.ui-dialog-buttonset')
            .children('button')
            .attr('class','')
            .addClass('md-btn md-btn-flat')
            .off('mouseenter focus');
        $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');

    }
};




























// Script for JTABLE starting for bookings page
$(function() {
    // crud table
    altair_crud_table.init();
});

altair_crud_table = {
    init: function() {

        $('#bookingsJTables').jtable({
            //title: 'Active Reservations',
            paging: true, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            addRecordButton: $('#recordAdd'),
            deleteConfirmation: function(data) {
                data.deleteConfirmMessage = 'Are you sure to delete student ' + data.record.Name + '?';
            },
            /*formCreated: function(event, data) {
             // replace click event on some clickable elements
             // to make icheck label works
             data.form.find('.jtable-option-text-clickable').each(function() {
             var $thisTarget = $(this).prev().attr('id');
             $(this)
             .attr('data-click-target',$thisTarget)
             .off('click')
             .on('click',function(e) {
             e.preventDefault();
             $('#'+$(this).attr('data-click-target')).iCheck('toggle');
             })
             });
             // create selectize
             data.form.find('select').each(function() {
             var $this = $(this);
             $this.after('<div class="selectize_fix"></div>')
             .selectize({
             dropdownParent: 'body',
             placeholder: 'Click here to select ...',
             onDropdownOpen: function($dropdown) {
             $dropdown
             .hide()
             .velocity('slideDown', {
             begin: function() {
             $dropdown.css({'margin-top':'0'})
             },
             duration: 200,
             easing: easing_swiftOut
             })
             },
             onDropdownClose: function($dropdown) {
             $dropdown
             .show()
             .velocity('slideUp', {
             complete: function() {
             $dropdown.css({'margin-top':''})
             },
             duration: 200,
             easing: easing_swiftOut
             })
             }
             });
             });
             // create icheck
             data.form
             .find('input[type="checkbox"],input[type="radio"]')
             .each(function() {
             var $this = $(this);
             $this.iCheck({
             checkboxClass: 'icheckbox_md',
             radioClass: 'iradio_md',
             increaseArea: '20%'
             })
             .on('ifChecked', function(event){
             $this.parent('div.icheckbox_md').next('span').text('Active');
             })
             .on('ifUnchecked', function(event){
             $this.parent('div.icheckbox_md').next('span').text('Passive');
             })
             });
             // reinitialize inputs
             data.form.find('.jtable-input').children('input[type="text"],input[type="password"],textarea').not('.md-input').each(function() {
             $(this).addClass('md-input');
             altair_forms.textarea_autosize();
             });
             altair_md.inputs();
             },*/
            actions: {
                listAction: base_url+'/admin/bookings/getAllActiveReservations',
                deleteAction: base_url+'/admin/bookings/getAll',
                updateAction: base_url+'/admin/bookings/getAll',
                createAction: base_url+'/admin/bookings/getAll'
            },
            fields: {
                id: {
                    key: true,
                    create: false,
                    edit: false,
                    list: false
                },
                car_model: {
                    title: 'Car Model',
                    defaultValue: 'Car Model'
                    //width: '23%'
                },
                FromLocation: {
                    title: 'From Location',
                    list: true,
                    defaultValue: 'Car Model'
                },
                ToLocation: {
                    title: 'To Location',
                    type: 'password',
                    list: true
                },
                FromDate: {
                    title: 'From Date'/*,
                     width: '13%',
                     options: {'M': 'Male', 'F': 'Female'}*/
                },
                ToDate: {
                    title: 'To Date'/*,
                     width: '12%',
                     options: 'data/crud_table/cities.php'*/
                }/*,
                 BirthDate: {
                 title: 'Birth Date',
                 width: '15%',
                 displayFormat: 'dd/mm/yy',
                 type: 'date',
                 input: function(data) {
                 if (data.record) {
                 return '<input class="md-input" type="text" name="BirthDate" value="' + data.value + '" data-uk-datepicker="{format:\'DD/MM/YYYY\'}"/>';
                 } else {
                 return '<input class="md-input" type="text" name="BirthDate"  value="" data-uk-datepicker="{format:\'DD/MM/YYYY\'}"/>';
                 }
                 }
                 },*/
                /*Education: {
                 title: 'Education',
                 type: 'radiobutton',
                 options: {
                 '1': 'Primary school',
                 '2': 'High school',
                 '3': 'University'
                 }
                 },*/
                /*About: {
                 title: 'About this person',
                 type: 'textarea',
                 list: false
                 },*/
                /*IsActive: {
                 title: 'Status',
                 width: '12%',
                 type: 'checkbox',
                 values: { 'false': 'Passive', 'true': 'Active' },
                 defaultValue: 'true'
                 },*/
                /*RecordDate: {
                 title: 'Record date',
                 width: '15%',
                 type: 'date',
                 displayFormat: 'dd/mm/yy',
                 create: false,
                 edit: false
                 }*/
            }
        }).jtable('load');

        // change buttons visual style in ui-dialog
        $('.ui-dialog-buttonset')
            .children('button')
            .attr('class','')
            .addClass('md-btn md-btn-flat')
            .off('mouseenter focus');
        $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');

    }
};
// Script for JTABLE ending for bookings page


/*
 $(document).ready(function () {

 $('#bookingsJTable').jtable({
 title: 'Student List',
 paging: true, //Enable paging
 sorting: true, //Enable sorting
 defaultSorting: 'Name ASC',
 //openChildAsAccordion: true, //Enable this line to show child tabes as accordion style
 actions: {
 listAction: base_url+'/admin/bookings/getAllActiveReservations',
 deleteAction: base_url+'/admin/bookings/getAll',
 updateAction: base_url+'/admin/bookings/getAll',
 createAction: base_url+'/admin/bookings/getAll'
 },
 fields: {
 StudentId: {
 key: true,
 create: false,
 edit: false,
 list: false
 },
 //CHILD TABLE DEFINITION FOR "PHONE NUMBERS"
 Phones: {
 title: '',
 width: '5%',
 sorting: false,
 edit: false,
 create: false,
 display: function (studentData) {
 //Create an image that will be used to open child table
 var $img = $('<img src="/Content/images/Misc/phone.png" title="Edit phone numbers" />');
 //Open child table when user clicks the image
 $img.click(function () {
 $('#bookingsJTable').jtable('openChildTable',
 $img.closest('tr'),
 {
 title: studentData.record.Name + ' - Phone numbers',
 actions: {
 listAction: '/Demo/PhoneList?StudentId=' + studentData.record.StudentId,
 deleteAction: '/Demo/DeletePhone',
 updateAction: '/Demo/UpdatePhone',
 createAction: '/Demo/CreatePhone'
 },
 fields: {
 StudentId: {
 type: 'hidden',
 defaultValue: studentData.record.StudentId
 },
 PhoneId: {
 key: true,
 create: false,
 edit: false,
 list: false
 },
 PhoneType: {
 title: 'Phone type',
 width: '30%',
 options: { '1': 'Home phone', '2': 'Office phone', '3': 'Cell phone' }
 },
 Number: {
 title: 'Phone Number',
 width: '30%'
 },
 RecordDate: {
 title: 'Record date',
 width: '20%',
 type: 'date',
 displayFormat: 'yy-mm-dd',
 create: false,
 edit: false
 }
 }
 }, function (data) { //opened handler
 data.childTable.jtable('load');
 });
 });
 //Return image to show on the person row
 return $img;
 }
 },
 //CHILD TABLE DEFINITION FOR "EXAMS"
 Exams: {
 title: '',
 width: '5%',
 sorting: false,
 edit: false,
 create: false,
 display: function (studentData) {
 //Create an image that will be used to open child table
 var $img = $('<img src="/Content/images/Misc/note.png" title="Edit exam results" />');
 //Open child table when user clicks the image
 $img.click(function () {
 $('#bookingsJTable').jtable('openChildTable',
 $img.closest('tr'), //Parent row
 {
 title: studentData.record.Name + ' - Exam Results',
 actions: {
 listAction: '/Demo/ExamList?StudentId=' + studentData.record.StudentId,
 deleteAction: '/Demo/DeleteExam',
 updateAction: '/Demo/UpdateExam',
 createAction: '/Demo/CreateExam'
 },
 fields: {
 StudentId: {
 type: 'hidden',
 defaultValue: studentData.record.StudentId
 },
 StudentExamId: {
 key: true,
 create: false,
 edit: false,
 list: false
 },
 CourseName: {
 title: 'Course name',
 width: '40%'
 },
 ExamDate: {
 title: 'Exam date',
 width: '30%',
 type: 'date',
 displayFormat: 'yy-mm-dd'
 },
 Degree: {
 title: 'Degree',
 width: '10%',
 options: ["AA", "BA", "BB", "CB", "CC", "DC", "DD", "FF"]
 }
 }
 }, function (data) { //opened handler
 data.childTable.jtable('load');
 });
 });
 //Return image to show on the person row
 return $img;
 }
 },
 Name: {
 title: 'Name',
 width: '20%'
 },
 EmailAddress: {
 title: 'Email address',
 list: false
 },
 Password: {
 title: 'User Password',
 type: 'password',
 list: false
 },
 Gender: {
 title: 'Gender',
 width: '11%',
 options: { 'M': 'Male', 'F': 'Female' }
 },
 CityId: {
 title: 'City',
 width: '12%',
 options: '/Demo/GetCityOptions'
 },
 BirthDate: {
 title: 'Birth date',
 width: '15%',
 type: 'date',
 displayFormat: 'yy-mm-dd'
 },
 Education: {
 title: 'Education',
 list: false,
 type: 'radiobutton',
 options: { '1': 'Primary school', '2': 'High school', '3': 'University' }
 },
 About: {
 title: 'About this person',
 type: 'textarea',
 list: false
 },
 IsActive: {
 title: 'Status',
 width: '12%',
 type: 'checkbox',
 values: { 'false': 'Passive', 'true': 'Active' },
 defaultValue: 'true'
 },
 RecordDate: {
 title: 'Record date',
 width: '15%',
 type: 'date',
 displayFormat: 'yy-mm-dd',
 create: false,
 edit: false,
 sorting: false //This column is not sortable!
 }
 }
 });

 //Load student list from server
 $('#bookingsJTable').jtable('load');

 });*/

/////// JTABLE from site /////////////


$(document).ready(function () {

    $('#StudentTableContainer').jtable({
        title: 'Student List',
        paging: true, //Enable paging
        sorting: true, //Enable sorting
        defaultSorting: 'Name ASC',
        //openChildAsAccordion: true, //Enable this line to show child tabes as accordion style
        actions: {
            listAction: base_url+'/admin/bookings/getAllActiveReservations',
            deleteAction: '/Demo/DeleteStudent',
            updateAction: '/Demo/UpdateStudent',
            createAction: '/Demo/CreateStudent")'
        },
        fields: {
            id: {
                key: true,
                create: false,
                edit: false,
                list: false
            },
            //CHILD TABLE DEFINITION FOR "PHONE NUMBERS"
            Phones: {
                title: '',
                width: '5%',
                sorting: false,
                edit: false,
                create: false,
                display: function (studentData) {
                    //Create an image that will be used to open child table
                    var $img = $('<img src="http://www.pngfactory.net/_png/_thumb/20631-bubka-Phone.png" title="Edit phone numbers" />');
                    //Open child table when user clicks the image
                    $img.click(function () {
                        $('#StudentTableContainer').jtable('openChildTable',
                            $img.closest('tr'),
                            {
                                title: studentData.record.Name + ' - Phone numbers',
                                actions: {
                                    listAction: '/Demo/PhoneList?StudentId=' + studentData.record.StudentId,
                                    deleteAction: '/Demo/DeletePhone',
                                    updateAction: '/Demo/UpdatePhone',
                                    createAction: '/Demo/CreatePhone'
                                },
                                fields: {
                                    StudentId: {
                                        type: 'hidden',
                                        defaultValue: studentData.record.StudentId
                                    },
                                    PhoneId: {
                                        key: true,
                                        create: false,
                                        edit: false,
                                        list: false
                                    },
                                    PhoneType: {
                                        title: 'Phone type',
                                        width: '30%',
                                        options: { '1': 'Home phone', '2': 'Office phone', '3': 'Cell phone' }
                                    },
                                    Number: {
                                        title: 'Phone Number',
                                        width: '30%'
                                    },
                                    RecordDate: {
                                        title: 'Record date',
                                        width: '20%',
                                        type: 'date',
                                        displayFormat: 'yy-mm-dd',
                                        create: false,
                                        edit: false
                                    }
                                }
                            }, function (data) { //opened handler
                                data.childTable.jtable('load');
                            });
                    });
                    //Return image to show on the person row
                    return $img;
                }
            },
            //CHILD TABLE DEFINITION FOR "EXAMS"
            Exams: {
                title: '',
                width: '5%',
                sorting: false,
                edit: false,
                create: false,
                display: function (studentData) {
                    //Create an image that will be used to open child table
                    var $img = $('<img src="http://www.pngfactory.net/_png/_thumb/20631-bubka-Phone.png" title="Edit exam results" />');
                    //Open child table when user clicks the image
                    $img.click(function () {
                        $('#StudentTableContainer').jtable('openChildTable',
                            $img.closest('tr'), //Parent row
                            {
                                title: studentData.record.Name + ' - Exam Results',
                                actions: {
                                    listAction: '/Demo/ExamList?StudentId=' + studentData.record.StudentId,
                                    deleteAction: '/Demo/DeleteExam',
                                    updateAction: '/Demo/UpdateExam',
                                    createAction: '/Demo/CreateExam'
                                },
                                fields: {
                                    StudentId: {
                                        type: 'hidden',
                                        defaultValue: studentData.record.StudentId
                                    },
                                    StudentExamId: {
                                        key: true,
                                        create: false,
                                        edit: false,
                                        list: false
                                    },
                                    CourseName: {
                                        title: 'Course name',
                                        width: '40%'
                                    },
                                    ExamDate: {
                                        title: 'Exam date',
                                        width: '30%',
                                        type: 'date',
                                        displayFormat: 'yy-mm-dd'
                                    },
                                    Degree: {
                                        title: 'Degree',
                                        width: '10%',
                                        options: ["AA", "BA", "BB", "CB", "CC", "DC", "DD", "FF"]
                                    }
                                }
                            }, function (data) { //opened handler
                                data.childTable.jtable('load');
                            });
                    });
                    //Return image to show on the person row
                    return $img;
                }
            },
            car_model: {
                title: 'Car Model',
                width: '20%'
            },
            sync: {
                title: 'Sync'
            },
            Password: {
                title: 'User Password',
                type: 'password',
                list: false
            },
            Gender: {
                title: 'Gender',
                width: '11%',
                options: { 'M': 'Male', 'F': 'Female' }
            },
            CityId: {
                title: 'City',
                width: '12%',
                options: '/Demo/GetCityOptions'
            },
            BirthDate: {
                title: 'Birth date',
                width: '15%',
                type: 'date',
                displayFormat: 'yy-mm-dd'
            },
            Education: {
                title: 'Education',
                list: false,
                type: 'radiobutton',
                options: { '1': 'Primary school', '2': 'High school', '3': 'University' }
            },
            About: {
                title: 'About this person',
                type: 'textarea',
                list: false
            },
            IsActive: {
                title: 'Status',
                width: '12%',
                type: 'checkbox',
                values: { 'false': 'Passive', 'true': 'Active' },
                defaultValue: 'true'
            },
            RecordDate: {
                title: 'Record date',
                width: '15%',
                type: 'date',
                displayFormat: 'yy-mm-dd',
                create: false,
                edit: false,
                sorting: false //This column is not sortable!
            }
        }
    });

    //Load student list from server
    $('#StudentTableContainer').jtable('load');

});


//////////////////////////////////////

$('#bookingsJTables').jtable({
    //title: 'Table of people',
    openChildAsAccordion: true,
    actions: {
        listAction: base_url+'/admin/bookings/getAllActiveReservations',
        createAction: '/GettingStarted/CreatePerson',
        updateAction: '/GettingStarted/UpdatePerson',
        deleteAction: '/GettingStarted/DeletePerson'
    },
    fields: {
        id: {
            key: true,
            list: false,
            create: false,
            edit: false,
        },
        Phones: {
            title: '',
            width: '5%',
            sorting: false,
            edit: false,
            create: false,
            display: function (studentData) {
                //Create an image that will be used to open child table
                var $img = $('<img src="http://icons.iconarchive.com/icons/graphicloads/100-flat/256/phone-icon.png" title="Edit phone numbers" />');
                //Open child table when user clicks the image
                $img.click(function () {
                    $('#bookingsJTable').jtable('openChildTable',
                        $img.closest('tr'),
                        {
                            title: studentData.record.Name + ' - Phone numbers',
                            actions: {
                                listAction: '/Demo/PhoneList?StudentId=' + studentData.record.StudentId,
                                deleteAction: '/Demo/DeletePhone',
                                updateAction: '/Demo/UpdatePhone',
                                createAction: '/Demo/CreatePhone'
                            },
                            fields: {
                                StudentId: {
                                    type: 'hidden',
                                    defaultValue: studentData.record.StudentId
                                },
                                PhoneId: {
                                    key: true,
                                    create: false,
                                    edit: false,
                                    list: false
                                },
                                PhoneType: {
                                    title: 'Phone type',
                                    width: '30%',
                                    options: { '1': 'Home phone', '2': 'Office phone', '3': 'Cell phone' }
                                },
                                Number: {
                                    title: 'Phone Number',
                                    width: '30%'
                                },
                                RecordDate: {
                                    title: 'Record date',
                                    width: '20%',
                                    type: 'date',
                                    displayFormat: 'yy-mm-dd',
                                    create: false,
                                    edit: false
                                }
                            }
                        }, function (data) { //opened handler
                            data.childTable.jtable('load');
                        });
                });
                //Return image to show on the person row
                return $img;
            }
        },
        PersonalData: {
            title: '',
            width: '5%',
            sorting: false,
            edit: false,
            create: false,
            display: function (studentData) {
                //Create an image that will be used to open child table
                var $img = $('<img src="http://icons.iconarchive.com/icons/graphicloads/100-flat/256/phone-icon.png" title="Edit phone numbers" />');
                //Open child table when user clicks the image
                $img.click(function () {
                    $('#bookingsJTable').jtable('openChildTable',
                        $img.closest('tr'),
                        {
                            title: studentData.record.Name + ' - Phone numbers',
                            actions: {
                                listAction: '/Demo/PhoneList?StudentId=' + studentData.record.StudentId,
                                deleteAction: '/Demo/DeletePhone',
                                updateAction: '/Demo/UpdatePhone',
                                createAction: '/Demo/CreatePhone'
                            },
                            fields: {
                                StudentId: {
                                    type: 'hidden',
                                    defaultValue: studentData.record.StudentId
                                },
                                PhoneId: {
                                    key: true,
                                    create: false,
                                    edit: false,
                                    list: false
                                },
                                PhoneType: {
                                    title: 'Phone type',
                                    width: '30%',
                                    options: { '1': 'Home phone', '2': 'Office phone', '3': 'Cell phone' }
                                },
                                Number: {
                                    title: 'Phone Number',
                                    width: '30%'
                                },
                                RecordDate: {
                                    title: 'Record date',
                                    width: '20%',
                                    type: 'date',
                                    displayFormat: 'yy-mm-dd',
                                    create: false,
                                    edit: false
                                }
                            }
                        }, function (data) { //opened handler
                            data.childTable.jtable('load');
                        });
                });
                //Return image to show on the person row
                return $img;
            }
        },
        car_model: {
            title: 'Car Model',
            width: '40%'
        },
        sync: {
            title: 'Sync',
            width: '20%'
        },
        booking_status: {
            title: 'Status',
            width: '30%',
            create: false,
            edit: false
        }
    }
});


$(document).ready(function () {
    $('#bookingsJTables').jtable('load');
});