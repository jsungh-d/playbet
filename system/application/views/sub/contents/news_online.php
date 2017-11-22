<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <h3>뉴스관리(온라인) <small>온라인 대기 뉴스, 노출 뉴스를 관리합니다.</small></h3>
    </div>

    <div class="x_panel">
        <div class="x_content">
            <br />
            <form class="form-horizontal form-label-left">
                <div class="form-group">
                    <div class="form-inline">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">뉴스 발행 시점</label>
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
                                <!--                                <label class="btn btn-info">
                                                                    <input class="timeChk" type="radio" name="search_type" value="1"> 1시간
                                                                </label>-->
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">언론사</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <select class="form-control min-width">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
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
                    <h2>노출뉴스<small id="rowtotal1">0000건</small></h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <table id="news_online_table1" class="table table-bordered bulk_action">
                        <colgroup><col width="30px"><col width="*"></colgroup>
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check-all"></th>
                                <th>온라인 뉴스 리스트</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>

                    <!--View Modal-->
                    <div id="onlineViewModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">온라인뉴스</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label">종목명</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="company_name_i" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label">뉴스상태</label>
                                        <div class="col-sm-9">
                                            <label class="radio-inline">
                                                <input type="radio" name="status" class="status" value="1">노출
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="status" class="status" value="0">대기
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label">언론사</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="writer" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label">뉴스발행시점</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="reg_date" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label">뉴스제목</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="news_title" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label">뉴스내용</label>
                                        <div class="col-sm-9">
                                            <textarea id="news_contents" class="form-control" rows="15" placeholder="뉴스 내용"></textarea>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-sm-3 control-label">뉴스이동(종목)</label>
                                        <div class="col-sm-9">
                                            <select id="multiple_select" class="form-control multiple-select" multiple="multiple" style="width: 100%">
                                            </select>
                                        </div>
                                    </div>


                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">취소</button>
                                    <button type="button" id="mod_submit_btn" class="btn btn-primary antosubmit">적용</button>
                                    <input type="hidden" id="news_seq" value="">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div id="statusModal1" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">뉴스 이동</h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            선택 한 뉴스를 노출 뉴스로 이동하시겠습니까? 해당뉴스가 반영되어 어플에 보여집니다.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default antoclose" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary antosubmit" onclick="statusSubmit1();">이동</button>
                        <input type="hidden" id="idxs1" value="">
                    </div>
                </div>
            </div>
        </div>

        <div id="statusModal2" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">뉴스 이동</h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            선택 한 뉴스를 대기 뉴스로 이동하시겠습니까? 해당 뉴스가 어플에 노출되지 않습니다.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default antoclose" data-dismiss="modal">취소</button>
                        <button type="button" class="btn btn-primary antosubmit" onclick="statusSubmit2();">이동</button>
                        <input type="hidden" id="idxs2" value="">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>노출 대기 뉴스<small id="rowtotal2">0000건</small></h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <table id="news_online_table2" class="table table-bordered bulk_action">
                        <colgroup><col width="30px"><col width="*"></colgroup>
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check-all"></th>
                                <th>온라인 뉴스 대기 리스트</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

</div>

</div>

</div>

