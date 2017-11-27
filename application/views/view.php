<div class="top_btn align_r">
    <button type="button" class="underline_btn mr15" onclick="location.href = '/'"><strong>닫기</strong></button>
</div>
<section class="main">
    <div class="contents">
        <div class="contents_main">
            <h4 class="contents_class"><!--a href="/index/user_info/<?= $info->MEMBER_IDX ?>"-->
                <?= $info->BUSINESS_NAME ?> 
                > 
                <?php if ($info->CATEGORY_TYPE == 'AREA') { ?>
                    <a onclick="locationSearch('<?= $info->SIGUNGU ?>');"><?= $info->LOCATION ?></a>
                <?php } else { ?>
                    <!--<a onclick="subCategorySearch('<?= $info->CATEGORY_IDX ?>', '<?= $info->CATEGORY_PNUM ?>');"><?= $info->CATEGORY_TYPE ?></a>-->
                    <a onclick="locationSearch('<?= $info->CATEGORY_TYPE ?>');"><?= $info->CATEGORY_TYPE ?></a>
                <?php } ?>
                > 
                <a href="/index/view/<?= $info->BOARD_IDX ?>"><span class="text_darkblue"><strong><?= $info->TITLE ?></strong></span></a></h4>
            <h3 class="contents_hour">
                <span class="text_blue">
                    <?php
                    $date1 = new DateTime($info->NOW);
                    $date2 = new DateTime($info->EFFECTIVE_TIME);
                    $diff = date_diff($date1, $date2);
                    if ($diff->invert == 0) {
                        echo $diff->d . '일 ' . $diff->h . ':' . $diff->i;
                    } else {
                        echo '종료';
                    }
                    ?>
                </span>
            </h3>

            <h1 class="contents_title pt30 pb30"><b><?= $info->TIME_LINE ?></b></h1>

            <?php if ($info->LINK_URL) { ?>
                <h5>
                    <strong>참조 링크</strong>
                    <br>
                    <a class="index_link_a" href="<?= $info->LINK_URL ?>"><?= $info->LINK_URL ?></a>
                </h5>
            <?php } ?>

            <div class="btn_area pt15 pb15 align_c">
                <?php
                $i = 0;
                foreach ($item_lists as $subRow) {
                    if ($i == 0) {
                        $color = 'yellow_btn';
                    }

                    if ($i == 1) {
                        $color = 'darkblue_btn';
                    }

                    if ($i == 2) {
                        $color = 'red_btn';
                    }

                    if ($subRow['MY_CHK'] == 'Y') {
                        $myClass = 'my_chk_btn';
                        // $color = 'default_btn';
                    } else if ($subRow['MY_CHK'] != 'Y') {
                        $myClass = ' ';
                        // $color = 'default_btn';
                    }
                    ?>
                    <a class="betting_modal <?= $color ?> color_btn <?= $myClass ?>" onclick="openBetting('<?= $subRow['NAME'] ?>', '<?= $info->TITLE ?>', '<?= $info->BOARD_IDX ?>', '<?= $subRow['ITEM_IDX'] ?>', '<?= $this->session->userdata('MEMBER_IDX') ?>', '<?= $info->MEMBER_IDX ?>', '<?= $diff->invert ?>');">
                        <button type="button" class="<?= $color ?> color_btn"><h3><b class="btn_text"><?= $subRow['NAME'] ?></b>  &nbsp;<strong><?= number_format($subRow['CNT']) ?></strong></h3></button>
                    </a>
                    <?php
                    $i++;
                }
                ?>
            </div>
        </div>

        <!-- 모달 확인 후 뜨는 버튼들 -->
        <div class="communuty_btn">
            <div class="comment_btn">
                <h5>
                    <img src="/images/common/comment.png" alt=""><span>댓글[<?= count($comment_lists) ?>]</span>
                </h5>
            </div>
            <div class="view_link_btn">
                <h5>
                    <img src="/images/common/plus.png" alt=""><span>옵션 상세</span>
                </h5>
            </div>
            <div class="share_btn">
                <a onclick="openShare('<?= $info->TITLE ?>');" class="betting_modal">
                    <h5>
                        <img src="/images/common/share.png" alt=""><span>공유</span>
                    </h5>
                </a>
            </div>
        </div>


        <div class="view_detail_area" style="<?php
        if (!$this->uri->segment(4))
            echo 'display:block';
        else
            echo 'display:none';
        ?>">
            <div class="input_all_wrapper">
                <!-- 이미지 슬라이드 -->
                <label class="align_custom_div" style="margin-top:0;">
                    <h4><strong>첨부 사진</strong></h4>
                    <?php if ($file_lists) { ?>
                        <div class="swiper-container view_swiper-container">
                            <div class="swiper-wrapper view_swiper_wrapper">
                                <?php foreach ($file_lists as $row) { ?>
                                    <div class="swiper-slide click_swiper_btn">
                                        <img src="<?= $row['LOCATION'] ?>">
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="swiper-pagination view_swiper-pagination"></div>

                            <div class="swiper-button-prev view_swiper-button-prev"></div>
                            <div class="swiper-button-next view_swiper-button-next"></div>
                        </div>
                    <?php } ?>
                </label>

                <!-- 이미지 슬라이드 -->
                <?php if ($info->VIDEO) { ?>
                    <label class="align_custom_div">
                        <h4><strong>첨부 영상</strong></h4>
                        <div class="video_area video_area_page" onclick="openVideoView();">
                            <video class="main_video" width="100%" height="200px">
                                <source src="<?= $info->VIDEO ?>" type="video/mp4"/>
                            </video>
                            <img class="video_modal_btn" src="/images/common/icon_play.png" alt="동영상 보기">
                            <div class="overlay_black" ></div>
                        </div>
                    </label>
                <?php } ?>

                <label class="align_custom_div scroll_target">
                    <h4 class="dp_ib"><strong>상 호 명</strong></h4>
                    <div class="inline_input_div">
                        <h5><?= $info->BUSINESS_NAME ?></h5>
                    </div>
                </label>

                <label class="align_custom_div">
                    <h4><strong>옵션 및 매장소개</strong></h4>
                    <h5><?= nl2br($info->CONTENTS) ?></h5>
                </label>

                <label class="align_custom_div">
                    <h4><strong>주소</strong></h4>
                    <div class="inline_input_div">
                        <?php
                        $exp_addr = explode(' ', $info->ADDR);
                        $link_addr = "";
                        for ($i = 0; $i < count($exp_addr); $i++) {
                            $link_addr .= $exp_addr[$i] . "+";
                        }
                        ?>
                        <h5><?= $info->ADDR ?><br><a href="http://map.naver.com/?query=<?= substr($link_addr, 0, -1) ?>" style="color: #1869bd; text-decoration: underline">지도보기</a></h5>
                    </div>
                </label>

                <label class="align_custom_div">
                    <h4><strong>대표전화</strong></h4>
                    <div class="inline_input_div">
                        <h5><?= preg_replace("/(^02.{0}|^01.{1}|^07.{1}|^03.{1}|^04.{1}|^05.{1}|^06.{1}|[0-9]{3})([0-9]+)([0-9]{4})/", "$1-$2-$3", $info->PHONE); ?></h5>
                    </div>
                </label>

                <label class="align_custom_div">
                    <h4><strong>웹페이지 주소</strong></h4>
                    <div class="inline_input_div">
                        <h5><a href="http://<?= $info->HOME_PAGE ?>"><?= $info->HOME_PAGE ?></a></h5>
                    </div>
                </label>
            </div>
        </div>


        <div class="comment_area" style="<?php if ($this->uri->segment(4) == 'comment') echo 'display:block'; ?>">
            <div class="comment_input">
                <div class="comment_input_wrapper">
                    <form method="post" onsubmit="commentChk(this);
                            return false;" action="/index.php/dataFunction/insComment">
                        <input type="hidden" name="member_idx" value="<?= $this->session->userdata('MEMBER_IDX'); ?>">
                        <input type="hidden" name="board_idx" value="<?= $info->BOARD_IDX ?>">
                        <input type="hidden" name="title" value="<?= $info->TITLE ?>">
                        <input type="hidden" name="comment_idx" value="">
                        <div class="thumb_img_wrapper dp_ib">
                            <?php if ($member_info) { ?>
                                <img src="<?= $member_info->PROFILE_IMG ?>" alt="프로필 이미지">
                            <?php } else { ?>
                                <img src="/images/thumb/default.png" alt="프로필 이미지">
                            <?php } ?>
                        </div>
                        <div class="dp_ib pl15 pr15 w70">
                            <textarea name="contents" required></textarea>
                        </div>
                        <div class="dp_ib w25">
                            <button type="submit" class="color_btn darkblue_btn"><h5>확인</h5></button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="comment_area">
                <?php
                foreach ($comment_lists as $row) {
                    ?>
                    <div class="comment_contents">
                        <div class="comment_input">
                            <div class="comment_input_wrapper">
                                <div class="thumb_img_wrapper dp_ib">
                                    <?php if ($row['PROFILE_IMG']) { ?>
                                        <img src="<?= $row['PROFILE_IMG'] ?>" alt="프로필 이미지">
                                    <?php } else { ?>
                                        <img src="/images/thumb/default.png" alt="프로필 이미지">
                                    <?php } ?>
                                </div>
                                <div class="dp_ib text_width_thumb">
                                    <h5>
                                        <strong><?= $row['NAME'] ?></strong>
                                        <?php if ($row['MEMBER_IDX'] == $this->session->userdata('MEMBER_IDX')) { ?>
                                            <div class="dp_ib comment_controll pl15">
                                                <span class="mod_contents_btn cursor_pointer">수정</span>&ensp;|&ensp;<span class="cursor_pointer" onclick="delContents('<?= $row['COMMENT_IDX'] ?>');">삭제</span>
                                            </div>
                                        <?php } ?>

                                        <span class="reply" style="cursor:pointer; margin-left: 15px;">답글</span>

                                    </h5>
                                    <h5>
                                        <div class="mod_contents_area" style="display: none;">
                                            <div class="dp_tc pr15 w70">
                                                <textarea name="contents" class="mod_contents" required><?= $row['CONTENTS'] ?></textarea>
                                            </div>
                                            <div class="dp_tc w25 valign_t">
                                                <button type="button" class="color_btn darkblue_btn mod_contents_submit_btn" value="<?= $row['COMMENT_IDX'] ?>"><h5>확인</h5></button>
                                            </div>
                                        </div>
                                        <div class="org_contents pr30">
                                            <?= nl2br($row['CONTENTS']) ?>
                                        </div>
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <!-- 대댓글 -->
                        <?php if (${'comment_sub_lists' . $row['COMMENT_IDX']}) { ?>
                            <div class="reply_read_area dotted_top">
                                <?php foreach (${'comment_sub_lists' . $row['COMMENT_IDX']} as $subRow) { ?>
                                    <div class="comment_input pb15 dotted_bottom">
                                        <div class="comment_input_wrapper pl15">
                                            <div class="thumb_img_wrapper dp_ib">
                                                <?php if ($subRow['PROFILE_IMG']) { ?>
                                                    <img src="<?= $subRow['PROFILE_IMG'] ?>" alt="프로필 이미지">
                                                <?php } else { ?>
                                                    <img src="/images/thumb/default.png" alt="프로필 이미지">
                                                <?php } ?>
                                            </div>
                                            <div class="dp_ib text_width_thumb">
                                                <h5>
                                                    <strong><?= $subRow['NAME'] ?></strong>
                                                    <?php if ($subRow['MEMBER_IDX'] == $this->session->userdata('MEMBER_IDX')) { ?>
                                                        <div class="dp_ib comment_controll pl15">
                                                            <span class="mod_contents_btn cursor_pointer">수정</span>&ensp;|&ensp;<span class="cursor_pointer" onclick="delContents('<?= $subRow['COMMENT_IDX'] ?>');">삭제</span>
                                                        </div>
                                                    <?php } ?>

                                                </h5>
                                                <h5>
                                                    <div class="mod_contents_area" style="display: none;">
                                                        <div class="dp_tc pr15 w70">
                                                            <textarea name="contents" class="mod_contents" required><?= $subRow['CONTENTS'] ?></textarea>
                                                        </div>
                                                        <div class="dp_tc w25 valign_t">
                                                            <button type="button" class="color_btn darkblue_btn mod_contents_submit_btn" value="<?= $subRow['COMMENT_IDX'] ?>"><h5>확인</h5></button>
                                                        </div>
                                                    </div>
                                                    <div class="org_contents pr30">
                                                        <?= nl2br($subRow['CONTENTS']) ?>
                                                    </div>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <!-- 대댓글 인풋 -->
                        <div class="reply_area" style="display: none;">
                            <div class="comment_input pt15">
                                <div class="comment_input_wrapper pl15">
                                    <form method="post" onsubmit="commentChk(this);
                                                return false;" action="/index.php/dataFunction/insComment">
                                        <input type="hidden" name="member_idx" value="<?= $this->session->userdata('MEMBER_IDX'); ?>">
                                        <input type="hidden" name="board_idx" value="<?= $info->BOARD_IDX ?>">
                                        <input type="hidden" name="title" value="<?= $info->TITLE ?>">
                                        <input type="hidden" name="comment_idx" value="<?= $row['COMMENT_IDX'] ?>">
                                        <div class="thumb_img_wrapper dp_ib">
                                            <?php if ($member_info) { ?>
                                                <img src="<?= $member_info->PROFILE_IMG ?>" alt="프로필 이미지">
                                            <?php } else { ?>
                                                <img src="/images/thumb/default.png" alt="프로필 이미지">
                                            <?php } ?>
                                        </div>
                                        <div class="dp_ib pl15 pr15 w70">
                                            <textarea name="contents" required></textarea>
                                        </div>
                                        <div class="dp_ib w25">
                                            <button type="submit" class="color_btn darkblue_btn"><h5>확인</h5></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                    </div>
                    <?php
                }
                ?>

            </div>
        </div>
    </div>

</div>

</section>
</div>

<div class="black_wrapper">
    <div class="click_close"></div>
    <div class="black"></div>
</div>


<button type="button" class="write_icon" onclick="location.href = '/index/write'">
    <img src="/images/common/write.png" alt=""><br>
    <h5>내기 등록</h5>
</button>


<!-- 이미지 뷰 모달 -->
<div id="imgView" class="align_c playbet_modal" style="display:none; height:100%">
    <!-- <img class="modal_title_img" src="/images/common/picture.png" alt=""> -->
    <!-- <h2 class="modal_title_h2">
        이미지 보기
    </h2> -->
    <img src="/images/common/left-arrow.png" alt="" class="cancle_btn">

    <div class="swiper-container modal_swiper-container" style=" height:100%; width:100%;">
        <div class="swiper-wrapper modal_swiper_wrapper" style=" height:100%; width:100%;">
            <?php foreach ($file_lists as $row) { ?>
                <div class="swiper-slide" style=" height:100%; width:100%;">
                    <div class="va_container">
                        <div class="va_row" style="-webkit-vertical-align:middle;">
                            <img src="<?= $row['LOCATION'] ?>">
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="swiper-pagination modal_swiper-pagination"></div>

        <div class="swiper-button-prev modal_swiper-button-prev"></div>
        <div class="swiper-button-next modal_swiper-button-next"></div>
    </div>

    <!-- <img class="imgView_img" src=""> -->
    <!-- <div class="btn_area pt15 pb15">
        <button type="button" class="color_btn cancle_btn default_btn">닫기</button>
    </div> -->
</div>


<!-- 비디오 뷰 모달 -->
<div id="videoView" class="align_c playbet_modal" style="display:none; height:100%">
    <img src="/images/common/left-arrow.png" alt="" class="cancle_btn">
    <div class="va_container">
        <div class="va_row">
            <div class="video_area">
                <video class="main_video" width="100%" height="250px" autoplay controls>
                    <source src="<?= $info->VIDEO ?>" type="video/mp4"/>
                </video>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {

        if (getParameters('scroll')) {
            $('html, body').animate({scrollTop: $(".scroll_target").offset().top - $(window).height() - 10}, 10);
        }

        $(".click_swiper_btn").click(function () {
            openImgView($(this).index());
        });

        var swiper = new Swiper('.view_swiper-container', {
            pagination: '.view_swiper-pagination',
            nextButton: '.view_swiper-button-next',
            prevButton: '.view_swiper-button-prev',
            paginationClickable: true
        });


        $(".mod_contents_btn").click(function () {
            var index = $(this).index('.mod_contents_btn');
            $(".org_contents:eq(" + index + ")").hide();
            $(".mod_contents_area:eq(" + index + ")").show();
        });

        $(".mod_contents_submit_btn").click(function () {
            var index = $(this).index('.mod_contents_submit_btn');
            var comment_idx = $(this).val();
            var contents = $(".mod_contents:eq(" + index + ")").val();

            var data = {comment_idx: comment_idx, contents: contents};

            $.ajax({
                dataType: 'text',
                url: '/index.php/dataFunction/modComment',
                data: data,
                type: 'POST',
                success: function (data, status, xhr) {
                    if (data == 'SUCCESS') {
                        alert("수정 되었습니다.");
                        location.reload();
                    } else {
                        alert("데이터 처리오류!!");
                    }
                }
            });
        });



        var replyIndex = 0;
        var replyClickCount = 0;
        $(".reply").click(function () {
            replyIndex = $(this).index(".reply");

//            $.post("/index.php/DataFunction/reply", {}, function (data) {
            if ($(".reply").eq(replyIndex).text() == "답글") {
                $(".reply_area").hide();

                $(".reply").text("답글");
                $(".reply").eq(replyIndex).text("취소");
                $(".reply_area").eq(replyIndex).show();

            } else {
                $(".reply").text("답글");
                $(".reply_area").hide();
            }
//            });
        });

        // $(".view_swiper_wrapper").css("height",$(".view_swiper_wrapper").width());
        // $( window ).resize(function(){
        //     $(".view_swiper_wrapper").css("height",$(".view_swiper_wrapper").width());
        // });

    });

    function commentChk(obj) {
        var contents = obj.contents.value;
        var member_idx = obj.member_idx.value;

        if (!member_idx) {
            alert("로그인후 작성해주세요.");
            return false;
        } else if (!$.trim(contents)) {
            alert("내용을 작성해주세요.");
            obj.contents.value = '';
            return false;
        } else {
            obj.submit();
        }
    }

    function delContents(idx) {
        var data = {idx: idx};
        $.ajax({
            dataType: 'text',
            url: '/index.php/dataFunction/delComment',
            data: data,
            type: 'POST',
            success: function (data, status, xhr) {
                if (data == 'SUCCESS') {
                    alert("삭제 되었습니다.");
                    location.reload();
                } else {
                    alert("데이터 처리오류!!");
                }
            }
        });
    }




    $(".my_chk_btn").siblings(".color_btn").addClass("none_select_btn");



</script>



