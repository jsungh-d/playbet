<div class="comment_input pb15 dotted_bottom">
    <div class="comment_input_wrapper pl15">
        <div class="thumb_img_wrapper dp_ib">
            <img src="<?= $row['PROFILE_IMG'] ?>" alt="프로필 이미지">
        </div>
        <div class="dp_ib text_width_thumb">
            <h5>
                <strong><?= $row['NAME'] ?></strong>
                <?php if ($row['MEMBER_IDX'] == $this->session->userdata('MEMBER_IDX')) { ?>
                <div class="dp_ib comment_controll pl15">
                    <span class="mod_contents_btn cursor_pointer">수정</span>&ensp;|&ensp;<span class="cursor_pointer" onclick="delContents('<?= $row['COMMENT_IDX'] ?>');">삭제</span>
                </div>
                <?php } ?>

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