<script>
    $(document).ready(function () {
        var s2 = $(".multiple-select").select2({
            multiple: true,
            minimumInputLength: 2,
            placeholder: "등록 된 데이터가 없습니다.",
            "language": {
                "noResults": function () {
                    return "일치하는 데이터가 없습니다.";
                }
            },
            ajax: {
                url: '/index.php/dataFunction/loadSelectOptionStock',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        s2.on("select2:unselect", function (e) {
            if (confirm(e.params.data.text + "를 삭제 하시겠습니까?") == true) {

            } else {
                s2.append('<option value="' + e.params.data.id + '" selected="selected">' + e.params.data.text + '</option>');
            }
//            console.log(e.params.data);
        });

        var text = $("#search").val();
        var check = $('.timeChk:checked').val();
        var sdate = $("#sdate").val();
        var edate = $("#edate").val();

        var table = $('#news_online_table1').dataTable({
            processing: true,
            serverSide: true,
            dom: '<"datatable_header"fl>t<"datatable_footer"Bp>',
            ajax: {
                url: '/index.php/dataFunction/newsObjectLists?news_kind=3&show=Y&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '',
                dataSrc: function (json) {
                    json.draw = json.data.draw;
                    json.recordsTotal = json.data.recordsTotal;
                    json.recordsFiltered = json.data.recordsFiltered;
                    return json.data.data;
                }
            },
//            ajax: '/index.php/dataFunction/newsObjectLists?news_kind=3&show=Y',
            columns: [
                {data: "checkbox"},
                {data: "news_title"}
            ],
            columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                },
                {className: "cursor", "targets": [1]}],
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            order: [],
            buttons: [
                {
                    className: 'btn btn-sm btn-primary',
                    text: '대기뉴스로 이동',
                    action: function (e, dt, node, config) {
                        statusContents2();
                    }
                }
            ],
            footerCallback: function (row, data, start, end, display) {
                if (!data.length) {
                    $("#rowtotal1").html('0건');
                } else {
                    $("#rowtotal1").html(numberWithCommas(data[0].recordsFiltered) + '건');
                }
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

        var table2 = $('#news_online_table2').dataTable({
            processing: true,
            serverSide: true,
            dom: '<"datatable_header"fl>t<"datatable_footer"Bp>',
            ajax: {
                url: '/index.php/dataFunction/newsObjectLists?news_kind=3&show=N&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '',
                dataSrc: function (json) {
                    json.draw = json.data.draw;
                    json.recordsTotal = json.data.recordsTotal;
                    json.recordsFiltered = json.data.recordsFiltered;
                    if (json.data.data) {
                        return json.data.data;
                    }
                }
            },
//            ajax: '/index.php/dataFunction/newsObjectLists?news_kind=3&show=N',
            columns: [
                {data: "checkbox"},
                {data: "news_title"}
            ],
            columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                },
                {className: "cursor", "targets": [1]}],
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            order: [],
            buttons: [
                {
                    className: 'btn btn-sm btn-primary',
                    text: '노출뉴스로 이동',
                    action: function (e, dt, node, config) {
                        statusContents1();
                    }
                }
            ],
            footerCallback: function (row, data, start, end, display) {
                if (!data.length || !data) {
                    $("#rowtotal2").html('0건');
                } else {
                    $("#rowtotal2").html(numberWithCommas(data[0].recordsFiltered) + '건');
                }
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

        $('#news_online_table1 tbody').on('click', 'td.cursor', function () {
            var index = $(this).index('#news_online_table1 tbody td.cursor');
            var idx = $(".row_check:eq(" + index + ")").val();
            var data = {idx: idx};

            $.ajax({
                dataType: 'json',
                url: '/index.php/dataFunction/newsView2',
                data: data,
                type: 'POST',
                success: function (data, status, xhr) {
                    $("#onlineViewModal").modal();

                    $("#news_seq").val(data.news_seq);
                    $("#company_name_i").val(data.company_name_i);

                    if (data.status == '노출') {
                        $(".status:eq(0)").attr('checked', true);
                    } else if (data.status == '대기') {
                        $(".status:eq(1)").attr('checked', true);
                    }

                    $("#writer").val(data.writer);
                    $("#reg_date").val(data.reg_date);
                    $("#news_title").val(data.news_title);
                    $("#news_contents").val(data.news_contents);

                    data = {news_seq: data.news_seq};

                    s2.html('');
                    s2.trigger('change');

                    $.ajax({
                        dataType: 'json',
                        url: '/index.php/dataFunction/newsViewStock',
                        data: data,
                        type: 'POST',
                        success: function (data, status, xhr) {

                            for (var i = 0; i < data.length; i++) {
                                s2.append('<option value="' + data[i].id + '" selected="selected">' + data[i].text + '</option>');
                            }
                            s2.trigger('change');
                        }
                    });
                }
            });
        });

        $('#news_online_table2 tbody').on('click', 'td.cursor', function () {
            var index = $(this).index('#news_online_table2 tbody td.cursor');
            var idx = $(".row_check2:eq(" + index + ")").val();
            var data = {idx: idx};

            $.ajax({
                dataType: 'json',
                url: '/index.php/dataFunction/newsView2',
                data: data,
                type: 'POST',
                success: function (data, status, xhr) {
                    $("#onlineViewModal").modal();

                    $("#news_seq").val(data.news_seq);
                    $("#company_name_i").val(data.company_name_i);

                    if (data.status == '노출') {
                        $(".status:eq(0)").attr('checked', true);
                    } else if (data.status == '대기') {
                        $(".status:eq(1)").attr('checked', true);
                    }

                    $("#writer").val(data.writer);
                    $("#reg_date").val(data.reg_date);
                    $("#news_title").val(data.news_title);
                    $("#news_contents").val(data.news_contents);

                    data = {news_seq: data.news_seq};

                    s2.html('');
                    s2.trigger('change');

                    $.ajax({
                        dataType: 'json',
                        url: '/index.php/dataFunction/newsViewStock',
                        data: data,
                        type: 'POST',
                        success: function (data, status, xhr) {

                            for (var i = 0; i < data.length; i++) {
                                s2.append('<option value="' + data[i].id + '" selected="selected">' + data[i].text + '</option>');
                            }
                            s2.trigger('change');
                        }
                    });
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

            table.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=3&show=Y&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
            table2.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=3&show=N&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
        });

//        $("#search").keydown(function (key) {
//            var text = $("#search").val();
//            var check = $('.timeChk:checked').val();
//            var sdate = $("#sdate").val();
//            var edate = $("#edate").val();
//            if (key.keyCode == 13) {
//                table.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=3&show=Y&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
//                table2.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=3&show=N&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
//            }
//        });

        $(".timeChk").change(function () {

            $("#sdate").val("");
            $("#edate").val("");

            var text = $("#search").val();
            var check = $('.timeChk:checked').val();
            var sdate = $("#sdate").val();
            var edate = $("#edate").val();

            table.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=3&show=Y&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
            table2.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=3&show=N&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
        });

        $(".date").change(function () {
            $('.timeChk').prop('checked', false);
            $("#search_type label").removeClass('active');
        });

        $("#mod_submit_btn").click(function () {
            var news_seq = $("#news_seq").val();
            var news_contents = $("#news_contents").val();
            var status = $(".status:checked").val();
            var multiple_select = $("#multiple_select").select().val();
            var news_title = $("#news_title").val();

            var data = {
                news_seq: news_seq,
                status: status,
                news_contents: news_contents,
                multiple_select: multiple_select,
                news_title: news_title,
                news_kind: '3'
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
                        $("#onlineViewModal").modal('hide');
                        var text = $("#search").val();
                        var check = $('.timeChk:checked').val();
                        var sdate = $("#sdate").val();
                        var edate = $("#edate").val();

                        table.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=3&show=Y&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
                        table2.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=3&show=N&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
//                        return false;
                    }
                }
            });
        });
    });

    function statusContents2() {
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
            $("#idxs2").val(appNum);
            $("#statusModal2").modal();
        }
    }

    function statusSubmit2() {
        var data = {idx: $("#idxs2").val(), status: 0, news_kind: '3'};
        $.ajax({
            dataType: 'text',
            url: '/index.php/dataFunction/newsStatusChange',
            data: data,
            type: 'POST',
            success: function (data, status, xhr) {
                alert("이동 되었습니다.");
                $("#statusModal2").modal('hide');
                var text = $("#search").val();
                var check = $('.timeChk:checked').val();
                var sdate = $("#sdate").val();
                var edate = $("#edate").val();
                var table = $('#news_online_table1').dataTable();
                var table2 = $('#news_online_table2').dataTable();
                table.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=3&show=Y&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
                table2.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=3&show=N&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
            }
        });
    }

    function statusContents1() {
        var check = $('.row_check2:checked').length;
        var check_number = "";
        if (check == 0) {
            alert('리스트를 체크 해주세요.');
            return false;
        } else {
            $(".row_check2:checked").each(function (index) {
                check_number += $(this).val() + ",";
            });
            var appNum = check_number.substr(0, check_number.length - 1);
            $("#idxs1").val(appNum);
            $("#statusModal1").modal();
        }
    }

    function statusSubmit1() {
        var data = {idx: $("#idxs1").val(), status: 1, news_kind: '3'};
        $.ajax({
            dataType: 'text',
            url: '/index.php/dataFunction/newsStatusChange',
            data: data,
            type: 'POST',
            success: function (data, status, xhr) {
                alert("이동 되었습니다.");
                $("#statusModal1").modal('hide');
                var text = $("#search").val();
                var check = $('.timeChk:checked').val();
                var sdate = $("#sdate").val();
                var edate = $("#edate").val();
                var table = $('#news_online_table1').dataTable();
                var table2 = $('#news_online_table2').dataTable();
                table.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=3&show=Y&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
                table2.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=3&show=N&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
            }
        });
    }

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>