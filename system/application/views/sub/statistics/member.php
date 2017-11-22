<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <h3>회원 수 <small>신규, 누적, 방문, 탈퇴 회원 통계를 확인할 수 있습니다.</small></h3>
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
//                                            echo $day_of_the_week;
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
                <table class="table table-bordered bulk_action">
                    <thead>
                        <tr>
                            <th>날짜</th>
                            <th class="cursor" onclick="changeChart('new');">신규회원 수</th>
                            <th class="cursor" onclick="changeChart('accumulate');">누적회원 수</th>
                            <th class="cursor" onclick="changeChart('ins');">방문회원 수</th>
                            <th class="cursor" onclick="changeChart('secession');">탈퇴회원 수</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($lists as $row) {
                            ?>
                            <tr>
                                <td><?= $row['DAY_NAME'] ?></td>
                                <td><?= $row['NEW_CNT'] ?></td>
                                <td><?= $row['ACCUMULATE_CNT'] ?></td>
                                <td><?= $row['INS_CNT'] ?></td>
                                <td><?= $row['SECESSION_CNT'] ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <div class="x_content2">
                    <span id="chart_title">신규회원 수</span>
                    <div id="line_chart" style="width:100%; height:300px;"></div>
                </div>
            </div>
            <?php
            $chart_data = "";
            foreach ($lists2 as $row) {
                $chart_data .= "{d: '" . $row['chart_day'] . "', value: " . $row['NEW_CNT'] . "},";
            }

            $chart_data1 = "";
            foreach ($lists3 as $row) {
                $chart_data1 .= "{d: '" . $row['chart_day'] . "', value: " . $row['ACCUMULATE_CNT'] . "},";
            }

            $chart_data2 = "";
            foreach ($lists4 as $row) {
                $chart_data2 .= "{d: '" . $row['chart_day'] . "', value: " . $row['INS_CNT'] . "},";
            }

            $chart_data3 = "";
            foreach ($lists5 as $row) {
                $chart_data3 .= "{d: '" . $row['chart_day'] . "', value: " . $row['SECESSION_CNT'] . "},";
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
                                        ykeys: ['value'],
                                        labels: ['Value'],
                                        parseTime: false
                                    };

                                    Morris.Line(options);

                                    $("#edate").change(function () {
                                        var value = $(this).val();
                                        var sdate = $("#sdate").val();

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

//                                        if (value < sdate) {
//                                            alert("날짜를 다시 지정해주세요.");
//                                            $('#edate').val('');
//                                        }
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
                                                $('#edate').val('');
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
                                    location.href = '/index/member/' + sdate + '/' + edate + '/' + range;
                                }

                                function excelDown() {
                                    var sdate = $("#sdate").val();
                                    var edate = $("#edate").val();
                                    var range = $("#range").val();

                                    location.href = '/index.php/dataFunction/excellDown?sdate=' + sdate + '&edate=' + edate + '&range=' + range + '&file=member';
                                }

                                function changeChart(type) {
                                    var options = {
                                        element: 'line_chart',
                                        data: [],
                                        xkey: 'd',
                                        ykeys: ['value'],
                                        labels: ['Value'],
                                        parseTime: false
                                    };

                                    var text = '';

                                    if (type == 'new') {
                                        options = {
                                            element: 'line_chart',
                                            data: [
<?= substr($chart_data, 0, -1) ?>
                                            ],
                                            xkey: 'd',
                                            ykeys: ['value'],
                                            labels: ['Value'],
                                            parseTime: false
                                        };
                                        text = '신규회원 수';
                                    }

                                    if (type == 'accumulate') {
                                        options = {
                                            element: 'line_chart',
                                            data: [
<?= substr($chart_data1, 0, -1) ?>
                                            ],
                                            xkey: 'd',
                                            ykeys: ['value'],
                                            labels: ['Value'],
                                            parseTime: false
                                        };

                                        text = '누적회원 수';
                                    }

                                    if (type == 'ins') {
                                        options = {
                                            element: 'line_chart',
                                            data: [
<?= substr($chart_data2, 0, -1) ?>
                                            ],
                                            xkey: 'd',
                                            ykeys: ['value'],
                                            labels: ['Value'],
                                            parseTime: false
                                        };

                                        text = '방문회원 수';
                                    }

                                    if (type == 'secession') {
                                        options = {
                                            element: 'line_chart',
                                            data: [
<?= substr($chart_data3, 0, -1) ?>
                                            ],
                                            xkey: 'd',
                                            ykeys: ['value'],
                                            labels: ['Value'],
                                            parseTime: false
                                        };

                                        text = '탈퇴회원 수';
                                    }
                                    $("#line_chart").empty();
                                    $("#chart_title").html(text);
                                    Morris.Line(options);
                                }
    </script>