<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_full">
            <h3>배제어 <small>종목, 키워드 분류시 배제해야 하는 단어를 관리합니다.</small></h3>
        </div>
    </div>

    <div class="x_panel">
        <div class="x_title">
            <h2>배제어 관리 <small>배제어를 추가, 삭제 할 수 있습니다.</small></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form class="form-horizontal form-label-left" method="post" onsubmit="chkForm(this); return false;" action="/index.php/dataFunction/insExclude">
                <div class="form-group">
                    <label class="control-label col-xs-3" for="exSel">구분
                    </label>
                    <div class="col-xs-6">
                        <div class="form-inline">
                            <select id="exSel" class="form-control" name="kind">
                                <option value="ALL">전체</option>
                                <option value="Y">코스피</option>
                                <option value="K">코스닥</option>
                                <option value="N">코넥스</option>
                            </select>
                            <select id="stock_seq" class="select2 form-control" name="stock_seq" style="width: 200px;">
                                <option></option>
                            </select>
                            <span id="crp_cd" style="display: none;">123456</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-xs-3" for="exAdd">배제어
                    </label>
                    <div class="col-xs-6">
                        <input type="text" id="tags_1" class="tags form-control" name="stopkeywords" placeholder="" required>
                        여러 키워드 입력 가능, (콤마)로 구분, 띄어쓰기 없이 입력
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-xs-6 col-xs-offset-3">
                        <button class="btn btn-default" type="button" onclick="location.href='/index/keyword_exclude'">취소</button>
                        <button type="submit" class="btn btn-primary">저장</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function () {

        var s2 = $(".select2").select2({
            placeholder: "Select a state",
            allowClear: true
        });

        $(".select2").hide();

        $("#exSel").change(function () {
            if ($(this).select().val() === 'ALL') {
                $("#stock_seq").attr('required', false);
                $(".select2").hide();
                $("#crp_cd").hide();
            } else {
                $("#stock_seq").attr('required', true);
                $(".select2").show();
                $("#crp_cd").show();
                $("#crp_cd").html('');

                s2.html('');
                s2.trigger('change');

                var data = {kind: $(this).select().val()};
                $.ajax({
                    dataType: 'json',
                    url: '/index.php/dataFunction/stockList',
                    data: data,
                    type: 'POST',
                    success: function (data, status, xhr) {
                        s2.append('<option value=""></option>');
                        for (var i = 0; i < data.length; i++) {
                            s2.append('<option value="' + data[i].id + '">' + data[i].text + '</option>');
                        }
                        s2.trigger('change');
                    }
                });
            }
        });

        $("#stock_seq").change(function () {
            var value = $(this).select().val();
            if (value) {
                var data = {stock_seq: value};
                $.ajax({
                    dataType: 'text',
                    url: '/index.php/dataFunction/getStockCode',
                    data: data,
                    type: 'POST',
                    success: function (data, status, xhr) {
                        $("#crp_cd").html(data);
                    }
                });
            } else {
                $("#crp_cd").html('');
            }
        });
    });

    function chkForm(obj) {
        if ($("#exSel").select().val() === 'ALL') {
            obj.submit();
        } else {
            var stock_seq = $("#stock_seq").select().val();
            var data = {stock_seq: stock_seq};
            $.ajax({
                dataType: 'text',
                url: '/index.php/dataFunction/chk_exclude',
                data: data,
                type: 'POST',
                success: function (data, status, xhr) {
                    if (data === 'DUPLE') {
                        alert("이미 등록된 종목입니다.");
                        return false;
                    } else {
                        obj.submit();
                    }
                }
            });
        }
    }
</script>