<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>프리미엄 신청 관리</h3>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>프리미엄 신청 관리</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <!-- start project list -->
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>회원번호</th>
                                <th>신청자명</th>
                                <th>연락처</th>
                                <th>이메일</th>
                                <th>금액</th>
                                <th>입금확인</th>
                                <th>신청일</th>
                                <th>프리미엄종료일</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$lists) { ?>
                                <tr>
                                    <td colspan="8" style="text-align: center">
                                        신청회원이 없습니다.
                                    </td>
                                </tr>
                                <?php
                            } else {
                                foreach ($lists as $row) {
                                    ?>
                                    <tr>
                                        <td>
                                            <a><?= $row['MEMBER_IDX'] ?></a>
                                        </td>
                                        <td>
                                            <a><?= $row['NAME'] ?></a>
                                        </td>
                                        <td>
                                            <a><?= $row['PHONE'] ?></a>
                                        </td>
                                        <td>
                                            <a><?= $row['EMAIL'] ?></a>
                                        </td>
                                        <td>
                                            <a><?= $row['PRICE'] ?></a>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-default ok_btn" value="<?= $row['MEMBER_IDX'] ?>" data-bind="<?= $row['PREMIUM_MONTH'] ?>">완료하기</button>
                                        </td>
                                        <td>
                                            <a><?= $row['PREMIUM_DATE'] ?></a>
                                        </td>
                                        <td>
                                            <a><?= $row['PREMIUM_MONTH'] ?>개월후</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <!-- end project list -->

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".ok_btn").click(function () {
            var data = {idx: $(this).val(), month: $(this).attr('data-bind')};
            if (confirm("입금확인처리 하시겠습니까?") === true) {    //확인
                $.ajax({
                    dataType: 'text',
                    url: '/index.php/dataFunction/premiumOk',
                    data: data,
                    type: 'POST',
                    success: function (data, status, xhr) {
                        alert("확인 되었습니다.");
                        location.reload();
                    }
                });
            } else {
                return false;
            }
        });
    });
</script>