<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_full">
            <h3>업종관리 <small>업종을 등록/관리합니다.</small></h3>
        </div>
    </div>

    <!--start 업종등록-->
    <div class="x_panel">
        <div class="x_title">
            <h2>업종등록 <small>업종은 "종목관리" 메뉴에도 함께 추가됩니다.</small></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />
            <form class="form-horizontal form-label-left" method="post" action="/index.php/dataFunction/insBusiness">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">업종명
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="first-name" required="required" name="description" class="form-control col-md-7 col-xs-12" placeholder="새로운 업종을 추가해주세요">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ind_cd">업종코드
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="ind_cd" required="required" name="ind_cd" class="form-control col-md-7 col-xs-12" pattern="[0-9]*" placeholder="새로운 업종코드를 추가해주세요">
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button class="btn btn-default" type="reset">취소</button>
                        <button type="submit" class="btn btn-primary">등록</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--end 업종등록-->

    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>업종 리스트<small id="rowtotal1">0건</small></h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <table id="business_table1" class="table table-bordered bulk_action">
                        <colgroup><col width="30px"><col width="*"></colgroup>
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check-all"></th>
                                <th>기업목록 리스트</th>
                            </tr>
                        </thead>
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
                                        업종을 삭제하면 등록된 종목 또한 모두 삭제됩니다. 삭제하시겠습니까?
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
        <div class="col-sm-6 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>기업 리스트<small id="rowtotal2">0건</small></h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <table id="business_table2" class="table table-striped table-bordered bulk_action">
                        <colgroup><col width="30px"><col width="*"></colgroup>
                        <thead>
                            <tr>
                                <th>기업명(종목코드)</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $('#business_table1').dataTable({
            dom: '<"datatable_header"fl>t<"datatable_footer"Bp>',
            ajax: '/index.php/dataFunction/businessLists',
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
            columns: [
                {data: "business_seq"},
                {data: "contents"}

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

        var table2 = $('#business_table2').dataTable({
            dom: '<"datatable_header"fl>t<"datatable_footer"p>',
            columns: [
                {data: "contents"}
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

        $('#business_table1 tbody').on('click', 'td.sorting_1', function () {
            var index = $(this).index('#business_table1 tbody td.sorting_1');
            var idx = $(".row_check:eq(" + index + ")").val();

            $("#business_table1 tbody tr").removeClass('clicked');
            $("#business_table1 tbody tr:eq(" + index + ")").addClass('clicked');
            table2.fnReloadAjax('/index.php/dataFunction/stockLists?business_seq=' + idx + '');
        });

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
            url: '/index.php/dataFunction/delBusiness',
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