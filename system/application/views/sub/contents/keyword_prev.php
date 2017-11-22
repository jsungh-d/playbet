<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <h3>사전 등록 <small>새로운 단어(이슈 단어)를 사전으로 등록, 관리합니다.</small></h3>
    </div>

    <div class="x_panel">
        <div class="x_title">
            <h2>단어 수 : <small id="rowtotal">0000건</small></h2>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <table id="keyword_prevTable" class="table table-bordered bulk_action">
                <colgroup><col width="40px"><col width="80px"><col width="250px"><col width="*"></colgroup>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="check-all"></th>
                        <th>번호</th>
                        <th>단어명</th>
                        <th>동의어</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

            <!--키워드삭제모달-->
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
                        <form method="post" enctype="multipart/form-data" id="excelForm" action="/index.php/dataFunction/loadExcel">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">단어 일괄 등록</h4>
                            </div>
                            <div class="modal-body">
                                <input type="file" name="file" id="file_allupload" required>

                            </div>
                            <div class="modal-footer">
                                <div class="pull-left">
                                    <a href="/images/common/dic_sample.xls" class="text-left">샘플 다운로드.xls</a>
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

        </div>
    </div>

</div>

<!-- /page content -->
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

        var table = $('#keyword_prevTable').dataTable({
            dom: '<"datatable_header"fl>t<"datatable_footer"Bp>',
            ajax: '/index.php/dataFunction/dictionaryLists',
            columns: [
                {data: "checkbox"},
                {data: "num"},
                {data: "keyword"},
                {data: "synonym"}
            ],
            columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                },
                {
                    className: "cursor", "targets": [2]
                }
            ],
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
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
                    text: '단어 일괄 등록',
                    action: function () {
                        $("#keywordInsModal").modal();
                    }
                },
                {
                    className: 'btn btn-sm btn-primary',
                    text: '단어 추가',
                    action: function () {
                        location.href = '/index/keyword_add';
                    }
                }
            ],
            language: {
                lengthMenu: "_MENU_",
                search: "",
                paginate: {
                    "next": "&raquo",
                    "previous": "&laquo"
                }
            },
            footerCallback: function (row, data, start, end, display) {
                $("#rowtotal").html(numberWithCommas(data.length) + '건');
            }
        });

        $('#keyword_prevTable tbody').on('click', 'td.cursor', function () {
            var index = $(this).index('#keyword_prevTable tbody td.cursor');
            var idx = $(".row_check:eq(" + index + ")").val();

            location.href = '/index/keyword_mod/' + idx + '';
        });

        $("#del_btn").click(function () {
            var idxs = $(this).val();

            var data = {idxs: idxs};

            $.ajax({
                dataType: 'text',
                url: '/index.php/dataFunction/del_dictionary',
                data: data,
                type: 'POST',
                success: function (data, status, xhr) {
                    alert("삭제 되었습니다.");
                    $("#keywordDelModal").modal('hide');
                    var table = $('#keyword_prevTable').dataTable();
                    table.fnReloadAjax('/index.php/dataFunction/dictionaryLists');
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

                            table.fnReloadAjax('/index.php/dataFunction/dictionaryLists');
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

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>