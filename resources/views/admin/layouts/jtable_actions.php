<script>
    // regions actions
    function regionActions() {
        var regionActions = {};
        <?php if (custom::rights(14, 'view'))
        { ?>
        regionActions['listAction'] = base_url + '/admin/region/getAll';
        <?php } ?>
        <?php if (custom::rights(14, 'delete'))
        { ?>
        regionActions['deleteAction'] = base_url + '/admin/region/deleteData';
        <?php } ?>
        <?php if (custom::rights(14, 'edit'))
        { ?>
        regionActions['updateAction'] = base_url + '/admin/region/updateData';
        <?php } ?>
        <?php if (custom::rights(14, 'add'))
        { ?>
        regionActions['createAction'] = base_url + '/admin/region/saveData';
        <?php } ?>
        return regionActions;
    }

    // cities actions
    function cityActions(data) {
        var cityActions = {};
        <?php if (custom::rights(14, 'view'))
        { ?>
        cityActions['listAction'] = base_url + '/admin/city/getAll?region_id=' + data.record.id;
        <?php } ?>
        <?php if (custom::rights(14, 'delete'))
        { ?>
        cityActions['deleteAction'] = base_url + '/admin/city/deleteData';
        <?php } ?>
        <?php if (custom::rights(14, 'edit'))
        { ?>
        cityActions['updateAction'] = base_url + '/admin/city/updateData';
        <?php } ?>
        <?php if (custom::rights(14, 'add'))
        { ?>
        cityActions['createAction'] = base_url + '/admin/city/saveData';
        <?php } ?>
        return cityActions;
    }

    // branches actions
    function branchActions(data) {
        var branchActions = {};
        <?php if (custom::rights(14, 'view'))
        { ?>
        branchActions['listAction'] = base_url + '/admin/branch/getAll?city_id=' + data.record.id + '&only_limousine_branches=' + only_limousine_branches;
        <?php } ?>
        <?php if (custom::rights(14, 'delete'))
        { ?>
        branchActions['deleteAction'] = base_url + '/admin/branch/deleteData';
        <?php } ?>
        <?php if (custom::rights(14, 'edit'))
        { ?>
        branchActions['updateAction'] = base_url + '/admin/branch/updateData';
        <?php } ?>
        <?php if (custom::rights(14, 'add'))
        { ?>
        branchActions['createAction'] = base_url + '/admin/branch/saveData';
        <?php } ?>
        return branchActions;
    }

    // car category actions
    function carCategoryActions() {
        var carCategoryActions = {};
        <?php if (custom::rights(15, 'view'))
        { ?>
        carCategoryActions['listAction'] = base_url + '/admin/car_category/getAllCarCategories';
        <?php } ?>
        <?php if (custom::rights(15, 'delete'))
        { ?>
        carCategoryActions['deleteAction'] = base_url + '/admin/car_category/deleteCategory';
        <?php } ?>
        <?php if (custom::rights(15, 'edit'))
        { ?>
        carCategoryActions['updateAction'] = base_url + '/admin/car_category/updateCategory';
        <?php } ?>
        <?php if (custom::rights(15, 'add'))
        { ?>
        carCategoryActions['createAction'] = base_url + '/admin/car_category/saveCategory';
        <?php } ?>
        return carCategoryActions;
    }

    // car group actions
    function carGroupActions(data) {
        var carGroupActions = {};
        <?php if (custom::rights(15, 'view'))
        { ?>
        carGroupActions['listAction'] = base_url + '/admin/car_group/getAllCarGroups?category_id=' + data.record.id;
        <?php } ?>
        <?php if (custom::rights(15, 'delete'))
        { ?>
        carGroupActions['deleteAction'] = base_url + '/admin/car_group/deleteCarGroup';
        <?php } ?>
        <?php if (custom::rights(15, 'edit'))
        { ?>
        carGroupActions['updateAction'] = base_url + '/admin/car_group/updateCarGroup';
        <?php } ?>
        <?php if (custom::rights(15, 'add'))
        { ?>
        carGroupActions['createAction'] = base_url + '/admin/car_group/saveCarGroup';
        <?php } ?>
        return carGroupActions;
    }

    // car type actions
    function carTypeActions(data) {
        var carTypeActions = {};
        <?php if (custom::rights(15, 'view'))
        { ?>
        carTypeActions['listAction'] = base_url + '/admin/car_type/getAllCarTypes?group_id=' + data.record.id;
        <?php } ?>
        <?php if (custom::rights(15, 'delete'))
        { ?>
        carTypeActions['deleteAction'] = base_url + '/admin/car_type/deleteCarType';
        <?php } ?>
        <?php if (custom::rights(15, 'edit'))
        { ?>
        carTypeActions['updateAction'] = base_url + '/admin/car_type/updateCarType';
        <?php } ?>
        <?php if (custom::rights(15, 'add'))
        { ?>
        carTypeActions['createAction'] = base_url + '/admin/car_type/saveCarType';
        <?php } ?>
        return carTypeActions;
    }

    // car model actions
    function carModelActions(data) {
        var carModelActions = {};
        <?php if (custom::rights(15, 'view'))
        { ?>
        carModelActions['listAction'] = base_url + '/admin/car_model/getAllCarModels?type_id=' + data.record.id;
        <?php } ?>
        <?php if (custom::rights(15, 'delete'))
        { ?>
        carModelActions['deleteAction'] = base_url + '/admin/car_model/deleteCarModel';
        <?php } ?>
        <?php if (custom::rights(15, 'edit'))
        { ?>
        carModelActions['updateAction'] = base_url + '/admin/car_model/updateCarModel';
        <?php } ?>
        <?php if (custom::rights(15, 'add'))
        { ?>
        carModelActions['createAction'] = base_url + '/admin/car_model/saveCarModel';
        <?php } ?>
        return carModelActions;
    }

    // current promotions and offers actions
    function currentPromotionActions() {
        var currentPromotionActions = {};
        <?php if (custom::rights(17, 'view'))
        { ?>
        currentPromotionActions['listAction'] = base_url + '/admin/promotions_offers/getAll?expired=0';
        <?php } ?>
        <?php if (custom::rights(17, 'delete'))
        { ?>
        currentPromotionActions['deleteAction'] = base_url + '/admin/promotions_offers/deleteData';
        <?php } ?>
        <?php if (custom::rights(17, 'edit'))
        { ?>
        currentPromotionActions['updateAction'] = base_url + '/admin/promotions_offers/updateData';
        <?php } ?>
        <?php if (custom::rights(17, 'add'))
        { ?>
        currentPromotionActions['createAction'] = base_url + '/admin/promotions_offers/saveData';
        <?php } ?>
        return currentPromotionActions;
    }

    // expired promotions and offers actions
    function expiredPromotionActions() {
        var expiredPromotionActions = {};
        <?php if (custom::rights(17, 'view'))
        { ?>
        expiredPromotionActions['listAction'] = base_url + '/admin/promotions_offers/getAll?expired=1';
        <?php } ?>
        <?php if (custom::rights(17, 'delete'))
        { ?>
        expiredPromotionActions['deleteAction'] = base_url + '/admin/promotions_offers/deleteData';
        <?php } ?>
        return expiredPromotionActions;
    }

    // drop-off actions
    function dropoffActions(data) {
        var dropoffActions = {};
        <?php if (custom::rights(16, 'view'))
        { ?>
        dropoffActions['listAction'] = base_url + '/admin/dropoff_charges/getAll?region_id=' + data.record.region_id + '&city_id=' + data.record.id;
        <?php } ?>
        <?php if (custom::rights(16, 'delete'))
        { ?>
        dropoffActions['deleteAction'] = base_url + '/admin/dropoff_charges/deleteData';
        <?php } ?>
        <?php if (custom::rights(16, 'edit'))
        { ?>
        dropoffActions['updateAction'] = base_url + '/admin/dropoff_charges/updateData';
        <?php } ?>
        <?php if (custom::rights(16, 'add'))
        { ?>
        dropoffActions['createAction'] = base_url + '/admin/dropoff_charges/saveData';
        <?php } ?>
        return dropoffActions;
    }

    // car price index page actions
    function carPriceIndexPageActions() {
        var carPriceIndexPageActions = {};
        carPriceIndexPageActions['listAction'] = base_url + '/admin/pricing/getAllCarModels';
        <?php if (custom::rights(18, 'edit'))
        { ?>
        carPriceIndexPageActions['updateAction'] = base_url + '/admin/car_model/updateCarModel';
        <?php } ?>

        return carPriceIndexPageActions;
    }

    // car price actions
    function carPriceActions(data) {
        var carPriceActions = {};
        <?php if (custom::rights(18, 'view'))
        { ?>
        carPriceActions['listAction'] = base_url + '/admin/pricing/getAllPricesForModel?model_id=' + data.record.id;
        <?php } ?>
        <?php if (custom::rights(18, 'delete'))
        { ?>
        carPriceActions['deleteAction'] = base_url + '/admin/pricing/deleteData';
        <?php } ?>
        <?php if (custom::rights(18, 'edit'))
        { ?>
        carPriceActions['updateAction'] = base_url + '/admin/pricing/updateData';
        <?php } ?>
        <?php if (custom::rights(18, 'add'))
        { ?>
        carPriceActions['createAction'] = base_url + '/admin/pricing/saveData';
        <?php } ?>
        return carPriceActions;
    }

    // car price actions
    function expiredCarPriceActions(data) {
        var carPriceActions = {};
        <?php if (custom::rights(18, 'view'))
        { ?>
        carPriceActions['listAction'] = base_url + '/admin/pricing/getAllPricesForModel?model_id=' + data.record.id + '&expired=1';
        <?php } ?>
        return carPriceActions;
    }

    // car extra charges actions
    function carExtraChargesActions(data) {
        var carExtraChargesActions = {};
        <?php if (custom::rights(18, 'view'))
        { ?>
        carExtraChargesActions['listAction'] = base_url + '/admin/pricing/getAllExtrasForModel?model_id=' + data.record.id;
        <?php } ?>
        <?php if (custom::rights(18, 'delete'))
        { ?>
        carExtraChargesActions['deleteAction'] = base_url + '/admin/pricing/deleteData';
        <?php } ?>
        <?php if (custom::rights(18, 'edit'))
        { ?>
        carExtraChargesActions['updateAction'] = base_url + '/admin/pricing/updateData';
        <?php } ?>
        <?php if (custom::rights(18, 'add'))
        { ?>
        carExtraChargesActions['createAction'] = base_url + '/admin/pricing/saveData';
        <?php } ?>
        return carExtraChargesActions;
    }

    // car extra charges actions
    function expiredCarExtraChargesActions(data) {
        var carExtraChargesActions = {};
        <?php if (custom::rights(18, 'view'))
        { ?>
        carExtraChargesActions['listAction'] = base_url + '/admin/pricing/getAllExtrasForModel?model_id=' + data.record.id + '&expired=1';
        <?php } ?>
        return carExtraChargesActions;
    }

    // loyalty card type actions
    function loyaltyCardTypeActions() {
        var loyaltyCardTypeActions = {};
        <?php if (custom::rights(12, 'edit'))
        { ?>
        loyaltyCardTypeActions['deleteAction'] = base_url + '/admin/settings/delete_loyalty_card_type';
        loyaltyCardTypeActions['updateAction'] = base_url + '/admin/settings/update_loyalty_card_type';
        loyaltyCardTypeActions['createAction'] = base_url + '/admin/settings/add_loyalty_card_type';
        <?php } ?>
        loyaltyCardTypeActions['listAction'] = base_url + '/admin/settings/get_loyalty_card_types';
        return loyaltyCardTypeActions;
    }

    // renting type actions
    function rentingTypeActions() {
        var rentingTypeActions = {};
        <?php if (custom::rights(12, 'edit'))
        { ?>
        rentingTypeActions['deleteAction'] = base_url + '/admin/settings/delete_renting_type';
        rentingTypeActions['updateAction'] = base_url + '/admin/settings/update_renting_type';
        rentingTypeActions['createAction'] = base_url + '/admin/settings/add_renting_type';
        <?php } ?>
        rentingTypeActions['listAction'] = base_url + '/admin/settings/get_renting_types';
        return rentingTypeActions;
    }

    // inquiry type actions
    function inquiryTypeActions() {
        var inquiryTypeActions = {};
        <?php if (custom::rights(12, 'edit'))
        { ?>
        inquiryTypeActions['deleteAction'] = base_url + '/admin/settings/delete_listing?tbl=setting_inquiry_type';
        inquiryTypeActions['updateAction'] = base_url + '/admin/settings/update_listing?tbl=setting_inquiry_type';
        inquiryTypeActions['createAction'] = base_url + '/admin/settings/add_listing?tbl=setting_inquiry_type';
        <?php } ?>
        inquiryTypeActions['listAction'] = base_url + '/admin/settings/get_listing?tbl=setting_inquiry_type';
        return inquiryTypeActions;
    }

    // department actions
    function departmentActions() {
        var departmentActions = {};
        <?php if (custom::rights(12, 'edit'))
        { ?>
        departmentActions['deleteAction'] = base_url + '/admin/settings/delete_departments';
        departmentActions['updateAction'] = base_url + '/admin/settings/update_departments';
        departmentActions['createAction'] = base_url + '/admin/settings/add_departments';
        <?php } ?>
        departmentActions['listAction'] = base_url + '/admin/settings/get_departments';
        return departmentActions;
    }

    // user role actions
    function userRoleActions() {
        var userRoleActions = {};
        <?php if (custom::rights(12, 'edit'))
        { ?>
        userRoleActions['deleteAction'] = base_url + '/admin/settings/delete_listing?tbl=setting_user_role';
        userRoleActions['updateAction'] = base_url + '/admin/settings/update_listing?tbl=setting_user_role';
        userRoleActions['createAction'] = base_url + '/admin/settings/add_listing?tbl=setting_user_role&action=save_rights_for_role';
        <?php } ?>
        userRoleActions['listAction'] = base_url + '/admin/settings/get_listing?tbl=setting_user_role';
        return userRoleActions;
    }

    // faqs listing actions
    function faqListingActions() {
        var faqListingActions = {};
        <?php if (custom::rights(11, 'edit'))
        { ?>
        faqListingActions['deleteAction'] = base_url + '/admin/settings/delete_listing?tbl=faqs_question';
        faqListingActions['updateAction'] = base_url + '/admin/settings/update_listing?tbl=faqs_question';
        faqListingActions['createAction'] = base_url + '/admin/settings/add_listing?tbl=faqs_question';
        <?php } ?>
        <?php if (custom::rights(11, 'view'))
        { ?>
        faqListingActions['listAction'] = base_url + '/admin/settings/get_listing?tbl=faqs_question';
        <?php } ?>
        return faqListingActions;
    }

    // news listing actions
    function newsListingActions() {
        var newsListingActions = {};
        <?php if (custom::rights(11, 'edit'))
        { ?>
        newsListingActions['deleteAction'] = base_url + '/admin/settings/delete_listing?tbl=news_listing';
        newsListingActions['updateAction'] = base_url + '/admin/settings/update_listing?tbl=news_listing';
        newsListingActions['createAction'] = base_url + '/admin/settings/add_listing?tbl=news_listing';
        <?php } ?>
        <?php if (custom::rights(11, 'view'))
        { ?>
        newsListingActions['listAction'] = base_url + '/admin/settings/get_listing?tbl=news_listing';
        <?php } ?>
        return newsListingActions;
    }

    /*for program rewards*/
    function programAwardsListingActions() {
        var programAwardsListingActions = {};
        <?php if (custom::rights(42, 'delete')) { ?>
        programAwardsListingActions['deleteAction'] = base_url + '/admin/settings/delete_listing?tbl=programs_rewards_listing';
        <?php } ?>
        <?php if (custom::rights(42, 'edit')){ ?>
        programAwardsListingActions['updateAction'] = base_url + '/admin/settings/update_listing?tbl=programs_rewards_listing';
        <?php } ?>
        <?php if (custom::rights(42, 'add')){ ?>
        programAwardsListingActions['createAction'] = base_url + '/admin/settings/add_listing?tbl=programs_rewards_listing';
        <?php } ?>
        <?php if (custom::rights(42, 'view')){ ?>
        programAwardsListingActions['listAction'] = base_url + '/admin/settings/get_listing?tbl=programs_rewards_listing';
        <?php } ?>
        return programAwardsListingActions;
    }

    /*for corporate sales*/

    function corporateSalesListingActions() {
        var corporateSalesListingActions = {};
        <?php if (custom::rights(43, 'delete')) { ?>
        corporateSalesListingActions['deleteAction'] = base_url + '/admin/settings/delete_listing?tbl=corporate_listing';
        <?php } ?>
        <?php if (custom::rights(43, 'edit')){ ?>
        corporateSalesListingActions['updateAction'] = base_url + '/admin/settings/update_listing?tbl=corporate_listing';
        <?php } ?>
        <?php if (custom::rights(43, 'add')){ ?>
        corporateSalesListingActions['createAction'] = base_url + '/admin/settings/add_listing?tbl=corporate_listing';
        <?php } ?>
        <?php if (custom::rights(43, 'view')){ ?>
        corporateSalesListingActions['listAction'] = base_url + '/admin/settings/get_listing?tbl=corporate_listing';
        <?php } ?>
        return corporateSalesListingActions;
    }

    // home slider listing actions
    function homeSliderListingActions() {
        var homeSliderListingActions = {};
        <?php if (custom::rights(11, 'edit'))
        { ?>
        homeSliderListingActions['deleteAction'] = base_url + '/admin/page/deleteHomeSlider';
        homeSliderListingActions['updateAction'] = base_url + '/admin/page/updateHomeSlider';
        homeSliderListingActions['createAction'] = base_url + '/admin/page/saveHomeSlider';
        <?php } ?>
        <?php if (custom::rights(11, 'view'))
        { ?>
        homeSliderListingActions['listAction'] = base_url + '/admin/page/getHomeSlider';
        <?php } ?>
        return homeSliderListingActions;
    }

    // Mobile slider listing actions
    function mobileSliderListingActions() {
        var mobileSliderListingActions = {};
        <?php if (custom::rights(11, 'edit'))
        { ?>
        mobileSliderListingActions['deleteAction'] = base_url + '/admin/page/deleteMobileSlider';
        mobileSliderListingActions['updateAction'] = base_url + '/admin/page/updateMobileSlider';
        mobileSliderListingActions['createAction'] = base_url + '/admin/page/saveMobileSlider';
        <?php } ?>
        <?php if (custom::rights(11, 'view'))
        { ?>
        mobileSliderListingActions['listAction'] = base_url + '/admin/page/getMobileSlider';
        <?php } ?>
        return mobileSliderListingActions;
    }

    // loyalty km actions
    function LoyaltyKmActions() {
        var LoyaltyKmActions = {};
        LoyaltyKmActions['updateAction'] = base_url + '/admin/settings/update_listing?tbl=setting_loyalty_km';
        //LoyaltyKmActions['createAction'] = base_url + '/admin/settings/add_listing?tbl=setting_loyalty_km';
        LoyaltyKmActions['listAction'] = base_url + '/admin/settings/get_listing?tbl=setting_loyalty_km';
        return LoyaltyKmActions;
    }

    // survey actions
    function surveyActions() {
        var surveyActions = {};
        <?php if (custom::rights(31, 'view'))
        { ?>
        surveyActions['listAction'] = base_url + '/admin/survey/getAllEmojis';
        <?php } ?>
        <?php if (custom::rights(31, 'edit'))
        { ?>
        surveyActions['updateAction'] = base_url + '/admin/settings/update_listing?tbl=survey_emoji';
        <?php } ?>
        return surveyActions;
    }

    // survey categories actions
    function surveyCategoryActions(data) {
        var surveyCategoryActions = {};

        <?php if (custom::rights(31, 'view'))
        { ?>
        surveyCategoryActions['listAction'] = base_url + '/admin/survey/getAllSurveyCategories?emoji_id=' + data.record.id;
        <?php } ?>
        <?php if (custom::rights(31, 'delete'))
        { ?>
        surveyCategoryActions['deleteAction'] = base_url + '/admin/survey/deleteSurveyCategoryData';
        <?php } ?>
        <?php if (custom::rights(31, 'edit'))
        { ?>
        surveyCategoryActions['updateAction'] = base_url + '/admin/survey/updateSurveyCategoryData';
        <?php } ?>
        <?php if (custom::rights(31, 'add'))
        { ?>
        surveyCategoryActions['createAction'] = base_url + '/admin/survey/saveSurveyCategoryData';
        <?php } ?>
        return surveyCategoryActions;
    }

    // survey categories options actions
    function surveyCategoryOptionsActions(data) {
        var surveyCategoryOptionsActions = {};

        <?php if (custom::rights(31, 'view'))
        { ?>
        surveyCategoryOptionsActions['listAction'] = base_url + '/admin/survey/getAllSurveyCategoryOptions?category_id=' + data.record.id;
        <?php } ?>
        <?php if (custom::rights(31, 'delete'))
        { ?>
        surveyCategoryOptionsActions['deleteAction'] = base_url + '/admin/survey/deleteSurveyCategoryOptionData';
        <?php } ?>
        <?php if (custom::rights(31, 'edit'))
        { ?>
        surveyCategoryOptionsActions['updateAction'] = base_url + '/admin/survey/updateSurveyCategoryOptionData';
        <?php } ?>
        <?php if (custom::rights(31, 'add'))
        { ?>
        surveyCategoryOptionsActions['createAction'] = base_url + '/admin/survey/saveSurveyCategoryOptionData';
        <?php } ?>
        return surveyCategoryOptionsActions;
    }

    // individual customer actions
    function individualCustomersActions() {
        var individualCustomersActions = {};
        <?php if (custom::rights(30, 'edit'))
        { ?>
        individualCustomersActions['updateAction'] = base_url + '/admin/updateCustomerForJTable';
        <?php } ?>
        individualCustomersActions['listAction'] = base_url + '/admin/getCustomerForJTable';
        return individualCustomersActions;
    }

    // car selling category actions
    function carSellingCategoryActions() {
        var carSellingCategoryActions = {};
        <?php if (custom::rights(38, 'delete'))
        { ?>
        carSellingCategoryActions['deleteAction'] = base_url + '/admin/settings/delete_listing?tbl=car_selling_brand';
        <?php } ?>
        <?php if (custom::rights(38, 'edit'))
        { ?>
        carSellingCategoryActions['updateAction'] = base_url + '/admin/settings/update_listing?tbl=car_selling_brand';
        <?php } ?>
        <?php if (custom::rights(38, 'add'))
        { ?>
        carSellingCategoryActions['createAction'] = base_url + '/admin/settings/add_listing?tbl=car_selling_brand';
        <?php } ?>
        carSellingCategoryActions['listAction'] = base_url + '/admin/settings/get_listing?tbl=car_selling_brand';
        return carSellingCategoryActions;
    }

    // car selling models actions
    function carSellingModelsActions(data) {
        var carSellingModelsActions = {};
        <?php if (custom::rights(38, 'delete'))
        { ?>
        carSellingModelsActions['deleteAction'] = base_url + '/admin/settings/delete_listing?tbl=car_selling_model';
        <?php } ?>
        <?php if (custom::rights(38, 'edit'))
        { ?>
        carSellingModelsActions['updateAction'] = base_url + '/admin/settings/update_listing?tbl=car_selling_model';
        <?php } ?>
        <?php if (custom::rights(38, 'add'))
        { ?>
        carSellingModelsActions['createAction'] = base_url + '/admin/settings/add_listing?tbl=car_selling_model';
        <?php } ?>
        carSellingModelsActions['listAction'] = base_url + '/admin/car-selling/get_car_models_listing?car_brand_id=' + data.record.id;
        return carSellingModelsActions;
    }

    function carsSellingResponsesActions() {
        var carsSellingResponsesActions = {};
        carsSellingResponsesActions['listAction'] = base_url + '/admin/car-selling/getAllResponses';
        return carsSellingResponsesActions;
    }

    function corporateSalesResponsesActions() {
        var corporateSalesResponsesActions = {};
        corporateSalesResponsesActions['listAction'] = base_url + '/admin/corporate-sales/getAllResponses';
        return corporateSalesResponsesActions;
    }

    function redeemFactorActions() {
        var redeemFactorActions = {};
        <?php if (custom::rights(40, 'view'))
        { ?>
        redeemFactorActions['listAction'] = base_url + '/admin/settings/get_listing?tbl=setting_redeem_factor';
        <?php } ?>
        <?php if (custom::rights(40, 'add'))
        { ?>
        redeemFactorActions['createAction'] = base_url + '/admin/settings/add_listing?tbl=setting_redeem_factor';
        <?php } ?>
        <?php if (custom::rights(40, 'edit'))
        { ?>
        redeemFactorActions['updateAction'] = base_url + '/admin/settings/update_listing?tbl=setting_redeem_factor';
        <?php } ?>
        <?php if (custom::rights(40, 'delete'))
        { ?>
        redeemFactorActions['deleteAction'] = base_url + '/admin/settings/delete_listing?tbl=setting_redeem_factor';
        <?php } ?>
        return redeemFactorActions;
    }

    function redeemSetupCarTypeActions() {
        var redeemSetupCarTypeActions = {};
        <?php if (custom::rights(41, 'view'))
        { ?>
        redeemSetupCarTypeActions['listAction'] = base_url + '/admin/redeem_setup/getAll';
        <?php } ?>
        <?php if (custom::rights(41, 'add'))
        { ?>
        redeemSetupCarTypeActions['createAction'] = base_url + '/admin/redeem_setup/saveData';
        <?php } ?>
        <?php if (custom::rights(41, 'edit'))
        { ?>
        redeemSetupCarTypeActions['updateAction'] = base_url + '/admin/redeem_setup/updateData';
        <?php } ?>
        <?php if (custom::rights(41, 'delete'))
        { ?>
        redeemSetupCarTypeActions['deleteAction'] = base_url + '/admin/redeem_setup/deleteData';
        <?php } ?>
        return redeemSetupCarTypeActions;
    }

    function availabilitySetupActions() {
        var availabilitySetupActions = {};
        <?php if (custom::rights(44, 'view'))
        { ?>
        availabilitySetupActions['listAction'] = base_url + '/admin/availability/getAll';
        <?php } ?>
        <?php if (custom::rights(44, 'add'))
        { ?>
        availabilitySetupActions['createAction'] = base_url + '/admin/availability/saveData';
        <?php } ?>
        <?php if (custom::rights(44, 'edit'))
        { ?>
        availabilitySetupActions['updateAction'] = base_url + '/admin/availability/updateData';
        <?php } ?>
        <?php if (custom::rights(44, 'delete'))
        { ?>
        availabilitySetupActions['deleteAction'] = base_url + '/admin/availability/deleteData';
        <?php } ?>
        return availabilitySetupActions;
    }

    function bookingCancellationReasonsActions() {
        var bookingCancellationReasonsActions = {};
        <?php if (custom::rights(53, 'view'))
        { ?>
        bookingCancellationReasonsActions['listAction'] = base_url + '/admin/booking-cancellation-reasons/getAll';
        <?php } ?>
        <?php if (custom::rights(53, 'add'))
        { ?>
        bookingCancellationReasonsActions['createAction'] = base_url + '/admin/booking-cancellation-reasons/saveData';
        <?php } ?>
        <?php if (custom::rights(53, 'edit'))
        { ?>
        bookingCancellationReasonsActions['updateAction'] = base_url + '/admin/booking-cancellation-reasons/updateData';
        <?php } ?>
        <?php if (custom::rights(53, 'delete'))
        { ?>
        bookingCancellationReasonsActions['deleteAction'] = base_url + '/admin/booking-cancellation-reasons/deleteData';
        <?php } ?>
        return bookingCancellationReasonsActions;
    }

    function loyaltyProgramsActions() {
        var loyaltyProgramsActions = {};
        <?php if (custom::rights(54, 'view'))
        { ?>
        loyaltyProgramsActions['listAction'] = base_url + '/admin/loyalty-programs/getAll';
        <?php } ?>
        <?php if (custom::rights(54, 'add'))
        { ?>
        loyaltyProgramsActions['createAction'] = base_url + '/admin/loyalty-programs/saveData';
        <?php } ?>
        <?php if (custom::rights(54, 'edit'))
        { ?>
        loyaltyProgramsActions['updateAction'] = base_url + '/admin/loyalty-programs/updateData';
        <?php } ?>
        <?php if (custom::rights(54, 'delete'))
        { ?>
        loyaltyProgramsActions['deleteAction'] = base_url + '/admin/loyalty-programs/deleteData';
        <?php } ?>
        return loyaltyProgramsActions;
    }

    function NotificationsActions() {
        var NotificationsActions = {};
        <?php if (custom::rights(55, 'view'))
        { ?>
        NotificationsActions['listAction'] = base_url + '/admin/notifications/getAll';
        <?php } ?>
        <?php if (custom::rights(55, 'add'))
        { ?>
        NotificationsActions['createAction'] = base_url + '/admin/notifications/saveData';
        <?php } ?>
        <?php if (custom::rights(55, 'delete'))
        { ?>
        NotificationsActions['deleteAction'] = base_url + '/admin/notifications/deleteData';
        <?php } ?>
        return NotificationsActions;
    }

    function GeneralTimingForDeliveryBranchesActions() {
        var GeneralTimingForDeliveryBranchesActions = {};
        <?php if (custom::rights(14, 'view'))
        { ?>
        GeneralTimingForDeliveryBranchesActions['listAction'] = base_url + '/admin/branch/get-general-timing-for-delivery-branches';
        <?php } ?>
        <?php if (custom::rights(14, 'edit'))
        { ?>
        GeneralTimingForDeliveryBranchesActions['updateAction'] = base_url + '/admin/branch/update-general-timing-for-delivery-branches';
        <?php } ?>
        return GeneralTimingForDeliveryBranchesActions;
    }

    function BookingAddedPaymentsTableActions() {
        var BookingAddedPaymentsTableActions = {};
        <?php if (custom::rights(58, 'view'))
        { ?>
        BookingAddedPaymentsTableActions['listAction'] = base_url + '/admin/bookings/getAllBookingAddedPayments';
        <?php } ?>
        return BookingAddedPaymentsTableActions;
    }

    function loyaltyCardsActions() {
        var loyaltyCardsActions = {};
        <?php if (custom::rights(11, 'view'))
        { ?>
        loyaltyCardsActions['listAction'] = base_url + '/admin/loyalty-cards/getAll';
        <?php } ?>
        <?php if (custom::rights(11, 'view'))
        { ?>
        loyaltyCardsActions['createAction'] = base_url + '/admin/loyalty-cards/saveData';
        <?php } ?>
        <?php if (custom::rights(11, 'view'))
        { ?>
        loyaltyCardsActions['updateAction'] = base_url + '/admin/loyalty-cards/updateData';
        <?php } ?>
        <?php if (custom::rights(11, 'view'))
        { ?>
        loyaltyCardsActions['deleteAction'] = base_url + '/admin/loyalty-cards/deleteData';
        <?php } ?>
        return loyaltyCardsActions;
    }

    function loyaltyRewardProgramsActions() {
        var loyaltyRewardProgramsActions = {};
        <?php if (custom::rights(11, 'view'))
        { ?>
        loyaltyRewardProgramsActions['listAction'] = base_url + '/admin/loyalty-reward-programs/getAll';
        <?php } ?>
        <?php if (custom::rights(11, 'view'))
        { ?>
        loyaltyRewardProgramsActions['createAction'] = base_url + '/admin/loyalty-reward-programs/saveData';
        <?php } ?>
        <?php if (custom::rights(11, 'view'))
        { ?>
        loyaltyRewardProgramsActions['updateAction'] = base_url + '/admin/loyalty-reward-programs/updateData';
        <?php } ?>
        <?php if (custom::rights(11, 'view'))
        { ?>
        loyaltyRewardProgramsActions['deleteAction'] = base_url + '/admin/loyalty-reward-programs/deleteData';
        <?php } ?>
        return loyaltyRewardProgramsActions;
    }

    function loyaltyFaqsActions() {
        var loyaltyFaqsActions = {};
        <?php if (custom::rights(11, 'view'))
        { ?>
        loyaltyFaqsActions['listAction'] = base_url + '/admin/loyalty-faqs/getAll';
        <?php } ?>
        <?php if (custom::rights(11, 'view'))
        { ?>
        loyaltyFaqsActions['createAction'] = base_url + '/admin/loyalty-faqs/saveData';
        <?php } ?>
        <?php if (custom::rights(11, 'view'))
        { ?>
        loyaltyFaqsActions['updateAction'] = base_url + '/admin/loyalty-faqs/updateData';
        <?php } ?>
        <?php if (custom::rights(11, 'view'))
        { ?>
        loyaltyFaqsActions['deleteAction'] = base_url + '/admin/loyalty-faqs/deleteData';
        <?php } ?>
        return loyaltyFaqsActions;
    }

    function CorporateQuotationsActions() {
        var corporateQuotationsActions = {};
        <?php if (custom::rights(60, 'view'))
        { ?>
        corporateQuotationsActions['listAction'] = base_url + '/admin/corporate_quotations/getCorporateQuotations?corporate_customer_id=<?php echo (isset($corporate_customer_id) ? $corporate_customer_id : '');  ?>';
        <?php } ?>
        <?php if (custom::rights(60, 'edit'))
        { ?>
        corporateQuotationsActions['updateAction'] = base_url + '/admin/corporate_quotations/updateCorporateQuotation';
        <?php } ?>
        <?php if (custom::rights(60, 'delete'))
        { ?>
        corporateQuotationsActions['deleteAction'] = base_url + '/admin/corporate_quotations/deleteCorporateQuotation';
        <?php } ?>
        return corporateQuotationsActions;
    }

    function CorporateQuotationPricesActions(id) {
        var corporateQuotationPricesActions = {};
        <?php if (custom::rights(60, 'view'))
        { ?>
        corporateQuotationPricesActions['listAction'] = base_url + '/admin/corporate_quotations/getCorporateQuotationPrices?corporate_quotation_id=' + id
        <?php } ?>
        <?php if (custom::rights(60, 'edit'))
        { ?>
        corporateQuotationPricesActions['updateAction'] = base_url + '/admin/corporate_quotations/updateCorporateQuotationPrice';
        <?php } ?>
        <?php if (custom::rights(60, 'delete'))
        { ?>
        corporateQuotationPricesActions['deleteAction'] = base_url + '/admin/corporate_quotations/deleteCorporateQuotationPrice';
        <?php } ?>
        return corporateQuotationPricesActions;
    }

    // car selling top slider actions
    function CarsSellingTopSliderActions() {
        var CarsSellingTopSliderActions = {};
        <?php if (custom::rights(37, 'delete'))
        { ?>
        CarsSellingTopSliderActions['deleteAction'] = base_url + '/admin/settings/delete_listing?tbl=car_selling_slider_images';
        <?php } ?>
        <?php if (custom::rights(37, 'edit'))
        { ?>
        CarsSellingTopSliderActions['updateAction'] = base_url + '/admin/settings/update_listing?tbl=car_selling_slider_images';
        <?php } ?>
        <?php if (custom::rights(37, 'add'))
        { ?>
        CarsSellingTopSliderActions['createAction'] = base_url + '/admin/settings/add_listing?tbl=car_selling_slider_images';
        <?php } ?>
        CarsSellingTopSliderActions['listAction'] = base_url + '/admin/settings/get_listing?tbl=car_selling_slider_images';
        return CarsSellingTopSliderActions;
    }

    // car selling services actions
    function CarsSellingServicesActions() {
        var CarsSellingServicesActions = {};
        <?php if (custom::rights(37, 'delete'))
        { ?>
        CarsSellingServicesActions['deleteAction'] = base_url + '/admin/settings/delete_listing?tbl=car_selling_services';
        <?php } ?>
        <?php if (custom::rights(37, 'edit'))
        { ?>
        CarsSellingServicesActions['updateAction'] = base_url + '/admin/settings/update_listing?tbl=car_selling_services';
        <?php } ?>
        <?php if (custom::rights(37, 'add'))
        { ?>
        CarsSellingServicesActions['createAction'] = base_url + '/admin/settings/add_listing?tbl=car_selling_services';
        <?php } ?>
        CarsSellingServicesActions['listAction'] = base_url + '/admin/settings/get_listing?tbl=car_selling_services';
        return CarsSellingServicesActions;
    }

</script>