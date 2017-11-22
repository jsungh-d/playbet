<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <h3>2차 분류어 <small>2차 분류를 하는 상위 종목에 대한 분류어입니다.</small></h3>
    </div>

    <form class="form-horizontal form-label-left">
        <div class="x_panel">
            <div class="x_content">
                <br />
                <div class="form-group">
                    <div class="form-inline">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">시점</label>
                        <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            <div class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </div>
                            <input type="text" class="form-control" id="sdate" placeholder="날짜지정">
                        </div>
                        <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            <div class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </div>
                            <input type="text" class="form-control" id="edate" placeholder="날짜지정">
                        </div>
                    </div>
                </div>
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
            <div class="col-sm-6 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        <table id="keyword_allTable1" class="table table-bordered bulk_action">
                            <thead>
                                <tr>
                                    <th>종목명</th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        <table id="keyword_allTable2" class="table table-bordered bulk_action">
                            <input type="hidden" id="stock_seq" value="">
                            <colgroup><col width="30px"><col width="*"></colgroup>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="check-all"></th>
                                    <th>2차 분류어</th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                        </table>
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
                                            선택하신 키워드를 삭제하시겠습니까?
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

                        <!-- 키워드추가모달1 -->
                        <div id="allAddModal1" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">키워드 추가</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row form-group">
                                            <label class="col-sm-3 control-label">키워드명</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="addKeyWordInput" class="form-control">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                        <button onclick="addKeyWord();" type="button" class="btn btn-primary antosubmit">추가</button>
                                        <input type="hidden" id="addKeyword" value="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 키워드추가모달2 -->
                        <div id="allAddModal2" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">키워드 추가</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>
                                            입력한 키워드를 추가하시겠습니까?
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                        <button type="button" class="btn btn-primary antosubmit" onclick="keyWordSubmit();">추가</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </form>
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

        var table = $('#keyword_allTable1').dataTable({
            dom: '<"datatable_header"fl>t<"datatable_footer"p>',
            ajax: '/index.php/dataFunction/stock_keyword',
            columns: [
                {data: "company_name_i"}
            ],
            order: [],
            columnDefs: [{
                    orderable: true,
                    className: 'cursor',
                    targets: 0
                }
            ],
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

        $('#keyword_allTable1 tbody').on('click', 'td.cursor', function () {
            var index = $(this).index('#keyword_allTable1 tbody td.cursor');
            var stock_seq = $(".stock_seq:eq(" + index + ")").val();
            $("#stock_seq").val(stock_seq);

            $("#keyword_allTable1 tbody tr").removeClass('clicked');
            $("#keyword_allTable1 tbody tr:eq(" + index + ")").addClass('clicked');

            table2.fnReloadAjax('/index.php/dataFunction/stock_keyword2?stock_seq=' + stock_seq + '');
        });

        var table2 = $('#keyword_allTable2').dataTable({
            dom: '<"datatable_header"fl>t<"datatable_footer"Bp>',
            columns: [
                {data: "checkbox"},
                {data: "keyword"}
            ],
            columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }],
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
                            $("#allDelModal").modal();
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
                            $("#allAddModal1").modal();
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

        $('input.optionSearchText').typeahead({
            source: function (query, process) {
                return $.get('/index.php/dataFunction/newsOptionSearchAutoComplete', {query: query}, function (data) {
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

            table.fnReloadAjax('/index.php/dataFunction/stock_keyword?sdate=' + sdate + '&edate=' + edate + '&text=' + text + '');
        });

        $("#search").keydown(function (key) {
            var text = $("#search").val();
            var check = $('.timeChk:checked').val();
            var sdate = $("#sdate").val();
            var edate = $("#edate").val();
            if (key.keyCode == 13) {
                table.fnReloadAjax('/index.php/dataFunction/stock_keyword?sdate=' + sdate + '&edate=' + edate + '&text=' + text + '');
            }
        });
    });

    function addKeyWord() {
        var value = $("#addKeyWordInput").val();

        if (!$.trim(value)) {
            alert("키워드를 입력하세요.");
            return false;
        } else {
            $("#addKeyword").val(value);
            $('#allAddModal2').modal();
        }
    }

    function keyWordSubmit() {
        var value = $("#addKeyword").val();
        var stock_seq = $("#stock_seq").val();

        var data = {stock_seq: stock_seq, value: value, keyword_type: 2};

        $.ajax({
            dataType: 'text',
            url: '/index.php/dataFunction/addKeyWord',
            data: data,
            type: 'POST',
            success: function (data, status, xhr) {
                if (data == 'SUCCESS') {
                    alert("키워드 추가 되었습니다.");
                    $('#allAddModal2').modal('hide');
                    $("#allAddModal1").modal('hide');
                    var table2 = $('#keyword_allTable2').dataTable();
                    table2.fnReloadAjax('/index.php/dataFunction/stock_keyword2?stock_seq=' + stock_seq + '');
                } else {
                    alert("데이터 처리오류!!");
                    return false;
                }
            }
        });
    }

    function delSubmit() {
        var data = {idx: $("#idxs").val(), keyword_type: 2};
        var stock_seq = $("#stock_seq").val();
        $.ajax({
            dataType: 'text',
            url: '/index.php/dataFunction/delKeyWord',
            data: data,
            type: 'POST',
            success: function (data, status, xhr) {
                alert("삭제 되었습니다.");
                $("#allDelModal").modal('hide');
                $("#check-all").attr('checked', false);
                var table2 = $('#keyword_allTable2').dataTable();
                table2.fnReloadAjax('/index.php/dataFunction/stock_keyword2?stock_seq=' + stock_seq + '');
            }
        });
    }
</script>