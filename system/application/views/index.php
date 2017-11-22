
<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="tile_count x_panel">
        <div class="tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> PV</span>
            <div class="count"><?= number_format($pv_value->value) ?></div>
        </div>
        <div class="tile_stats_count">
            <span class="count_top"><i class="fa fa-clock-o"></i> UV</span>
            <div class="count"><?= number_format($uv_value->value) ?></div>
        </div>
        <div class="tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> 사용유저</span>
            <div class="count green"><?= number_format($user_all->CNT) ?></div>
        </div>
        <div class="tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> 가입회원 수</span>
            <div class="count"><?= number_format($user_today->CNT) ?></div>
        </div>
        <div class="tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> 탈퇴회원 수</span>
            <div class="count"><?= number_format($user_drop->CNT) ?></div>
        </div>
    </div>
    <!-- /top tiles -->

    <div class="x_panel">
        <div class="x_title">
            <h2>전체 통계</h2>
            <ul class="nav navbar-right panel_toolbox" style="width: 140px;">
                <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                    <div class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                    </div>
                    <input type="text" class="form-control" id="date" value="<?= $selectDay ?>" placeholder="날짜지정">
                </div>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div id="echart_line" style="height:350px; width: 100%;"></div>
        </div>
    </div>
    <br />

    <script type="text/javascript">
        $(document).ready(function () {
            $("#date").change(function () {
                location.href = '/index/main/' + $(this).val() + '';
            });
            init_echarts();
        });
        /* ECHRTS */
        function init_echarts() {

            if (typeof (echarts) === 'undefined') {
                return;
            }
            console.log('init_echarts');
            var theme = {
                color: [
                    '#26B99A', '#34495E', '#BDC3C7', '#3498DB',
                    '#9B59B6', '#8abb6f', '#759c6a', '#bfd3b7'
                ],
                title: {
                    itemGap: 8,
                    textStyle: {
                        fontWeight: 'normal',
                        color: '#408829'
                    }
                },
                dataRange: {
                    color: ['#1f610a', '#97b58d']
                },
                toolbox: {
                    color: ['#408829', '#408829', '#408829', '#408829']
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.5)',
                    axisPointer: {
                        type: 'line',
                        lineStyle: {
                            color: '#408829',
                            type: 'dashed'
                        },
                        crossStyle: {
                            color: '#408829'
                        },
                        shadowStyle: {
                            color: 'rgba(200,200,200,0.3)'
                        }
                    }
                },
                dataZoom: {
                    dataBackgroundColor: '#eee',
                    fillerColor: 'rgba(64,136,41,0.2)',
                    handleColor: '#408829'
                },
                grid: {
                    borderWidth: 0
                },
                categoryAxis: {
                    axisLine: {
                        lineStyle: {
                            color: '#408829'
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: ['#eee']
                        }
                    }
                },
                valueAxis: {
                    axisLine: {
                        lineStyle: {
                            color: '#408829'
                        }
                    },
                    splitArea: {
                        show: true,
                        areaStyle: {
                            color: ['rgba(250,250,250,0.1)', 'rgba(200,200,200,0.1)']
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: ['#eee']
                        }
                    }
                },
                timeline: {
                    lineStyle: {
                        color: '#408829'
                    },
                    controlStyle: {
                        normal: {color: '#408829'},
                        emphasis: {color: '#408829'}
                    }
                },
                k: {
                    itemStyle: {
                        normal: {
                            color: '#68a54a',
                            color0: '#a9cba2',
                            lineStyle: {
                                width: 1,
                                color: '#408829',
                                color0: '#86b379'
                            }
                        }
                    }
                },
                map: {
                    itemStyle: {
                        normal: {
                            areaStyle: {
                                color: '#ddd'
                            },
                            label: {
                                textStyle: {
                                    color: '#c12e34'
                                }
                            }
                        },
                        emphasis: {
                            areaStyle: {
                                color: '#99d2dd'
                            },
                            label: {
                                textStyle: {
                                    color: '#c12e34'
                                }
                            }
                        }
                    }
                },
                force: {
                    itemStyle: {
                        normal: {
                            linkStyle: {
                                strokeColor: '#408829'
                            }
                        }
                    }
                },
                chord: {
                    padding: 4,
                    itemStyle: {
                        normal: {
                            lineStyle: {
                                width: 1,
                                color: 'rgba(128, 128, 128, 0.5)'
                            },
                            chordStyle: {
                                lineStyle: {
                                    width: 1,
                                    color: 'rgba(128, 128, 128, 0.5)'
                                }
                            }
                        },
                        emphasis: {
                            lineStyle: {
                                width: 1,
                                color: 'rgba(128, 128, 128, 0.5)'
                            },
                            chordStyle: {
                                lineStyle: {
                                    width: 1,
                                    color: 'rgba(128, 128, 128, 0.5)'
                                }
                            }
                        }
                    }
                },
                gauge: {
                    startAngle: 225,
                    endAngle: -45,
                    axisLine: {
                        show: true,
                        lineStyle: {
                            color: [[0.2, '#86b379'], [0.8, '#68a54a'], [1, '#408829']],
                            width: 8
                        }
                    },
                    axisTick: {
                        splitNumber: 10,
                        length: 12,
                        lineStyle: {
                            color: 'auto'
                        }
                    },
                    axisLabel: {
                        textStyle: {
                            color: 'auto'
                        }
                    },
                    splitLine: {
                        length: 18,
                        lineStyle: {
                            color: 'auto'
                        }
                    },
                    pointer: {
                        length: '90%',
                        color: 'auto'
                    },
                    title: {
                        textStyle: {
                            color: '#333'
                        }
                    },
                    detail: {
                        textStyle: {
                            color: 'auto'
                        }
                    }
                },
                textStyle: {
                    fontFamily: 'Arial, Verdana, sans-serif'
                }
            };
            if ($('#echart_line').length) {

                var echartLine = echarts.init(document.getElementById('echart_line'), theme);
                echartLine.setOption({
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        x: 220,
                        y: 40,
                        data: ['PV', 'UV', '사용유저', '탈퇴회원']
                    },
                    toolbox: {
                        show: true,
                        feature: {
                            magicType: {
                                show: true,
                                title: {
                                    line: 'Line',
                                    bar: 'Bar',
                                    stack: 'Stack',
                                    tiled: 'Tiled'
                                },
                                type: ['line', 'bar']
                            },
                            restore: {
                                show: true,
                                title: "Restore"
                            },
                            saveAsImage: {
                                show: true,
                                title: "다운로드"
                            }
                        }
                    },
                    calculable: true,
                    xAxis: [{
                            type: 'category',
                            boundaryGap: false,
                            data: [
<?php
$date = "";
foreach ($date_lists as $row) {
    $date .= "'" . $row['d'] . "',";
}
echo $date;
?>
                            ]
                        }],
                    yAxis: [{
                            type: 'value'
                        }],
                    series: [
                        {
                            name: 'PV',
                            type: 'line',
                            smooth: true,
                            itemStyle: {
                                normal: {
                                    areaStyle: {
                                        type: 'default'
                                    }
                                }
                            },
                            data: [
<?php
$pv = "";
foreach ($pv_graph as $row) {
    $pv .= "'" . $row['CNT'] . "',";
}
echo $pv;
?>
                            ]
                        },
                        {
                            name: 'UV',
                            type: 'line',
                            smooth: true,
                            itemStyle: {
                                normal: {
                                    areaStyle: {
                                        type: 'default'
                                    }
                                }
                            },
                            data: [
<?php
$uv = "";
foreach ($uv_graph as $row) {
    $uv .= "'" . $row['CNT'] . "',";
}
echo $uv;
?>
                            ]
                        },
                        {
                            name: '사용유저',
                            type: 'line',
                            smooth: true,
                            itemStyle: {
                                normal: {
                                    areaStyle: {
                                        type: 'default'
                                    }
                                }
                            },
                            data: [
<?php
$user = "";
foreach ($user_graph as $row) {
    $user .= "'" . $row['CNT'] . "',";
}
echo $user;
?>
                            ]
                        },
                        {
                            name: '탈퇴회원',
                            type: 'line',
                            smooth: true,
                            itemStyle: {
                                normal: {
                                    areaStyle: {
                                        type: 'default'
                                    }
                                }
                            },
                            data: [
<?php
$user_drop = "";
foreach ($user_drop_graph as $row) {
    $user_drop .= "'" . $row['CNT'] . "',";
}
echo $user_drop;
?>
                            ]
                        }
                    ]
                });
                window.onresize = function () {
                    echartLine.resize();
                };
            }


        }
    </script>


    <script src="/vendors/echarts/dist/echarts.min.js"></script>


    <div class="row">
        <!-- Start to do list -->
        <div class="col-xs-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Q&A <small><b class="green"><?= $qna_comp->CNT ?></b> / <?= count($qna_lists) ?></small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="">
                        <ul class="to_do">
                            <?php foreach ($qna_lists as $row) { ?>
                                <li class="cursor">
                                    <p onclick="location.href = '/index/question_view/<?= $row['board_seq'] ?>'">
                                        <?= $row['board_contents'] ?>
                                    </p>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Keyword <small><b id="rowtotal" class="green"></b></small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-bordered" id="indexKeyword">
                        <thead>
                            <tr>
                                <th>종목명</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- End to do list -->
    </div>
</div>
<!-- /page content -->

<script type="text/javascript">
    $(document).ready(function () {
        var table1 = $('#indexKeyword').dataTable({
            processing: true,
            serverSide: true,
            pagingType: "simple",
            dom: 't<"datatable_footer"p>',
            ajax: {
                url: '/index.php/dataFunction/indexKeyWordList',
                dataSrc: function (json) {
                    json.draw = json.data.draw;
                    json.recordsTotal = json.data.recordsTotal;
                    json.recordsFiltered = json.data.recordsFiltered;
                    return json.data.data;
                }
            },
            pageLength: 10,
            columns: [
                {data: "company_name_i"}
            ],
            order: [],
            columnDefs: [{
                    orderable: false,
                    className: 'cursor',
                    targets: 0
                }],
            language: {
                lengthMenu: "_MENU_",
                search: "",
                paginate: {
                    "next": "&raquo;",
                    "previous": "&laquo;"
                }
            },
            footerCallback: function (row, data, start, end, display) {
                if (!data.length) {
                    $("#rowtotal").html('0건');
                } else {
                    $("#rowtotal").html(numberWithCommas(data[0].recordsFiltered) + '건');
                }
            }
        });

        $('#indexKeyword tbody').on('click', 'td.cursor', function () {
//                alert($(this).html());
            var index = $(this).index('#indexKeyword tbody td.cursor');
            var stock_seq = $(".idx:eq(" + index + ")").val();
            location.href = '/index/keyword_kospi?text=' + $(this).html() + '';
        });
    });

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>