<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <h3>종목추가 <small>서비스 종목을 관리합니다.</small></h3>
    </div>


    <div class="x_panel">
        <div class="x_content">
            <br />
            <form class="form-horizontal form-label-left" method="post" action="/index.php/dataFunction/insOption">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">서비스 노출</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="form-control" name="stock_status">
                            <option value="1">노출</option>
                            <option value="0">미노출</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">구분<span class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="form-control" name="kind">
                            <option value="Y">유가</option>
                            <option value="K">코스닥</option>
                            <option value="N">코넥스</option>
                            <option value="E">기타</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">종목명<span class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" name="company_name" placeholder="" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">종목명(영어)</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" name="company_name_e" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">종목명(축약)</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" name="company_name_i" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">종목코드</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" name="crp_cd" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">업종<span class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="form-control" name="business_seq">
                            <?php foreach ($lists as $row) { ?>
                                <option value="<?= $row['business_seq'] ?>"><?= $row['description'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">법인등록번호</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" name="crp_no" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">사업자등록번호</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" name="bsn_no" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">2차 분류어 사용</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div id="diff" class="btn-group" data-toggle="buttons">
                            <label class="btn btn-primary">
                                <input type="radio" name="diff" value="use">&nbsp; 사용 &nbsp;
                            </label>
                            <label class="btn btn-primary active">
                                <input type="radio" name="diff" value="unuse" checked="checked">미사용
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">1차 분류어</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group">
                            <input type="text" class="form-control" id="keyword">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-primary add_option_btn">추가</button>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-offset-3 col-md-6">
                        <div class="well add_ipt_area">
                        </div>
                    </div>
                </div>	
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                        <button type="button" class="btn btn-default" onclick="location.href = '/index/option'">취소</button>
                        <button type="submit" class="btn btn-primary">등록</button>
                    </div>
                </div>

            </form>

            <!-- 삭제모달 -->
            <div id="allDelModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">삭제경고</h4>
                        </div>
                        <div class="modal-body">
                            <p>
                                분류어를 삭제하시겠습니까?
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                            <button type="button" class="btn btn-primary antosubmit" onclick="delRow();">삭제처리</button>
                            <input type="hidden" id="idx" value="">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- /page content -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
                                $(document).ready(function () {
                                    $('.tags').tagsInput({
                                        width: 'auto',
                                        height: 'auto'
                                    });

                                    $(".add_ipt_area").sortable({
                                        revert: true
                                    });

                                    var i = $(".add_ipt_area .input-group .input-group-addon").length;

                                    $(".add_option_btn").click(function () {

                                        var keyword = $("#keyword").val();
                                        if (!$.trim(keyword)) {
                                            alert("분류어를 입력해주세요.");
                                            $("#keyword").focus();
                                            return false;
                                        } else {
                                            i++;
                                            var index = i;
                                            var data = {keyword: keyword, index: index};
                                            $.post("/index.php/dataFunction/keyword_add_html", data, function (data) {
                                                $(".well").append(data);
                                                $("#keyword").val('');

                                                $(".del_row_btn").click(function () {
                                                    $("#allDelModal").modal();
                                                    $("#idx").val($(this).index('.del_row_btn'));
//                        $(this).parent().parent().remove();
                                                });

                                                $(".add_sub_row").click(function () {
                                                    var index = $(this).val();
                                                    var html = '<div class="add_ipt_row input-group"><input type="text" class="tags form-control" name="keyword_row[' + index + '][]" required>';
                                                    html += '<span class="input-group-btn">';
                                                    html += '<button type="button" class="btn btn-default del_sub_row" style="display: block;">-</button>';
                                                    html += '</span></div>';
                                                    $(this).parent().parent().parent().append(html);

                                                    $('.tags').tagsInput({
                                                        width: 'auto'
                                                    });

                                                    $(".del_sub_row").click(function () {
                                                        $(this).parent().parent().remove();
                                                    });

                                                });

                                                $('.tags').tagsInput({
                                                    width: 'auto'
                                                });

                                            });
                                        }
                                    });
                                });

                                function delRow() {
                                    var index = $("#idx").val();
                                    $(".del_row_btn:eq(" + index + ")").parent().parent().remove();
                                    $("#allDelModal").modal('hide');
                                }
</script>