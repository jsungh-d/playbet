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
                            <?php
                            $date_class = 'rank';
                            if ($this->uri->segment(6) == 'week') {
                                $date_class = 'date_week rank_week';
                            } else if ($this->uri->segment(6) == 'month') {
                                $date_class = 'date_month rank_month';
                            }
                            ?>
                            <div class="input-group date <?= $date_class ?>" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                                <div class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </div>
                                <input type="text" class="form-control" id="sdate" placeholder="시작일" value="<?= $sdate ?>">
                            </div>
                            <div class="input-group date <?= $date_class ?>" data-provide="datepicker" data-date-format="yyyy-mm-dd">
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
                                <button class="btn btn-primary" type="button" onclick="dayChange($('#sdate').val(), $('#edate').val(), '<?= $search_day ?>', 'search')">검색</button>
                            </div>
                        </div>
                        <div class="btn-group" data-toggle="buttons">
                            <button type="button" class="btn btn-info <?php if ($this->uri->segment(6) == 'day' || !$this->uri->segment(6)) echo 'active'; ?>" onclick="dayChange('<?= date("Y-m-d", strtotime("-1 week")) ?>', '<?= date("Y-m-d", strtotime("-1 day")) ?>', 'day', 'change');" data-toggle="tooltip" data-placement="top" title="일간 데이터를 보여줍니다. (당일 선택 안됨)">일간</button>
                            <?php
                            //오늘 날짜 출력
                            $today_date = date('Y-m-d');

                            //일주일전
                            $prev_week_day = date('Y-m-d', strtotime("-1 week"));

                            //주의 마지막까지 남은날짜
                            $day_last = date('w');

                            //주의 마지막 날짜
                            $day_of_the_week = date('Y-m-d', strtotime("-$day_last day"));

                            //오늘의 첫째주인 날짜 출력 ex) 2013-04-07 (일요일임) 
                            $a_week_ago = date('Y-m-d', strtotime($today_date . " -" . $day_of_the_week . "days"));
                            $a_week_ago2 = date('Y-m-d', strtotime($today_date . " -" . $day_last . "days"));

                            //특정일의 첫째주인 날짜 출력
                            $start_week_ago = date("Y-m-d", strtotime("$a_week_ago2 -3week"));
                            ?>
                            <button type="button" class="btn btn-info <?php if ($this->uri->segment(6) == 'week') echo 'active'; ?>" onclick="dayChange('<?= date("Y-m-d", strtotime("$start_week_ago -6 day")) ?>', '<?= $day_of_the_week ?>', 'week', 'change');" data-toggle="tooltip" data-placement="top" title="한주간의 데이터를 보여줍니다. (시작일 월요일, 종료일 일요일)">주간</button>
                            <?php
                            $range_day = date('Ym01');

                            $prev_date = date("Y-m-d", strtotime("-1 month"));
                            $prev_date_exp = explode("-", $prev_date);
                            $end_day = date("t", mktime(0, 0, 0, $prev_date_exp[1], 1, $prev_date_exp[0]));
                            $range_day2 = $prev_date_exp[0] . "-" . $prev_date_exp[1] . "-" . $end_day;
                            ?>
                            <button type="button" class="btn btn-info <?php if ($this->uri->segment(6) == 'month') echo 'active'; ?>" onclick="dayChange('<?= date("Y-m-d", strtotime("$range_day - 6 month")) ?>', '<?= $range_day2 ?>', 'month', 'change');" data-toggle="tooltip" data-placement="top" title="월간 데이터를 보여줍니다. (당월 선택 안됨)">월간</button>
                            <button type="button" class="btn btn-info" onclick="excelDown();">다운로드</button>
                        </div>

                    </form>
                </div>
                <div class="form-group">
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <select id="kind" class="form-control" style="margin-bottom:15px">
                            <!--<option value="1" <?php if ($kind == 1) echo 'selected'; ?>>관심종목</option>-->
                            <option value="2" <?php if ($kind == 2) echo 'selected'; ?>>인기종목</option>
                            <option value="3" <?php if ($kind == 3) echo 'selected'; ?>>인기키워드</option>
                        </select>
                    </div>
                </div>
                <table id="rank_datatable" class="table table-bordered datatable">
                    <thead>
                        <tr>
                            <td></td>
                            <?php
                            $day = array('일', '월', '화', '수', '목', '금', '토');
                            for ($i = 0; $i < 7; $i++) {
                                ?>
                                <td>2017-03-2<?= $i ?>(<?= $day[$i] ?>)</td>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 1; $i < 10; $i++) { ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>테스트<?= $i ?></td>
                                <td>테스트<?= $i ?></td>
                                <td>테스트<?= $i ?></td>
                                <td>테스트<?= $i ?></td>
                                <td>테스트<?= $i ?></td>
                                <td>테스트<?= $i ?></td>
                                <td>테스트<?= $i ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="x_content2">
                    <!--우리들제약-->
                    <?php
                    $chart_data = "";
                    for ($i = 0; $i < 7; $i++) {
                        $chart_data .= "{d: '2" . $i . "일', value: " . $i . "},";
                    }
//                    foreach ($chart_datas as $row) {
//                        $chart_data .= "{d: '" . $row['DAY_NAME'] . "', value: " . $row['SUM_VALUE'] . "},";
//                    }
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