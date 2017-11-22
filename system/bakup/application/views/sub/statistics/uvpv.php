<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <h3>UV / PV <small>UV / PV 통계를 확인 할 수 있습니다.</small></h3>
        </div>
        <div class="x_panel">

            <div class="x_content">
                <br />

                <div class="form-group">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="date01">조회 일자</label>
                            <?php
                            $date_class = '';
                            if ($this->uri->segment(5) == 'week') {
                                $date_class = 'date_week';
                            } else if ($this->uri->segment(5) == 'month') {
                                $date_class = 'date_month';
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
                                if (!$this->uri->segment(5) || $this->uri->segment(5) == 'day') {
                                    $search_day = 'day';
                                }

                                if ($this->uri->segment(5) == 'week') {
                                    $search_day = 'week';
                                }

                                if ($this->uri->segment(5) == 'month') {
                                    $search_day = 'month';
                                }
                                ?>
                                <button class="btn btn-primary" type="button" onclick="dayChange($('#sdate').val(), $('#edate').val(), '<?= $search_day ?>', 'search')">검색</button>
                            </div>
                        </div>
                        <div class="btn-group" data-toggle="buttons">
                            <button type="button" class="btn btn-info <?php if ($this->uri->segment(5) == 'day' || !$this->uri->segment(5)) echo 'active'; ?>" onclick="dayChange('<?= date("Y-m-d", strtotime("-1 week")) ?>', '<?= date("Y-m-d", strtotime("-1 day")) ?>', 'day', 'change');" data-toggle="tooltip" data-placement="top" title="일간 데이터를 보여줍니다. (당일 선택 안됨)">일간</button>
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
                            <button type="button" class="btn btn-info <?php if ($this->uri->segment(5) == 'week') echo 'active'; ?>" onclick="dayChange('<?= date("Y-m-d", strtotime("$start_week_ago -6 day")) ?>', '<?= $day_of_the_week ?>', 'week', 'change');" data-toggle="tooltip" data-placement="top" title="한주간의 데이터를 보여줍니다. (시작일 월요일, 종료일 일요일)">주간</button>
                            <?php
                            $range_day = date('Ym01');

                            $prev_date = date("Y-m-d", strtotime("-1 month"));
                            $prev_date_exp = explode("-", $prev_date);
                            $end_day = date("t", mktime(0, 0, 0, $prev_date_exp[1], 1, $prev_date_exp[0]));
                            $range_day2 = $prev_date_exp[0] . "-" . $prev_date_exp[1] . "-" . $end_day;
                            ?>
                            <button type="button" class="btn btn-info <?php if ($this->uri->segment(5) == 'month') echo 'active'; ?>" onclick="dayChange('<?= date("Y-m-d", strtotime("$range_day - 6 month")) ?>', '<?= $range_day2 ?>', 'month', 'change');" data-toggle="tooltip" data-placement="top" title="월간 데이터를 보여줍니다. (당월 선택 안됨)">월간</button>
                            <button type="button" class="btn btn-info" onclick="excelDown();">다운로드</button>
                        </div>

                    </form>
                </div>
                <colgroup><col width="*"><col width="*"><col width="*"><col width="*"><col width="*"><col width="*"><col width="*"><col width="*"></colgroup>
                <table class="table table-bordered bulk_action">
                    <thead>
                        <tr>
                            <th rowspan="2"></th>
                            <th class="cursor" colspan="2" onclick="changeChart('main_keyword');">메인<br>(키워드)</th>
                            <th class="cursor" colspan="2" onclick="changeChart('main_list_cnt');">메인<br>(리스트)</th>
                            <th class="cursor" colspan="2" onclick="changeChart('interest_cnt');">관심종목</th>
                            <th class="cursor" colspan="2" onclick="changeChart('lank_popular_cnt');">랭킹<br>(인기종목)</th>
                            <th class="cursor" colspan="2" onclick="changeChart('lank_popular_key_cnt');">랭킹<br>(인기키워드)</th>
                            <th class="cursor" colspan="2" onclick="changeChart('lank_popular_news_cnt');">랭킹<br>(인기뉴스)</th>
                            <th class="cursor" colspan="2" onclick="changeChart('notice_cnt');">알림</th>
                            <th class="cursor" colspan="2" onclick="changeChart('scrap_cnt');">스크랩</th>
                            <th class="cursor" colspan="2" onclick="changeChart('login_cnt');">로그인</th>
                        </tr>
                        <tr>
                            <th>UV</th>
                            <th>PV</th>
                            <th>UV</th>
                            <th>PV</th>
                            <th>UV</th>
                            <th>PV</th>
                            <th>UV</th>
                            <th>PV</th>
                            <th>UV</th>
                            <th>PV</th>
                            <th>UV</th>
                            <th>PV</th>
                            <th>UV</th>
                            <th>PV</th>
                            <th>UV</th>
                            <th>PV</th>
                            <th>UV</th>
                            <th>PV</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($lists as $row) {
                            ?>
                            <tr>
                                <td><?= $row['DAY_NAME'] ?></td>
                                <td><?= $row['UV_MAIN_KEY_CNT'] ?></td>
                                <td><?= $row['PV_MAIN_KEY_CNT'] ?></td>
                                <td><?= $row['UV_MAIN_LIST_CNT'] ?></td>
                                <td><?= $row['PV_MAIN_LIST_CNT'] ?></td>
                                <td><?= $row['UV_INTEREST_CNT'] ?></td>
                                <td><?= $row['PV_INTEREST_CNT'] ?></td>
                                <td><?= $row['UV_LANK_POPULAR_CNT'] ?></td>
                                <td><?= $row['PV_LANK_POPULAR_CNT'] ?></td>
                                <td><?= $row['UV_LANK_POPULAR_KEY_CNT'] ?></td>
                                <td><?= $row['PV_LANK_POPULAR_KEY_CNT'] ?></td>
                                <td><?= $row['UV_LANK_POPULAR_NEWS_CNT'] ?></td>
                                <td><?= $row['PV_LANK_POPULAR_NEWS_CNT'] ?></td>
                                <td><?= $row['UV_NOTICE_CNT'] ?></td>
                                <td><?= $row['PV_NOTICE_CNT'] ?></td>
                                <td><?= $row['UV_SCRAP_CNT'] ?></td>
                                <td><?= $row['PV_SCRAP_CNT'] ?></td>
                                <td><?= $row['UV_LOGIN_CNT'] ?></td>
                                <td><?= $row['PV_LOGIN_CNT'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="x_content2">
                    <span id="chart_title">메인(키워드)</span>
                    <div id="line_chart" style="width:100%; height:300px;"></div>
                </div>
            </div>

            <?php
            $chart_data = "";
            foreach ($lists2 as $row) {
                $chart_data .= "{d: '" . $row['chart_day'] . "', uv: " . $row['UV_MAIN_KEY_CNT'] . ", pv:" . $row['PV_MAIN_KEY_CNT'] . "},";
            }

            $chart_data1 = "";
            foreach ($lists3 as $row) {
                $chart_data1 .= "{d: '" . $row['chart_day'] . "', uv: " . $row['UV_MAIN_LIST_CNT'] . ", pv:" . $row['PV_MAIN_LIST_CNT'] . "},";
            }

            $chart_data2 = "";
            foreach ($lists4 as $row) {
                $chart_data2 .= "{d: '" . $row['chart_day'] . "', uv: " . $row['UV_INTEREST_CNT'] . ", pv:" . $row['PV_INTEREST_CNT'] . "},";
            }

            $chart_data3 = "";
            foreach ($lists5 as $row) {
                $chart_data3 .= "{d: '" . $row['chart_day'] . "', uv: " . $row['UV_LANK_POPULAR_CNT'] . ", pv:" . $row['UV_LANK_POPULAR_CNT'] . "},";
            }

            $chart_data4 = "";
            foreach ($lists6 as $row) {
                $chart_data4 .= "{d: '" . $row['chart_day'] . "', uv: " . $row['UV_LANK_POPULAR_KEY_CNT'] . ", pv:" . $row['PV_LANK_POPULAR_KEY_CNT'] . "},";
            }

            $chart_data5 = "";
            foreach ($lists7 as $row) {
                $chart_data5 .= "{d: '" . $row['chart_day'] . "', uv: " . $row['UV_LANK_POPULAR_NEWS_CNT'] . ", pv:" . $row['PV_LANK_POPULAR_NEWS_CNT'] . "},";
            }

            $chart_data6 = "";
            foreach ($lists8 as $row) {
                $chart_data6 .= "{d: '" . $row['chart_day'] . "', uv: " . $row['UV_NOTICE_CNT'] . ", pv:" . $row['PV_NOTICE_CNT'] . "},";
            }

            $chart_data7 = "";
            foreach ($lists9 as $row) {
                $chart_data7 .= "{d: '" . $row['chart_day'] . "', uv: " . $row['UV_SCRAP_CNT'] . ", pv:" . $row['PV_SCRAP_CNT'] . "},";
            }

            $chart_data8 = "";
            foreach ($lists10 as $row) {
                $chart_data8 .= "{d: '" . $row['chart_day'] . "', uv: " . $row['UV_LOGIN_CNT'] . ", pv:" . $row['PV_LOGIN_CNT'] . "},";
            }
            ?>

        </div>
    </div>
    <input type="hidden" id="range" value="<?= $range ?>">
    <!-- /page content -->



    <!-- morris.js -->
    <script src="/vendors/raphael/raphael.min.js"></script>
    <script src="/vendors/morris.js/morris.min.js"></script>
    <script type="text/javascript">

                                $(document).ready(function () {
                                    $('.datepicker').datepicker();


                                    var options = {
                                        element: 'line_chart',
                                        data: [
<?= substr($chart_data, 0, -1) ?>
                                        ],
                                        xkey: 'd',
                                        ykeys: ['uv', 'pv'],
                                        labels: ['uv', 'pv'],
                                        parseTime: false
                                    };

                                    Morris.Line(options);

                                    $("#edate").change(function () {
                                        var value = $(this).val();
                                        var sdate = $("#sdate").val();

                                        if (value < sdate) {
                                            alert("날짜를 다시 지정해주세요.");
                                            $('#edate').val('');
                                        }
                                    });

                                    $("#sdate").change(function () {
                                        var value = $(this).val();
                                        var edate = $("#edate").val();

                                        if (edate) {
                                            if (value > edate) {
                                                alert("날짜를 다시 지정해주세요.");
                                                $('#sdate').val('');
                                                $('#edate').val('');
                                            }
                                        }
                                    });
                                });

                                function dayChange(sdate, edate, range, type) {
                                    var date = new Date();
                                    var year = date.getFullYear(); //get year
                                    var month = date.getMonth(); //get month
                                    var day = date.getDate(); //get month
                                    if (month < 10) {
                                        month = '0' + (date.getMonth() + 1);
                                    }
                                    if (day < 10) {
                                        day = '0' + date.getDate();
                                    }

                                    if (type == 'search') {
                                        var value = $("#edate").val();
                                        var sdate = $("#sdate").val();

                                        if (!sdate || !edate) {
                                            alert("날짜를 선택해주세요.");
                                            return false;
                                        }

                                        var edate_split = edate.split('-');
                                        var sdate_split = sdate.split('-');

                                        if (range == 'month') {
                                            if (edate_split[0] + '-' + edate_split[1] > year + '-' + month) {
                                                $("#edate").val('');
                                                alert("현재월 이후로는 조회 불가합니다.");
                                                $("#edate").focus();
                                                return false;
                                            }

                                            if (sdate_split[0] + '-' + sdate_split[1] > year + '-' + month) {
                                                $("#sdate").val('');
                                                alert("현재월 이후로는 조회 불가합니다.");
                                                $("#sdate").focus();
                                                return false;
                                            }
                                        }

                                        if (range == 'week') {
                                            if ($("#edate").val() > '<?= $day_of_the_week ?>') {
                                                alert("종료일은 지난주 일요일 보다 클수없습니다.");
                                                $('#edate').val('<?= $day_of_the_week ?>');
                                                $('.date.date_week:eq(1)').data('datepicker').setDate(null);
                                                return false;
                                            }

                                            if ($("#sdate").val() > '<?= $day_of_the_week ?>') {
                                                alert("시작일은 지난주 일요일 보다 클수없습니다.");
                                                $('#edate').val('');
                                                return false;
                                            }
                                        }

                                        if (value < sdate) {
                                            $("#edate").val('');
                                            alert("날짜를 다시 지정해주세요.");
                                            $("#edate").focus();
                                            return false;
                                        }
                                    }
                                    location.href = '/index/uvpv/' + sdate + '/' + edate + '/' + range;
                                }

                                function excelDown() {
                                    var sdate = $("#sdate").val();
                                    var edate = $("#edate").val();
                                    var range = $("#range").val();

                                    location.href = '/index.php/dataFunction/excellDown?sdate=' + sdate + '&edate=' + edate + '&range=' + range + '&file=uvpv';
                                }

                                function changeChart(type) {
                                    var options = {
                                        element: 'line_chart',
                                        data: [],
                                        xkey: 'd',
                                        ykeys: ['uv', 'pv'],
                                        labels: ['uv', 'pv'],
                                        parseTime: false
                                    };

                                    var text = '';

                                    if (type == 'main_keyword') {
                                        options = {
                                            element: 'line_chart',
                                            data: [
<?= substr($chart_data, 0, -1) ?>
                                            ],
                                            xkey: 'd',
                                            ykeys: ['uv', 'pv'],
                                            labels: ['uv', 'pv'],
                                            parseTime: false
                                        };
                                        text = '메인(키워드)';
                                    }

                                    if (type == 'main_list_cnt') {
                                        options = {
                                            element: 'line_chart',
                                            data: [
<?= substr($chart_data1, 0, -1) ?>
                                            ],
                                            xkey: 'd',
                                            ykeys: ['uv', 'pv'],
                                            labels: ['uv', 'pv'],
                                            parseTime: false
                                        };

                                        text = '메인(리스트)';
                                    }

                                    if (type == 'interest_cnt') {
                                        options = {
                                            element: 'line_chart',
                                            data: [
<?= substr($chart_data2, 0, -1) ?>
                                            ],
                                            xkey: 'd',
                                            ykeys: ['uv', 'pv'],
                                            labels: ['uv', 'pv'],
                                            parseTime: false
                                        };

                                        text = '관심종목';
                                    }

                                    if (type == 'lank_popular_cnt') {
                                        options = {
                                            element: 'line_chart',
                                            data: [
<?= substr($chart_data3, 0, -1) ?>
                                            ],
                                            xkey: 'd',
                                            ykeys: ['uv', 'pv'],
                                            labels: ['uv', 'pv'],
                                            parseTime: false
                                        };

                                        text = '랭킹(인기종목)';
                                    }

                                    if (type == 'lank_popular_key_cnt') {
                                        options = {
                                            element: 'line_chart',
                                            data: [
<?= substr($chart_data4, 0, -1) ?>
                                            ],
                                            xkey: 'd',
                                            ykeys: ['uv', 'pv'],
                                            labels: ['uv', 'pv'],
                                            parseTime: false
                                        };

                                        text = '랭킹(인기키워드)';
                                    }

                                    if (type == 'lank_popular_news_cnt') {
                                        options = {
                                            element: 'line_chart',
                                            data: [
<?= substr($chart_data5, 0, -1) ?>
                                            ],
                                            xkey: 'd',
                                            ykeys: ['uv', 'pv'],
                                            labels: ['uv', 'pv'],
                                            parseTime: false
                                        };

                                        text = '랭킹(인기뉴스)';
                                    }

                                    if (type == 'notice_cnt') {
                                        options = {
                                            element: 'line_chart',
                                            data: [
<?= substr($chart_data6, 0, -1) ?>
                                            ],
                                            xkey: 'd',
                                            ykeys: ['uv', 'pv'],
                                            labels: ['uv', 'pv'],
                                            parseTime: false
                                        };

                                        text = '알림';
                                    }

                                    if (type == 'scrap_cnt') {
                                        options = {
                                            element: 'line_chart',
                                            data: [
<?= substr($chart_data7, 0, -1) ?>
                                            ],
                                            xkey: 'd',
                                            ykeys: ['uv', 'pv'],
                                            labels: ['uv', 'pv'],
                                            parseTime: false
                                        };

                                        text = '스크랩';
                                    }

                                    if (type == 'login_cnt') {
                                        options = {
                                            element: 'line_chart',
                                            data: [
<?= substr($chart_data8, 0, -1) ?>
                                            ],
                                            xkey: 'd',
                                            ykeys: ['uv', 'pv'],
                                            labels: ['uv', 'pv'],
                                            parseTime: false
                                        };

                                        text = '로그인';
                                    }

                                    $("#line_chart").empty();
                                    $("#chart_title").html(text);
                                    Morris.Line(options);
                                }
    </script>