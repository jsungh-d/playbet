<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <h3>뉴스관리(신문) <small>신문 대기 뉴스, 노출 뉴스를 관리합니다.</small></h3>
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
                                <th>신문 리스트</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
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
                                <th>신문 리스트</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--news detail modal-->
    <div id="newsDetailModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">뉴스상세보기</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered dataTable">
                        <tbody>
                            <tr>
                                <th>종목명</th>
                                <td id="company_name_i">삼성전자</td>
                            </tr>
                            <tr>
                                <th>뉴스상태</th>
                                <td>
                                    <div class="form-inline">
                                        <label class="radio-inline">
                                            <input type="radio" name="status" class="status" value="1">노출
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="status" class="status" value="0">대기
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>언론사</th>
                                <td id="writer">헤럴드경제</td>
                            </tr>
                            <tr>
                                <th>뉴스발행시점</th>
                                <td id="reg_date">2017.03.02 12:05</td>
                            </tr>
                            <tr>
                                <th>뉴스제목</th>
                                <td id="news_title">
                                    삼성SDI, 소형전지사업부장도 삼성전자 반도체 
                                    전문가 출신 영입
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <!-- image cropping -->
                                    <div style="max-width: 550px; width: 100%;">
                                        <div class="cropper">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="img-container">
                                                        <img id="image" class="img-responsive" src="/production/images/cropper.jpg" alt="Picture">
                                                    </div>	
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 docs-buttons">

                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
                                                            <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;zoom&quot;, 0.1)">
                                                                <span class="fa fa-search-plus"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
                                                            <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;zoom&quot;, -0.1)">
                                                                <span class="fa fa-search-minus"></span>
                                                            </span>
                                                        </button>

                                                        <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
                                                            <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;move&quot;, -10, 0)">
                                                                <span class="fa fa-arrow-left"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Move Right">
                                                            <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;move&quot;, 10, 0)">
                                                                <span class="fa fa-arrow-right"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
                                                            <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;move&quot;, 0, -10)">
                                                                <span class="fa fa-arrow-up"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Move Down">
                                                            <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;move&quot;, 0, 10)">
                                                                <span class="fa fa-arrow-down"></span>
                                                            </span>
                                                        </button>

                                                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
                                                            <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;rotate&quot;, -45)">
                                                                <span class="fa fa-rotate-left"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
                                                            <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;rotate&quot;, 45)">
                                                                <span class="fa fa-rotate-right"></span>
                                                            </span>
                                                        </button>

                                                        <button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="Flip Horizontal">
                                                            <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;scaleX&quot;, -1)">
                                                                <span class="fa fa-arrows-h"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Flip Vertical">
                                                            <span class="docs-tooltip" data-toggle="tooltip" title="$().cropper(&quot;scaleY&quot;, -1)">
                                                                <span class="fa fa-arrows-v"></span>
                                                            </span>
                                                        </button>



                                                        <button type="button" class="btn btn-primary" data-method="zoomTo" data-option="1">
                                                            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.zoomTo(1)">
                                                                100%
                                                            </span>
                                                        </button>

                                                    </div>

                                                    <!-- Show the cropped image in modal -->
                                                    <div class="modal fade docs-cropped" id="getCroppedCanvasModal" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title" id="getCroppedCanvasTitle">Cropped</h4>
                                                                </div>
                                                                <div class="modal-body"></div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    <a class="btn btn-primary" id="download" href="javascript:void(0);" download="cropped.png">Download</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><!-- /.modal -->

                                                </div><!-- /.docs-toggles -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /image cropping -->
                                    <input type="hidden" id="news_contents" value="">
                                </td>
                            </tr>
                            <tr>
                                <th>뉴스이동(종목)</th>
                                <td>
                                    <select id="multiple_select" class="form-control multiple-select" multiple="multiple" style="width: 100%">
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="news_seq" value="">
                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                    <button id="mod_submit_btn" class="btn btn-default btn-primary" type="button">적용</button>
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
</div>
</div>
</div>
</div>
</div>

<!-- Cropper -->
<script type="text/javascript" src="/vendors/cropper/dist/cropper.min.js"></script>

