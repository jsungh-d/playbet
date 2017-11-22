<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <h3>공지사항 <small>공지사항을 등록하며 푸쉬를 발송 할 수 있습니다.</small></h3>
    </div>

    <div class="x_panel">
        <div class="x_content">
            <form method="post" action="/index.php/dataFunction/insNotice">
                <table class="table table-bordered">
                    <colgroup><col width="120px"><col width="*"></colgroup>
                    <tbody>
                        <tr>
                            <th>서비스 반영</th>
                            <td>
                                <select class="form-control" name="notice_status" required>
                                    <option value="1">반영</option>
                                    <option value="0">미반영</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>푸쉬 발송</th>
                            <td>
                                <select class="form-control" name="push_type" id="push_type" required>
                                    <option value="0">미발송</option>
                                    <option value="2">발송 예약</option>
                                    <option value="1">즉시 발송</option>
                                </select>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="is_toast" value="Y"> Toast 발송(안드로이드 해당)
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr id="send_time" style="display: none;">
                            <th>발송시간</th>
                            <td>
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                    <input type="text" name="push_date" class="form-control daterangepicker" value="" style="top: 0; left: 0; margin-top: 0;"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>제목</th>
                            <td><input type="text" class="form-control" name="title" required /></td>
                        </tr>
                        <tr>
                            <th>내용</th>
                            <td>
                                <textarea required="required" class="form-control" name="contents" style="resize: none; height:100px;"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>상단 공지</th>
                            <td>
                                <select class="form-control" name="is_top" required>
                                    <option value="1">등록</option>
                                    <option value="0">미등록</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group text-right">
                    <button class="btn btn-sm btn-primary" type="button" onclick="location.href = '/index/notice'">취소</button>
                    <button class="btn btn-sm btn-primary" type="button">미리보기</button>
                    <button type="submit" class="btn btn-sm btn-primary">등록</button>
                </div>
            </form>
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