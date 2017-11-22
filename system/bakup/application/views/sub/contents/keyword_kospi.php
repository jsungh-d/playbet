<style type="text/css">
    .dataTables_scrollHeadInner {width: auto !important;}
    .dataTables_scrollBody {margin-bottom: 6px;}
</style>

<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <h3>메트릭스 <small>종목별 분석된 키워드를 관리합니다.(키워드메트릭스)</small></h3>
    </div>

    <div class="x_panel">
        <div class="x_content">
            <br />
            <div class="form-horizontal form-label-left">
                <div class="form-group">
                    <div class="form-inline">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">시점</label>
                        <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            <div class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </div>
                            <input type="text" class="form-control" id="sdate" value="" placeholder="날짜지정">
                        </div>
                        <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            <div class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </div>
                            <input type="text" class="form-control" id="edate" value="" placeholder="날짜지정">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-inline">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">종목명</label>

                        <select id="type" class="form-control">
                            <option value="">전체</option>
                            <option value="Y">유가</option>
                            <option value="K">코스닥</option>
                            <option value="N">코넥스</option>
                            <option value="E">기타</option>
                        </select>

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
    </div>

    <div class="row">
        <div class="col-xs-2 custom_table_header">
            <div class="x_panel">
                <div class="x_content">
                    <table id="keyword_kospiTable1" class="table table-bordered bulk_action" style="margin-top: 0 !important;">
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

        <div class="col-xs-4 custom_table_header">
            <div class="x_panel">
                <div class="x_content">
                    <table id="keyword_kospiTable2" class="table table-bordered bulk_action" style="margin-top: 0 !important;">
                        <colgroup><col width="30px"><col width="*"></colgroup>
                        <thead>
                            <tr>
                                <th style="font-size:11px; height: 34px; padding-top:0;padding-bottom: 0px;vertical-align: middle"><input type="checkbox" id="check-all1"></th>
                                <th style="font-size:11px; height: 34px; padding-top:0;padding-bottom: 0px;vertical-align: middle">종목 분석 <br>키워드</th>
                                <th style="font-size:11px; height: 34px; padding-top:0;padding-bottom: 0px;vertical-align: middle">뉴스 건수 <br>(종목)</th>
                                <th style="font-size:11px; height: 34px; padding-top:0;padding-bottom: 0px;vertical-align: middle">언급 량 <br>(종목)</th>
                                <th style="font-size:11px; height: 34px; padding-top:0;padding-bottom: 0px;vertical-align: middle">언급 량 <br>(전체)</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>


                    <!-- 노출모달 -->
                    <div id="allExpoModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">노출 설정</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        선택하신 키워드를 노출하시겠습니까?
                                        <input type="hidden" id="expo_keyword" value="">
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                    <button type="button" class="btn btn-primary antosubmit" id="expoSubmit">처리</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 비노출모달 -->
                    <div id="allUnexpoModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">비노출 설정</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        선택하신 키워드를 비노출 처리하겠습니까?
                                        <input type="hidden" id="unexpo_keyword" value="">
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                    <button type="button" class="btn btn-primary antosubmit" id="unexpoSubmit">처리</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--완전삭제모달-->
                    <div id="kospiAllDelModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">삭제경고</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        전체 삭제하시겠습니까?
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                    <button type="button" class="btn btn-primary antosubmit">삭제처리</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--키워드삭제모달-->
                    <div id="kospiDelModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">삭제경고</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        키워드를 삭제하시겠습니까?
                                    </p>
                                    <input type="checkbox" id="delAllCheckBox" style="margin-top: 20px;">전체삭제
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                    <button type="button" id="delNewKeyWord" class="btn btn-primary antosubmit">삭제처리</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--동의어연결모달-->
                    <div id="kospiConnectModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">동의어 연결</h4>
                                </div>
                                <div class="modal-body">
                                    '<span id="connect_title">이재</span>'를
                                    <input type="text" id="synonym1" class="form-control" style="width:40%; display: inline-block;">
                                    연결하시겠습니까?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                    <button type="button" class="btn btn-primary antosubmit" id="connect_btn1" value="">연결</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--동의어연결모달 연결1-->
                    <div id="kospiConnectModal2" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">동의어 연결</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        <span id="connect_title2">이제</span>를 <span id="connect_title2_1">이재용</span> 단어에 연결하였습니다.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose connect_btn">닫기</button>
                                    <button type="button" class="btn btn-primary antosubmit connect_btn">확인</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--동의어연결모달 연결2-->
                    <div id="kospiConnectModal2_1" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">동의어 연결</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        <span id="connect_title2_11">이재</span>는 이미 <span id="connect_title2_12">이재용</span>으로 연결되어있습니다.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--동의어연결모달 연결3-1-->
                    <div id="kospiConnectModal3" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">동의어 연결</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        "<span id="connect_title3">이재용</span>" 단어가 등록 되 있지 않습니다. 새로운 단어로 등록하시겠습니까?
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                    <button type="button" class="btn btn-primary antosubmit" id="connect_btn3">등록</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--동의어연결모달 연결3-2-->
                    <div id="kospiConnectModal4" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">동의어 연결</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        "<span id="connect_title4">이재용</span>" 단어를 등록하고 <span id="connect_title4_1">이재</span>를 <span id="connect_title4_2">이재용</span> 단어에 연결하였습니다.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose connect_btn">닫기</button>
                                    <button type="button" class="btn btn-primary antosubmit connect_btn">확인</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-3 custom_table_header">
            <div class="x_panel">
                <div class="x_content">
                    <table id="keyword_kospiTable3" class="table table-bordered bulk_action">
                        <colgroup><col width="30px"><col width="*"></colgroup>
                        <thead>
                            <tr>
                                <th>번호</th>
                                <th><input type="checkbox" id="check-all2"></th>
                                <th>신규 노출 키워드</th>
                                <th>색상</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="addNewKeyWordModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">노출키워드</h4>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="addNewKeyWord" class="form-control" style="width:40%; display: inline-block;">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default antoclose" data-dismiss="modal">취소</button>
                        <button type="button" id="addNewKeyWordBtn" class="btn btn-primary antosubmit">추가</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-3 custom_table_header">
            <div class="x_panel">
                <div class="x_content">
                    <table id="keyword_kospiTable4" class="table table-bordered bulk_action">
                        <colgroup><col width="30px"><col width="*"></colgroup>
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check-all3"></th>
                                <th>현재 노출 키워드</th>
                                <th>색상</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="stock_seq" value="">
    <input type="hidden" id="crp_cd" value="">
