<div class="top_btn align_r">
    <button type="button" class="underline_btn mr15" onclick="location.href = '/'"><strong>닫기</strong></button>
</div>
<form id="dataForm" method="post" onsubmit="" enctype="multipart/form-data" action="/index.php/dataFunction/insBoard">
    <input type="hidden" name="member_idx" value="<?= $info->MEMBER_IDX ?>">
    <section class="main mypage_main myinfo_main">
        <div class="pt30 pb30 my_thumb">
            <div class="thumb_img_wrapper">
                <?php if ($info->PROFILE_IMG) { ?>
                <img src="<?= $info->PROFILE_IMG ?>" alt="프로필이미지">
                <?php } else { ?>
                <img src="/images/thumb/default.png" alt="프로필이미지">
                <?php } ?>
            </div>
            <h4><?= $info->NAME ?></h4>
        </div>

        <div class="input_all_wrapper">
            <h5 class="pt15 pb15"><span class="red_star">*</span> 는 필수 입력항목입니다.</h5>
            <h5 class="pb15" style="text-align: center;">"사회,&ensp;정치,&ensp;경제,&ensp;문화,&ensp;스포츠&ensp;등의&ensp;이슈등을&ensp;이용하여&ensp;내기를&ensp;만들어보세요."</h5>
            <h4><strong><span class="red_star">*</span> 내기 입력</strong></h4>
            <textarea name="time_line" class="textarea1 mt10" placeholder="ex) 박병호가 양키스와의 경기에서 홈런을 칠까요?"></textarea>

            <!-- 참조링크 인풋 -->
            <div class="label_time ">
                <h4><strong>참조 링크</strong></h4>
                <input class="input_w100 link_url_input" type="url" name="link_url" value="" placeholder="ex) http://playbetcomm.com">
            </div>

            <div class="align_custom_div label_time">
                <h4><strong><span class="red_star">*</span> 유 형</strong></h4>
                <select id="item_cnt" name="item_cnt">
                    <option value="1"> 단식형</option>
                    <option value="2"> 복식형</option>
                    <option value="3"> 멀티형</option>
                </select>
                <div id="item_area" class="inline_input_div">
                    <input type="text" name="item_name[]" placeholder="ex) 맞다" required>
                </div>
            </div>

            <div class="label_time">
                <h4><strong><span class="red_star">*</span> 내기 옵션</strong></h4>
                <input class="input_w100" type="text" name="title" placeholder="ex) 피자 1+1 쿠폰 증정" required>
            </div>

            <div class="align_custom_div label_time">
                <h4><strong><span class="red_star">*</span> 베팅 제한시간</strong></h4>
                <div class="inline_input_div">
                    <input type="text" class="date_time betting_time" name="effective_time" required>
                </div>
            </div>
            <!--            <div class="align_custom_div label_time ">
                            <h4><strong><span class="red_star">*</span> 지 역</strong></h4>
                            <div class="inline_input_div">
                                <select id="si">
                                    <option value="">시, 도</option>
            <?php foreach ($si_lists as $row) { ?>
                                                                                                                                                                                                                                                        <option value="<?= $row['SIDO'] ?>" <?php if ($address_org_info->SIDO == $row['SIDO']) echo 'selected'; ?>><?= $row['SIDO'] ?></option>
            <?php } ?>
                                </select>
                                <select id="gu">
                                    <option value="">시, 군, 구</option>
                                </select>
                                <select id="gugun" name="dong" required>
                                    <option value="">동, 면, 읍</option>
                                </select>
                            </div>
                        </div>-->
                        <input type="hidden" name="dong" value="<?= $address_org_info->ADDRESS_ORG_IDX ?>">

                        <div class="label_time ">
                            <h4><strong><span class="red_star">*</span>옵션 및 매장 소개</strong></h4>
                            <textarea class="textarea1 mt10" name="contents" placeholder="ex) 쿠폰, 혜택, 매장 등 추가적인 내용을 자유롭게 작성할 수 있습니다" required></textarea>
                        </div>

                        <div class="align_custom_div label_time">
                            <h4><strong><span class="red_star">*</span> 옵션 유효기간</strong></h4>
                            <div class="inline_input_div">
                                <input type="text" class="date_time option_time" name="cupon_time" required>
                            </div>
                        </div>


                        <div class="align_custom_div label_time" style="display: inline-block;width: 50%; box-sizing: border-box; vertical-align: top;">
                            <h4><strong><span class="red_star">*</span> 카테고리</strong></h4>
                            <div class="inline_input_div">
                                <select id="categorySelect" name="category_idx" required style="width:100%;">
                                    <?php foreach ($category_lists as $row) { ?>
                                    <option value="<?= $row['CATEGORY_IDX'] ?>"><?= $row['NAME'] ?></option>
                                    <?php } ?>
                                </select>
                                <!-- 서브 카테고리 -->
                                <!--<select id="categorySelect2" name="category_idx2"></select>--> 
                            </div>
                        </div>

                        <div class="align_custom_div label_time" style="display: inline-block;width: 48%; width: calc(50% - 3px); box-sizing: border-box; vertical-align: top;">
                            <h6 style="padding: 4px 0 3px;"><strong><span class="red_star">*</span> 하위 카테고리(최대10자)</strong></h6>
                            <div class="inline_input_div">
                                <input type="text" class="input_w100" id="hash_tag" name="hash_tag" maxlength="10" placeholder="하위 카테고리" required>
                            </div>
                        </div>

                        <div class="label_time">
                            <h4><strong><span class="red_star">*</span>사진첨부</strong><button type="button" class="blue_border_btn ml15 file_add_btn">추가</button></h4>
                            <ul class="attachment_ul">
                                <li><input type="file" name="file[]" required onchange="chk_file_type_pm(this);"></li>
                            </ul>
                        </div>
                        <?php if( $info->TYPE =='Y') {?>
                        <div class="label_time pb15">
                            <h4><strong>동영상첨부</strong><span style="font-size:11px;"> (mp4, avi, wmv, mpg, mpeg 파일)</span></h4>
                            <ul>
                                <li><input type="file" name="video" accept="video/*" onchange="chk_file_type_video(this); chk_file_size(this);"></li>
                            </ul>
                        </div>
                        <?php } ?>
            <!--            <div class="align_custom_div label_time  pt15">
                            <h4 class="dp_ib"><strong>상 호 명</strong></h4>
                            <div class="dp_ib inline_checkbox">
                                <input type="checkbox" id="myInfoChk" name="myInfo">
                                <span>내 정보 가져오기</span>
                            </div>
                            <div class="inline_input_div">
                                <input class="w50" type="text" id="business_name" name="business_name" maxlength="20" placeholder="ex) 주식회사 배다른남매">
            
                            </div>
                        </div>
            
                        <div class="align_custom_div label_time ">
                            <h4><strong><span class="red_star">*</span> 연 락 처</strong></h4>
                            <div class="inline_input_div">
                                <input class="w50" type="text" id="phone" name="phone" pattern="[0-9]*" maxlength="11" placeholder="ex) 01012345678" required>
                            </div>
                        </div>
            
                        <div class="align_custom_div label_time ">
                            <h4><strong>사업자 번호</strong></h4>
                            <div class="inline_input_div mb30">
                                <input class="w50" type="text" id="business_number" name="business_number" maxlength="10" pattern="[0-9]*" placeholder="ex) 768 68 68686">
                            </div>
                        </div>-->
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
            $(function () {

                $(".betting_modal .cancle_btn").click(function () {
                    $(".close-modal").trigger("click");
                });

                var data = {idx: $("#categorySelect").select().val()};
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

                var file_count = 1;
                $(".file_add_btn").click(function () {
// 쿼리에서 권한값을 가져와야함
<?php if ($info->TYPE == 'N') { ?>
    file_count++;
                    // 업로드 가능 파일 갯수 4개 이하
                    if (file_count <= 4) {
                        var html = '<li><input type="file" name="file[]" required onchange="chk_file_type(this)"><button type="button" class="delFileBtn">삭제</button></li>';
                        $(".attachment_ul").append(html);
                        $(".delFileBtn").click(function () {
                            $(this).parent().remove();
                            file_count--;
                        });
                    } else {
                        if(confirm("프리미엄 서비스 등록 후 이용가능합니다. '확인'을 누르면 프리미엄 안내 페이지로 이동합니다.")){
                                location.href="/index/premium";
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
//validation이 끝난 이후의 submit 직전 추가 작업할 부분
submitHandler: function () {
    <?php if ($info->TYPE == 'N' || $info->TYPE == 'I') { ?>
        var f2 = confirm("무료회원은 내기 결과 입력 후 25일이 지나서 다음 내기등록이 가능합니다.");
        if (f2) {

            if (moment($(".option_time").val()) < moment($(".betting_time").val())) {
                alert("옵션 유효시간이 배팅 제한시간보다 빠릅니다.");
                return false;
            }

            return true;
        } else {
            return false;
        }
        <?php } else { ?>
            var f = confirm("내기를 등록하겠습니까?");
            if (f) {

                if (moment($(".option_time").val()) < moment($(".betting_time").val())) {
                    alert("옵션 유효시간이 배팅 제한시간보다 빠릅니다.");
                    return false;
                }

                return true;
            } else {
                return false;
            }
            <?php } ?>
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
                        , onSelectDate:function(ct,$i){
                          if(moment(ct)>moment(alertDate)){
                            if(confirm("프리미엄 서비스 등록 후 이용가능합니다.")){
                                location.href="/index/premium";
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
            if ($("#si").select().val()) {
                var data = {si: $("#si").select().val(), infoVal: '<?= $address_org_info->SIGUNGU ?>'};
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
                var data = {gu: '<?= $address_org_info->SIGUNGU ?>', infoVal: '<?= $address_org_info->ADDRESS_ORG_IDX ?>'};
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
            $("#item_cnt").change(function () {
                var value = $(this).select().val();
                var html = '<input type="text" name="item_name[]" placeholder="ex) 맞다" required>';
                $("#item_area").html('');
                for (var i = 0; i < value; i++) {
                    $("#item_area").append(html);
                }
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
    $(".mypage_nav_contents").eq(mypage_nav_index).css("display", "block")

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
});
function addFile() {
    var html = '<li><input type="file" name="file[]" required><button type="button" class="delFileBtn">삭제</button></li>';
    $(".attachment_ul").append(html);
    $(".delFileBtn").click(function () {
        $(this).parent().remove();
    });
}


function chk_file_type(obj) {
    var file_kind = obj.value.lastIndexOf('.');
    var file_name = obj.value.substring(file_kind + 1, obj.length);
    var file_type = file_name.toLowerCase();
    var check_file_type = ['jpg', 'gif', 'png', 'jpeg', 'bmp'];
    if (check_file_type.indexOf(file_type) == -1) {
        if(confirm("프리미엄 서비스 등록 후 이용가능합니다. '확인'을 누르면 프리미엄 안내 페이지로 이동합니다.")){
            location.href="/index/premium";
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
    if(fileSize > maxSize) {
        alert("파일용량은 100MB까지 가능합니다.");
        $("input[type='file']").val("");
        return false;
    }
}
</script>