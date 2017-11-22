<div class="top_btn align_r">
    <button type="button" class="underline_btn mr15" onclick="window.history.back()"><strong>닫기</strong></button>
</div>
<section class="main mypage_main">

    <div>
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
                <label>
                    <h4><strong>이 름</strong></h4>
                    <h4><?= $info->NAME ?></h4> 
                </label>

                <label class="align_custom_div">
                    <h4><strong>연 락 처</strong></h4>
                    <div class="inline_input_div">
                        <h4><?= $info->PHONE ?></h4> 
                    </div>
                </label>



                <label class="align_custom_div">
                    <h4><strong>지 역</strong></h4>
                    <div class="inline_input_div">
                        <h4><?= $info->LOCATION?></h4>
                    </div>
                </label>

                <label class="align_custom_div">
                    <h4 class="dp_ib"><strong>상 호 명</strong></h4>
                    <div class="inline_input_div">
                        <h4><?= $info->BUSINESS_NAME ?></h4> 
                    </div>
                </label>

                <label class="align_custom_div">
                    <h4><strong>사업자번호</strong></h4>
                    <div class="inline_input_div mb30">
                        <h4><?= $info->BUSINESS_NUMBER ?></h4> 
                    </div>
                </label>
            </div>
        </section>
        </form>
    </div>
</section>
</div>

<div class="black_wrapper">
    <div class="click_close"></div>
    <div class="black"></div>
</div>