</div>
<style>
    .unexpo_line{background-color: red !important;}
</style>
<!--<script type="text/javascript" src="dataTables.scrollingPagination.js"></script>-->

<script type="text/javascript">
    $(document).ready(function () {

        $("#check-all1").click(function () {
            //만약 전체 선택 체크박스가 체크된상태일경우 
            if ($("#check-all1").prop("checked")) {
                //해당화면에 전체 checkbox들을 체크해준다 
                $(".row_checkbox").prop("checked", true);
            } else {
                //해당화면에 모든 checkbox들의 체크를해제시킨다. 
                $(".row_checkbox").prop("checked", false);
            }
        });
        $("#check-all2").click(function () {
            //만약 전체 선택 체크박스가 체크된상태일경우 
            if ($("#check-all2").prop("checked")) {
                //해당화면에 전체 checkbox들을 체크해준다 
                $(".row_checkbox2").prop("checked", true);
                $("#keyword_kospiTable3 tbody tr").addClass('selected');
            } else {
                //해당화면에 모든 checkbox들의 체크를해제시킨다. 
                $(".row_checkbox2").prop("checked", false);
                $("#keyword_kospiTable3 tbody tr").removeClass('selected');
            }
        });
        $("#check-all3").click(function () {
            //만약 전체 선택 체크박스가 체크된상태일경우 
            if ($("#check-all3").prop("checked")) {
                //해당화면에 전체 checkbox들을 체크해준다 
                $(".row_checkbox3").prop("checked", true);
            } else {
                //해당화면에 모든 checkbox들의 체크를해제시킨다. 
                $(".row_checkbox3").prop("checked", false);
            }
        });

        var table1 = $('#keyword_kospiTable1').dataTable({
            scrollY: "600px",
            scrollCollapse: true,
            pagingType: "simple",
            dom: '<"datatable_header"f>t<"datatable_footer"p>',
            ajax: '/index.php/dataFunction/keywordStockLists',
            pageLength: 50,
            columns: [
                {data: "company_name_i"}
            ],
            order: [],
            columnDefs: [{
                    orderable: true,
                    className: 'cursor',
                    targets: 0
                }],
            language: {
                lengthMenu: "_MENU_",
                search: "",
                paginate: {
                    "next": "&raquo;",
                    "previous": "&laquo;"
                }
            }
        });

        var table2 = $('#keyword_kospiTable2').dataTable({
            dom: '<"datatable_header"f>t<"datatable_footer"B>',
            scrollY: "600px",
            scrollCollapse: true,
            paging: false,
            //ajax: '/index.php/dataFunction/keywordStockLists2',
            columns: [
                {data: "checkbox"},
                {data: "related_keyword"},
                {data: "article_cnt"},
                {data: "mention_cnt"},
                {data: "_rank"}
            ],
            language: {
                lengthMenu: "_MENU_",
                search: "",
                paginate: {
                    "next": "&raquo;",
                    "previous": "&laquo;"
                }
            },
            columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }],
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            order: [[1, 'asc']],
            buttons: [
                {
                    className: 'btn btn-sm btn-primary',
                    text: '노출',
                    action: function (e, dt, node, config) {
                        expo();
                    }
                },
                {
                    className: 'btn btn-sm btn-primary',
                    text: '미노출',
                    action: function (e, dt, node, config) {
                        unexpo();
                    }
                },
                {
                    className: 'btn btn-sm btn-primary',
                    text: '<i class="fa fa-chevron-right"></i>',
                    action: function (e, dt, node, config) {
                        var check = $('.row_checkbox:checked').length;
                        var check2 = $('.row_checkbox2').length;
                        var check_number = "";
                        if (check == 0) {
                            alert('키워드를 체크 해주세요.');
                            return false;
                        } else {
                            if (parseInt(check) > parseInt(14)) {
                                alert('키워드는 14개 이상 선택 불가합니다.');
                                return false;
                            }

                            if (parseInt(check2) + parseInt(check) >= parseInt(15)) {
                                alert('선택키워드와 신규노출의 합이 14개 이상 등록되어있습니다.');
                                return false;
                            }

                            var stock_seq = $("#stock_seq").val();
                            var crp_cd = $("#crp_cd").val();
//                            table3.clear().draw();
                            var i = 1;
                            $(".row_checkbox:checked").each(function (index) {
                                check_number += "'" + $(this).val() + "',";
                                table3.row.add([
                                    i,
                                    '<input type="checkbox" class="row_checkbox2" value="' + $(this).val() + '"><input type="hidden" class="type" value="0">',
                                    '' + $(this).val() + '',
                                    ''
                                ]).draw(false);
                                i++;
                            });
                            $(".row_checkbox").attr('checked', false);
                            $("#check-all1").attr('checked', false);

                            $(".row_checkbox2").click(function () {
                                if ($(this).is(":checked")) {
                                    $(this).parent().parent().addClass('selected');
                                } else {
                                    $(this).parent().parent().removeClass('selected');
                                }
                            });
                        }
                    }
                },
                {
                    className: 'btn btn-sm btn-primary',
                    text: '동의어연결',
                    action: function (e, dt, node, config) {
                        var check = $('.row_checkbox:checked').length;
                        var check_number = "";
                        if (check == 0) {
                            alert('키워드를 체크 해주세요.');
                            return false;
                        } else {
                            if (check > 1) {
                                alert('키워드를 하나만체크 해주세요.');
                                return false;
                            } else {
                                $(".row_checkbox:checked").each(function (index) {
                                    $("#connect_title").html($(this).val());
                                    $("#connect_btn1").val($(this).val());
                                });

                                $("#kospiConnectModal").modal();
                            }
                        }
                    }
                }

            ],
            createdRow: function (row, data, dataIndex) {
//                console.log(row);
                if (data['created_on']) {
                    $(row).addClass('unexpo_line');
                }
//                console.log(data['created_on']);
//                console.log(dataIndex);
            }
        });

        $("#connect_btn1").click(function () {
            var keyword = $(this).val();
            var synonym = $("#synonym1").val();

            if (!$.trim(synonym)) {
                alert("단어를 입력해주세요.");
                return false;
            }

            var data = {keyword: synonym, synonym: keyword};
            $.ajax({
                dataType: 'text',
                url: '/index.php/dataFunction/chk_dictionary',
                data: data,
                type: 'POST',
                success: function (data, status, xhr) {
                    if (data == 'NO_MATCH') {

                        $("#connect_title3").html(synonym);
                        $("#kospiConnectModal3").modal();

                    } else if (data == 'SUCCESS') {

                        $("#connect_title2").html(keyword);
                        $("#connect_title2_1").html(synonym);
                        $("#kospiConnectModal2").modal();

                    } else if (data == 'DUPLE') {
                        $("#connect_title2_11").html(keyword);
                        $("#connect_title2_12").html(synonym);
                        $("#kospiConnectModal2_1").modal();

                    } else {
                        alert('데이터 처리오류!');
                    }
                }
            });
        });

        $("#connect_btn3").click(function () {
            var keyword = $("#connect_btn1").val();
            var synonym = $("#synonym1").val();

            var data = {keyword: keyword, synonym: synonym};
            $.ajax({
                dataType: 'text',
                url: '/index.php/dataFunction/ins_dictionary',
                data: data,
                type: 'POST',
                success: function (data, status, xhr) {
                    if (data == 'SUCCESS') {
                        $("#connect_title4").html(synonym);
                        $("#connect_title4_1").html(keyword);
                        $("#connect_title4_2").html(synonym);
                        $("#kospiConnectModal4").modal();
                        $("#synonym1").val('');
                    } else if (data == 'FAILED') {
                        alert('데이터 처리오류!');
                    }
                }
            });
        });

        $(".connect_btn").click(function () {
            $("#kospiConnectModal4").modal('hide');
            $("#kospiConnectModal3").modal('hide');
            $("#kospiConnectModal2_1").modal('hide');
            $("#kospiConnectModal2").modal('hide');
            $("#kospiConnectModal").modal('hide');
        });


        $('#keyword_kospiTable1 tbody').on('click', 'td.cursor', function () {
            var index = $(this).index('#keyword_kospiTable1 tbody td.cursor');
            var stock_seq = $(".stock_seq:eq(" + index + ")").val();
            var crp_cd = $(".crp_cd:eq(" + index + ")").val();
            $("#keyword_kospiTable1 tbody tr").removeClass('clicked');
            $("#keyword_kospiTable1 tbody tr:eq(" + index + ")").addClass('clicked');
            table2.fnReloadAjax('/index.php/dataFunction/keywordStockLists2?stock_sql=' + stock_seq + '');
            table3.clear().draw();
            table4.fnReloadAjax('/index.php/dataFunction/keywordStockLists4?stock_seq=' + stock_seq + '');
            $("#crp_cd").val(crp_cd);
            $("#stock_seq").val(stock_seq);
        });
        var table3 = $('#keyword_kospiTable3').DataTable({
            dom: '<"datatable_header"f>t<"datatable_footer"B>',
            paging: false,
            columnDefs: [
                {targets: 0, visible: false},
                {
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 1
                },
                {targets: 2, className: 'cursor'},
                {targets: 3, visible: false}
            ],
            pageLength: 14,
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            order: [[0, 'asc']],
            buttons: [
                {
                    className: 'btn btn-sm btn-primary icon-delete',
                    text: '삭제',
                    action: function (e, dt, node, config) {
                        var check = $('.row_checkbox2:checked').length;
                        var check_number = "";
                        if (check == 0) {
                            alert('키워드를 체크 해주세요.');
                            return false;
                        } else {
                            $("#kospiDelModal").modal();
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
                            if($(".row_checkbox2").length >= 14){
                                alert("이미 14건의 신규노출 키워드가 추가되어있습니다.");
                                return false;
                            }
                            $("#addNewKeyWordModal").modal();
                        }
                    }
                },
                {
                    className: 'btn btn-sm btn-primary',
                    text: '핵심K',
                    action: function (e, dt, node, config) {
                        var check = $('.row_checkbox2:checked').length;
                        var row_cnt = $("#keyword_kospiTable3 tbody td.key_important").length;
                        var check_number = "";
                        if (check == 0) {
                            alert('키워드가 없습니다.');
                            return false;
                        } else {

                            if (parseInt(check) > parseInt(3)) {
                                alert("3개이상선택 할수없습니다.");
                                return false;
                            }

                            if (parseInt(check) > parseInt(3) && (parseInt(row_cnt) + parseInt(check)) > parseInt(3)) {
                                alert("이미3개이상 선택되었습니다.");
                                return false;
                            }

//                            $("#keyword_kospiTable3 tbody td.cursor").removeClass('key_important');
//                            $(".type").val('0');

                            $(".row_checkbox2").each(function (index) {

                                $("#keyword_kospiTable3 tbody td.cursor:eq(" + index + ")").removeClass('key_important');

                                if ($(".row_checkbox2:eq(" + index + ")").is(":checked")) {

                                    if ($('#keyword_kospiTable3 tbody td.cursor:eq(' + index + ')').hasClass('key_issue')) {
                                        $("#keyword_kospiTable3 tbody td.cursor:eq(" + index + ")").removeClass('key_issue');
                                    }

                                    $("#keyword_kospiTable3 tbody td.cursor:eq(" + index + ")").addClass("key_important");
                                    $(".type:eq(" + index + ")").val('1');
                                    $(this).prop('checked', false);
                                }
                            });
                        }
                    }
                },
                {
                    className: 'btn btn-sm btn-primary',
                    text: '이슈K',
                    action: function (e, dt, node, config) {
                        var check = $('.row_checkbox2:checked').length;
                        var row_cnt = $("#keyword_kospiTable3 tbody td.key_issue").length;
                        var check_number = "";
                        if (check == 0) {
                            alert('키워드가 없습니다.');
                            return false;
                        } else {

                            if (parseInt(check) > parseInt(3)) {
                                alert("3개이상선택 할수없습니다.");
                                return false;
                            }

                            if (parseInt(check) > parseInt(3) && (parseInt(row_cnt) + parseInt(check)) > parseInt(3)) {
                                alert("이미3개이상 선택되었습니다.");
                                return false;
                            }

//                            $("#keyword_kospiTable3 tbody td.cursor").removeClass('key_issue');
//                            $(".type").val('0');
                            $(".row_checkbox2").each(function (index) {

                                $("#keyword_kospiTable3 tbody td.cursor:eq(" + index + ")").removeClass('key_issue');

                                if ($(".row_checkbox2:eq(" + index + ")").is(":checked")) {
                                    if ($('#keyword_kospiTable3 tbody td.cursor:eq(' + index + ')').hasClass('key_important')) {
                                        $("#keyword_kospiTable3 tbody td.cursor:eq(" + index + ")").removeClass('key_important');
                                    }

                                    $("#keyword_kospiTable3 tbody td.cursor:eq(" + index + ")").addClass("key_issue");
                                    $(".type:eq(" + index + ")").val('2');
                                }
                            });
                        }
                    }
                },
                {
                    className: 'btn btn-sm btn-primary',
                    text: '적용',
                    action: function (e, dt, node, config) {
                        var check = $('.row_checkbox2').length;
                        var check_number = "";
                        if (check == 0) {
                            alert('키워드가 없습니다.');
                            return false;
                        } else {
                            var stock_seq = $("#stock_seq").val();
                            var type = "";
                            $(".row_checkbox2").each(function (index) {
                                check_number += $(this).val() + ",";
                                if ($("#keyword_kospiTable3 tbody td.cursor:eq(" + index + ")").hasClass('key_important')) {
                                    type += '1,';
                                } else if ($("#keyword_kospiTable3 tbody td.cursor:eq(" + index + ")").hasClass('key_issue')) {
                                    type += '2,';
                                } else {
                                    type += '0,';
                                }
                            });

                            var keyword = check_number.substr(0, check_number.length - 1);
                            var type_data = type.substr(0, type.length - 1);
                            var data = {stock_seq: stock_seq, keyword: keyword, type_data: type_data};
                            $.ajax({
                                dataType: 'text',
                                url: '/index.php/dataFunction/insKeyWord',
                                data: data,
                                type: 'POST',
                                success: function (data, status, xhr) {
                                    if (data == 'SUCCESS') {
                                        $("#check-all2").attr('checked', false);
                                        table4.fnReloadAjax('/index.php/dataFunction/keywordStockLists4?stock_seq=' + stock_seq + '');
                                        table3.clear().draw();
                                    } else {
                                        alert('데이터 처리오류!');
                                    }
                                }
                            });
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
            },
            createdRow: function (row, data, dataIndex) {
                $(row).attr('id', 'row-' + dataIndex);
            },
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            fnCreatedRow: function (nRow, aData, iDataIndex) {
                if (aData[3] == 'key_important') {
                    $(nRow).find('td.cursor').addClass("key_important");
                }

                if (aData[3] == 'key_issue') {
                    $(nRow).find('td.cursor').addClass("key_issue");
                }
            }
        });
        $("#addNewKeyWordBtn").click(function () {
            var value = $("#addNewKeyWord").val();

            var rowCnt = $("#keyword_kospiTable3 tbody tr").length;
            var i = 1;

            if (!$.trim(value)) {
                alert("키워드를 입력하세요.");
                $("#addNewKeyWord").val("");
                return false;
            } else {

                if (rowCnt == 1) {
                    i = 1;
                } else {
                    i = parseInt(rowCnt) + parseInt(1);
                }

                table3.row.add([
                    i,
                    '<input type="checkbox" class="row_checkbox2" value="' + value + '">',
                    '' + value + '',
                    ''
                ]).draw(false);
                $("#addNewKeyWordModal").modal('hide');
                $("#addNewKeyWord").val('');
            }
        });
        $("#delNewKeyWord").click(function () {
            var check = $('.row_checkbox2').length;
            var check_number = "";
            if (check == 0) {
                alert('키워드가 없습니다.');
                return false;
            } else {
                if (!$("#delAllCheckBox").is(":checked")) {
                    table3.rows('.selected').remove().draw();
                    $("#check-all2").prop('checked', false);
                    $(".row_checkbox2").each(function (index) {
                        if ($(".row_checkbox2:eq(" + index + ")").is(":checked")) {
                            console.log(index);
                            table3.row($(".row_checkbox2").index($(".row_checkbox2:eq(" + index + ")"))).remove().draw();
                        }
                    });
                } else {
                    table3.clear().draw();
                }
                $("#check-all2").attr('checked', false);
                $("#delAllCheckBox").attr('checked', false);
                $("#kospiDelModal").modal('hide');
            }
        });
        $('#keyword_kospiTable3 tbody').on('click', 'button.icon-delete', function () {
            table3.row($(this).parents('tr')).remove().draw();
        });
        var table4 = $('#keyword_kospiTable4').dataTable({
            dom: '<"datatable_header"f>t<"datatable_footer"B>',
            paging: false,
            columns: [
                {data: "checkbox"},
                {data: "keyword"},
                {data: "color"}

            ],
            pageLength: 14,
            columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                },
                {
                    className: 'color_row',
                    targets: 1
                },
                {targets: 2, visible: false}
            ],
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            order: [],
            buttons: [
                {
                    className: 'btn btn-sm btn-primary',
                    text: '<i class="fa fa-chevron-left"></i>',
                    action: function (e, dt, node, config) {
                        var check = $('.row_checkbox3:checked').length;
                        var check2 = $('.row_checkbox2').length;
                        var check_number = "";
                        if (check == 0) {
                            alert('키워드를 체크 해주세요.');
                            return false;
                        } else {

                            if (parseInt(check2) + parseInt(check) >= parseInt(15)) {
                                alert('선택키워드와 신규노출의 합이 14개 이상 등록되어있습니다.');
                                return false;
                            }

                            var stock_seq = $("#stock_seq").val();
                            $(".row_checkbox3:checked").each(function (index) {
                                check_number += "'" + $(this).val() + "',";
                            });
                            var keyword = check_number.substr(0, check_number.length - 1);
                            var data = {stock_seq: stock_seq, keyword: keyword};

                            var i = 1;
                            $(".row_checkbox3:checked").each(function (index) {
                                var color = "";
                                if ($(this).hasClass("key_important")) {
                                    color = "key_important";
                                }

                                if ($(this).hasClass("key_issue")) {
                                    color = "key_issue";
                                }

                                check_number += "'" + $(this).val() + "',";
                                table3.row.add([
                                    i,
                                    '<input type="checkbox" class="row_checkbox2" value="' + $(this).val() + '">',
                                    '' + $(this).val() + '',
                                    color
                                ]).draw(false);
                                i++;
                            });

//                            $.ajax({
//                                dataType: 'text',
//                                url: '/index.php/dataFunction/delKeyWord2',
//                                data: data,
//                                type: 'POST',
//                                success: function (data, status, xhr) {
//                                    if (data == 'SUCCESS') {
//                                        table4.fnReloadAjax('/index.php/dataFunction/keywordStockLists4?stock_seq=' + stock_seq + '');
//                                        var i = 1;
//                                        $(".row_checkbox3:checked").each(function (index) {
//                                            check_number += "'" + $(this).val() + "',";
//                                            table3.row.add([
//                                                i,
//                                                '<input type="checkbox" class="row_checkbox2" value="' + $(this).val() + '">',
//                                                '' + $(this).val() + ''
//                                            ]).draw(false);
//                                            i++;
//                                        });
//                                    } else {
//                                        alert('데이터 처리오류!');
//                                    }
//                                }
//                            });
                            $(".row_checkbox2").click(function () {
                                if ($(this).is(":checked")) {
                                    $(this).parent().parent().addClass('selected');
                                } else {
                                    $(this).parent().parent().removeClass('selected');
                                }
                            });
                            $("#check-all3").attr('checked', false);
                            $(".row_checkbox3").attr('checked', false);
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
            },
            fnCreatedRow: function (nRow, aData, iDataIndex) {
                if (aData.color == 'key_important') {
                    $(nRow).find('td.color_row').addClass("key_important");
                }

                if (aData.color == 'key_issue') {
                    $(nRow).find('td.color_row').addClass("key_issue");
                }
            }

        });
        $("#expoSubmit").click(function () {
            var expo_keyword = $("#expo_keyword").val();
            var stock_seq = $("#stock_seq").val();
            var crp_cd = $("#crp_cd").val();
            var data = {expo_keyword: expo_keyword, crp_cd: crp_cd};
            $.ajax({
                dataType: 'text',
                url: '/index.php/dataFunction/keywordExpo',
                data: data,
                type: 'POST',
                success: function (data, status, xhr) {
                    if (data == 'SUCCESS') {
                        $('#allExpoModal').modal('toggle');
                        table2.fnReloadAjax('/index.php/dataFunction/keywordStockLists2?stock_sql=' + stock_seq + '');
                    } else {
                        alert('데이터 처리오류!');
                    }
                }
            });
        });
        $("#unexpoSubmit").click(function () {
            var unexpo_keyword = $("#unexpo_keyword").val();
            var stock_seq = $("#stock_seq").val();
            var crp_cd = $("#crp_cd").val();
            var data = {unexpo_keyword: unexpo_keyword, crp_cd: crp_cd};
            $.ajax({
                dataType: 'text',
                url: '/index.php/dataFunction/keywordUnexpo',
                data: data,
                type: 'POST',
                success: function (data, status, xhr) {
                    if (data == 'SUCCESS') {
                        $('#allUnexpoModal').modal('toggle');
                        table2.fnReloadAjax('/index.php/dataFunction/keywordStockLists2?stock_sql=' + stock_seq + '');
                    } else {
                        alert('데이터 처리오류!');
                    }
                }
            });
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
            var type = $('#type').select().val();
            var sdate = $("#sdate").val();
            var edate = $("#edate").val();

            table1.fnReloadAjax('/index.php/dataFunction/keywordStockLists?sdate=' + sdate + '&edate=' + edate + '&type=' + type + '&text=' + text + '');
            $('#keyword_kospiTable2').DataTable().clear().draw();
            table3.clear().draw();
            $('#keyword_kospiTable4').DataTable().clear().draw();
        });

        $("#search").keydown(function (key) {
            var text = $("#search").val();
            var type = $('#type').select().val();
            var sdate = $("#sdate").val();
            var edate = $("#edate").val();
            if (key.keyCode == 13) {
                table1.fnReloadAjax('/index.php/dataFunction/keywordStockLists?sdate=' + sdate + '&edate=' + edate + '&type=' + type + '&text=' + text + '');
                $('#keyword_kospiTable2').DataTable().clear().draw();
                table3.clear().draw();
                $('#keyword_kospiTable4').DataTable().clear().draw();
            }
        });
    });
    function expo() {
        var check = $('.row_checkbox:checked').length;
        var check_number = "";
        if (check == 0) {
            alert('키워드를 체크 해주세요.');
            return false;
        } else {
            $(".row_checkbox:checked").each(function (index) {
                check_number += "'" + $(this).val() + "',";
            });
            var keyword = check_number.substr(0, check_number.length - 1);
            $("#expo_keyword").val(keyword);
            $("#allExpoModal").modal();
        }
    }

    function unexpo() {
        var check = $('.row_checkbox:checked').length;
        var check_number = "";
        if (check == 0) {
            alert('키워드를 체크 해주세요.');
            return false;
        } else {
            $(".row_checkbox:checked").each(function (index) {
                check_number += $(this).val() + ",";
            });
            var keyword = check_number.substr(0, check_number.length - 1);
            $("#unexpo_keyword").val(keyword);
            $("#allUnexpoModal").modal();
        }
    }
</script>