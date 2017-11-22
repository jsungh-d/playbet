<div class="result_modal_contents_wrapper custom_checkbox align_l">
    <?php foreach ($lists as $row) { ?>
        <div class="resuit_modal_contents">
            <!-- 체크박스에 각기 다른 아이디가 존재함 -->
            <input type="checkbox" id="c<?= $row['ITEM_INFO_IDX'] ?>" class="check_box" name="chk_row" value="<?= $row['ITEM_INFO_IDX'] ?>">
            <label for="c<?= $row['ITEM_INFO_IDX'] ?>">
                <span class="va_container">
                    <div class="thumb_img_wrapper va_row">
                        <?php if (!$row['PROFILE_IMG']) { ?>
                            <img src="/images/thumb/default.png" alt="프로필 이미지">
                        <?php } else { ?>
                            <img src="<?= $row['PROFILE_IMG'] ?>" alt="프로필 이미지">
                        <?php } ?>
                    </div>
                    <div class="va_row">
                        <h4><strong><?= $row['NAME']?></strong></h4>
                    </div>
                </span>
            </label>
        </div>
    <?php } ?>
    <div class="pt15 pb15">
        <textarea class="full_textarea" id="cupon_text" name="" placeholder="옵션과 함께 보낼 텍스트를 입력해주세요."></textarea>
    </div>
</div>