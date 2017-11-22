<form style="display: block; height: 100%; width: 100%;" id="excellLoadForm" method="post" action="/index.php/dataFunction/excellDataSave">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">단어 일괄 등록</h4>
    </div>
    <div class="modal-body">
        <table class="tb">
            <div class="form_submit">
                <input type="hidden" name="totalRows" value="<?= $total_rows ?>">
            </div>
            <?php
            $i = 0;
            foreach ($sheetData_a as $row) {
                ?>
                <tr>
                    <td>
                        <?= $i ?>
                    </td>
                    <td>
                        <input type="hidden" name="col_A[]" value="<?= $row['A'] ?>">
                        <?= $row["A"] ?>
                    </td>
                    <td>
                        <input type="hidden" name="col_B[]" value="<?= $row['B']; ?>">
                        <?= $row['B']; ?>
                    </td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </table>
    </div>
    <div class="modal-footer">
        <div class="pull-right">
            <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
            <button type="submit" class="btn btn-primary antosubmit">등록</button>
        </div>
    </div>
</form>