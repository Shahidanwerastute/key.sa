// Changing language
function changeLanguage(language) {
    if (language === 'arb') {
        window.location.href = base_url + '/en/survey' + url_part;
    } else {
        window.location.href = base_url + '/survey' + url_part;
    }
}

function changeLanguageForOasis(language, type) {
    if (type > 0) {
        if (language === 'arb') {
            window.location.href = base_url + '/en/limousine-oasis-survey' + url_part;
        } else {
            window.location.href = base_url + '/limousine-oasis-survey' + url_part;
        }
    } else {
        if (language === 'arb') {
            window.location.href = base_url + '/en/oasis-survey' + url_part;
        } else {
            window.location.href = base_url + '/oasis-survey' + url_part;
        }
    }
}

$(document).on('click', '.getCategories', function () {
    resetValues();
    $('.loaderSpiner').show();
    $('.emojiList > li').removeClass('active');
    $(this).parent('li').addClass('active');
    var emoji_id = $(this).data('emoji-id');
    var url = lang_base_url + '/getCategoriesForEmoji';
    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        data: {emoji_id: emoji_id},
        success: function (response) {
            $('.mainFeedBk').show();
            $('.categories_list').html('');
            $('.categories_list').html(response.html);
            $('html,body').animate({scrollTop: $('.categories_list').position().top}, 'slow');
            $('.loaderSpiner').hide();
        }
    });
});

$(document).on('click', '.getOptions', function () {
    $('.loaderSpiner').show();
    var category_id = $(this).data('category-id');
    var category_title = $(this).data('category-title');
    var question = $(this).data('question-title');
    $('.category_id').val(category_id);
    $('.category_desc').val(category_title);
    $('.question_desc').val(question);

    var url = lang_base_url + '/getOptionsForCategory';
    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        data: {category_id: category_id},
        success: function (response) {
            $('.options_list').children('.sandGrayBG').children('.container-fluid').html('');
            $('.options_list').children('.sandGrayBG').children('.container-fluid').html(response.html);
            $('.options_list').removeClass('hide');
            $('.mainFeedBk').hide();
            $('.hasCarDetails').hide(); // added this to hide the car booking details section to get survey things move up.
            $('.options_list').show();
            $('.contains_category').text(category_title);
            $('.contains_question').text(question);
            $('.loaderSpiner').hide();
        }
    });
});

function reset() {
    $('.mainFeedBk').show();
    $('.hasCarDetails').show();
    $('.options_list').hide();
}

function resetValues() {
    $('.category_id').val('');
    $('.category_desc').val('');
    $('.question_desc').val('');
    $('.option_id').val('');
    $('.answer_desc').val('');
}

$(document).on('click', '.feedback_option', function () {
    if ($(this).val() === 'Other') {
        $('.feedback_textfield').attr('disabled', false);
    } else {
        $('.feedback_textfield').attr('disabled', true);
    }
});

$(document).on('click', '.feedback_option', function () {
    var option_id = $(this).data('option-id');
    var option_desc = $(this).val();
    $('.option_id').val(option_id);
    $('.answer_desc').val(option_desc);
    if (option_id != '0') {
        $('.feedback_textfield').val('');
    }
});

function post_feedback() {
    var survey_type = $('.survey_type').val();
    if (survey_type === 'website_survey') {
        submit_website_feedback();
    } else if (survey_type === 'oasis_survey') {
        submit_oasis_feedback();
    }
}