<script>
                        $(document).ready(function () {
//                                $("#news_view").hide();

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

                            var table = $('#news_online_table1').dataTable({
                                dom: '<"datatable_header"fl>t<"datatable_footer"Bp>',
//                                                        ajax: '/index.php/dataFunction/newsObjectLists?news_kind=2&show=Y',
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
                                    $("#rowtotal1").html(numberWithCommas(data.length) + '건');
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
                                dom: '<"datatable_header"fl>t<"datatable_footer"Bp>',
//                                                        ajax: '/index.php/dataFunction/newsObjectLists?news_kind=2&show=N',
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
                                    $("#rowtotal2").html(numberWithCommas(data.length) + '건');
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

                                $("#news_online_table1 tbody tr").removeClass('clicked');
                                $("#news_online_table1 tbody tr:eq(" + index + ")").addClass('clicked');

                                $.ajax({
                                    dataType: 'json',
                                    url: '/index.php/dataFunction/newsView2',
                                    data: data,
                                    type: 'POST',
                                    success: function (data, status, xhr) {
                                        $("#newsDetailModal").modal('show');

                                        $("#news_seq").val(data.news_seq);
                                        $("#company_name_i").html(data.company_name_i);

                                        if (data.status == '노출') {
                                            $(".status:eq(0)").attr('checked', true);
                                        } else if (data.status == '대기') {
                                            $(".status:eq(1)").attr('checked', true);
                                        }

                                        $("#writer").html(data.writer);
                                        $("#reg_date").html(data.reg_date);
                                        $("#news_title").html(data.news_title);
                                        $("#news_contents").val(data.news_contents);
//                                                                $("#image").cropper("clear");
//                                                                $("#image").attr('src', data.news_img_path);

                                        var imageURL = data.news_img_path;
                                        var imageBox = $('.img-container img');
                                        var DefaultCropBoxOptionObj = {}; // declare early
                                        var options = {
                                            aspectRatio: 1 / 2,
                                            built: function () {
                                                imageBox.cropper('setCropBoxData', DefaultCropBoxOptionObj);
                                            },
                                        };

                                        if (imageURL != null) {
                                            // init
                                            imageBox.cropper(options);
                                            // set params
                                            DefaultCropBoxOptionObj = {
                                                width: 25,
                                                left: 100,
                                            };
                                            // replace seems a bit buggy, fire once on built event
                                            imageBox.one('built.cropper', function () {
                                                imageBox.cropper('replace', imageURL);
                                            });
                                        }

//                                                                $('#image').one('built.cropper'), function () {
//                                                                    $('#image').cropper('replace', data.news_img_path);
//                                                                };

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
                                        $("#newsDetailModal").modal('show');

                                        $("#news_seq").val(data.news_seq);
                                        $("#company_name_i").html(data.company_name_i);

                                        if (data.status == '노출') {
                                            $(".status:eq(0)").attr('checked', true);
                                        } else if (data.status == '대기') {
                                            $(".status:eq(1)").attr('checked', true);
                                        }

                                        $("#writer").html(data.writer);
                                        $("#reg_date").html(data.reg_date);
                                        $("#news_title").html(data.news_title);
                                        $("#news_contents").val(data.news_contents);
                                        $("#image").cropper("clear")
                                        $("#image").attr('src', data.news_img_path);
//                    $('#image').cropper('checkImageOrigin', false);
                                        $('#image').cropper('replace', data.news_img_path);

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

                                table.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=2&show=Y&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
                                table2.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=2&show=N&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
                            });

//                                $("#search").keydown(function (key) {
//                                    var text = $("#search").val();
//                                    var check = $('.timeChk:checked').val();
//                                    var sdate = $("#sdate").val();
//                                    var edate = $("#edate").val();
//                                    if (key.keyCode == 13) {
//                                        table.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=2&show=Y&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
//                                        table2.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=2&show=N&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
//                                    }
//                                });

                            $(".timeChk").change(function () {

                                $("#sdate").val("");
                                $("#edate").val("");

                                var text = $("#search").val();
                                var check = $('.timeChk:checked').val();
                                var sdate = $("#sdate").val();
                                var edate = $("#edate").val();

                                table.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=2&show=Y&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
                                table2.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=2&show=N&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
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
                                var news_title = $("#news_title").html();

                                var data = {
                                    news_seq: news_seq,
                                    status: status,
                                    news_contents: news_contents,
                                    multiple_select: multiple_select,
                                    news_title: news_title,
                                    news_kind: '2'
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
                                            $("#newsDetailModal").modal('hide');
                                            var text = $("#search").val();
                                            var check = $('.timeChk:checked').val();
                                            var sdate = $("#sdate").val();
                                            var edate = $("#edate").val();

                                            table.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=2&show=Y&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
                                            table2.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=2&show=N&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
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
                            var data = {idx: $("#idxs2").val(), status: 0, news_kind: '2'};
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
                                    table.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=2&show=Y&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
                                    table2.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=2&show=N&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
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
                            var data = {idx: $("#idxs1").val(), status: 1, news_kind: '2'};
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
                                    table.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=2&show=Y&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
                                    table2.fnReloadAjax('/index.php/dataFunction/newsObjectLists?news_kind=2&show=N&sdate=' + sdate + '&edate=' + edate + '&time_chk=' + check + '&text=' + text + '');
                                }
                            });
                        }

                        function numberWithCommas(x) {
                            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
</script>