@extends('admin.layouts.template')

@section('content')
    <style>
        input[type=text], select {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type=submit] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d0e9c6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

    </style>

    <div id="page_content">
        <div id="page_content_inner">

            <form action="save_metapages" method="post">

                <div class="md-card">
                    <div class="md-card-toolbar">
                        <h3 class="md-card-toolbar-heading-text">
                            Meta Information
                        </h3>
                    </div>
                    <div class="md-card-content large-padding">

                        <div class="uk-grid uk-grid-divider uk-grid-medium" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <select name="page" data-md-selectize data-md-selectize-bottom
                                            data-uk-tooltip="{pos:'top'}" title="Select Page">
                                        <option value="">Select Page</option>
                                        <option value="register">Register</option>
                                        <option value="create-ind-login">Create Login</option>
                                        <option value="create-ind-user">Create Individual User</option>
                                        <option value="my-profile">My Profile</option>
                                        <option value="edit-my-profile">Edit My Profile</option>
                                        <option value="search-results">Search Results</option>
                                        <option value="fleet">Fleet</option>
                                        <option value="book-car">Book Car</option>
                                        <option value="my-bookings">My Bookings</option>
                                        <option value="booking-detail">Booking Detail</option>
                                        <option value="booking-done">Booking Done</option>
                                        <option value="refer_and_earn">Refer And Earn</option>
                                        <option value="search-results">Search Results</option>
                                        <option value="extra-services">Extra Services</option>
                                        <option value="payment">Payment</option>
                                        <option value="cc-payment">CC Payment</option>

                                    </select>
                                </div>

                            </div>
                        </div>


                        <div class="uk-grid uk-grid-divider uk-grid-medium" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>English Meta Title</label>
                                    <input type="text" class="md-input" name="eng_meta_title" />
                                </div>

                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Arabic Meta Title</label>
                                    <input type="text" class="md-input" name="arb_meta_title" />
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>English Meta Description</label>
                                    <textarea cols="30" rows="4" class="md-input" name="eng_meta_description"></textarea>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Arabic Meta Description</label>
                                    <textarea cols="30" rows="4" class="md-input" name="arb_meta_description"></textarea>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>English Meta Keyword</label>
                                    <textarea cols="30" rows="4" class="md-input" name="eng_meta_keyword"></textarea>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Arabic Meta Keyword</label>
                                    <textarea cols="30" rows="4" class="md-input" name="arb_meta_keyword"></textarea>
                                </div>
                            </div>

                            <div class="uk-width-medium-1-1">
                                <div class="uk-form-row">
                                    <button type="submit"
                                            class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="success-message">
                        {{ session('success') }}
                    </div>
                @endif
            </form>

        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('select[name="page"]').change(function() {
                var selectedPage = $(this).val();
                if (selectedPage !== '') {
                    $.ajax({
                        url: '{{ secure_url("en/get-meta-data/:page") }}'.replace(':page', selectedPage),
                        type: 'GET',
                        success: function(response) {
                            if (response.metaData) {
                                // Populate the form fields with the retrieved metadata
                                $('input[name="eng_meta_title"]').val(response.metaData.eng_meta_title).focus();
                                $('input[name="arb_meta_title"]').val(response.metaData.arb_meta_title).focus();
                                $('textarea[name="eng_meta_description"]').val(response.metaData.eng_meta_description).focus();
                                $('textarea[name="arb_meta_description"]').val(response.metaData.arb_meta_description).focus();
                                $('textarea[name="eng_meta_keyword"]').val(response.metaData.eng_meta_keyword).focus();
                                $('textarea[name="arb_meta_keyword"]').val(response.metaData.arb_meta_keyword).focus();

                                // Add the class md-input-filled to filled input fields
                                $('input[name="eng_meta_title"], input[name="arb_meta_title"], textarea[name="eng_meta_description"], textarea[name="arb_meta_description"], textarea[name="eng_meta_keyword"], textarea[name="arb_meta_keyword"]').each(function() {
                                    if ($(this).val().trim() !== '') {
                                        $(this).addClass('md-input-filled');
                                    } else {
                                        $(this).removeClass('md-input-filled');
                                    }
                                });
                            } else {
                                // Handle case when no metadata is found for the selected page
                                alert('No metadata found for the selected page.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                } else {
                    // Handle case when no page is selected
                    alert('Please select a page.');
                }
            });
        });


    </script>


@endsection
