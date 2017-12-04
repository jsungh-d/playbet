
<div class="mypage_nav">
    <ul>
        <li class="<?php if (!$this->uri->segment(3)) echo 'select_mypage_nav'; ?>">
            <h5>
                <img src="/images/common/clock1.png" alt="" style="width:12px"> 
                활동기록
                <?php if ($history_new->CNT > 0) { ?>
                    <img src="/images/new.png" class="new_text" style="margin-left: 3px; width:15px;">
                <?php } ?>
            </h5>
        </li>
        <li class="<?php if ($this->uri->segment(3) == 'cupon') echo 'select_mypage_nav'; ?>">
            <h5>
                <img src="/images/common/record.png" alt="" style="width:12px"> 
                받은 옵션함
                <?php if ($cupon_new->CNT > 0) { ?>
                    <img src="/images/new.png" class="new_text" style="margin-left: 3px; width:15px;">
                <?php } ?>
            </h5>
        </li>
        <li class="<?php if ($this->uri->segment(3) == 'write') echo 'select_mypage_nav'; ?>">
            <h5>
                <img src="/images/common/pen.png" alt="" style="width:12px"> 
                내기 등록리스트
                <?php
                $new_cnt = '';
                foreach ($write_new_lists as $row) {
                    $new_cnt .= $row['CNT'] . ',';
                }
                $new_array = explode(',', substr($new_cnt, 0, -1));

                if (in_array('0', $new_array) && $write_new_lists) {
                    ?>
                    <img src="/images/new.png" class="new_text" style="margin-left: 3px; width:15px;">
                <?php } ?>
            </h5>
        </li>
        <li class="<?php if ($this->uri->segment(3) == 'info') echo 'select_mypage_nav'; ?>"><h5><img src="/images/common/option.png" alt="" style="width:12px"> 내 정보</h5></li>
    </ul>
