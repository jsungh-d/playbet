<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <h3>종목관리 <small>서비스 종목을 관리합니다.</small></h3>
    </div>

    <div class="form-group">
        <div class="form-inline">
            <div class="form-group">
                <select id="option" class="form-control">
                    <option value="all">전체</option>
                    <option value="K">KOSDAQ</option>
                    <option value="Y">KOSPI</option>
                    <option value="N">KONEX</option>
                    <option value="E">기타</option>
                </select>
            </div>

            <div class="form-group">
                <input type="text" id="search" class="form-control optionSearchText" placeholder="검색어를 입력해주세요">
            </div>

            <button type="button" id="searchBtn" class="btn btn-default">검색</button>
        </div>
    </div>

    <div class="x_panel">
        <div class="x_title">
            <h2>종목 수<small id="rowtotal">건</small></h2>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <table id="option_table" class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="check-all"></th>
                        <th>구분</th>
                        <th>종목명</th>
                        <th>종목코드</th>
                        <th>업종</th>
                        <th>서비스 노출</th>
                    </tr>
                </thead>

                <tbody>
                </tbody>
            </table>

            <!--업종삭제모달-->
            <div id="businessDelModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">삭제경고</h4>
                        </div>
                        <div class="modal-body">
                            <p>
                                종목을 삭제하시겠습니까?
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                            <button type="button" class="btn btn-warning antosubmit" onclick="delSubmit();">삭제처리</button>
                            <input type="hidden" id="idxs" value="">
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        var table = $('#option_table').dataTable({
            processing: true,
            serverSide: true,
            dom: '<"datatable_header"fl>t<"datatable_footer"Bp>',
            ajax: {
                url: '/index.php/dataFunction/optionLists',
                dataSrc: function (json) {
                    json.draw = json.data.draw;
                    json.recordsTotal = json.data.recordsTotal;
                    json.recordsFiltered = json.data.recordsFiltered;
                    return json.data.data;
                }
            },
            columns: [
                {data: "checkbox"},
                {data: "kind"},
                {data: "company_name"},
                {data: "crp_cd"},
                {data: "description"},
                {data: "stock_status"}
            ],
            columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                },
                {className: "cursor", "targets": [2]}
            ],
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            order: [],
            buttons: [
                {
                    className: 'btn btn-sm btn-primary',
                    text: '종목추가',
                    action: function (e, dt, node, config) {
                        location.href = '/index/option_add';
                    }
                },
                {
                    className: 'btn btn-sm btn-primary',
                    text: '제외',
                    action: function (e, dt, node, config) {
                        delContents();
                    }
                }
            ],
            footerCallback: function (row, data, start, end, display) {
//                console.log(data);
                if (!data.length) {
                    $("#rowtotal").html('0건');
                } else {
                    $("#rowtotal").html(numberWithCommas(data[0].recordsFiltered) + '건');
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
        $('#option_table tbody').on('click', 'td.cursor', function () {
            var index = $(this).index('#option_table tbody td.cursor');
            var idx = $(".row_check:eq(" + index + ")").val();
            location.href = '/index/option_mod/' + idx + '';
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
            var option = $("#option").select().val();
            var text = $("#search").val();
            table.fnReloadAjax('/index.php/dataFunction/optionLists?option=' + option + '&text=' + text + '');
        });
//        $("#search").keydown(function (key) {
//            var option = $("#option").select().val();
//            var text = $("#search").val();
//            if (key.keyCode == 13) {
//                table.fnReloadAjax('/index.php/dataFunction/optionLists?option=' + option + '&text=' + text + '');
//            }
//        });
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
        var data = {idx: $("#idxs").val()};
        $.ajax({
            dataType: 'text',
            url: '/index.php/dataFunction/delOption',
            data: data,
            type: 'POST',
            success: function (data, status, xhr) {
                alert("삭제 되었습니다.");
                location.reload();
            }
        });
    }

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>