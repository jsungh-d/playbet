<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <h3>팝업 관리 <small>사용자에게 팝업을 발송, 발송 된 팝업을 관리 할 수 있습니다.</small></h3>
    </div>

    <div class="x_panel">
        <div class="x_content">
            <br />
            <form method="post" enctype="multipart/form-data" action="/index.php/dataFunction/insPopUp">
                <table class="table table-bordered">
                    <colgroup><col width="120px"><col width="*"></colgroup>
                    <tbody>
                        <tr>
                            <th>팝업 노출 기간</th>
                            <td>
                                <div class="form-inline">
                                    <div class="input-group date popup" data-provide="datepicker">
                                        <div class="input-group-addon">
                                            <span class="fa fa-calendar"></span>
                                        </div>
                                        <input type="text" class="form-control" id="sdate" name="popup_start_day" placeholder="시작일" value="" required>
                                    </div>
                                    <div class="input-group date popup" data-provide="datepicker">
                                        <div class="input-group-addon">
                                            <span class="fa fa-calendar"></span>
                                        </div>
                                        <input type="text" class="form-control" id="edate" name="popup_end_day" placeholder="종료일" value="" required>
                                    </div>

                                    <div class="btn-group" data-toggle="buttons">
                                        <label id="onday_button" class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                            <input type="checkbox" id="oneday"> 당일 하루
                                        </label>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <th>제목</th>
                            <td><input type="text" class="form-control" name="popup_title" required /></td>
                        </tr>
                        <tr>
                            <th>내용</th>
                            <td>
                                <textarea required="required" class="form-control" name="popup_contents" style="resize: none; height:100px;"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>이미지 첨부</th>
                            <td>
                                <input type='file' id="imgInp" name="file" accept="image/*"/>

                                <div class="file_img">
                                    <img id="blah" src="" alt="이미지를 선택해주세요" style="display: none;"/>
                                    <button class="file_img_del" type="button" id="delImgBtn" style="display: none;">삭제</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>서비스 노출</th>
                            <td>
                                <select class="form-control" name="popup_status" required>
                                    <option value="1">노출</option>
                                    <option value="0">미노출</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group text-right">
                    <button class="btn btn-sm btn-primary" type="button" onclick="location.href = '/index/popup'">취소</button>
                    <button type="submit" class="btn btn-sm btn-success">등록</button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- /page content -->

<script type="text/javascript">
    $(function () {
        $("#imgInp").on('change', function () {
            readURL(this);
        });

        $("#onday_button").click(function () {
            if (!$(this).hasClass('active')) {
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth() + 1; //January is 0!
                var yyyy = today.getFullYear();

                if (dd < 10) {
                    dd = '0' + dd;
                }

                if (mm < 10) {
                    mm = '0' + mm;
                }

                today = yyyy + '-' + mm + '-' + dd;

                $("#sdate").val(today);
                $("#edate").val(today);
            } else {
                $("#sdate").val("");
                $("#edate").val("");
            }

        });

        $('input').on('ifChecked', function (event) {

            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            var yyyy = today.getFullYear();

            if (dd < 10) {
                dd = '0' + dd;
            }

            if (mm < 10) {
                mm = '0' + mm;
            }

            today = yyyy + '-' + mm + '-' + dd;

            $("#sdate").val(today);
            $("#edate").val(today);
//            alert(event.type + ' callback');
        });

        $('input').on('ifUnchecked', function (event) {
            $("#sdate").val("");
            $("#edate").val("");
        });

//        $("#edate").change(function () {
//            var sdate = $("#sdate").val();
//            var edate = $("#edate").val();
//
//            if (!sdate) {
//                alert("시작일을 먼저 선택해주세요.");
//                $(this).val('');
//                return false;
//            }
//
//            if (sdate > edate) {
//                alert("노출기간이 올바르지 않습니다.");
//                $(this).val('');
//                return false;
//            }
//
//        });

        $("#delImgBtn").click(function () {
            $("#delImgBtn").hide();
            $("#blah").hide();
            $('#blah').attr('src', '');
            $("#imgInp").val('');
        });
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $("#blah").show();
                $('#blah').attr('src', e.target.result);
                $("#delImgBtn").show();
            }

            reader.readAsDataURL(input.files[0]);
        }
    }


</script>