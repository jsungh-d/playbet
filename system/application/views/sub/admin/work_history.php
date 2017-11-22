<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <h3>작업 히스토리 <small>메뉴별 관리자 작업 히스토리를 확인 할 수 있습니다.</small></h3>
    </div>

    <div class="row">
        <div class="col-md-4 col-sm-4 col-xs-4">
            <div class="x_panel">
                <div class="x_content">

                    <table id="menu_table" class="table table-bordered datatable">
                        <colgroup><col width="180px"><col width="*"><col width="*"></colgroup>
                        <thead>
                            <tr>
                                <th>메뉴명</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-sm-8 col-xs-8">
            <div class="x_panel">
                <div class="x_content">

                    <table id="history_datatable" class="table table-bordered datatable">
                        <colgroup><col width="180px"><col width="*"><col width="*"></colgroup>
                        <thead>
                            <tr>
                                <th>작업일자</th>
                                <th>작업 내용</th>
                                <th>관리자 이름</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        var table = $('#menu_table').dataTable({
            dom: '<"datatable_header">t<"datatable_footer"p>',
            ajax: '/index.php/dataFunction/menuLists',
            columns: [
                {data: "menu_name"}
            ],
            pageLength: 50,
            order: [],
            columnDefs: [{
                    orderable: false,
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

        $('#menu_table tbody').on('click', 'td.cursor', function () {
            var index = $(this).index('#menu_table tbody td.cursor');
            var menu_seq = $(".menu_seq:eq(" + index + ")").val();

            $("#menu_table tbody tr").removeClass('clicked');
            $("#menu_table tbody tr:eq(" + index + ")").addClass('clicked');
            table2.fnReloadAjax('/index.php/dataFunction/historyLists?menu_seq=' + menu_seq + '');
        });

        var table2 = $('#history_datatable').dataTable({
            processing: true,
            serverSide: true,
            dom: '<"datatable_header"fl>t<"datatable_footer"p>',
            ajax: {
                url: '/index.php/dataFunction/historyLists?menu_seq=all',
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
                {data: "reg_date"},
                {data: "work"},
                {data: "admin_name"}
            ],
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
    });
</script>