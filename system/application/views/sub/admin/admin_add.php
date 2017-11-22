<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <h3>관리자 계정 <small>관리자 계정을 생성, 삭제 할 수 있습니다.</small></h3>
    </div>

    <div class="x_panel">
        <div class="x_content">
            <br />
            <form class="form-horizontal form-label-left" method="post" action="/index.php/dataFunction/insAdmin" autocomplete="off">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">이름</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" name="admin_name" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">아이디</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" name="admin_id" id="admin_id" onblur="chkId();" required autocomplete="new-id">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">패스워드</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="password" name="admin_pass" class="form-control" required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">접속 권한</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="form-control" name="admin_level" style="margin-bottom: 15px;">
                            <option value="A">관리자</option>
                            <option value="O">운영자</option>
                        </select>
                        <?php foreach ($top_menu_lists as $row) { ?>
                            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                <p style="font-weight:600;"><?= $row['menu_name'] ?></p>
                                <?php foreach (${'sub_menu_lists' . $row['menu_seq']} as $subRow) { ?>
                                    <label style="display: block; font-weight:400;">
                                        <input type="checkbox" name="menu_seq_list[]" value="<?= $subRow['menu_seq'] ?>"> <?= $subRow['menu_name'] ?>
                                    </label>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">접속 허용</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="form-control" name="acept">
                            <option value="1">허용</option>
                            <option value="0">비허용</option>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                        <button type="button" class="btn btn-default" onclick="location.href = '/index/admin'">취소</button>
                        <button type="submit" class="btn btn-primary">등록</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function chkId() {
        var reg_id = /^[a-z0-9_-]{4,12}$/;
        if (!reg_id.test($("#admin_id").val())) {
            alert("아이디는 4-12자 이여야 하며 \n"
                    + "마침표, '-', '_'를 제외한 문자(한글포함)는 사용하실수 없습니다.");
            $("#admin_id").val('');
            return false;
        }

        var id = $("#admin_id").val();

        if (!$.trim(id)) {
            alert("아이디를 입력해주세요.");
            $("#admin_id").val('');
            return false;
        }

        var data = {id: id};
        $.ajax({
            dataType: 'text',
            url: '/index.php/dataFunction/chk_adminId',
            data: data,
            type: 'POST',
            success: function (data, status, xhr) {
                if (data == 'DUPLE') {
                    alert("이미 등록된 아이디입니다.");
                    $("#admin_id").val('');
//                    $("#admin_id").focus();
                    return false;
                }
            }
        });
    }
</script>