<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_full">
            <h3>배제어 <small>종목, 키워드 분류시 배제해야 하는 단어를 관리합니다.</small></h3>
        </div>
    </div>

    <div class="x_panel">
        <div class="x_content">
            <br />
            <div class="form-group">
                <div class="form-inline">

                    <select id="kind" class="form-control" style="min-width: 100px;">
                        <option value="all">전체</option>
                        <option value="Y">코스피</option>
                        <option value="K">코스닥</option>
                        <option value="N">코넥스</option>
                    </select>

                    <select id="search_type" class="form-control" style="min-width: 100px;">
                        <option value="all">전체</option>
                        <option value="company_name_i">종목명</option>
                        <option value="stopkeywords">배제어</option>
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

        <div class="x_content">
            <table id="exclueTable" class="table table-bordered bulk_action">
                <colgroup><col width="30px"><col width="60px"><col width="140px"><col width="*"><col width="*"></colgroup>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="check-all"></th>
                        <th>번호</th>
                        <th>구분</th>
                        <th>종목명</th>
                        <th>배제어</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td><input type="checkbox" name=""></td>
                        <td>no</td>
                        <td>전체</td>
                        <td>naver</td>
                        <td>com</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name=""></td>
                        <td>no</td>
                        <td>코스피</td>
                        <td>daum</td>
                        <td>net</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name=""></td>
                        <td>no</td>
                        <td>코스닥</td>
                        <td>daum</td>
                        <td>@</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--배제어삭제모달-->
<div id="keywordDelModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">삭제경고</h4>
            </div>
            <div class="modal-body">
                <p>
                    단어를 삭제하시겠습니까?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-primary antosubmit" id="del_btn" value="">삭제처리</button>
            </div>
        </div>

    </div>
</div>
<!--단어일괄등록모달-->
<div id="keywordInsModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" id="excelForm" action="/index.php/dataFunction/loadExcel2">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">단어 일괄 등록</h4>
                </div>
                <div class="modal-body">
                    <input type="file" name="file" id="file_allupload" required>

                </div>
                <div class="modal-footer">
                    <div class="pull-left">
                        <a href="/images/common/exlude_sample.xls" class="text-left">샘플 다운로드.xls</a>
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                        <button type="submit" class="btn btn-primary antosubmit">등록</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--엑셀로드뷰 모달-->
<div id="excelViewModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div id="excelViewBody" class="modal-content">

        </div>
    </div>
</div>

<script src="http://malsup.github.com/jquery.form.js"></script>
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

        var table = $('#exclueTable').dataTable({
            dom: '<"datatable_header"fl>t<"datatable_footer"Bp>',
            ajax: '/index.php/dataFunction/keyword_exclude',
            columns: [
                {data: "checkbox"},
                {data: "num"},
                {data: "kind"},
                {data: "company_name_i"},
                {data: "stopkeywords"}
            ],
            pagingType: "simple",
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
                },
                {
                    className: "cursor", 
                    targets: [3]
                },
                {
                    className: "cursor2", 
                    targets: [4]
                }
            ],
            order: [],
            buttons: [
                {
                    className: 'btn btn-sm btn-primary',
                    text: '삭제',
                    action: function () {
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
                            $("#del_btn").val(appNum);
                            $("#keywordDelModal").modal();
                        }
                    }
                },
                {
                    className: 'btn btn-sm btn-primary',
                    text: '배제어 일괄 등록',
                    action: function (e, dt, node, config) {
                        $("#keywordInsModal").modal();
                    }
                },
                {
                    className: 'btn btn-sm btn-primary',
                    text: '베제어 추가',
                    action: function (e, dt, node, config) {
                        location.href = '/index/keyword_exclude_add';
                    }
                }
            ]
        });

        $('#exclueTable tbody').on('click', 'td.cursor', function () {
            var index = $(this).index('#exclueTable tbody td.cursor');
            var idx = $(".row_check:eq(" + index + ")").val();
            location.href = '/index/keyword_exclude_mod/' + idx + '';
        });
        
        $('#exclueTable tbody').on('click', 'td.cursor2', function () {
            var index = $(this).index('#exclueTable tbody td.cursor2');
            var idx = $(".row_check:eq(" + index + ")").val();
            location.href = '/index/keyword_exclude_mod/' + idx + '';
        });

        $('#searchBtn').click(function () {
            var text = $("#search").val();
            var kind = $("#kind").select().val();
            var search_type = $("#search_type").select().val();

            table.fnReloadAjax('/index.php/dataFunction/keyword_exclude?kind=' + kind + '&search_type=' + search_type + '&text=' + text + '');
        });

        $("#search").keydown(function (key) {
            var text = $("#search").val();
            var kind = $("#kind").select().val();
            var search_type = $("#search_type").select().val();

            if (key.keyCode == 13) {
                table.fnReloadAjax('/index.php/dataFunction/keyword_exclude?kind=' + kind + '&search_type=' + search_type + '&text=' + text + '');
            }
        });

        $("#del_btn").click(function () {
            var idxs = $(this).val();

            var data = {idxs: idxs};

            $.ajax({
                dataType: 'text',
                url: '/index.php/dataFunction/del_keyword_exclude',
                data: data,
                type: 'POST',
                success: function (data, status, xhr) {
                    alert("삭제 되었습니다.");
                    $("#keywordDelModal").modal('hide');
                    var table = $('#exclueTable').dataTable();
                    table.fnReloadAjax('/index.php/dataFunction/keyword_exclude');
                }
            });
        });

        $("#excelForm").ajaxForm({
            success: function (data) {
                if (data == 'ERR_FORMAT') {
                    alert('엑셀파일만 업로드 가능합니다. (xls, xlsx 확장자의 파일포멧)');
                    return false;
                }

                if (data == 'ERR_UPLOAD') {
                    alert('업로드된 파일을 옮기는 중 에러가 발생했습니다.');
                    return false;
                }

                if (data == 'NO_DATA') {
                    alert('데이터가 없습니다.');
                    return false;
                }
                $("#excelViewModal").modal();
                $("#excelViewBody").html(data);

                $("#excellLoadForm").ajaxForm({
                    success: function (data) {
                        if (data == 'SUCCESS') {
                            alert("업로드 되었습니다.");
                            $("#excelViewModal").modal('hide');
                            $("#keywordInsModal").modal('hide');

                            table.fnReloadAjax('/index.php/dataFunction/keyword_exclude');
                            return false;
                        }
                    }, error: function () {
                        alert("데이터 처리오류!!");
                        return false;
                    }
                });
            },
            error: function () {
                alert("데이터 처리오류!!");
                return false;
            }
        });
    });
</script>



