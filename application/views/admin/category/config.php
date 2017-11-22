<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>카테고리 관리</h3>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>카테고리 리스트 관리<small>추가시 오른쪽 끝 +버튼을 눌러주세요.</small></h2>
                    <ul class="nav navbar-right panel_toolbox" style="min-width: 0;">
                        <li>
                            <a data-toggle="modal" data-target="#addModal">
                                <i class="fa fa-plus"></i>
                            </a>

                            <div id="addModal" title="Client 등록" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title" id="myModalLabel">카테고리 등록</h4>
                                        </div>
                                        <form method="post" class="form-horizontal" enctype="multipart/form-data" action="/index.php/dataFunction/addCategory">
                                            <div class="modal-body">
                                                <div id="testmodal" style="padding: 5px 20px;">
<!--                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">상위카테고리</label>
                                                        <div class="col-sm-8">
                                                            <select name="pnum" class="form-control">
                                                                <option value="0">최상위카테고리</option>
                                                                <?php foreach ($plists as $row) { ?>
                                                                    <option value="<?= $row['CATEGORY_IDX'] ?>"><?= $row['NAME'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>-->
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">카테고리명</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name="name" placeholder="카테고리명" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">노출순서</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name="show_level" placeholder="노출순서(숫자만입력)" pattern="[0-9]*">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">사용여부</label>
                                                        <div class="col-sm-8">
                                                            <select name="use_yn" class="form-control">
                                                                <option value="Y">사용</option>
                                                                <option value="N">미사용</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                                <button type="submit" class="btn btn-primary antosubmit">등록</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <!-- start project list -->
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th>카테고리명</th>
                                <th>노출순서</th>
                                <th>사용여부</th>
                                <th>관리</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lists as $row) { ?>
                                <tr>
                                    <td>
                                        <a><?= $row['NAME'] ?></a>
                                    </td>
                                    <td>
                                        <?= $row['SHOW_LEVEL'] ?>
                                    </td>
                                    <td class="project_progress">
                                        <?= $row['USE_YN'] ?>
                                    </td>
                                    <td>
                                        <!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
                                        <a href="#" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modModal<?= $row['CATEGORY_IDX'] ?>"><i class="fa fa-pencil"></i>수정</a>
                                        <div id="modModal<?= $row['CATEGORY_IDX'] ?>" title="<?= $row['NAME'] ?>수정"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h4 class="modal-title" id="myModalLabel"><?= $row['NAME'] ?> 수정</h4>
                                                    </div>
                                                    <form method="post" class="form-horizontal" role="form" enctype="multipart/form-data" action="/index.php/dataFunction/modCategory">
                                                        <input type="hidden" name="category_idx" value="<?= $row['CATEGORY_IDX'] ?>">
                                                        <div class="modal-body">
                                                            <div id="testmodal" style="padding: 5px 20px;">
<!--                                                                <div class="form-group">
                                                                    <label class="col-sm-4 control-label">상위카테고리</label>
                                                                    <div class="col-sm-8">
                                                                        <select name="pnum" class="form-control">
                                                                            <option value="0">최상위카테고리</option>
                                                                            <?php foreach ($plists as $subRow) { ?>
                                                                                <option value="<?= $subRow['CATEGORY_IDX'] ?>" <?php if ($subRow['CATEGORY_IDX'] == $row['PNUM']) echo 'selected'; ?>><?= $subRow['NAME'] ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>-->
                                                                <div class="form-group">
                                                                    <label class="col-sm-4 control-label">카테고리명</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" name="name" value="<?= $row['NAME'] ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-4 control-label">노출순서</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" name="show_level" value="<?= $row['SHOW_LEVEL'] ?>" placeholder="노출순서(숫자만입력)" pattern="[0-9]*">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-4 control-label">사용여부</label>
                                                                    <div class="col-sm-8">
                                                                        <select name="use_yn" class="form-control">
                                                                            <option value="Y" <?php if ($row['USE_YN'] == 'Y') echo 'selected'; ?>>사용</option>
                                                                            <option value="N" <?php if ($row['USE_YN'] == 'N') echo 'selected'; ?>>미사용</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                                            <button type="submit" class="btn btn-primary antosubmit"><i class="fa fa-pencil"></i>수정</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!--<a href="#" class="btn btn-danger btn-xs" onclick="delContents(<?= $row['CATEGORY_IDX'] ?>);"><i class="fa fa-trash-o"></i>삭제</a>-->
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <!-- end project list -->

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function delContents(idx) {
        if (confirm("삭제 하시겠습니까?") == true) {    //확인
            var data = {idx: idx};
            $.ajax({
                dataType: 'text',
                url: '/index.php/dataFunction/delClient',
                data: data,
                type: 'POST',
                success: function (data, status, xhr) {
                    alert("삭제 되었습니다.");
                    location.reload();
                }
            });
        } else {
            return false;
        }
    }

    function delImg(idx) {
        $("#imgArea" + idx + "").remove();
        var html = '<input type="file" name="file" placeholder="기본이미지"> <input type="hidden" name="location1" value="">';
        $("#addImgArea" + idx + "").append(html);
    }
</script>