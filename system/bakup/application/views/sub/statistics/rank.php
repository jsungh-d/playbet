<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <h3>컨텐츠 랭킹 <small>컨텐츠 랭킹 TOP 100을 확인 할 수 있습니다.</small></h3>
        </div>
        <div class="x_panel">

            <div class="x_content">
                <br />

                <div class="form-group">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="date01">조회 일자</label>
                            <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                                <div class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </div>
                                <input type="text" class="form-control" id="sdate" placeholder="시작일" value="<?= $sdate ?>">
                            </div>
                            <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                                <div class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </div>
                                <input type="text" class="form-control" id="edate" placeholder="종료일" value="<?= $edate ?>">
                            </div>
                            <div class="input-group">
                                <?php
                                if (!$this->uri->segment(6) || $this->uri->segment(6) == 'day') {
                                    $search_day = 'day';
                                }

                                if ($this->uri->segment(6) == 'week') {
                                    $search_day = 'week';
                                }

                                if ($this->uri->segment(6) == 'month') {
                                    $search_day = 'month';
                                }
                                ?>
                                <button class="btn btn-primary" type="button" onclick="dayChange($('#sdate').val(), $('#edate').val(), '<?= $search_day ?>')">검색</button>
                            </div>
                        </div>
                        <div class="btn-group" data-toggle="buttons">
                            <button type="button" class="btn btn-info <?php if ($this->uri->segment(6) == 'day' || !$this->uri->segment(6)) echo 'active'; ?>" onclick="dayChange('<?= date("Y-m-d", strtotime("-1 week")) ?>', '<?= date("Y-m-d", strtotime("-1 day")) ?>', 'day');" data-toggle="tooltip" data-placement="top" title="일간 데이터를 보여줍니다. (당일 선택 안됨)">일간</button>
                            <?php
                            //오늘 날짜 출력
                            $today_date = date('Y-m-d');

                            //일주일전
                            $prev_week_day = date('Y-m-d', strtotime("-1 week"));

                            //주의 마지막까지 남은날짜
                            $day_last = date('w');

                            //주의 마지막 날짜
                            $day_of_the_week = date('w') + $day_last;

                            //오늘의 첫째주인 날짜 출력 ex) 2013-04-07 (일요일임) 
                            $a_week_ago = date('Y-m-d', strtotime($today_date . " -" . $day_last . "days"));

                            //특정일의 첫째주인 날짜 출력
                            $start_week_ago = date("Y-m-d", strtotime("$a_week_ago -3week"));
                            ?>
                            <button type="button" class="btn btn-info <?php if ($this->uri->segment(6) == 'week') echo 'active'; ?>" onclick="dayChange('<?= date("Y-m-d", strtotime("$start_week_ago -6 day")) ?>', '<?= $a_week_ago ?>', 'week');" data-toggle="tooltip" data-placement="top" title="한주간의 데이터를 보여줍니다. (시작일 월요일, 종료일 일요일)">주간</button>
                            <?php
                            $range_day = date('Ym01');

                            $prev_date = date("Y-m-d", strtotime("-1 month"));
                            $prev_date_exp = explode("-", $prev_date);
                            $end_day = date("t", mktime(0, 0, 0, $prev_date_exp[1], 1, $prev_date_exp[0]));
                            $range_day2 = $prev_date_exp[0] . "-" . $prev_date_exp[1] . "-" . $end_day;
                            ?>
                            <button type="button" class="btn btn-info <?php if ($this->uri->segment(6) == 'month') echo 'active'; ?>" onclick="dayChange('<?= date("Y-m-d", strtotime("$range_day - 6 month")) ?>', '<?= $range_day2 ?>', 'month');" data-toggle="tooltip" data-placement="top" title="월간 데이터를 보여줍니다. (당월 선택 안됨)">월간</button>
                            <button type="button" class="btn btn-info" onclick="excelDown();">다운로드</button>
                        </div>

                    </form>
                </div>
                <div class="form-group">
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <select id="kind" class="form-control" style="margin-bottom:15px">
                            <option value="1" <?php if ($kind == 1) echo 'selected'; ?>>관심종목</option>
                            <option value="2" <?php if ($kind == 2) echo 'selected'; ?>>인기종목</option>
                            <option value="3" <?php if ($kind == 3) echo 'selected'; ?>>인기키워드</option>
                        </select>
                    </div>
                </div>
                <table id="rank_datatable" class="table table-bordered datatable">
                    <thead>
                        <tr>
                            <td></td>
                            <?php foreach ($dayList as $row) { ?>
                                <td><?= $row['DAY_NAME'] ?></td>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < $cnt_row->MAX_CNT; $i++) { ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <?php foreach ($dayList as $row) { ?>
                                    <?php if (${'rank_list' . $row['DAY_FORMAT'] . $i}) { ?>
                                        <?php foreach (${'rank_list' . $row['DAY_FORMAT'] . $i} as $row) {
                                            ?>
                                            <td><?= $row['company_name_i'] ?></td>
                                        <?php }
                                        ?>
                                    <?php } else { ?>
                                        <td>-</td>
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="x_content2">
                    <!--우리들제약-->
                    <?php
                    $chart_data = "";
                    foreach ($chart_datas as $row) {
                        $chart_data .= "{d: '" . $row['DAY_NAME'] . "', value: " . $row['SUM_VALUE'] . "},";
                    }
                    ?>
                    <div id="line_chart" style="width:100%; height:300px;"></div>
                </div>
            </div>

        </div>
    </div>

    <!-- /page content -->


    <!-- morris.js -->
    <script src="/vendors/raphael/raphael.min.js"></script>
    <script src="/vendors/morris.js/morris.min.js"></script>
    <script type="text/javascript">

                                $(document).ready(function () {
                                    $('.datepicker').datepicker();

                                    var table = $('#rank_datatable').dataTable({
                                        dom: '<"datatable_header"fl>t<"datatable_footer"p>',
                                        language: {
                                            lengthMenu: "_MENU_",
                                            search: "",
                                            paginate: {
                                                "next": "&raquo",
                                                "previous": "&laquo"
                                            }
                                        }
                                    });

                                    new Morris.Line({
                                        element: 'line_chart',
                                        data: [
<?= substr($chart_data, 0, -1) ?>
                                        ],
                                        xkey: 'd',
                                        ykeys: ['value'],
                                        labels: ['Value'],
                                        parseTime: false
                                    });

                                    $("#kind").change(function () {
                                        var kind = $("#kind").select().val();
                                        var sdate = $("#sdate").val();
                                        var edate = $("#edate").val();
                                        location.href = '/index/rank/' + sdate + '/' + edate + '/' + kind;
                                    });
                                });

                                function dayChange(sdate, edate, day) {
                                    var kind = $("#kind").select().val();
                                    location.href = '/index/rank/' + sdate + '/' + edate + '/' + kind + '/' + day;
                                }
    </script>