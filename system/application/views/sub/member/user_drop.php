<!-- page content -->
<div class="right_col" role="main">

    <div class="page-title">
        <h3>탈퇴회원 <small>서비스 탈퇴회원을 관리합니다.</small></h3>
    </div>

    <div class="form-group">
        <form class="form-inline">
            <div class="form-group">
                <label for="sdate">탈퇴일시</label>
                <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                    <div class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                    </div>
                    <input type="text" class="form-control" id="sdate" placeholder="선택안함">
                </div>
                <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                    <div class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                    </div>
                    <input type="text" class="form-control" id="edate" placeholder="선택안함">
                </div>
            </div>
            <div class="form-group">
                <select class="form-control" id="type_select">
                    <option value="all">전체</option>
                    <option value="login_path">로그인경로</option>
                    <option value="pay">유료/무료</option>
                </select>
            </div>
            <div class="form-group" id="type_area" style="display: none">
                <select class="form-control type_select" id="type_location" style="display: none;">
                    <option value="0">타이밍</option>
                    <option value="2">페이스북</option>
                    <option value="1">카카오</option>
                    <option value="3">구글플러스</option>
                </select>
                <select class="form-control type_select" id="type_pay" style="display: none;">
                    <option value="1">유료(IOS)</option>
                    <option value="2">유료(Android)</option>
                    <option value="0">무료</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="search_text" placeholder="검색어입력">
            </div>
            <button type="button" id="search_btn" class="btn btn-default">검색</button>
        </form>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>탈퇴회원 수 <small id="rowtotal">명</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="user_drop_table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>번호</th>
                                <th>이름</th>
                                <th >이메일</th>
                                <th>휴대폰</th>
                                <th>로그인 경로</th>
                                <th>가입일</th>
                                <th>탈퇴일</th>
                                <th>유료/무료</th>
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
<!-- /page content -->

<!--user detail view Modal-->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">회원정보 수정</h4>
            </div>
            <div class="modal-body">

                <form id="antoform" class="form-horizontal calender" role="form">
                    <div class="row form-group">
                        <label class="col-sm-3 control-label">이름</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-3 control-label">이메일</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-3 control-label">휴대폰</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-3 control-label">로그인경로</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-3 control-label">가입일시</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-3 control-label">탈퇴일시</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-3 control-label">유로/무료</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-primary antosubmit">탈퇴처리</button>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var table = $('#user_drop_table').dataTable({
            processing: true,
            serverSide: true,
            dom: '<"datatable_header"fl>t<"datatable_footer"p>',
            ajax: {
                url: '/index.php/dataFunction/user_drop_list',
                dataSrc: function (json) {
                    json.draw = json.data.draw;
                    json.recordsTotal = json.data.recordsTotal;
                    json.recordsFiltered = json.data.recordsFiltered;
                    return json.data.data;
                }
            },
            columns: [
                {data: "customer_seq"},
                {data: "user_name"},
                {data: "email"},
                {data: "phone"},
                {data: "customer_type"},
                {data: "reg_date"},
                {data: "withdraw_date"},
                {data: "status"}
            ],
            order: [],
            language: {
                lengthMenu: "_MENU_",
                search: "",
                paginate: {
                    "next": "&raquo;",
                    "previous": "&laquo;"
                }
            },
            footerCallback: function (row, data, start, end, display) {
                if (!data.length) {
                    $("#rowtotal").html('0명');
                } else {
                    $("#rowtotal").html(numberWithCommas(data[0].recordsFiltered) + '명');
                }
            }
        });

        $("#type_select").change(function () {
            var value = $(this).select().val();
            if (value == 'login_path') {
                $("#type_area").show();
                $(".type_select").hide();
                $("#type_location").show();
            } else if (value == 'pay') {
                $("#type_area").show();
                $(".type_select").hide();
                $("#type_pay").show();
            } else {
                $("#type_area").hide();
                $(".type_select").hide();
            }
        });

        $('#search_btn').click(function () {
            var sdate = $("#sdate").val();
            var edate = $("#edate").val();
            var type_select = $("#type_select").select().val();
            var type_location = $('#type_location').select().val();
            var type_pay = $('#type_pay').select().val();
            var search_text = $("#search_text").val();

            if (type_select == 'all') {
                type_location = 'none';
                type_pay = 'none';
            }

            if (!$.trim(search_text)) {
                $("#search_text").val('');
                search_text = '';
            }

            table.fnReloadAjax('/index.php/dataFunction/user_drop_list?sdate=' + sdate + '&edate=' + edate + '&type_select=' + type_select + '&type_location=' + type_location + '&type_pay=' + type_pay + '&search_text=' + search_text + '');
        });

        $("#search_text").keydown(function (key) {
            var sdate = $("#sdate").val();
            var edate = $("#edate").val();
            var type_select = $("#type_select").select().val();
            var type_location = $('#type_location').select().val();
            var type_pay = $('#type_pay').select().val();
            var search_text = $("#search_text").val();

            if (type_select == 'all') {
                type_location = 'none';
                type_pay = 'none';
            }

            if (!$.trim(search_text)) {
                $("#search_text").val('');
                search_text = '';
            }

            if (key.keyCode == 13) {
                table.fnReloadAjax('/index.php/dataFunction/user_drop_list?sdate=' + sdate + '&edate=' + edate + '&type_select=' + type_select + '&type_location=' + type_location + '&type_pay=' + type_pay + '&search_text=' + search_text + '');
            }
        });
    });

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>