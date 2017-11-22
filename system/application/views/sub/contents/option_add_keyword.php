<div class="input-group">
    <span class="add-on input-group-addon">
        <?= $keyword ?>
        <input type="hidden" name="keyword[]" value="<?= $keyword ?>">
        <input type="hidden" name="main_id[]" value="<?= $index ?>">
        <i class="fa fa-close del_row_btn"></i>
    </span>
    <div class="add_ipt_row input-group">
        <input type="text" class="tags form-control" name="keyword_row[<?= $index ?>][]">
        <span class="input-group-btn">
            <button type="button" class="btn btn-default add_sub_row" style="display: block;" value="<?= $index ?>">+</button>
        </span>
    </div>
</div>