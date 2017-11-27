<div class="top_btn align_r">
    <button type="button" class="underline_btn mr15" onclick="location.href = '/'"><strong>닫기</strong></button>
</div>
<form id="dataForm" method="post" onsubmit="" enctype="multipart/form-data" action="/index.php/dataFunction/modBoard">
    <input type="hidden" name="board_idx" value="<?= $board_info->BOARD_IDX ?>">
    <section class="main mypage_main myinfo_main">
        <div class="pt30 pb30 my_thumb">
            <div class="thumb_img_wrapper">
                <img src="<?= $info->PROFILE_IMG ?>" alt="프로필이미지">
            </div>
            <h4><?= $info->NAME ?></h4>
        </div>

        <div class="input_all_wrapper">
            <h5 class="pt15 pb15"><span class="red_star">*</span> 는 필수 입력항목입니다.</h5>
            <h5 class="pb15">사회,&ensp;정치,&ensp;경제,&ensp;문화,&ensp;스포츠&ensp;등의&ensp;이슈등을&ensp;이용하여&ensp;내기를&ensp;만들어보세요.</h5>
            <h4><strong><span class="red_star">*</span> 내기 입력</strong></h4>
            <textarea name="time_line" class="textarea1 mt10" placeholder="ex) 박병호가 양키스와의 경기에서 홈런을 칠까요?" required><?= nl2br($board_info->TIME_LINE) ?></textarea>

            <!-- 참조링크 인풋 -->
            <div class="label_time ">
                <h4><strong>참조 링크</strong></h4>
                <input class="input_w100" type="url" name="link_url" value="<?= $board_info->LINK_URL ?>" placeholder="ex) http://playbetcomm.com">
            </div>

            <div class="align_custom_div label_time">
                <h4><strong><span class="red_star">*</span> 유 형</strong></h4>
                <select id="item_cnt" name="item_cnt">
                    <option value="1" <?php if (count($board_item_lists) == 1) echo 'selected'; ?>> 단식형</option>
                    <option value="2" <?php if (count($board_item_lists) == 2) echo 'selected'; ?>> 복식형</option>
                    <option value="3" <?php if (count($board_item_lists) == 3) echo 'selected'; ?>> 멀티형</option>
                </select>
                <div id="item_area" class="inline_input_div">
                    <?php foreach ($board_item_lists as $row) { ?>
                        <input type="text" name="item_name[]" value="<?= $row['NAME'] ?>" placeholder="ex) 맞다" required>
                    <?php } ?>
                </div>
            </div>

            <div class="label_time">
                <h4><strong><span class="red_star">*</span> 내기 옵션</strong></h4>
                <input class="input_w100" type="text" name="title" value="<?= $board_info->TITLE ?>" placeholder="ex) 피자 1+1 쿠폰 증정" required>
            </div>


            <div class="align_custom_div label_time">
                <h4><strong><span class="red_star">*</span> 배팅 제한시간</strong></h4>
                <div class="inline_input_div">
                    <input type="text" class="date_time betting_time" name="effective_time" value="<?= $board_info->EFFECTIVE_TIME ?>" required>
                </div>
            </div>



            <input type="hidden" name="dong" value="<?= $board_info->ADDRESS_ORG_IDX ?>">

            <div class="label_time ">
                <h4><strong><span class="red_star">*</span>옵션 및 매장 소개</strong></h4>
                <textarea class="textarea1 mt10" name="contents" placeholder="ex) 쿠폰, 혜택, 매장 등 추가적인 내용을 자유롭게 작성할 수 있습니다" required><?= $board_info->CONTENTS ?></textarea>
            </div>

            <div class="align_custom_div label_time">
                <h4><strong><span class="red_star">*</span> 옵션 유효기간</strong></h4>
                <div class="inline_input_div">
                    <input type="text" class="date_time option_time" name="cupon_time" value="<?= $board_info->CUPON_TIME ?>" required>
                </div>
            </div>

            <div class="align_custom_div label_time" style="display: inline-block;width: 50%; box-sizing: border-box;">
                <h4><strong><span class="red_star">*</span> 카테고리</strong></h4>
                <div class="inline_input_div">
                    <select id="categorySelect" name="category_idx" required style="width:100%;">
                        <!-- <option value="offline">오프라인</option> -->
                        <?php foreach ($category_lists as $row) { ?>
                            <option value="<?= $row['CATEGORY_IDX'] ?>" <?php if ($board_info->CATEGORY_IDX == $row['CATEGORY_IDX']) echo 'selected'; ?>><?= $row['NAME'] ?></option>
                        <?php } ?>
                    </select>
                    <!-- 서브 카테고리 -->
                    <!--<select id="categorySelect2" name="category_idx2"></select>--> 
                </div>
            </div>

            <div class="align_custom_div label_time" style="display: inline-block;width: 48%; width: calc(50% - 3px); box-sizing: border-box;">
                <h6><strong><span class="red_star">*</span> 하위 카테고리(최대10자)</strong></h6>
                <div class="inline_input_div">
                    <input type="text" class="input_w100" id="hash_tag" name="hash_tag" maxlength="10" value="<?= $board_info->HASH_TAG ?>" placeholder="해쉬태그" required>
                </div>
            </div>


            <div class="label_time">
                <h4><strong><span class="red_star">*</span>사진첨부</strong><button type="button" class="blue_border_btn ml15 file_add_btn">추가</button></h4>
                <ul class="attachment_ul">
                    <?php foreach ($img_lists as $row) { ?>
                        <li>
                            <?= $row['ORG_NAME'] ?>
                            <input type="hidden" class="file_length" name="location[]" value="<?= $row['LOCATION'] ?>">
                            <input type="hidden" name="org_name[]" value="<?= $row['ORG_NAME'] ?>">
                            <button type="button" class="delFileBtn">삭제</button>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <?php if ($info->TYPE == 'Y') { ?>
                <div class="label_time pb15">
                    <h4><strong>동영상첨부</strong><span style="font-size:11px;"> (mp4, avi, wmv, mpg, mpeg 파일)</span></h4>
                    <ul class="video_area_wrapper">
                        <?php if ($board_info->VIDEO) { ?>
                            <li>
                                <br>
                                현재 첨부되어있는 동영상이 있습니다.<br><br>
                                <div class="video_area">
                                    <video class="main_video" width="100%" height="150px" autoplay="autoplay" loop>
                                        <source src="<?= $board_info->VIDEO ?>" type="video/mp4"/>
                                    </video>
                                    <div class="overlay_black"></div>
                                </div>
                            </li>

                            <input type="hidden" class="video_length" name="video_location" value="<?= $board_info->VIDEO ?>">
                            <button type="button" class="delVideoBtn">삭제</button>
                        <?php } else { ?>
                            <input type="file" name="video" accept="video/*"  onchange="chk_file_type_video(this);
                                        chk_file_size(this);">
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </section>
    <div class="bottom_btn align_r">
        <button type="submit" class="underline_btn mr15">
            <strong>완료</strong>
        </button>
    </div>
</form>
</div>

<div class="black_wrapper">
    <div class="click_close"></div>
    <div class="black"></div>
</div>

<link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.css"/>
<script src="/js/jquery.datetimepicker.full.js"></script>
<!--jquery validation 플러그인-->
<script src="/js/jquery.validate.min.js"></script>
<script src="/js/additional-methods.min.js"></script>
<script src="/js/messages_ko.min.js"></script>
<!--jquery validation 플러그인-->
<script type="text/javascript">
                            $(document).ready(function () {

                                $(".betting_modal .cancle_btn").click(function () {
                                    $(".close-modal").trigger("click");
                                });

                                var data = {idx: $("#categorySelect").select().val(), idx2: ''};
                                $.ajax({
                                    url: '/index.php/dataFunction/subCategoryLists',
                                    type: "POST",
                                    dataType: 'text',
                                    data: data,
                                    success: function (data, status, xhr) {
                                        $("#categorySelect2").html(data);
                                    }
                                });

                                if ($("#categorySelect").select().val() === '1') {
                                    $("#hash_tag").attr('disabled', true);
                                } else {
                                    $("#hash_tag").attr('disabled', false);
                                }

                                $(".file_add_btn").click(function () {
                                    // 쿼리에서 권한값을 가져와야함
<?php if ($info->TYPE == 'N') { ?>
                                        // 업로드 가능 파일 갯수 4개 이하
                                        if ($(".file_length").length <= 3) {
                                            var html = '<li><input type="file" class="file_length" name="file[]" required onchange="chk_file_type(this)"><button type="button" class="delFileBtn">삭제</button></li>';
                                            $(".attachment_ul").append(html);
                                            $(".delFileBtn").click(function () {
                                                $(this).parent().remove();
                                            });
                                        } else {
                                            if (confirm("프리미엄 서비스 등록 후 이용가능합니다. '확인'을 누르면 프리미엄 안내 페이지로 이동합니다.")) {
                                                location.href = "/index/premium";
                                            }
                                        }

<?php } else { ?>

                                        var html = '<li><input type="file" name="file[]" required onchange="chk_file_type_pm(this)"><button type="button" class="delFileBtn">삭제</button></li>';
                                        $(".attachment_ul").append(html);
                                        $(".delFileBtn").click(function () {
                                            $(this).parent().remove();
                                        });
<?php } ?>
                                });
                                $.datetimepicker.setLocale('ko');
                                $.validator.addMethod("dateTime", function (value, element) {
                                    var stamp = value.split(" ");
                                    var validDate = !/Invalid|NaN/.test(new Date(stamp[0]).toString());
                                    var validTime = /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/i.test(stamp[1]);
                                    return this.optional(element) || (validDate && validTime);
                                }, "Please enter a valid date and time.");
                                $('#dataForm').validate({
                                    submitHandler: function () {
                                        var f = confirm("내기 수정을 완료하겠습니까?");
                                        if (f) {
                                            if ($(".attachment_ul li").length == 0) {
                                                alert("사진을 한 장 이상 첨부해주세요.");
                                                return false;
                                            }
                                            if (moment($(".option_time").val()) < moment($(".betting_time").val())) {
                                                alert("옵션 유효시간이 배팅 제한시간보다 빠릅니다.");
                                                return false;
                                            }
                                            return true;
                                        } else {
                                            return false;
                                        }
                                    },
                                    rules: {
                                        time_line: {
                                            required: true
                                        },
                                        'item_name[]': {
                                            required: true
                                        },
                                        cupon_time: {
                                            required: true,
                                            dateTime: true

                                        },
                                        effective_time: {
                                            required: true,
                                            dateTime: true
                                        },
                                        'file[]': {
                                            required: true
                                        }
                                    },
                                    //규칙체크 실패시 출력될 메시지
                                    messages: {
                                        time_line: {
                                            required: "<span class='helpR'>내기 제목을 입력해주세요.</span>"
                                        },
                                        'item_name[]': {
                                            required: "<span class='helpR'>유형명을 입력하세요.</span>"
                                        },
                                        cupon_time: {
                                            required: "<span class='helpR'>옵션 유효기간을 선택해주세요.</span>",
                                            dateTime: "<span class='helpR'>형식이 올바르지 않습니다.</span>"
                                        }
                                        ,
                                        effective_time: {
                                            required: "<span class='helpR'>배팅 제한시간을 선택해주세요.</span>",
                                            dateTime: "<span class='helpR'>형식이 올바르지 않습니다.</span>"
                                        },
                                        'file[]': {
                                            required: "<span class='helpR'>파일을 선택하세요.</span>"
                                        }
                                    }
                                });
                                var dateToday = new Date();
                                var maxDate = new Date();
                                var alertDate = new Date();
                                // 원하는 일 수에서  -1을 하면 됌
                                maxDate.setDate(maxDate.getDate() + 4);
                                alertDate.setDate(alertDate.getDate() + 5);
                                $(".betting_time").datetimepicker({
                                format: 'Y-m-d H:i',
                                        minDate: dateToday
                                        // 쿼리에서 권한값을 가져와야함
<?php if ($info->TYPE == 'N') { ?>
                                    , onSelectDate:function (ct, $i) {
                                        if (moment(ct) > moment(alertDate)) {
                                            if (confirm("프리미엄 서비스 등록 후 이용가능합니다.")) {
                                                location.href = "/index/premium";
                                            }
                                            $(".betting_time").val("");
                                        }
                                        }
<?php } ?>
                                    // , maxDate: maxDate
                                });
                                        $(".option_time").datetimepicker({
                                    format: 'Y-m-d H:i',
                                    minDate: dateToday
                                });
                                $(".option_time").change(function () {
                                    console.log(moment($(".option_time").val()));
                                });
                                $(".betting_time").change(function () {

                                });
                                $(".link_url_input").focus(function () {
                                    if (!$.trim($(this).val())) {
                                        $(this).val("http://");
                                    }
                                });
                                $(".link_url_input").blur(function () {
                                    if ($.trim($(this).val()) == 'http://' || $.trim($(this).val()) == 'https://') {
                                        $(this).val("");
                                    }
                                });
                                if ($("#si").select().val()) {
                                    var data = {si: $("#si").select().val(), infoVal: '<?= $board_info->SIGUNGU ?>'};
                                    $.ajax({
                                        dataType: 'text',
                                        url: '/index.php/dataFunction/guLists',
                                        data: data,
                                        type: 'POST',
                                        success: function (data, status, xhr) {
                                            $("#gu").html(data);
                                            $("#gugun").html('<option value="">선택</option>');
                                        }
                                    });
                                    var data = {gu: '<?= $board_info->SIGUNGU ?>', infoVal: '<?= $board_info->ADDRESS_ORG_IDX ?>'};
                                    $.ajax({
                                        dataType: 'text',
                                        url: '/index.php/dataFunction/gugunLists',
                                        data: data,
                                        type: 'POST',
                                        success: function (data, status, xhr) {
                                            $("#gugun").html(data);
                                        }
                                    });
                                }

                                $("#si").change(function () {
                                    if ($(this).select().val() == '') {
                                        $("#gu").html('<option value="">선택</option>');
                                        $("#gugun").html('<option value="">선택</option>');
                                    }
                                    var data = {si: $(this).select().val()};
                                    $.ajax({
                                        dataType: 'text',
                                        url: '/index.php/dataFunction/guLists',
                                        data: data,
                                        type: 'POST',
                                        success: function (data, status, xhr) {
                                            $("#gu").html(data);
                                            $("#gugun").html('<option value="">선택</option>');
                                        }
                                    });
                                });
                                $("#gu").change(function () {
                                    if ($(this).select().val() == '') {
                                        $("#gugun").html('<option value="">선택</option>');
                                    }

                                    var data = {gu: $(this).select().val()};
                                    $.ajax({
                                        dataType: 'text',
                                        url: '/index.php/dataFunction/gugunLists',
                                        data: data,
                                        type: 'POST',
                                        success: function (data, status, xhr) {
                                            $("#gugun").html(data);
                                        }
                                    });
                                });
                                $("#myInfoChk").click(function () {
                                    if ($(this).is(":checked")) {
                                        $("#business_name").val('<?= $info->BUSINESS_NAME ?>');
                                        $("#phone").val('<?= $info->PHONE ?>');
                                        $("#business_number").val('<?= $info->BUSINESS_NUMBER ?>');
                                    } else {
                                        $("#business_name").val('');
                                        $("#phone").val('');
                                        $("#business_number").val('');
                                    }
                                });
                                $(".delFileBtn").click(function () {
                                    $(this).parent().remove();
                                });
                                $(".delVideoBtn").click(function () {
                                    $(this).parent().empty();
                                    var html = '<input type="file" name="video" accept="video/*" onchange="chk_file_type_video(this); chk_file_size(this);">';
                                    $(".video_area_wrapper").append(html);
                                });

                                $("#item_cnt").change(function () {
                                    var value = $(this).select().val();
                                    var html = '<input type="text" name="item_name[]" placeholder="ex) 맞다" required>';
                                    $("#item_area").html('');
                                    for (var i = 0; i < value; i++) {
                                        $("#item_area").append(html);
                                    }
                                });
                                // 카테고리 셀렉트 부분
                                $("#categorySelect").change(function () {
                                    var data = {idx: $(this).select().val()};
                                    $.ajax({
                                        url: '/index.php/dataFunction/subCategoryLists',
                                        type: "POST",
                                        dataType: 'text',
                                        data: data,
                                        success: function (data, status, xhr) {
                                            $("#categorySelect2").html(data);
                                        }
                                    });

                                    if ($("#categorySelect").select().val() === '1') {
                                        $("#hash_tag").attr('disabled', true);
                                    } else {
                                        $("#hash_tag").attr('disabled', false);
                                    }
                                });


                                $(".mypage_nav li").click(function () {
                                    var mypage_nav_index = $(this).index(".mypage_nav li");
                                    $(".mypage_nav_contents").css("display", "none");
                                    $(".mypage_nav_contents").eq(mypage_nav_index).css("display", "block");
                                    $(".mypage_nav li").removeClass("select_mypage_nav");
                                    $(this).addClass("select_mypage_nav");
                                });
                                $(".result_modal_nav ul li").click(function () {
                                    $(".result_modal_nav ul li").removeClass("result_modal_nav_select");
                                    $(this).addClass("result_modal_nav_select");
                                });
                                $("#cAll").click(function () {
                                    if ($("#cAll").prop("checked")) {
                                        $(".check_box").prop("checked", true);
                                    } else {
                                        $(".check_box").prop("checked", false);
                                    }
                                });
                            });
                            function addFile() {
                                var html = '<li><input type="file" name="file[]" required><button type="button" class="delFileBtn">삭제</button></li>';
                                $(".attachment_ul").append(html);
                                $(".delFileBtn").click(function () {
                                    $(this).parent().remove();
                                });
                            }

                            function chk_file_type(obj) {
                                // var index = obj.index("input[type='file']");
                                // console.log(index);
                                var file_kind = obj.value.lastIndexOf('.');
                                var file_name = obj.value.substring(file_kind + 1, obj.length);
                                var file_type = file_name.toLowerCase();
                                var check_file_type = ['jpg', 'gif', 'png', 'jpeg', 'bmp'];
                                if (check_file_type.indexOf(file_type) == -1) {
                                    if (confirm("프리미엄 서비스 등록 후 이용가능합니다. '확인'을 누르면 프리미엄 안내 페이지로 이동합니다.")) {
                                        location.href = "/index/premium";
                                    }
                                    var parent_Obj = obj.parentNode
                                    var node = parent_Obj.replaceChild(obj.cloneNode(true), obj);
                                    $("input[type='file']").val("");
                                    return false;
                                }
                            }

                            function chk_file_type_pm(obj) {
                                var file_kind = obj.value.lastIndexOf('.');
                                var file_name = obj.value.substring(file_kind + 1, obj.length);
                                var file_type = file_name.toLowerCase();
                                var check_file_type = ['jpg', 'gif', 'png', 'jpeg', 'bmp'];
                                if (check_file_type.indexOf(file_type) == -1) {
                                    alert('이미지 파일만 선택할 수 있습니다.');
                                    var parent_Obj = obj.parentNode
                                    var node = parent_Obj.replaceChild(obj.cloneNode(true), obj);
                                    $("input[type='file']").val("");
                                    return false;
                                }
                            }

                            function chk_file_type_video(obj) {
                                var file_kind = obj.value.lastIndexOf('.');
                                var file_name = obj.value.substring(file_kind + 1, obj.length);
                                var file_type = file_name.toLowerCase();
                                var check_file_type = ['mp4', 'avi', 'wmv', 'mpg', 'mpeg'];
                                if (check_file_type.indexOf(file_type) == -1) {
                                    alert('동영상 파일만 선택할 수 있습니다. (mp4, avi, wmv, mpg, mpeg)');
                                    var parent_Obj = obj.parentNode
                                    var node = parent_Obj.replaceChild(obj.cloneNode(true), obj);
                                    $("input[type='file']").val("");
                                    return false;
                                }
                            }

                            function chk_file_size(obj) {
                                var fileSize = obj.files[0].size;
                                var maxSize = 102400 * 102400;
                                if (fileSize > maxSize) {
                                    alert("파일용량은 100MB까지 가능합니다.");
                                    $("input[type='file']").val("");
                                    return false;
                                }
                            }
</script>