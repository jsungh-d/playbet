<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <h3>뉴스관리(공시) <small>노출 공시를 관리합니다.</small></h3>
    </div>

    <div class="x_panel">
        <div class="x_content">
            <br />
            <form class="form-horizontal form-label-left">
                <div class="form-group">
                    <div class="form-inline">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">공시 수집 시점</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
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

                            <div id="search_type" class="btn-group" data-toggle="buttons">
                                <label class="btn btn-info active">
                                    <input class="timeChk" type="radio" name="search_type" value="now"> 실시간
                                </label>
                                <label class="btn btn-info">
                                    <input class="timeChk" type="radio" name="search_type" value="1"> 1시간
                                </label>
                                <label class="btn btn-info">
                                    <input class="timeChk" type="radio" name="search_type" value="6"> 6시간
                                </label>
                                <label class="btn btn-info">
                                    <input class="timeChk" type="radio" name="search_type" value="12"> 12시간
                                </label>
                                <label class="btn btn-info">
                                    <input class="timeChk" type="radio" name="search_type" value="day"> 1일
                                </label>
                                <label class="btn btn-info">
                                    <input class="timeChk" type="radio" name="search_type" value="2day"> 2일
                                </label>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-inline">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">종목명</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="input-group">
                                <input type="text" id="search" class="form-control optionSearchText" placeholder="">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" id="searchBtn" type="button">검색</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>노출뉴스<small id="rowtotal">0000건</small></h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <table id="news_disclosure_table" class="table table-bordered bulk_action">
                        <colgroup><col width="30px"><col width="*"></colgroup>
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check-all"></th>
                                <th>공시 리스트</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>


                    <!--삭제모달-->
                    <div id="businessDelModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">뉴스 제외</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        선택 한 뉴스를 해당 종목에서 제외 하시겠습니까?
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                    <button type="button" class="btn btn-warning antosubmit" onclick="delSubmit();">제외</button>
                                    <input type="hidden" id="idxs" value="">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div id="news_view" class="col-xs-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>노출공시 상세보기</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <table class="table table-bordered">
                        <colgroup><col width="135px"><col width="*"></colgroup>
                        <tbody>
                            <tr>
                                <th>종목명</th>
                                <td id="company_name_i"></td>
                            </tr>
                            <tr>
                                <th>뉴스상태</th>
                                <td id="status"></td>
                            </tr>
                            <tr>
                                <th>제출인</th>
                                <td id="writer"></td>
                            </tr>
                            <tr>
                                <th>공시 수집 시점</th>
                                <td id="reg_date"></td>
                            </tr>
                            <tr>
                                <th>공시 제목</th>
                                <td id="news_title"></td>
                            </tr>
                            <tr>
                                <th>공시 내용</th>
                                <td>
                                    <textarea id="news_contents" class="form-control" rows="15"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="text-right">
                        <!--                            <button type="button" class="btn btn-sm btn-default">취소</button>
                        <button type="button" id="mod_submit_btn" class="btn btn-sm btn-primary">적용</button>-->
                        <input type="hidden" id="news_seq" value="">
                    </div>

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
        $("#news_view").hide();
        var table = $('#news_disclosure_table').dataTable({
            dom: '<"datatable_header"fl>t<"datatable_footer"Bp>',
//            ajax: '/index.php/dataFunction/newsObjectLists?news_kind=1',
            columns: [
                {data: "checkbox"},
                {data: "news_title"}
            ],
            columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                },
                {className: "cursor", "targets": [1]}
            ],
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            order: [],
            buttons: [
                {
                    className: 'btn btn-sm btn-primary',
                    text: '제외',
                    action: function (e, dt, node, config) {
                        delContents();
                    }
                }
            ],
            footerCallback: function (row, data, start, end, display) {
                $("#rowtotal").html(numberWithCommas(data.length) + '건');
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

        $('#news_disclosure_table tbody').on('click', 'td.cursor', function () {
            var index = $(this).index('#news_disclosure_table tbody td.cursor');
            var idx = $(".row_check:eq(" + index + ")").val();
            var data = {idx: idx};

            $("#news_disclosure_table tbody tr").removeClass('clicked');
            $("#news_disclosure_table tbody tr:eq(" + index + ")").addClass('clicked');

            $.ajax({
                dataType: 'json',
                url: '/index.php/dataFunction/newsView2',
                data: data,
                type: 'POST',
                success: function (data, status, xhr) {
                    $("#news_view").show();

                    $("#news_seq").val(data.news_seq);
                    $("#company_name_i").html(data.company_name_i);
                    $("#status").html(data.status);
                    $("#writer").html(data.writer);
                    $("#reg_date").html(data.reg_date);
                    $("#news_title").html(data.news_title);
                    $("#news_contents").val(data.news_contents);
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
            var check = $('.timeChk:checked').val();
            var sdate = $("#sdate").val();
            var edate = $("#edate").val();

            table.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=1&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
        });

//        $("#search").keydown(function (key) {
//            var text = $("#search").val();
//            var check = $('.timeChk:checked').val();
//            var sdate = $("#sdate").val();
//            var edate = $("#edate").val();
//            if (key.keyCode == 13) {
//                table.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=1&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
//            }
//        });

        $(".timeChk").change(function () {

            $("#sdate").val("");
            $("#edate").val("");

            var text = $("#search").val();
            var check = $('.timeChk:checked').val();
            var sdate = $("#sdate").val();
            var edate = $("#edate").val();

            table.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=1&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
        });

        $(".date").change(function () {
            $('.timeChk').prop('checked', false);
            $("#search_type label").removeClass('active');
        });

        $("#mod_submit_btn").click(function () {
            var news_seq = $("#news_seq").val();
            var news_contents = $("#news_contents").val();

            var data = {
                news_seq: news_seq,
                news_contents: news_contents
            };

            $.ajax({
                dataType: 'text',
                url: '/index.php/dataFunction/modNews',
                data: data,
                type: 'POST',
                success: function (data, status, xhr) {
                    if (data == 'FAILED') {
                        alert("데이터 처리오류!!");
//                        return false;
                    } else if (data == 'SUCCESS') {
                        alert("수정 되었습니다.");
//                        return false;
                    }
                }
            });
        });
    });

    function delContents() {
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
            $("#businessDelModal").modal();
        }
    }

    function delSubmit() {
        var data = {idx: $("#idxs").val(), news_kind: '1'};
        $.ajax({
            dataType: 'text',
            url: '/index.php/dataFunction/delNews',
            data: data,
            type: 'POST',
            success: function (data, status, xhr) {
                alert("제외 되었습니다.");
                location.reload();
            }
        });
    }

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>