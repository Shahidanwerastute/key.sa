
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>main-html</title>
    <link href="<?php echo custom::baseurl('/public/frontend/css/rtl.css'); ?>" rel="stylesheet" type="text/css" media="all">
    <link href="http://fonts.googleapis.com/css?family=Cairo:300,400,600,700,900|Lato:100,300,400,700,900"
          rel="stylesheet">
    <style type="text/css">
        body {
            margin: 0 auto;
            font-family: Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif !important;
            background: #fff;
        }
        body:before {
            display:none !important;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        body img {
            max-width: 100%;
            height: auto;
        }
        .prt_contianer {
            width: 600px;
            margin: 15px auto;
            border:.5px solid #d3d3d3;
            -webkit-box-shadow: 0px 1px 5px 0px rgba(0, 0, 0, 0.2);
            -moz-box-shadow: 0px 1px 5px 0px rgba(0, 0, 0, 0.2);
            box-shadow: 0px 1px 5px 0px rgba(0, 0, 0, 0.2);
            padding: 30px;
            direction: rtl;
            position: relative;
            min-height: 100%;
        }
        .logo {
            text-align: left;
        }
        .logo img {
            width: 80px;
            margin-right: auto;
        }
        .mn_content {
            text-align: right;
        }
        .mn_content h1 {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }
        .mn_content ul {
            padding-right: 0;
            list-style: none;
        }
        .mn_content ul li {
            font-size: 12px;
        }
        .mn_content ul li span {
            float: right;
            margin-left: 10px;
        }
        .mn_content ul li p {
            display: table;
            margin: 5px 0px 5px 0;
        }
        .mn_content ol {
            list-style: none;
            padding-right: 60px;
        }
        .mn_content ol li span {
            float: right;
            margin-left: 5px;
        }
        .mn_content ol li span input[type="checkbox"] {
            width: 15px;
            height: 15px;
        }
        .mn_content ol li p {
            display: table;
        }
        .mn_content ol h2 {
            font-size: 12px;
            font-weight: bold;
        }
        .de-footer {
            width:70%;
            margin:0 auto;
        }
        .pg-1 .de-footer {
            position: absolute;
            bottom: 30px;
            left: 15%;
        }
        .pg-1 .de-table {
            margin-bottom: 75px;
        }
        /*.de-footer img{width: 70%; margin: 0 auto;}*/

        .de-table table th {
            text-align:left;
            font-size: 11px;
        }
        .de-table table tr {
            margin-bottom:10px;
        }
        .form-control {
            display: block;
            width: 100%;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding-right:10px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .de-table table td input.fd-style {
            border: dashed 1px #000;
            height: 20px;
        }
        .de-table table td input.fd-style:focus {
            outline:none;
        }
        .grp_btns {
            display: table;
            width: 100%;
        }
        .grp_btns .sbmt input {
            background: #2cbfc9;
            color: #FFF;
            float: left;
            border:0;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: -7px;
        }
        .grp_btns .dwld button {
            background: #176591;
            color: #fff;
            float: right;
            border:0;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        @media print {
            .prt_contianer {
                border:none;
                box-shadow: unset;
            }
            .grp_btns .sbmt input {
                background: #176591;
                color: #FFF;
                float: left;
                border:0;
                padding: 5px 10px;
                border-radius: 4px;
                cursor: pointer;
                margin-left: -7px;
            }
            .grp_btns .dwld button {
                background: #2cbfc9;
                color: #fff;
                float: right;
                border:0;
                padding: 5px 10px;
                border-radius: 4px;
                cursor: pointer;
            }
        }
        @media (max-width: 599px) {
            .res_contain {
                overflow-y: auto;
            }
        }
    </style>
</head>
<body>
<!-- Page-1 -->
<div class="res_contain">
    <div class="prt_contianer pg-1">
        <div class="logo"><img src="images/logo.png" alt="logo"></div>
        <div class="mn_content">
            <h1>شروط وأحكام والتزامات استخدام نظام خدمة التصديق الالكتروني</h1>
            <ul>
                <li><span>1.</span>
                    <p> الالتزام والموافقة على دليل الإستخدام بقنوات الغرفة باليوتيوب. </p>
                </li>
                <li><span>2.</span>
                    <p>الالتزام بكامل البنود والشروط المذكورة في الموقع الخاص بالتصديق الالكتروني وكافة أنظمة الغرفة المعمول بها . </p>
                </li>
                <li><span>3.</span>
                    <p> الالتزام بتسليم الغرفة التجارية الصناعية بجدة عبر الموقع صورة ملونة عن ختم المنشأة المعتمد والمحررات الرسمية ليتم تسجيلها بالنظام (رأس وتذييل الخطاب الرسمي) وفي حالة وجود أي تعديل يتم تزويد الغرفة التجارية الصناعية بجدة بها قبل تنفيذ أي تصديق. </p>
                </li>
                <li><span>4.</span>
                    <p> الالتزام بالكتابة والطباعة الملونة من خلال نظام التصديق الالكتروني. </p>
                </li>
                <li><span>5.</span>
                    <p>تحديد الأشخاص المخولين لاستخدام النظام بخطابات رسمية مصدقة من المستخدم مع ضرورة التأكيد بعدم الإفصاح بإسم المستخدم وكلمة السر لأي شخص حيث أن هذه البيانات تعتبر سرية ومسئولية المستخدم. </p>
                </li>
                <li><span>6.</span>
                    <p> الالتزام بعدم تنفيذ التصديق الالكتروني على المحررات التي صدرت بشأنها قرارات من جهات رسمية بعدم التصديق عليها (مرفق). </p>
                </li>
                <li><span>7.</span>
                    <p>يلتزم المستخدم بالامتناع عن التصديق على محتوى قد عمم عليه بشكل سابق من قبل الغرفة التجارية الصناعية بجدة. </p>
                </li>
                <li><span>8.</span>
                    <p>في حالة تنفيذ التصديق الالكتروني على المحررات الموجودة ضمن القائمة السابق ذكرها ( الفقرة 6) تكون على مسئولية المستخدم  بالكامل و ليس للغرفة التجارية الصناعية بجدة  أي علاقة. </p>
                </li>
                <li><span>9.</span>
                    <p>يعتبر الورق والملصق الأمني ضمن عهدة المستخدم ولا يحق له إعادة بيع أو إعادة استخدامه في غير مخرجات نظام التصديق الالكتروني. </p>
                </li>
                <li><span>10.</span>
                    <p>يتحمل المستخدم المسئولية الكاملة تجاه تعامله مع الورق واللاصق الأمني ولا يحق له إرجاع او طلب او استبدال الورق واللاصق الأمني إلا في حالة وجود عيب مصنعي واضح. </p>
                </li>
                <li><span>11.</span>
                    <p>يتحمل المستخدم منفرداً المسئولية الكاملة على جميع المحررات ومحتوياتها الصادرة من خلال نظام التصديق الالكتروني.</p>
                </li>
                <li><span>12.</span>
                    <p>يتحمل المستخدم كامل المسئولية في حالة وجود أي التزام قانوني أو مالي أو أدبي نتيجة سوء استخدام النظام , ولا يدخل الطرف الأول في أي مسائلات أو نزاعات قد تطرأ بعد ذلك .</p>
                </li>
                <li><span>13.</span>
                    <p>يتحمل المستخدم كامل المسئولية على المحتوى الذي تمت المصادقة عليه ولا تدخل الغرفة التجارية الصناعية بجدة  في أي مسائلات أو نزاعات قد تطرأ نتيجة التصديق على المحتوى.</p>
                </li>
                <li><span>14.</span>
                    <p>عند انتهاء فترة اشتراك عضوية المستخدم لدي الغرفة التجارية الصناعية بجدة لا يحق للمستخدم استخدام النظام أو الورق الأمني أو الملصق الأمني إلا بعد تجديد العضوية لدى الغرفة التجارية الصناعية بجدة .</p>
                </li>
                <li><span>15.</span>
                    <p>يلتزم المستخدم بالتصديق على المحررات الخاصة به فقط , ولا يحق له استخدام النظام للتصديق لمؤسسة أو شركة أخرى.</p>
                </li>
                <li><span>16.</span>
                    <p>يتحمل المستخدم المسؤولية الكاملة في حال التأكد بأنه قد استخدم أحد برامج مايكروسوفت أوفيس أو غيرها من برامج الكتابة أو الطباعة على المحررات , وفي حال استخدامه لها فإنها تعد من جرائم التزوير.</p>
                </li>
                <li><span>17.</span>
                    <p>في حال توقف نشاط المنشأة أو الاغلاق أو عدم رغبة المستخدم بخدمة التصديق الالكتروني فلا يحق لها طلب استرداد أي مبالغ دفعت مقابل انتفاعه من الخدمة .</p>
                </li>
                <li><span>18.</span>
                    <p>بناءً على ما ورد في المادة (16) من لائحة نظام الغرفة التجارية بإضافة رسم قدره (10) ريالات مقابل التصديق الالكتروني .</p>
                </li>
                <li><span>19.</span>
                    <p>قيمة التصديق الواحد (35) ريالاً للورقة الواحدة واللاصق الأمني .</p>
                </li>
            </ul>
            <div class="clearfix"></div>
            <ol>
                <li>
                    <h2><span>
            <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
            </span>
                        <p>اتعهد وأقر بأني قمت بقراءة وفهم جميع البنود أعلاه والالتزام والتقيد بها دون ادنى مسئولية تقع على الغرفة في حال عدم التقييد بالشروط والاحكام أعلاه .</p>
                    </h2>
                    <div class="clearfix"></div>
                </li>
            </ol>
            <div class="de-table">
                <table width="100%" height="119" border="0" cellpadding="0" cellspacing="5">
                    <tr>
                        <th width="25%" scope="row">الاسم :</th>
                        <td width="75%"><input type="text" class="form-control fd-style" name="" placeholder="نص"></td>
                    </tr>
                    <tr>
                        <th scope="row">توقيع المدير العام :</th>
                        <td><input type="text" class="form-control fd-style" name="" placeholder="نص"></td>
                    </tr>
                    <tr>
                        <th scope="row">رقم السجل التجاري :</th>
                        <td><input type="text" class="form-control fd-style" name="" placeholder="نص"></td>
                    </tr>
                    <tr>
                        <th scope="row">رقم العضوية :</th>
                        <td><input type="text" class="form-control fd-style" name="" placeholder="نص"></td>
                    </tr>
                </table>

            </div>
            <div class="de-footer"> <img src="images/footer.png" alt="footer"> </div>
        </div>
    </div>
    <!-- Page-1-end -->

    <!-- Page-2 -->
    <div class="prt_contianer pg-2">
        <div class="logo"><img src="images/logo.png" alt="logo"></div>
        <div class="mn_content">
            <h5> ملحق العقد </h5>
        </div>
        <div class="de-footer"> <img src="images/footer.png" alt="footer"> </div>
    </div>
    <!-- Page-2-end -->

    <!-- Page-3 -->

    <div class="prt_contianer pg-3">
        <div class="logo"><img src="images/logo.png" alt="logo"></div>
        <div class="mn_content">
            <h1> شروط و أحكام عامة </h1>
            <ul>
                <li><span>1.</span>
                    <p> االالتزام بالكتابة والطباعة الملونة من خلال نظام التصديق الالكتروني .</p>
                </li>
                <li><span>2.</span>
                    <p>تتحمل المنشأة المسؤولية الكاملة في حال التأكد بأنه تم استخدم أحد برامج مايكروسوفت أوفيس أو غيرها من برامج الكتابة أو الطباعة على المحررات , وفي حال استخدامها فإنها تعد من جرائم التزوير. </p>
                </li>
                <li><span>3.</span>
                    <p> التأكيد بعدم البوح بإسم المستخدم وكلمة السر لأي شخص حيث أن هذه البيانات تعتبر سرية ومسئولية مستخدمين المنشأة .</p>
                </li>
                <li><span>4.</span>
                    <p> الموافقة على عدم تنفيذ التصديق الالكتروني على المحررات التي لا يصادق عليها ، وفي حالة تنفيذ التصديق الالكتروني على المحررات الموجودة ضمن القائمة السابق ذكرها تكون مسئولية المنشأة بالكامل و ليس لغرفة جدة أي علاقة . </p>
                </li>
                <li><span>5.</span>
                    <p>تيعتبر الورق و الملصق الأمني ضمن عهدة المنشأة ولا يحق لها إعادة بيع أو إعادة استخدامه في غير مخرجات نظام التصديق الالكتروني . </p>
                </li>
                <li><span>6.</span>
                    <p> تتحمل المنشأة المسئولية الكاملة تجاه تعاملها مع الورق واللاصق الأمني ولا يحق لها إرجاع الورق واللاصق الأمني إلا في حالة وجود عيب مصنعي واضح . </p>
                </li>
                <li><span>7.</span>
                    <p>يتتحمل المنشأة المسئولية الكاملة على جميع المحررات ومحتوياتها الصادرة من خلال نظام التصديق الالكتروني . </p>
                </li>
                <li><span>8.</span>
                    <p>فتتحمل المنشأة كامل المسئولية في حالة وجود أي التزام قانوني أو مالي أو أدبي نتيجة استخدام النظام , ولا تدخل غرفة جدة في أي مسائلات أو نزاعات قد تطرأ . </p>
                </li>
                <li><span>9.</span>
                    <p>يتتحمل المنشأة كامل المسئولية على المحتوى الذي تمت المصادقة عليه ولا تدخل غرفة جدة في أي مسائلات أو نزاعات قد تطرأ نتيجة التصديق على المحتوى . </p>
                </li>
                <li><span>10.</span>
                    <p>يتلتزم المنشأة بالامتناع عن التصديق على محتوى قد عمم عليه بشكل سابق من قبل غرفة جدة . </p>
                </li>
                <li><span>11.</span>
                    <p> تلتزم المنشأة بالتصديق على المحررات الخاصة بها فقط , ولا يحق لها استخدام النظام للتصديق لمؤسسة أو شركة أخرى . </p>
                </li>
            </ul>
            <div class="clearfix"></div>
            <h2 class="secnd"> شالمحررات التي لا تصادق عليها بموجب قرارات وزارية صادرة في هذا الشأن: </h2>
            <ul style="margin-bottom: 50px;">
                <li><span>1.</span>
                    <p> الوثائق الصادرة من منشأة وتكون : </p>
                    <ul class="inn-list">
                        <li>غير مشتركة .</li>
                        <li>غغير مجددة .</li>
                        <li>غالتوقيع غير معتمد .</li>
                        <li>غغير مطابق .</li>
                    </ul>
                </li>
                <li><span>2.</span>
                    <p>تمصادقة منتسب على منشآت غير منتسبة أو على تواقيع أفراد . </p>
                </li>
                <li><span>3.</span>
                    <p> المحررات التي لا يتوافق مضمونها مع النشاط المسجل بالسجل التجاري . </p>
                </li>
                <li><span>4.</span>
                    <p> الشهادات والمحررات والمستندات الرسمية الصادرة من جهات حكومية وصورها . </p>
                </li>
                <li><span>5.</span>
                    <p>تترجمة الوثائق الرسمية ( ما عدا الصادرة من وزارة التجارة ) . </p>
                </li>
                <li><span>6.</span>
                    <p> تالوثائق المتضمنة مبايعات مالية إلا بحضور البائع شخصياً . </p>
                </li>
                <li><span>7.</span>
                    <p>يالتفويض الشامل المطلق الغير محدد المدة والصلاحيات . </p>
                </li>
            </ul>
            <div class="clearfix"></div>
            <div class="de-footer"> <img src="images/footer.png" alt="footer"> </div>
        </div>
    </div>


    <!-- Page-3-end -->


    <!-- Page-4 -->

    <div class="prt_contianer pg-3">
        <div class="logo"><img src="images/logo.png" alt="logo"></div>
        <div class="mn_content">
            <ul style="margin-bottom: 75px;">
                <li><span>8.</span>
                    <p>فجميع الأوراق التجارية ( الشيك ، السند لأمر ، الكمبيالة ) . </p>
                </li>
                <li><span>9.</span>
                    <p>يالخطابات الداخلية والمكتوبة بلغة غير اللغة العربية . </p>
                </li>
                <li><span>10.</span>
                    <p>يالشهادات الدراسية وشهادات التدريب . </p>
                </li>
                <li><span>11.</span>
                    <p> تعقود الزواج والطلاق والتفويض لها . </p>
                </li>
                <li><span>12.</span>
                    <p> الوثائق المخالفة للشريعة الإسلامية أو الآداب العامة أو النظام . </p>
                </li>
                <li><span>13.</span>
                    <p> الوثائق الفارغة المحتوى . </p>
                </li>
                <li><span>14.</span>
                    <p> طلبات الاستقدام المحررة من مكاتب الخدمات العامة . </p>
                </li>
                <li><span>15.</span>
                    <p> عقود وخطابات الإعارة للعمالة لمزاولة العمل عند غير الكفيل . </p>
                </li>
                <li><span>16.</span>
                    <p> عقود الشراكة التجارية بين سعودي والعمالة الأجنبية . </p>
                </li>
                <li><span>17.</span>
                    <p> تفويض غير السعودي بالاستيراد . </p>
                </li>
                <li><span>18.</span>
                    <p> تفويض غير السعودي بتسويق وحمل الذهب والمجوهرات . </p>
                </li>
                <li><span>19.</span>
                    <p> طلبات الزيارة والاستقدام وتفاويض الجوازات الموقعة من قبل غير السعوديين ما لم يكن مديراً عاماً بشركة أجنبية أو خليجية (استثمار أجنبي) ومدون اسمه بالسجل التجاري. </p>
                </li>
                <li><span>20.</span>
                    <p> تفويض غير السعودي بالتعقيب ومراجعة الدوائر الحكومية . </p>
                </li>
                <li><span>21.</span>
                    <p> تفويض غير السعودي في الأمور المالية . </p>
                </li>
                <li><span>22.</span>
                    <p> تفويض غير السعودي بأعمال التخليص الجمركي . </p>
                </li>
                <li><span>23.</span>
                    <p> تفويض غير السعودي لاستقدام عمالة من الخارج إلا لكفيله . </p>
                </li>
                <li><span>24.</span>
                    <p> تفويض السائق غير السعودي بقيادة سيارة أو شاحنة ما لم يكن هذا السائق تحت كفالة مالكها السعودي . </p>
                </li>
                <li><span>25.</span>
                    <p> التفويض بالبيع على ظهر السيارة (المتنقلة) وبالأخص إذا كان البائع أجنبياً . </p>
                </li>
                <li><span>26.</span>
                    <p> المبايعات للمنشآت الصحية ( مستشفى ، مستوصف ، صيدلية ، مختبر ، معمل ) إلا بعد تقديم ما يثبت عدم ممانعة الشؤون الصحية . </p>
                </li>
                <li><span>27.</span>
                    <p> عقود بيع العقارات داخل وخارج المملكة . </p>
                </li>
                <li><span>28.</span>
                    <p> عقود بيع المعدات الزراعية للأجانب من أجل التصدير للخارج إلا بعد تقديم ما يثبت عدم ممانعة البنك الزراعي . </p>
                </li>
                <li><span>29.</span>
                    <p> عقود التنازل عن المقاولات والتعهدات من الباطن ، ما لم يرفق بالتنازل موافقة خطية موثقة من الجهة التي يجري التنفيذ لصالحها . </p>
                </li>
                <li><span>30.</span>
                    <p> عدم التصديق على استمارات الخروج والعودة ونقل الكفالة ، وإنما يتم التصديق على خطاب المنشأة الطالبة . </p>
                </li>
            </ul>
            <div class="clearfix"></div>
            <div class="de-footer"> <img src="images/footer.png" alt="footer"> </div>
            <div class="grp_btns">
                <div class="sbmt">
                    <input type="submit" value="Submit">
                </div>
                <!-- <div class="dwld"><button class="btn"><i class="fa fa-download"></i> Download</button></div> -->
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <!-- Page-4-end -->


</div>
</body>
</html>
