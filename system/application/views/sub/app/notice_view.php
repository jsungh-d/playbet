<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <h3>공지사항 <small>공지사항을 등록하며 푸쉬를 발송 할 수 있습니다.</small></h3>
    </div>

    <div class="x_panel">
        <div class="x_content">
            <form method="post" action="/index.php/dataFunction/modNotice">
                <input type="hidden" name="notice_seq" value="<?= $info->notice_seq ?>">
                <table class="table table-bordered bulk_action">
                    <colgroup><col width="120px"><col width="*"></colgroup>
                    <tbody>
                        <tr>
                            <th>서비스 반영</th>
                            <td>
                                <select class="form-control" name="notice_status" required>
                                    <option value="1" <?php if ($info->notice_status == 1) echo 'selected' ?>>반영</option>
                                    <option value="0" <?php if ($info->notice_status == 0) echo 'selected' ?>>미반영</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>푸쉬 발송</th>
                            <td>
                                <select class="form-control" name="push_type" id="push_type" required>
                                    <option value="0" <?php if ($info->push_type == 0) echo 'selected' ?>>미발송</option>
                                    <option value="2" <?php if ($info->push_type == 2) echo 'selected' ?>>발송 예약</option>
                                    <option value="1" <?php if ($info->push_type == 1) echo 'selected' ?>>즉시 발송</option>
                                </select>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="is_toast" value="Y" <?php if ($info->is_toast) echo 'checked'; ?>> Toast 발송(안드로이드 해당)
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr id="send_time" style="display: <?php if ($info->push_type != 2) echo 'none'; ?>;">
                            <th>발송시간</th>
                            <td>
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                    <input type="text" name="push_date" class="form-control daterangepicker" value="<?= $info->push_date ?>" style="top: 0; left: 0; margin-top: 0;"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>제목</th>
                            <td><input type="text" class="form-control" name="title" value="<?= $info->title ?>" required /></td>
                        </tr>
                        <tr>
                            <th>내용</th>
                            <td>
                                <textarea required="required" class="form-control" name="contents" style="resize: none; height:100px;"><?= $info->contents ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>상단 공지</th>
                            <td>
                                <select class="form-control" name="is_top" required>
                                    <option value="1" <?php if ($info->is_top == 1) echo 'selected'; ?>>등록</option>
                                    <option value="0" <?php if ($info->is_top == 0) echo 'selected'; ?>>미등록</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group text-right">
                    <button class="btn btn-sm btn-primary" type="button" onclick="location.href = '/index/notice'">취소</button>
                    <button class="btn btn-sm btn-primary" type="button" onclick="$('#noticePreview').modal('show');">미리보기</button>
                    <button type="submit" class="btn btn-sm btn-primary">수정</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style type="text/css">
    .modal {
        text-align: center;
        padding: 0!important;
    }

    .modal:before {
        content: '';
        display: inline-block;
        height: 100%;
        vertical-align: middle;
        margin-right: -4px;
    }

    .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
    }
</style>
<!--user detail view Modal-->
<div id="noticePreview" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 375px;">
        <!-- Modal content-->
        <div class="notice_preview">
            <div class="notice_preview_header">
                <img src="/images/notice/ico_prev.png">
                <h4>공지사항</h4>
            </div>
            <div class="notice_preview_container">
                <ul>
                    <li class="on">
                        <h4><?= $info->title ?></h4>
                        <span><?= $info->INS_DATE ?></span>
                        <img src="/images/notice/ico_up.png">
                        <div class="notice_preview_contents">
                            <p>
                                <?= nl2br($info->contents) ?>
                            </p>
                        </div>
                    </li>
                    <li>
                        <h4>개인정보 처리 방침 개정 안내</h4>
                        <span>2017.03.10</span>
                        <img src="/images/notice/ico_down.png">
                    </li>
                    <li>
                        <h4>위치기반 서비스 이용약관 변경 안내</h4>
                        <span>2017.03.10</span>
                        <img src="/images/notice/ico_down.png">
                    </li>
                    <li>
                        <h4>보다 강력한 기능이 옵니다.</h4>
                        <span>2017.03.10</span>
                        <img src="/images/notice/ico_down.png">
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#push_type").change(function () {
            if ($(this).select().val() == 2) {
                $("#send_time").show();
            } else {
                $("#send_time").hide();
            }
        });
    });
</script>