</div>
<section class="main mypage_main">
    <div class="pt30 pb30 my_thumb my_thumb_view">
        <div class="thumb_img_wrapper">
            <?php if ($info->PROFILE_IMG) { ?>
                <img class="user_image" src="<?= $info->PROFILE_IMG ?>" alt="프로필이미지">
            <?php } else { ?>
                <img class="user_image" src="/images/thumb/default.png" alt="프로필이미지">
            <?php } ?>
        </div>
        <h4><?= $info->NAME ?></h4>
    </div>

    <!-- 활동기록 부분 -->
    <div class="mypage_nav_contents <?php if (!$this->uri->segment(3)) echo 'active_log'; ?>">

        <?php if (!$historyLists) { ?>
            <div class="contents pl15 pr15 pt15 pb15">
                <div class="table_list">
                    <div class="table_list_cell">
                        <h4>활동내역이 없습니다.</h4>
                    </div>
                    <div class="table_list_cell reg"><h5></h5></div>
                </div>
            </div>
        <?php } else if ($historyLists) { ?>

            <?php foreach ($historyLists as $row) {
                ?>

                <?php if ($row['TYPE'] == 'BC' || $row['TYPE'] == 'BD' || $row['TYPE'] == 'BF') { ?>
                    <!--배팅완료, 취소, 실패-->
                    <div class="contents pl15 pr15 pt15 pb15">
                        <div class="table_list">
                            <div class="table_list_cell">
                                <h5><?= $row['BUSINESS_NAME'] ?> > <?= $row['LOCATION'] ?> > <?= $row['TITLE'] ?></h5>
                                <h4><?= $row['CONTENTS'] ?></h4>
                            </div>
                            <div class="table_list_cell reg"><h5><?= $row['H_DATE'] ?></h5></div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($row['TYPE'] == 'CR' || $row['TYPE'] == 'CU' || $row['TYPE'] == 'CD' || $row['TYPE'] == 'CI') { ?>
                    <!--쿠폰수령, 사용, 삭제, 댓글작성-->
                    <div class="contents pl15 pr15 pt15 pb15">
                        <div class="table_list">
                            <div class="table_list_cell">
                                <h5><?= $row['BUSINESS_NAME'] ?> > <?= $row['LOCATION'] ?></h5>
                                <h4><?= $row['CONTENTS'] ?></h4>
                            </div>
                            <div class="table_list_cell reg"><h5><?= $row['H_DATE'] ?></h5></div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($row['TYPE'] == 'WB' || $row['TYPE'] == 'MB' || $row['TYPE'] == 'DB' || $row['TYPE'] == 'EB' || $row['TYPE'] == 'SC') { ?>
                    <!--글쓰기완료, 수정, 삭제, 종료, 쿠폰발송-->
                    <div class="contents pl15 pr15 pt15 pb15">
                        <div class="table_list">
                            <div class="table_list_cell">
                                <h4><?= $row['CONTENTS'] ?></h4>
                            </div>
                            <div class="table_list_cell reg"><h5><?= $row['H_DATE'] ?></h5></div>
                        </div>
                    </div>
                <?php } ?>
                <?php
            }
        }
        ?>
    </div>

    <div class="mypage_nav_contents coupon <?php if ($this->uri->segment(3) == 'cupon') echo 'active_log'; ?>">
        <?php foreach ($cupon_lists as $row) { ?>
            <div class="contents pl15 pr15 pt15 pb15 bg_lightgray">
                <div class="table_list">
                    <div class="table_list_cell table_list_title">
                        <div>
                            <h4 class="dp_ib"><strong><?= $row['NAME'] ?>님의 베팅에 성공하셨습니다.</strong></h4>
                            <h5 class="dp_ib title_reg"><?= $row['CUPON_TIME'] ?> 까지 사용가능</h5>
                        </div>
                        <h4 class="h4">‘<?= $row['NAME'] ?> <?= $row['TITLE'] ?>’ 베팅에 성공하셨습니다.</h4>
                    </div>
                    <div class="table_list_cell reg">
                        <?php
                        $date1 = new DateTime($row['NOW']);
                        $date2 = new DateTime($row['CUPON_TIME']);
                        $diff = date_diff($date1, $date2);
                        ?>

                        <?php if ($row['CUPON_USE_YN'] == 'Y') { ?>
                            <a onclick="alert('사용한 옵션입니다.');" class="betting_modal">
                                <button type="button" class="link_btn_xxs"><span>사용완료</span></button>
                            </a>
                        <?php } ?>

                        <?php if ($diff->invert == 0 && $row['CUPON_USE_YN'] == 'N') { ?>
                            <a onclick="openCoupon('<?= $row['ITEM_INFO_IDX'] ?>', '<?= $row['NAME'] ?>', '<?= $row['TITLE'] ?>', '<?= $row['CUPON_TIME'] ?>', '<?= $row['CUPON_MSG'] ?>');" class="betting_modal">
                                <button type="button" class="link_btn_xxs"><span>사용하기</span></button>
                            </a>
                        <?php } else if ($diff->invert > 0 && $row['CUPON_USE_YN'] == 'N') { ?>
                            <a onclick="alert('옵션 사용기간이 지났습니다.');" class="betting_modal">
                                <button type="button" class="link_btn_xxs"><span>기간만료</span></button>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>


    <!-- 내가 쓴 글 부분 -->
    <div class="mypage_nav_contents wrote <?php if ($this->uri->segment(3) == 'write') echo 'active_log'; ?>">
        <?php
        foreach ($myboard_lists as $row) {
            $date1 = new DateTime($row['NOW']);
            $date2 = new DateTime($row['EFFECTIVE_TIME']);
            $diff = date_diff($date1, $date2);

            $date2_1 = new DateTime($row['CUPON_TIME']);
            $diff2 = date_diff($date1, $date2_1);
            ?>
            <div class="contents pl15 pr15 pt15 pb15 <?php if ($diff->invert > 0) echo 'bg_lightgray'; ?>">
                <div class="table_list">
                    <div class="table_list_cell table_list_title">
                        <div>
                            <h4 class="dp_ib">
                                <strong>
                                    <?php
                                    if ($diff->invert == 0) {
                                        echo $diff->d . '일 ' . $diff->h . ':' . $diff->i . ' 남음';
                                    } else {
                                        echo '시간종료';
                                    }
                                    ?>
                                </strong>
                                <?php if ($row['TODAY'] == 'Y') { ?>
                                    <span class="text_red">New</span>
                                <?php } ?>
                            </h4>
                            <h5 class="dp_ib title_reg"><?= $row['INS_DATE'] ?></h5>
                        </div>
                        <h4 class="h4">
                            <?= $row['TIME_LINE'] ?>
                        </h4>
                    </div>
                    <div class="table_list_cell reg">
                        <?php if ($diff->invert == 0 && $row['ITEM_INFO_CNT'] == 0) { ?>
                            <button type="button" class="link_btn_xxs" onclick="location.href = '/index/modify/<?= $row['BOARD_IDX'] ?>'"><span>수정하기</span></button>
                        <?php } else if ($diff->invert == 0 && $row['ITEM_INFO_CNT'] > 0) { ?>
                            <button type="button" class="link_btn_xxs" onclick="alert('베팅 참여자가 있습니다.')"><span>수정하기</span></button>
                        <?php } else if ($diff->invert > 0 && in_array('0', $new_array) && $row['ITEM_INFO_CNT'] > 0 && $row['COMP_YN'] == 'N') { ?>
                            <a class="betting_modal">
                                <button type="button" class="link_btn_xxs" onclick="openResult('<?= $row['BOARD_IDX'] ?>', '<?= $row['ITEM_IDX'] ?>'); console.log()"><span>결과값 입력</span></button>
                            </a>
                        <?php } else if ($diff->invert > 0 && $row['ITEM_INFO_CNT'] > 0 && $row['COMP_YN'] == 'Y') { ?>
                            <!-- border:1px solid #c1c1c1; color:#c1c1c1; -->
                            <button type="button" class="link_btn_xxs" onclick="openWonList('<?= $row['BOARD_IDX'] ?>');" style="" value="<?= $row['BOARD_IDX'] ?>"><span>결과확인</span></button>

                            <!-- 당첨자 확인 모달 -->
                            <div id="wonList<?= $row['BOARD_IDX'] ?>" class="align_c playbet_modal" style="display:none;">
                                <img src="/images/common/coupon.png" alt="" style="margin-top: 12px; width: 30px;">
                                <h2 class="modal_title_h2">
                                    <strong>당첨자 확인</strong>
                                </h2>

                                <h2 class="pb15" style="word-break: keep-all;">
                                    <span><?= $row['TIME_LINE'] ?></span><br>
                                    <button id="wonListBtn<?= $row['BOARD_IDX'] ?>" class="yellow_btn color_btn " type="button" style="padding: 6px 15px;">버튼</button>
                                    에 베팅한 인원은 총 <span id="wonListCount<?= $row['BOARD_IDX'] ?>">0명</span>입니다.<br><br>
                                    확인하시겠습니까?
                                </h2>
                                <div class="btn_area pb15">
                                    <button type="button" class="color_btn cancle_btn default_btn">취소</button>
                                    <button type="button" class="color_btn darkblue_btn" onclick="location.href = '/index.php/dataFunction/memExcelDown?board_idx=' + <?= $row['BOARD_IDX'] ?> + ''">엑셀로 확인하기</button>
                                </div>
                            </div>

                        <?php } else if ($diff->invert > 0 && $row['ITEM_INFO_CNT'] == 0) { ?>
                            <!--참여자 없음-->
                            <button type="button" class="link_btn_xxs" onclick="compBtn('<?= $row['BOARD_IDX'] ?>')"><span>결과확인</span></button>
                        <?php } ?>

                        <?php if ($diff2->invert > 0) { ?>
                            <button type="button" class="link_btn_xxs delBoardBtn" value="<?= $row['BOARD_IDX'] ?>"><span>삭제</span></button>
                        <?php } else if ($diff->invert == 0 && $row['ITEM_INFO_CNT'] == 0) { ?>
                            <button type="button" class="link_btn_xxs delBoardBtn" value="<?= $row['BOARD_IDX'] ?>"><span>삭제</span></button>
                        <?php } else { ?>
                            <button type="button" class="link_btn_xxs" onclick="alert('옵션유효시간 만료후 삭제가능합니다.')"><span>삭제</span></button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="mypage_nav_contents <?php if ($this->uri->segment(3) == 'info') echo 'active_log'; ?>">
        <form method="post" enctype="multipart/form-data" id="mypage_form" action="/index.php/dataFunction/modMember">
            <input type="hidden" id="idx" name="idx" value="<?= $info->MEMBER_IDX ?>">
            <section class="main mypage_main myinfo_main">
                <div class="pt30 pb30 my_thumb">
                    <div class="thumb_img_wrapper">
                        <?php if ($info->PROFILE_IMG) { ?>
                            <img class="user_image" src="<?= $info->PROFILE_IMG ?>" alt="프로필이미지">
                        <?php } else { ?>
                            <img class="user_image" src="/images/thumb/default.png" alt="프로필이미지">
                        <?php } ?>
                    </div>
                    <div class="filebox">
                        <input class="upload-name" type="hidden" placeholder="파일첨부 png,jpg,zip만 가능(최대 5mb미만)" disabled="disabled">
                        <label for="ex_filename"><h4 class="file_name">프로필 사진 수정</h4></label>
                        <input type="file" id="ex_filename" class="upload-hidden" name="profile_img" accept="image/*">
                    </div>
                </div>

                <div class="input_all_wrapper">
                    <label>
                        <h4><strong>이 름</strong></h4>
                        <input class="input_w100" type="text" name="name" value="<?= $info->NAME ?>" placeholder="이름" required>
                    </label>

                    <!--                    <label>
                                            <h4><strong>비밀번호</strong></h4>
                                            <input class="input_w100" type="password" name="" placeholder="" required>
                                        </label>
                    
                                        <label>
                                            <h4><strong>비밀번호 확인</strong></h4>
                                            <input class="input_w100" type="password" name="" placeholder="" required>
                                        </label>-->

                    <label class="align_custom_div">
                        <h4><strong>연 락 처</strong></h4>
                        <div class="inline_input_div">
                            <input class="input_w100" pattern="[0-9]*" type="text" id="phone" name="phone" value="<?= $info->PHONE ?>" <?php if ($info->PHONE) echo 'readonly'; ?> maxlength="11" placeholder="ex) 01012345678">
                            <?php if (!$info->PHONE) { ?>
                                <button type="button" id="phone_auth_send_btn" class="underline_btn blue_underline_btn" style="margin:5px 0 5px 5px;">인증번호 받기</button>
                            <?php } else { ?>
                                <button type="button" id="phone_mod_btn" class="underline_btn blue_underline_btn" style="margin:5px 0 5px 5px;">연락처 수정</button>
                            <?php } ?>
                        </div>
                    </label>

                    <div id="auth_container">
                        <?php
                        if (!$info->PHONE) {
                            $auth = 'N';
                            ?>
                            <label id="auth_area" class="align_custom_div">
                                <h4><strong>인증번호</strong></h4>
                                <div class="inline_input_div">
                                    <input class="input_w100" type="text" id="phone_auth" name="" placeholder="ex) 123234">
                                    <button type="button" id="phone_auth_chk_btn" class="underline_btn" style="margin:5px 0 5px 5px;">인증번호 확인</button>
                                </div>
                            </label>
                            <?php
                        } else if ($info->PHONE) {
                            $auth = 'Y';
                        }
                        ?>
                    </div>
                    <input type="hidden" id="phone_auth_chk" name="phone_auth_chk" value="<?= $auth ?>">

                    <div class="align_custom_div">
                        <h4><strong>지 역</strong></h4>
                        <div class="inline_input_div">
                            <select id="si">
                                <option value="">선택</option>
                                <?php foreach ($si_lists as $row) { ?>
                                    <option value="<?= $row['SIDO'] ?>" <?php if ($info->SIDO == $row['SIDO']) echo 'selected'; ?>><?= $row['SIDO'] ?></option>
                                <?php } ?>
                            </select>
                            <select id="gu">
                                <option value="">선택</option>
                            </select>
                            <select id="gugun" name="dong">
                                <option value="">선택</option>
                            </select>
                        </div>
                        <input class="input_w100 detail_addr" type="text" name="detail_addr" value="<?= $info->DETAIL_ADDR ?>" maxlength="100" placeholder="상세주소를 입력해주세요.">
                    </div>

                    <label class="align_custom_div">
                        <h4 class="dp_ib"><strong>상 호 명</strong></h4>
                        <div class="inline_input_div">
                            <input class="input_w100" type="text" name="business_name" value="<?= $info->BUSINESS_NAME ?>" placeholder="ex) 주식회사 배다른남매">
                        </div>
                    </label>

                    <label class="align_custom_div">
                        <h4><strong>사업자번호</strong></h4>
                        <div class="inline_input_div">
                            <input class="input_w100" type="text" id="business_number" name="business_number" value="<?= $info->BUSINESS_NUMBER ?>" maxlength="10" pattern="[0-9]*" placeholder="ex) 7686868686">
                        </div>
                    </label>

                    <label class="align_custom_div">
                        <h4><strong>대표전화</strong></h4>
                        <div class="inline_input_div">
                            <input class="input_w100" type="text" name="business_phone" value="<?= $info->BUSINESS_PHONE ?>" maxlength="11" pattern="[0-9]*" placeholder="ex) 01012345678">
                        </div>
                    </label>

                    <!--                    <label class="align_custom_div">
                                            <h4><strong>사업자 주소</strong></h4>
                                            <div class="inline_input_div">
                                                 <input type="button" onclick="daumPostcode()" value="주소 찾기"> 
                                                <input onclick="daumPostcode()" class="input_w100 mb1" type="text" id="addr" name="addr" value="<?= $info->ADDR ?>" maxlength="100" placeholder="사업장의 주소지를 입력하세요." readonly>
                                                <input class="input_w100" type="text" id="detail_addr" name="detail_addr" value="<?= $info->DETAIL_ADDR ?>" maxlength="100" placeholder="사업장의 상세 주소지를 입력하세요.">
                                            </div>
                                        </label>-->

                    <label class="align_custom_div">
                        <h4><strong>사업자 홈페이지</strong></h4>
                        <div class="inline_input_div mb30">
                            <input class="input_w100" type="url" id="home_page" name="home_page" value="<?= $info->HOME_PAGE ?>" maxlength="100" placeholder="http://playbetcomm.com">
                        </div>
                    </label>
                </div>
            </section>
            <div class="bottom_btn align_r">
                <button type="submit" id="submit_btn" class="underline_btn mr15"><strong>완료</strong></button>
            </div>
        </form>
    </div>
</section>
</div>




<div class="black_wrapper">
    <div class="click_close"></div>
    <div class="black"></div>
</div>

<script src="http://malsup.github.com/jquery.form.js"></script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">
                        $(document).ready(function () {
                            $(".betting_modal .cancle_btn").click(function () {
                                $(".close-modal").trigger("click");
                            });

                            // if($("#home_page").val().substr(0,4)!="http"){
                            //     var value = $("#home_page").val();
                            //     $("#home_page").val("http://"+value);
                            // };

                            $("#home_page").focus(function () {
                                if (!$.trim($(this).val())) {
                                    $(this).val("http://");
                                }
                            });


                            if ($("#si").select().val()) {
                                var data = {si: $("#si").select().val(), infoVal: '<?= $info->SIGUNGU ?>'};
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

                                var data = {gu: '<?= $info->SIGUNGU ?>', infoVal: '<?= $info->ADDRESS_ORG_IDX ?>'};
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
                                $('#gu').focus();
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
                                $("#gugun").focus();
                            });

                            $("#gugun").change(function () {
                                $(".detail_addr").focus();
                            });


                            $("#phone_auth_send_btn").click(function () {
                                var phone = $("#phone").val();

                                var regNumber = /^[0-9]*$/;

                                if (!regNumber.test(phone)) {
                                    alert("연락처는 숫자만 입력 가능합니다.");
                                    $("#phone").val('');
                                    return false;
                                }

                                if (!$.trim(phone)) {
                                    alert("연락처를 입력해주세요.");
                                    $("#phone").val('');
                                } else {
                                    var data = {phone: phone};
                                    $.ajax({
                                        dataType: 'text',
                                        url: '/index.php/dataFunction/sendSms',
                                        data: data,
                                        type: 'POST',
                                        success: function (data, status, xhr) {
                                            if (data == 'SUCCESS') {
                                                alert('인증번호가 전송 되었습니다.');
                                                $("#phone").attr('readonly', 'readonly');
                                                $('#phone_auth').focus();
                                                return false;
                                            }

                                            if (data == 'DUPLE') {
                                                alert('이미 인증된 번호입니다.');
                                                $("#phone").val('');
                                                return false;
                                            }

                                            if (data == 'FAILED') {
                                                alert('데이터 처리오류');
                                                return false;
                                            }
                                        }
                                    });
                                }

                            });

                            $("#phone_mod_btn").click(function () {
                                $(this).attr('id', 'phone_auth_send_btn');
                                $(this).html('인증번호 받기');
                                $('#phone').attr('readonly', false);
                                var html = '<label id="auth_area" class="align_custom_div">';
                                html += '<h4><strong>인증번호</strong></h4>';
                                html += '<div class="inline_input_div">';
                                html += '<input class="input_w100" type="text" id="phone_auth" name="" placeholder="ex) 123234">';
                                html += '<button type="button" id="phone_auth_chk_btn" class="underline_btn" style="margin:5px 0 15px 5px;">인증번호 확인</button>';
                                html += '</div>';
                                html += '</label>';

                                $("#auth_container").html(html);
                                $("#phone_auth_chk").val('N');

                                $("#phone_auth_send_btn").click(function () {
                                    var phone = $("#phone").val();

                                    var regNumber = /^[0-9]*$/;

                                    if (!regNumber.test(phone)) {
                                        alert("연락처는 숫자만 입력 가능합니다.");
                                        $("#phone").val('');
                                        return false;
                                    }

                                    if (!$.trim(phone)) {
                                        alert("연락처를 입력해주세요.");
                                        $("#phone").val('');
                                    } else {
                                        var data = {phone: phone};
                                        $.ajax({
                                            dataType: 'text',
                                            url: '/index.php/dataFunction/sendSms',
                                            data: data,
                                            type: 'POST',
                                            success: function (data, status, xhr) {
                                                if (data == 'SUCCESS') {
                                                    alert('인증번호가 전송 되었습니다.');
                                                    $("#phone").attr('readonly', 'readonly');
                                                    $('#phone_auth').focus();
                                                    return false;
                                                }

                                                if (data == 'DUPLE') {
                                                    alert('이미 인증된 번호입니다.');
                                                    $("#phone").val('');
                                                    return false;
                                                }

                                                if (data == 'FAILED') {
                                                    alert('데이터 처리오류');
                                                    return false;
                                                }
                                            }
                                        });
                                    }
                                });

                                //인증문자 비교 처리
                                $('#phone_auth_chk_btn').click(function () {
                                    var phone = $("#phone").val();
                                    var accessKey = $("#phone_auth").val();
                                    var idx = $("#idx").val();

                                    if (!accessKey) {
                                        alert("인증번호를 입력해주세요.");
                                        $("#phone_auth").focus();
                                        return false;
                                    }

                                    if (!$.trim(phone)) {
                                        alert("핸드폰 번호를 입력해주세요.");
                                        return false;

                                    } else {
                                        var data = {phone: phone, accessKey: accessKey, idx: idx};
                                        $.ajax({
                                            dataType: 'text',
                                            url: '/index.php/dataFunction/smsAccessChk',
                                            data: data,
                                            type: 'POST',
                                            success: function (data, status, xhr) {
                                                if (data == 'SUCCESS') {
                                                    alert('인증 되었습니다.');
                                                    $("#phone").attr('readonly', 'readonly');
                                                    $('#phone_auth').attr('disabled', 'disabled');
                                                    $("#phone_auth_send_btn").attr('disabled', 'disabled');
                                                    $("#phone_auth_chk_btn").attr('disabled', 'disabled');
                                                    $("#phone_auth_chk").val('Y');
                                                    $("#phone_auth_send_btn").hide();
                                                    $("#auth_area").hide();
                                                    return false;
                                                }

                                                if (data == 'FAILED') {
                                                    alert('인증번호가 일치하지 않습니다.');
                                                    $("#phone_auth").val('');
                                                    $("#phone_auth").focus();
                                                    return false;
                                                }
                                            }

                                        });
                                    }
                                });
                            });

                            //인증문자 비교 처리
                            $('#phone_auth_chk_btn').click(function () {
                                var phone = $("#phone").val();
                                var accessKey = $("#phone_auth").val();
                                var idx = $("#idx").val();

                                if (!accessKey) {
                                    alert("인증번호를 입력해주세요.");
                                    $("#phone_auth").focus();
                                    return false;
                                }

                                if (!$.trim(phone)) {
                                    alert("핸드폰 번호를 입력해주세요.");
                                    return false;

                                } else {
                                    var data = {phone: phone, accessKey: accessKey, idx: idx};
                                    $.ajax({
                                        dataType: 'text',
                                        url: '/index.php/dataFunction/smsAccessChk',
                                        data: data,
                                        type: 'POST',
                                        success: function (data, status, xhr) {
                                            if (data == 'SUCCESS') {
                                                alert('인증 되었습니다.');
                                                $("#phone").attr('readonly', 'readonly');
                                                $('#phone_auth').attr('disabled', 'disabled');
                                                $("#phone_auth_send_btn").attr('disabled', 'disabled');
                                                $("#phone_auth_chk_btn").attr('disabled', 'disabled');
                                                $("#phone_auth_chk").val('Y');
                                                $("#phone_auth_send_btn").hide();
                                                $("#auth_area").hide();
                                                return false;
                                            }

                                            if (data == 'FAILED') {
                                                alert('인증번호가 일치하지 않습니다.');
                                                $("#phone_auth").val('');
                                                $("#phone_auth").focus();
                                                return false;
                                            }
                                        }

                                    });
                                }
                            });

                            $("#mypage_form").ajaxForm({
                                success: function (data) {
                                    if (data == 'SUCCESS') {
                                        alert("수정되었습니다.");
                                        return false;
                                    }

                                    if (data == 'FAILED') {
                                        alert('데이터 처리오류!!');
                                        return false;
                                    }
                                }
                            });

                            $(".delBoardBtn").click(function () {
                                if (confirm("정말 삭제하시겠습니까??") == true) {    //확인
                                    $(this).parent().parent().parent().remove();
                                    var data = {board_idx: $(this).val()};
                                    $.ajax({
                                        dataType: 'text',
                                        url: '/index.php/dataFunction/delBoard',
                                        data: data,
                                        type: 'POST',
                                        success: function (data, status, xhr) {
//                                            if (data == 'SUCCESS') {
//                                                alert("삭제 되었습니다.");
//                                            }

                                            if (data == 'FAILED') {
                                                alert("데이터 처리오류!!");
                                                return false;
                                            }
                                        }

                                    });
                                } else {
                                    return false;
                                }
                            });
                        });

                        function openWonList(board_idx) {
                            var data = {board_idx: board_idx};

                            $.ajax({
                                dataType: 'json',
                                url: '/index.php/dataFunction/wonList',
                                data: data,
                                type: 'POST',
                                success: function (data, status, xhr) {
                                    if (data.RESULT === 'SUCCESS') {
                                        $("#wonList" + board_idx).modal();
                                        $("#wonListBtn" + board_idx).html(data.NAME);
                                        $("#wonListCount" + board_idx).html(data.CNT + "명");
                                        console.log(data);
                                    } else {
                                        console.log("실패");
                                    }
                                }
                            });
                        }

                        function compBtn(board_idx) {
                            var data = {board_idx: board_idx};

                            $.ajax({
                                dataType: 'text',
                                url: '/index.php/dataFunction/compBoard',
                                data: data,
                                type: 'POST',
                                success: function (data, status, xhr) {
                                    if (data === 'SUCCESS') {
                                        alert('베팅 참여자가 없습니다.');
                                    } else {
                                        console.log("실패");
                                    }
                                }
                            });
                        }

                        function daumPostcode() {
                            new daum.Postcode({
                                oncomplete: function (data) {
                                    // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                                    // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                                    // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                                    var fullAddr = ''; // 최종 주소 변수
                                    var extraAddr = ''; // 조합형 주소 변수

                                    // 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                                    if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                                        fullAddr = data.roadAddress;

                                    } else { // 사용자가 지번 주소를 선택했을 경우(J)
                                        fullAddr = data.jibunAddress;
                                    }

                                    // 사용자가 선택한 주소가 도로명 타입일때 조합한다.
                                    if (data.userSelectedType === 'R') {
                                        //법정동명이 있을 경우 추가한다.
                                        if (data.bname !== '') {
                                            extraAddr += data.bname;
                                        }
                                        // 건물명이 있을 경우 추가한다.
                                        if (data.buildingName !== '') {
                                            extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                                        }
                                        // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                                        fullAddr += (extraAddr !== '' ? ' (' + extraAddr + ')' : '');
                                    }

                                    // 우편번호와 주소 정보를 해당 필드에 넣는다.
                                    //document.getElementById('sample6_postcode').value = data.zonecode; //5자리 새우편번호 사용
                                    document.getElementById('addr').value = fullAddr;

                                    // 커서를 상세주소 필드로 이동한다.
                                    document.getElementById('detail_addr').focus();
                                }
                            }).open();
                        }
</script>

<script type="text/javascript">
    $(".mypage_nav li").click(function () {
        var mypage_nav_index = $(this).index(".mypage_nav li");

        // 상단 썸네일이 내정보수정일때 가려지고 다른 썸네일 div로 대체됨
        if (mypage_nav_index == 3) {
            $(".my_thumb_view").css("display", "none");
        } else {
            $(".my_thumb_view").css("display", "block");
        }

        if (mypage_nav_index == 0) {
            var data = {member_idx: '<?= $this->session->userdata('MEMBER_IDX') ?>'};
            $.ajax({
                dataType: 'text',
                url: '/index.php/dataFunction/histoyRead',
                data: data,
                type: 'POST',
                success: function (data, status, xhr) {
                    if (data == 'FAILED') {
                        alert("데이터 처리오류!!");
                        return false;
                    }
                }
            });
        }

        $(".mypage_nav_contents").css("display", "none");
        $(".mypage_nav_contents").eq(mypage_nav_index).css("display", "block")

        $(".mypage_nav li").removeClass("select_mypage_nav");
        $(this).addClass("select_mypage_nav");
    });



</script>

<script type="text/javascript">
    $(document).ready(function () {

<?php if ($this->uri->segment(3) == 'info') { ?>
            $(".my_thumb_view").css("display", "none");
<?php } ?>

        var fileTarget = $('.filebox .upload-hidden');

        fileTarget.on('change', function () {
            if (window.FileReader) {
                var filename = $(this)[0].files[0].name;
            } else {
                var filename = $(this).val().split('/').pop().split('\\').pop();
            }
            $('.upload-name').val(filename);

            $(".file_name").text(filename);

            readURL(this);
        });

        if ($(".user_image").attr("src") == "") {
            $(".user_image").attr("src", "/images/thumb/default.png");
        }

    });


    //이미지 바꾸는 함수
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.user_image').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

</script>
