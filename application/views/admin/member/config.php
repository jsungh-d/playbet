<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>회원 관리</h3>
        </div>
        <div class="title_right">
            <div class="col-md-10 col-sm-10 col-xs-12 form-group pull-right top_search">
                <div class="input-group" style="width:60%; float:right;">
                    <input type="text" class="form-control" id="search_text" placeholder="Search for..." value="" style="display:inline-block;">
                    <span class="input-group-btn">
                        <button class="btn btn-default" id="search_btn" type="button">Go!</button>
                    </span>
                </div>
                <select id="search_select" style="">
                    <option value="name">이름</option>
                    <option value="phone">연락처</option>
                </select>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">
                    <!-- start project list -->
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>회원번호</th>
                                <th>가입경로</th>
                                <th>이름</th>
                                <th>이메일</th>
                                <th>구분</th>
                                <th>연락처</th>
                                <th>사업자명</th>
                                <th>가입일</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lists as $row) { ?>
                            <tr>
                                <td>
                                    <?= $row['MEMBER_IDX'] ?>
                                </td>
                                <td>
                                    <?= $row['JOIN_ROOT'] ?>
                                </td>
                                <td>
                                    <?= $row['NAME'] ?>
                                </td>
                                <td>
                                    <?= $row['EMAIL'] ?>
                                </td>
                                <td>
                                    <select id="<?= $row['MEMBER_IDX'] ?>" class="member_type">
                                        <option value="N" <?php if ($row['TYPE'] == 'N') echo 'selected'; ?>>일반회원</option>
                                        <option value="Y" <?php if ($row['TYPE'] == 'Y') echo 'selected'; ?>>프리미엄회원</option>
                                    </select>
                                </td>
                                <td>
                                    <?= $row['PHONE'] ?>
                                </td>
                                <td>
                                    <?= $row['BUSINESS_NAME'] ?>
                                </td>
                                <td>
                                    <?= $row['INS_TIME'] ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <!-- end project list -->
                    <?= $pagination ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".member_type").change(function () {
        var value = $(this).select().val();
        var data = {value: value, idx: $(this).attr('id')};
        if (value == 'Y') {
            alert('프리미엄 회원은 강제로 생성할수 없습니다.');
            $(this).select().val('N');
            return false;
        }
        
        if (confirm("회원권한 변경 하시겠습니까?") === true) {    //확인
            $.ajax({
                dataType: 'text',
                url: '/index.php/dataFunction/memberTypeChange',
                data: data,
                type: 'POST',
                success: function (data, status, xhr) {
                    alert("변경 되었습니다.");
                }
            });
        } else {
            if (value == 'N') {
                $(this).select().val('Y');
            } else {
                $(this).select().val('N');
            }
            return false;
        }
    });

     $("#search_text").keydown(function (key) {
            var gubun = $("#search_select").val();
            var text = $("#search_text").val();

            if (!$.trim(text)) {
                text = 'none';
            }

            if (key.keyCode == 13) {
                if (text !== 'none') {
                    location.href = '/admin/memberConfig/q/gubun/' + gubun + '/text/' + text;
                } else {
                    location.href = '/admin/memberConfig';
                }
            }
        });

        $("#search_btn").click(function () {
            var gubun = $("#search_select").val();
            var text = $("#search_text").val();

            if (!$.trim(text)) {
                text = 'none';
            }

            if (text !== 'none') {
                location.href = '/admin/memberConfig/q/gubun/' + gubun + '/text/' + text;
            } else {
                location.href = '/admin/memberConfig';
            }
        });
</script>