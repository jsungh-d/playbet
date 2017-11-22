<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_full">
            <h3>유의어 <small>종목명 유의어를 등록, 관리합니다.</small></h3>
        </div>
    </div>

    <div class="x_panel">
        <div class="x_content">
            <br />
            <div class="form-group">
                <div class="form-inline">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">종목명</label>
                    <div class="input-group">
                        <input type="text" id="search" class="form-control optionSearchText" placeholder="">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" id="searchBtn" type="button">검색</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4">
            <div class="x_panel">
                <div class="x_content">
                    <table id="keyword_synonymTable1" class="table table-bordered bulk_action">
                        <colgroup><col width="30px"><col width="*"></colgroup>
                        <thead>
                            <tr>
                                <th>종목명</th>
                            </tr>
                        </thead>

                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>

        <div class="col-xs-8">
            <div class="x_panel">
                <div class="x_content">
                    <table id="keyword_synonymTable2" class="table table-bordered bulk_action">
                        <input type="hidden" id="stock_seq" value="">
                        <colgroup><col width="30px"><col width="*"></colgroup>
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check-all"></th>
                                <th>유의어</th>
                            </tr>
                        </thead>

                        <tbody></tbody>
                    </table>


                    <!--유의어삭제모달-->
                    <div id="synonymDelModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">삭제경고</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        유의어를 삭제하시겠습니까?
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                    <button type="button" class="btn btn-primary antosubmit" onclick="delSubmit();">삭제처리</button>
                                    <input type="hidden" id="idxs" value="">
                                </div>
                            </div>

                        </div>
                    </div>


                    <!--유의어추가모달-->
                    <div id="synonymIntModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">유의어 추가</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-horizontal">
                                        <div class="row form-group">
                                            <label class="col-sm-3 control-label">유의어 등록</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="addKeyWordInput" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                    <button type="button" onclick="keyWordSubmit();" class="btn btn-primary antosubmit">추가</button>
                                </div>
                            </div>

                        </div>
                    </div>



                    <!--유의어수정모달-->
                    <div id="synonymFixModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">유의어 수정</h4>
                                </div>
                                <div class="modal-body">
                                    <input type="text" id="modTextInput" class="form-control">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                    <button type="button" class="btn btn-primary antosubmit" onclick="modKeyWord();">수정</button>
                                    <input type="hidden" id="keyword_seq" value="">
                                </div>
                            </div>

                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $("#check-all").click(function () {
            //만약 전체 선택 체크박스가 체크된상태일경우 
            if ($("#check-all").prop("checked")) {
                //해당화면에 전체 checkbox들을 체크해준다 
                $(".row_check").prop("checked", true);
            } else {
                //해당화면에 모든 checkbox들의 체크를해제시킨다. 
                $(".row_check").prop("checked", false);
            }
        });

        var table = $('#keyword_synonymTable1').dataTable({
            processing: true,
            serverSide: true,
            dom: '<"datatable_header"fl>t<"datatable_footer"p>',
            ajax: {
                url: '/index.php/dataFunction/stock_keyword',
                dataSrc: function (json) {
                    json.draw = json.data.draw;
                    json.recordsTotal = json.data.recordsTotal;
                    json.recordsFiltered = json.data.recordsFiltered;
                    if (json.data.data) {
                        return json.data.data;
                    }
                }
            },
            columns: [
                {data: "company_name_i"}
            ],
            order: [],
            columnDefs: [{
                    orderable: true,
                    className: 'cursor',
                    targets: 0
                }],
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            language: {
                lengthMenu: "_MENU_",
                search: "",
                paginate: {
                    "next": "&raquo;",
                    "previous": "&laquo;"
                }
            }
        });

        $('#keyword_synonymTable1 tbody').on('click', 'td.cursor', function () {
            var index = $(this).index('#keyword_synonymTable1 tbody td.cursor');
            var stock_seq = $(".stock_seq:eq(" + index + ")").val();
            $("#stock_seq").val(stock_seq);
            $("#keyword_synonymTable1 tbody tr").removeClass('clicked');
            $("#keyword_synonymTable1 tbody tr:eq(" + index + ")").addClass('clicked');

            table2.fnReloadAjax('/index.php/dataFunction/stock_keyword1?stock_seq=' + stock_seq + '');
        });

        var table2 = $('#keyword_synonymTable2').dataTable({
            dom: '<"datatable_header"fl>t<"datatable_footer"Bp>',
            columns: [
                {data: "checkbox"},
                {data: "keyword"}
            ],
            columnDefs: [
                {
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                },
                {
                    className: 'cursor',
                    targets: 1
                }
            ],
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            order: [],
            buttons: [
                {
                    className: 'btn btn-sm btn-primary',
                    text: '삭제',
                    action: function (e, dt, node, config) {
                        var check = $('.row_check:checked').length;
                        var check_number = "";
                        if (check == 0) {
                            alert('리스트를 체크 해주세요.');
                            return false;
                        } else {
                            $(".row_check:checked").each(function (index) {
                                check_number += $(this).val() + ",";
                            });
                            var appNum = check_number.substr(0, check_number.length - 1);
                            $("#idxs").val(appNum);
                            $("#synonymDelModal").modal();
                        }
                    }
                },
                {
                    className: 'btn btn-sm btn-primary',
                    text: '추가',
                    action: function (e, dt, node, config) {
                        var stock_seq = $("#stock_seq").val();

                        if (!stock_seq) {
                            alert("종목을 선택하세요.");
                            return false;
                        } else {
                            $("#synonymIntModal").modal();
                        }
                    }
                }
            ],
            language: {
                lengthMenu: "_MENU_",
                search: "",
                paginate: {
                    "next": "&raquo;",
                    "previous": "&laquo;"
                }
            }
        });

        $('#keyword_synonymTable2 tbody').on('click', 'td.cursor', function () {
            var index = $(this).index('#keyword_synonymTable2 tbody td.cursor');
            var idx = $(".row_check:eq(" + index + ")").val();
            var text = $("#keyword_synonymTable2 tbody td.cursor:eq(" + index + ")").text();

            $("#modTextInput").val(text);
            $("#keyword_seq").val(idx);

            $("#synonymFixModal").modal();
        });

        $('input.optionSearchText').typeahead({
            source: function (query, process) {
                return $.get('/index.php/dataFunction/optionSearchAutoComplete', {query: query}, function (data) {
//                    console.log(data);
                    data = $.parseJSON(data);
                    return process(data);
                });
            }
        });

        $('#searchBtn').click(function () {
            var text = $("#search").val();
            var check = $('.timeChk:checked').val();
            var sdate = $("#sdate").val();
            var edate = $("#edate").val();

            table.fnReloadAjax('/index.php/dataFunction/stock_keyword?text=' + text + '');
        });

        $("#search").keydown(function (key) {
            var text = $("#search").val();
            var check = $('.timeChk:checked').val();
            var sdate = $("#sdate").val();
            var edate = $("#edate").val();
            if (key.keyCode == 13) {
                table.fnReloadAjax('/index.php/dataFunction/stock_keyword?text=' + text + '');
            }
        });

    });

    function keyWordSubmit() {
        var value = $("#addKeyWordInput").val();
        var stock_seq = $("#stock_seq").val();

        if (!$.trim(value)) {
            alert("키워드를 입력하세요.");
            return false;
        } else {

            var data = {stock_seq: stock_seq, value: value, keyword_type: 1};

            $.ajax({
                dataType: 'text',
                url: '/index.php/dataFunction/addKeyWord',
                data: data,
                type: 'POST',
                success: function (data, status, xhr) {
                    if (data == 'SUCCESS') {
                        alert("유의어 추가 되었습니다.");
                        $("#synonymIntModal").modal('hide');
                        $("#stock_seq").val(stock_seq);
                        var table2 = $('#keyword_synonymTable2').dataTable();
                        table2.fnReloadAjax('/index.php/dataFunction/stock_keyword1?stock_seq=' + stock_seq + '');
                    } else {
                        alert("데이터 처리오류!!");
                        return false;
                    }
                }
            });
        }
    }

    function delSubmit() {
        var data = {idx: $("#idxs").val(), keyword_type: 1};
        $.ajax({
            dataType: 'text',
            url: '/index.php/dataFunction/delKeyWord',
            data: data,
            type: 'POST',
            success: function (data, status, xhr) {
                alert("삭제 되었습니다.");
                $("#synonymDelModal").modal('hide');
                var stock_seq = $("#stock_seq").val();
                $("#stock_seq").val(stock_seq);
                $("#check-all").attr('checked', false);
                var table2 = $('#keyword_synonymTable2').dataTable();
                table2.fnReloadAjax('/index.php/dataFunction/stock_keyword1?stock_seq=' + stock_seq + '');
            }
        });
    }

    function modKeyWord() {
        var value = $("#modTextInput").val();
        var keyword_seq = $("#keyword_seq").val();

        var data = {keyword_seq: keyword_seq, value: value};

        $.ajax({
            dataType: 'text',
            url: '/index.php/dataFunction/modKeyWord',
            data: data,
            type: 'POST',
            success: function (data, status, xhr) {
                if (data == 'SUCCESS') {
                    alert("유의어 수정 되었습니다.");
                    location.reload();
                } else {
                    alert("데이터 처리오류!!");
                    return false;
                }
            }
        });
    }
</script>