<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>관리자 정보</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>
                <form class="form-horizontal" name="modForm" method="post" enctype="multipart/form-data" action="/index.php/dataFunction/adminModfiy">

                    <div class="form-group">
                        <label class="control-label col-md-1 col-sm-3 col-xs-12">아이디</label>
                        <div class="col-md-11 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" name="admin_id" placeholder="아이디" value="<?= $info->ID ?>" readonly required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-1 col-sm-3 col-xs-12">비밀번호</label>
                        <div class="col-md-11 col-sm-9 col-xs-12">
                            <input type="password" class="form-control" name="admin_pwd" placeholder="비밀번호" value="<?= $info->PWD ?>" required>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <!--                                <button type="button" class="btn btn-primary">Cancel</button>
                                                            <button type="reset" class="btn btn-primary">Reset</button>-->
                            <button type="submit" class="btn btn-success">수정</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->
<script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">
    function openDaumPostcode() {
        new daum.Postcode({
            oncomplete: function (data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
                // 우편번호와 주소 정보를 해당 필드에 넣고, 커서를 상세주소 필드로 이동한다.
                $("#zip_code").val(data.postcode1 + "-" + data.postcode2);
//                                        document.getElementById('post2').value = data.postcode2;
//                                        document.getElementById('addr').value = data.address;
                $("#addr").val(data.address);

                //전체 주소에서 연결 번지 및 ()로 묶여 있는 부가정보를 제거하고자 할 경우,
                //아래와 같은 정규식을 사용해도 된다. 정규식은 개발자의 목적에 맞게 수정해서 사용 가능하다.
                //var addr = data.address.replace(/(\s|^)\(.+\)$|\S+~\S+/g, '');
                //document.getElementById('addr').value = addr;

                document.getElementById('detail_addr').focus();
            }
        }).open();
    }

    function delLogo() {
        $("#logoFile").remove();
        var html = '<input type="file" name="logo">';
        $("#logo_add").append(html);
    }
</script>