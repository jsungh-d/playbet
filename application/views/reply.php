<div class="comment_input pt15">
    <div class="comment_input_wrapper pl15">
        <form method="post" onsubmit="commentChk(this);return false;" action="/index.php/dataFunction/insComment">
            <input type="hidden" name="member_idx" >
            <input type="hidden" name="board_idx">
            <input type="hidden" name="title">
            <div class="thumb_img_wrapper dp_ib">

                <img src="/images/thumb/default.png" alt="프로필 이미지">

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


