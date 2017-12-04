<?php foreach ($lists as $row) { ?>
    <div class="contents">
        <div class="contents_main">
            <h4 class="contents_class"> 
                <!-- 아이디 클릭시 페이지 이동 주소 -->
                <!-- href="/index/user_info/<?= $row['MEMBER_IDX'] ?>" -->
                <a style="cursor: default;"><?= $row['BUSINESS_NAME'] ?></a>
                > 
                <?php if ($row['CATEGORY_TYPE'] == 'AREA') { ?>
                    <a onclick="locationSearchArea('<?= $row['SIGUNGU'] ?>');"><?= $row['LOCATION'] ?></a>
                <?php } else { ?>
                    <!--<a onclick="subCategorySearch('<?= $row['CATEGORY_IDX'] ?>', '<?= $row['CATEGORY_PNUM'] ?>');"><?= $row['CATEGORY_TYPE'] ?></a>-->
                    <a onclick="locationSearch('<?= $row['CATEGORY_TYPE'] ?>');"><?= $row['CATEGORY_TYPE'] ?></a>
                <?php } ?>
                > 
                <a href="/index/view/<?= $row['BOARD_IDX'] ?>/?scroll=true"><span class="text_darkblue"><strong><?= $row['TITLE'] ?></strong></span></a>
            </h4>
            <h3 class="contents_hour">
                <span class="text_blue">
                    <?php
                    $date1 = new DateTime($row['NOW']);
                    $date2 = new DateTime($row['EFFECTIVE_TIME']);
                    $diff = date_diff($date1, $date2);
                    if ($diff->invert == 0) {
                        echo $diff->d . '일 ' . $diff->h . ':' . $diff->i;
                    } else {
                        echo '종료';
                    }
                    ?>
                </span>
            </h3>

            <a href="/index/view/<?= $row['BOARD_IDX'] ?>">
                <h1 class="contents_title pt15 pb15"><b><?= $row['TIME_LINE'] ?></b></h1>
            </a>
            <?php if ($row['LINK_URL']) { ?>

                <h5>
                    <strong>참조 링크</strong>
                    <br>
                    <a class="index_link_a" href="<?= $row['LINK_URL'] ?>"><?= $row['LINK_URL'] ?></a>
                    <!-- id="link_<?= $i ?>"  -->
                </h5>





            <?php } ?>

            <div class="btn_area pb15 pt15 align_c">
                <?php
                $i = 0;
                foreach (${'item_lists' . $row['BOARD_IDX']} as $subRow) {
                    if ($i == 0) {
                        $color = 'yellow_btn';
                    }

                    if ($i == 1) {
                        $color = 'darkblue_btn';
                    }

                    if ($i == 2) {
                        $color = 'red_btn';
                    }

                    $myClass = '';

                    if ($subRow['MY_CHK'] == 'Y') {
                        $myClass = 'my_chk_btn';
                        // $color = 'default_btn';
                    } else if ($subRow['MY_CHK'] == 'N') {
                        $myClass = '';
                        // $color = 'default_btn';
                    } else if ($subRow['MY_CHK'] == 'NO') {
                        $myClass = '';
                    }

                    if ($subRow['COMP_CNT'] == '0' && count(${'item_lists' . $row['BOARD_IDX']}) == 1 && $subRow['COMP_YN'] == 'Y') {
                        $myClass = 'none_select_btn';
                    }

//                echo $subRow['MY_CHK'];
                    ?>

                    <a class="betting_modal <?= $color ?> color_btn <?= $myClass ?>" onclick="openBetting('<?= $subRow['NAME'] ?>', '<?= $row['TITLE'] ?>', '<?= $row['BOARD_IDX'] ?>', '<?= $subRow['ITEM_IDX'] ?>', '<?= $this->session->userdata('MEMBER_IDX') ?>', '<?= $row['MEMBER_IDX'] ?>', '<?= $diff->invert ?>');">
                        <button type="button" class="<?= $color ?> color_btn"><h3><b class="btn_text"><?= $subRow['NAME'] ?></b>  &nbsp;<strong><?= number_format($subRow['CNT']) ?></strong></h3></button>
                    </a>

                    <?php
                    $i++;
                }
                ?>
            </div>
        </div>
    </div>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function () {
        $(".my_chk_btn").siblings(".color_btn").addClass("none_select_btn");
    });
</script>