function submit_website_feedback() {
    var customer_id = $('.customer_id').val();
    var booking_id = $('.booking_id').val();
    var emoji_id = $('.emoji_id').val();
    var emoji_desc = $('.emoji_desc').val();
    var category_id = $('.category_id').val();
    var category_desc = $('.category_desc').val();
    var question_desc = $('.question_desc').val();
    var option_id = $('.option_id').val(); // will be 0 in case of other
    var answer_desc = $('.answer_desc').val();
    var feedback_textfield = $('.feedback_textfield').val();

    if (option_id === '0' && feedback_textfield === '') {
        $('.feedback_textfield').attr("data-original-title", required_message);
        $('.feedback_textfield').attr("data-placement", "left");
        $('.feedback_textfield').tooltip('show');
        return false;
    } else {
        $('.feedback_textfield').tooltip('destroy');
        $('.feedback_textfield').tooltip('hide');
    }

    $.ajax({
        type: 'POST',
        url: lang_base_url + '/saveSurveyFeedback',
        dataType: 'json',
        data: {
            customer_id: customer_id,
            booking_id: booking_id,
            emoji_id: emoji_id,
            emoji_desc: emoji_desc,
            category_id: category_id,
            category_desc: category_desc,
            question_desc: question_desc,
            option_id: option_id,
            answer_desc: answer_desc,
            feedback_textfield: feedback_textfield
        },
        success: function (response) {
            if (lang === 'eng') {
                var isMsg = 'Message';
            } else {
                var isMsg = 'تنبيه';
            }
            //$('.responseTitle').html(isMsg);
            $('.responseTitle').html('');
            $('.responseMsg').html(response.message);

            if (response.status === 1 || response.status === 2) {
                var redUrl;

                if (response.redirect_url === 'en' || response.redirect_url === '' || response.redirect_url === 'home' || response.redirect_url === 'my-profile') {
                    redUrl = lang_base_url;
                } else {
                    redUrl = lang_base_url + '/payment';
                }

                $("#OKBtn").attr("href", redUrl);
                $('#openMsgPopupRedirect').click();

            } else {
                $('#openMsgPopupNoRedirect').click();
            }
        }
    });
}

$(document).on('submit', '#saveOasisSurveyFeedback', function (e) {

    var form_ele = this;

    // validating all option selected
    var all_ok = true;
    for(var i = 1; i <= $('#questions_count').val(); i++) {
        if (!$('input[name="question-' + i + '"]').is(':checked')) {
            all_ok = false;
        }
    }

    var booking_status = $('input[name="booking_status"]').val();

    if (booking_status == 'C') {
        if ($('select[name="purpose_of_renting"]').val() == '') {
            all_ok = false;
        }

        if ($('select[name="suggestion_or_opinion_you_would_like_to_share"]').val() == '') {
            all_ok = false;
        }
    }

    if (!all_ok) {
        alert('Please provide all answers to complete the survey!');
    } else {
        $form = $(form_ele);
        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: new FormData(form_ele),
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                alert(response.message);
                if (response.status == 1 || response.status == 2) {
                    setTimeout(function () {
                        window.location.href = lang_base_url;
                    }, 2000);
                }
            }
        });
    }
});

function submit_oasis_feedback() {

    var all_ok = true;
    for(var i = 1; i <= $('#questions_count').val(); i++) {
        if (!$('input[name="question-"' + i + ']').is(':checked')) {
            all_ok = false;
        }
    }

    alert(all_ok);

    if (!all_ok) {
        // alert('Please provide all answers to complete the survey!');
    }

    var contract_no = $('.contract_no').val();
    var booking_status = $('.booking_status').val();

    /*var emoji_id = $('.emoji_id').val();
    var emoji_desc = $('.emoji_desc').val();
    var category_id = $('.category_id').val();
    var category_desc = $('.category_desc').val();
    var question_desc = $('.question_desc').val();
    var option_id = $('.option_id').val(); // will be 0 in case of other
    var answer_desc = $('.answer_desc').val();
    var feedback_textfield = $('.feedback_textfield').val();

    if (option_id === '0' && feedback_textfield === '') {
        $('.feedback_textfield').attr("data-original-title", required_message);
        $('.feedback_textfield').attr("data-placement", "left");
        $('.feedback_textfield').tooltip('show');
        return false;
    } else {
        $('.feedback_textfield').tooltip('destroy');
        $('.feedback_textfield').tooltip('hide');
    }

    $.ajax({
        type: 'POST',
        url: lang_base_url + '/saveOasisSurveyFeedback',
        dataType: 'json',
        data: {
            contract_no: contract_no,
            booking_status: booking_status,
            emoji_id: emoji_id,
            emoji_desc: emoji_desc,
            category_id: category_id,
            category_desc: category_desc,
            question_desc: question_desc,
            option_id: option_id,
            answer_desc: answer_desc,
            feedback_textfield: feedback_textfield
        },
        success: function (response) {
            if (lang === 'eng') {
                var isMsg = 'Message';
            } else {
                var isMsg = 'تنبيه';
            }
            //$('.responseTitle').html(isMsg);
            $('.responseTitle').html('');
            $('.responseMsg').html(response.message);

            if (response.status === 1 || response.status === 2) {
                var redUrl = lang_base_url;
                $("#OKBtn").attr("href", redUrl);
                $('#openMsgPopupRedirect').click();
            } else {
                $('#openMsgPopupNoRedirect').click();
            }
        }
    });*/
}