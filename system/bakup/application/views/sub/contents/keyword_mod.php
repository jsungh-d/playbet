<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <h3>사전 수정 <small>새로운 단어(이슈 단어)를 사전으로 등록, 관리합니다.</small></h3>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        <br />
                        <form class="form-horizontal form-label-left" method="post" onsubmit="chkForm(this);return false;" action="/index.php/dataFunction/mod_dictionary2">
                            <input type="hidden" name="idx" value="<?= $this->uri->segment(3) ?>">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="x_panel">
                                        <div class="x_content">
                                            <table class="table table-bordered dataTable">
                                                <tbody>
                                                    <tr>
                                                        <th rowspan="2">동의어</th>
                                                        <td>
                                                            <input type="text" id="tags_1" class="tags form-control" name="synonym" value="<?= $info->synonym?>" placeholder="" style="display: inline-block; width:30%"><br>
                                                            여러 키워드 입력 가능, (콤마)로 구분, 띄어쓰기 없이 입력
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th>*단어명</th>
                                                        <td>
                                                            <input type="text" id="keyword" class="form-control" name="keyword" value="<?= $info->keyword?>" placeholder="" style="display: inline-block; width:30%" readonly>
                                                        </td>
                                                    </tr>
                                                </tbody> 
                                            </table>
                                            <div class="datatable_footer">
                                                <div class="pull-right btn-group">
                                                    <button type="button" class="btn btn-default btn-sm btn-warning" onclick="location.href = '/index/keyword_prev'">
                                                        취소
                                                    </button>
                                                    <button type="submit" class="btn btn-default btn-sm btn-primary">
                                                        수정
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

<!-- /page content -->
<script type="text/javascript">

    function chkForm(obj) {
        obj.submit();
//        var keyword = $("#keyword").val();
//        var data = {keyword: keyword};
//        $.ajax({
//            dataType: 'text',
//            url: '/index.php/dataFunction/chk_keyword',
//            data: data,
//            type: 'POST',
//            success: function (data, status, xhr) {
//                if (data == 'DUPLE') {
//                    alert("이미 등록된 단어명입니다.");
//                    $('#keyword').val('');
//                    return false;
//                } else {
//                    
//                }
//            }
//        });
    }
</script>