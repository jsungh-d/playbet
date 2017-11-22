<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <h3>문의관리 <small>사용자 1:1문의 및 제휴문의를 관리 할 수 있습니다.</small></h3>
    </div>

    <div class="x_panel">
        <div class="x_content">
            <form method="post" action="/index.php/dataFunction/mod_board_ask">
                <input type="hidden" name="board_seq" value="<?= $info->board_seq ?>">
                <input type="hidden" name="admin_seq" value="<?= $this->session->userdata('admin_seq') ?>">
                <table class="table table-bordered bulk_action">
                    <colgroup><col width="120px"><col width="*"></colgroup>
                    <tbody>
                        <tr>
                            <th>문의 구분</th>
                            <td>
                                <?= $info->customer_type ?>
                            </td>
                        </tr>
                        <tr>
                            <th>회원 이름</th>
                            <td>
                                <?= $info->user_name ?>
                            </td>
                        </tr>
                        <tr>
                            <th>회원 이메일</th>
                            <td>
                                <?= $info->email ?>
                            </td>
                        </tr>
                        <tr>
                            <th>휴대폰번호</th>
                            <td>
                                <?= $info->phone ?>
                            </td>
                        </tr>
                        <tr>
                            <th>문의 내용</th>
                            <td>
                                <?= nl2br($info->board_contents) ?>
                            </td>
                        </tr>
                        <tr>
                            <th>첨부이미지</th>
                            <td>
                                <?= $info->img_file_name ?>
                            </td>
                        </tr>
                        <tr>
                            <th>답변 내용</th>
                            <td>
                                <textarea required="required" id="contents_response" class="form-control" name="contents_response" style="resize: none; height:100px;"><?= $info->contents_response ?></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group text-right">
                    <button class="btn btn-sm btn-primary" type="button" onclick="location.href = '/index/question'">취소</button>
                    <button class="btn btn-sm btn-primary" id="tmp_save" type="button" value="<?= $info->board_seq ?>">임시저장</button>
                    <button type="submit" class="btn btn-sm btn-primary">답변완료</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#tmp_save").click(function () {
            var idx = $(this).val();
            var text = $("#contents_response").val();

            if (!$.trim(text)) {
                alert("답변 내용을 입력해주세요.");
                $("#contents_response").val('');
                $("#contents_response").focus();
                return false;
            } else {
                var data = {idx: idx, text: text};
                $.ajax({
                    dataType: 'text',
                    url: '/index.php/dataFunction/ins_question_tmp',
                    data: data,
                    type: 'POST',
                    success: function (data, status, xhr) {
                        if (data === 'SUCCESS') {
                            alert("임시 저장 되었습니다.");
                            return false;
                        } else {
                            alert("데이터 처리오류!!");
                            return false;
                        }
                    }
                });
            }
        });
    });
</script>