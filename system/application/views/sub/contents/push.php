<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <h3>푸쉬관리(뉴스/공시) <small>시스템에서 발송된 뉴스와 공시 푸쉬 내역을 확인합니다.</small></h3>
    </div>

    <div class="x_panel">
        <div class="x_content">

            <form class="form-horizontal form-label-left">
                <div class="form-group">
                    <div class="form-inline">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">푸쉬 발송 시점</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="input-group date" data-provide="datepicker">
                                <div class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </div>
                                <input type="text" class="form-control" placeholder="날짜지정">
                            </div>
                            <div class="input-group date" data-provide="datepicker">
                                <div class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </div>
                                <input type="text" class="form-control" placeholder="날짜지정">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-inline">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">1차 범위</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <select class="form-control min-width">
                                <option value="전체">전체</option>
                                <option value="온라인 뉴스">온라인 뉴스</option>
                                <option value="신문">신문</option>
                                <option value="방송">방송</option>
                                <option value="공시">공시</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-inline">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">2차 범위</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <select class="form-control min-width">
                                <option value="전체">전체</option>
                                <option value="종목명">종목명</option>
                                <option value="분류어">분류어</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-inline">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">제목명</label>
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

    <div class="x_panel">
        <div class="x_content">
            <table id="push_table" class="table table-bordered">
                <colgroup><col width="70px"><col width="100px"><col width="100px"><col width="120px"><col width="120px"><col width="*"><col width="140px"><col width="140px"></colgroup>
                <thead>
                    <tr>
                        <td>번호</td>
                        <td>뉴스/공시</td>
                        <td>종목</td>
                        <td>1차 분류어</td>
                        <td>2차 분류어</td>
                        <td>제목</td>
                        <td>뉴스.공시 등록 시점</td>
                        <td>발송 시간</td>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var table = $('#push_table').dataTable({
            processing: true,
            serverSide: true,
            dom: '<"datatable_header"fl>t<"datatable_footer"p>',
            ajax: {
                url: '/index.php/dataFunction/pushLists',
                dataSrc: function (json) {
                    json.draw = json.data.draw;
                    json.recordsTotal = json.data.recordsTotal;
                    json.recordsFiltered = json.data.recordsFiltered;
                    return json.data.data;
                }
            },
            columns: [
                {data: "send_id"},
                {data: "news_kind"},
                {data: "stock_name"},
                {data: "1stock_name"},
                {data: "2stock_name"},
                {data: "news_title"},
                {data: "reg_date"},
                {data: "confirm_date"}
            ],
            columnDefs: [
                {
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                },
                {
                    className: 'cursor',
                    targets: 5
                }],
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            order: [],
            language: {
                lengthMenu: "_MENU_",
                search: "",
                paginate: {
                    "next": "&raquo;",
                    "previous": "&laquo;"
                }
            }
        });

        $('#push_table tbody').on('click', 'td.cursor', function () {
            var index = $(this).index('#push_table tbody td.cursor');
            var idx = $(".row_value:eq(" + index + ")").val();
            location.href = '/index/push_detail/' + idx + '';
        });
    });

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

</script>


<!-- /page content