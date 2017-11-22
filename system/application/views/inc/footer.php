</div>
</div>


<!-- FastClick -->
<script src="/vendors/fastclick/fastclick.js"></script><!--완료-->
<!-- NProgress -->
<script src="/vendors/nprogress/nprogress.js"></script><!--완료-->
<!-- iCheck -->
<script src="/vendors/iCheck/icheck.min.js"></script><!--완료-->

<!-- Datatables -->
<script src="/vendors/datatables/jquery.dataTables.js"></script><!--완료-->
<script src="/vendors/datatables/dataTables.bootstrap.min.js"></script><!--완료-->
<script src="/vendors/datatables/dataTables.buttons.min.js"></script><!--완료-->
<script src="/vendors/datatables/buttons.bootstrap.min.js"></script><!--완료-->
<script src="/vendors/datatables/buttons.flash.min.js"></script><!--완료-->
<script src="/vendors/datatables/buttons.html5.min.js"></script><!--완료-->
<script src="/vendors/datatables/buttons.print.min.js"></script><!--완료-->
<script src="/vendors/datatables/dataTables.fixedHeader.min.js"></script><!--완료-->
<script src="/vendors/datatables/dataTables.keyTable.min.js"></script><!--완료-->
<script src="/vendors/datatables/dataTables.responsive.min.js"></script><!--완료-->
<script src="/vendors/datatables/responsive.bootstrap.js"></script><!--완료-->
<script src="/vendors/datatables/dataTables.scroller.min.js"></script><!--완료-->
<script src="/vendors/jszip/jszip.min.js"></script><!--완료-->
<script src="/vendors/pdfmake/pdfmake.min.js"></script><!--완료-->
<script src="/vendors/pdfmake/vfs_fonts.js"></script><!--완료-->
<script src="https://cdn.datatables.net/plug-ins/1.10.15/api/fnReloadAjax.js"></script>
<script src="/js/dataTables.rowReorder.js"></script><!--완료-->

<!-- jQuery Tags Input -->
<script src="/vendors/jquery.tagsinput/jquery.tagsinput.js"></script><!--완료-->
<!-- Switchery -->
<script src="/vendors/switchery/switchery.min.js"></script><!--완료-->
<script src="/vendors/moment/moment.min.js"></script><!--완료-->
<script src="/vendors/daterangepicker/daterangepicker.js"></script><!--완료-->

<!-- Datepicker -->
<script src="/vendors/datepicker/bootstrap-datepicker.js"></script><!--완료-->
<script src="/vendors/datepicker/bootstrap-datepicker.kr.js"></script><!--완료-->

<!-- typeahead -->
<script src="/build/js/bootstrap3-typeahead.js"></script>

<!-- Custom Theme Scripts -->
<script src="/build/js/custom.js"></script>

<!--자동완성-->
<script type="text/javascript">
    $('input.typeahead').typeahead({
        source: function (query, process) {
            return $.get('/inc/autocomplete.php', {query: query}, function (data) {
//                console.log(data);
                data = $.parseJSON(data);
                return process(data);
            });
        }
    });
</script>
<style>
    .datepicker table tr.week:hover{
        background: #eee;
    }

    .datepicker table tr.week-active,
    .datepicker table tr.week-active td,
    .datepicker table tr.week-active td:hover,
    .datepicker table tr.week-active.week td,
    .datepicker table tr.week-active.week td:hover,
    .datepicker table tr.week-active.week,
    .datepicker table tr.week-active:hover{
        background-color: #006dcc;
        background-image: -moz-linear-gradient(top, #0088cc, #0044cc);
        background-image: -ms-linear-gradient(top, #0088cc, #0044cc);
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0044cc));
        background-image: -webkit-linear-gradient(top, #0088cc, #0044cc);
        background-image: -o-linear-gradient(top, #0088cc, #0044cc);
        background-image: linear-gradient(top, #0088cc, #0044cc);
        background-repeat: repeat-x;
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#0088cc', endColorstr='#0044cc', GradientType=0);
        border-color: #0044cc #0044cc #002a80;
        border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
        filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
        color: #fff;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
    }
</style>
<script type="text/javascript">

    function clear() {
        $("#sdate").val('');
    }

    function clear2() {
        $("#edate").val('');
    }

    function dateDiff(_date1, _date2) {
        var diffDate_1 = _date1 instanceof Date ? _date1 : new Date(_date1);
        var diffDate_2 = _date2 instanceof Date ? _date2 : new Date(_date2);

        diffDate_1 = new Date(diffDate_1.getFullYear(), diffDate_1.getMonth() + 1, diffDate_1.getDate());
        diffDate_2 = new Date(diffDate_2.getFullYear(), diffDate_2.getMonth() + 1, diffDate_2.getDate());

        var diff = Math.abs(diffDate_2.getTime() - diffDate_1.getTime());
        diff = Math.ceil(diff / (1000 * 3600 * 24));

        return diff;
    }

    function monthDiff(_date1, _date2) {
        var diffDate_1 = _date1 instanceof Date ? _date1 : new Date(_date1);
        var diffDate_2 = _date2 instanceof Date ? _date2 : new Date(_date2);
        
        var months;
        months = (diffDate_2.getFullYear() - diffDate_1.getFullYear()) * 12;
        months -= diffDate_1.getMonth() + 1;
        months += diffDate_2.getMonth();
        return months <= 0 ? 0 : months;
    }

    $(document).ready(function () {
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

        var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());

        $('.date.popup:eq(0)').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            language: "kr",
            todayHighlight: true
        }).on('changeDate', function (ev) {

            if (date > ev.date && year + '-' + month + '-' + day != ev.format()) {
                alert("시작일은 오늘보다 작을수 없습니다.");
                clear();
                clear2();
                return false;
            }

            if (ev.format() > $("#edate").val() && $("#edate").val()) {
                alert("시작일은 종료일보다 클수 없습니다.");
                clear();
                clear2();
                return false;
            }
        });

        $('.date.popup:eq(1)').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            language: "kr",
            todayHighlight: true
        }).on('changeDate', function (ev) {
            if (date > ev.date && year + '-' + month + '-' + day != ev.format()) {
                alert("종료일은 오늘보다 작을수 없습니다.");
                clear();
                clear2();
            }

            if (ev.format() < $("#sdate").val()) {
                alert("종료일은 시작일보다 작을수 없습니다.");
                clear();
                clear2();
            }
        });

        $('.date.rank:eq(0)').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            language: "kr",
            todayHighlight: true,
            endDate: today
        }).on('changeDate', function (ev) {
            if (dateDiff(ev.format(), $("#edate").val()) > 12) {
                alert("최대 조회가능 일자는 12일 입니다.");
                clear();
                clear2();
                return false;
            }

            if (ev.format() > $("#edate").val() && $("#edate").val()) {
                alert("시작일은 종료일보다 클수 없습니다.");
                clear();
                clear2();
                return false;
            }
        });

        $('.date.rank:eq(1)').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            language: "kr",
            todayHighlight: true,
            endDate: today
        }).on('changeDate', function (ev) {
            if (dateDiff($("#sdate").val(), ev.format()) > 12) {
                alert("최대 조회가능 일자는 12일 입니다.");
                clear();
                clear2();
                return false;
            }

            if (ev.format() < $("#sdate").val()) {
                alert("종료일은 시작일보다 작을수 없습니다.");
                clear();
                clear2();
            }
        });

        $('.date.rank_week:eq(0)').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            language: "kr",
            todayHighlight: true,
            endDate: today
        }).on('changeDate', function (ev) {
            if (dateDiff(ev.format(), $("#edate").val()) > 84) {
                alert("최대 조회가능 일자는 12주 입니다.");
                clear();
                clear2();
                return false;
            }

            if (ev.format() > $("#edate").val() && $("#edate").val()) {
                alert("시작일은 종료일보다 클수 없습니다.");
                clear();
                clear2();
                return false;
            }
        });

        $('.date.rank_week:eq(1)').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            language: "kr",
            todayHighlight: true,
            endDate: today
        }).on('changeDate', function (ev) {
            if (dateDiff($("#sdate").val(), ev.format()) > 84) {
                alert("최대 조회가능 일자는 12주 입니다.");
                clear();
                clear2();
                return false;
            }

            if (ev.format() < $("#sdate").val()) {
                alert("종료일은 시작일보다 작을수 없습니다.");
                clear();
                clear2();
                return false;
            }
        });

        $('.date.rank_month:eq(0)').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            language: "kr",
            todayHighlight: true,
//            endDate: 'm',
            viewMode: "months",
            minViewMode: "months"
        }).on('changeDate', function (ev) {
            if (monthDiff(ev.format(), $("#edate").val()) > 12) {
                alert("최대 조회가능 일자는 12개월 입니다.");
                clear();
                clear2();
                return false;
            }

            if (ev.format() > $("#edate").val() && $("#edate").val()) {
                alert("시작일은 종료일보다 클수 없습니다.");
                clear();
                clear2();
                return false;
            }
        });

        $('.date.rank_month:eq(1)').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            language: "kr",
            todayHighlight: true,
//            endDate: 'm',
            viewMode: "months",
            minViewMode: "months"
        }).on('changeDate', function (ev) {
            if (monthDiff($("#sdate").val(), ev.format()) > 12) {
                alert("최대 조회가능 일자는 12개월 입니다.");
                clear();
                clear2();
                return false;
            }

            if (ev.format() < $("#sdate").val()) {
                alert("종료일은 시작일보다 작을수 없습니다.");
                clear();
                clear2();
                return false;
            }
        });

        $('.date.date_month:eq(0)').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            language: "kr",
            todayHighlight: true,
//            endDate: 'm',
            viewMode: "months",
            minViewMode: "months"
        }).on('hide', function (ev) {
            if (ev.format() > year + '-' + month + '-' + day) {
                alert("현재월 이후로는 조회 불가합니다.");
                clear();
                $('.date.date_month:eq(0)').data('datepicker').setDate(null);
            }
        });

        $('.date.date_month:eq(1)').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            language: "kr",
            todayHighlight: true,
//            endDate: 'm',
            viewMode: "months",
            minViewMode: "months"
        }).on('hide', function (e) {
//            console.log('date changed');
            end_month(e);
            if (e.format() < $("#sdate").val()) {
                alert("종료일은 시작일보다 작을수 없습니다.");
                clear2();
                $('.date.date_month:eq(1)').data('datepicker').setDate(null);
            }

            if (e.format() > year + '-' + month + '-' + day) {
                alert("현재월 이후로는 조회 불가합니다.");
                clear2();
                $('.date.date_month:eq(1)').data('datepicker').setDate(null);
            }
        });

        $('.date.push:eq(0)').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            language: "kr",
            todayHighlight: true
        }).on('changeDate', function (ev) {

            var dateString = ev.format();

            var dateArray = dateString.split("-");

            var dateObj = new Date(dateArray[0], Number(dateArray[1]) - 1, dateArray[2]);

            var edateString = $("#edate").val();

            var edateArray = edateString.split("-");

            var edateObj = new Date(edateArray[0], Number(edateArray[1]) - 1, edateArray[2]);

            var betweenDay = (edateObj.getTime() - dateObj.getTime()) / 1000 / 60 / 60 / 24;

            if (date < ev.date && year + '-' + month + '-' + day != ev.format()) {
                alert("시작일은 오늘보다 클수 없습니다.");
                clear();
            }

            if (ev.format() > $("#edate").val()) {
                alert("시작일은 종료일보다 클수 없습니다.");
                clear();
            }

            if ($("#edate").val() && betweenDay > 6) {
                alert("기간은 최대 7일 입니다.");
                clear();
            }
        });

        $('.date.push:eq(1)').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            language: "kr",
            todayHighlight: true
        }).on('changeDate', function (ev) {

            var dateString = ev.format();

            var dateArray = dateString.split("-");

            var dateObj = new Date(dateArray[0], Number(dateArray[1]) - 1, dateArray[2]);

            var sdateString = $("#sdate").val();

            var sdateArray = sdateString.split("-");

            var sdateObj = new Date(sdateArray[0], Number(sdateArray[1]) - 1, sdateArray[2]);

            var betweenDay = (dateObj.getTime() - sdateObj.getTime()) / 1000 / 60 / 60 / 24;

            if (date < ev.date && year + '-' + month + '-' + day != ev.format()) {
                alert("종료일은 오늘보다 클수 없습니다.");
                clear2();
            }

            if (ev.format() < $("#sdate").val()) {
                alert("종료일은 시작일보다 작을수 없습니다.");
                clear2();
            }

            if ($("#sdate").val() && betweenDay > 6) {
                alert("기간은 최대 7일 입니다.");
                clear2();
            }
        });

        var end_month = function (e) {
            var input = e.currentTarget.children;
            var date = e.date;

            var start_date = new Date(date.getFullYear(), (date.getMonth() + 1), date.getDate());
            // make a friendly string

            start_date.setDate(0);

            var month = (start_date.getMonth() + 1);
            if (month < 10) {
                month = '0' + (start_date.getMonth() + 1);
            }

            var day = start_date.getDate();
            if (day < 10) {
                day = '0' + start_date.getDate();
            }


            var friendly_string = start_date.getFullYear() + '-' + month + '-' + day;

//            console.log(friendly_string);

            $(input).val(friendly_string);
        }

        $('.date.date_week:eq(0)').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            language: "kr",
            weekStart: 1,
            daysOfWeekDisabled: "0, 1",
            todayHighlight: true
//            endDate: '-1w'
        }).on('show', function (e) {

            var tr = $('body').find('.datepicker-days table tbody tr');

            tr.mouseover(function () {
                $(this).addClass('week');
            });

            tr.mouseout(function () {
                $(this).removeClass('week');
            });

            calculate_week_range(e);

        }).on('hide', function (e) {
//            console.log('date changed');
            calculate_week_range(e);
        });

        var calculate_week_range = function (e) {

            var input = e.currentTarget.children;
            // remove all active class
            $('body').find('.datepicker-days table tbody tr').removeClass('week-active');

            // add active class
            var tr = $('body').find('.datepicker-days table tbody tr td.active.day').parent();
            tr.addClass('week-active');

            // find start and end date of the week

            var date = e.date;

            var start_date = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
//            console.log(start_date);
            // make a friendly string

            start_date.setDate(start_date.getDate() + 1);

            var month = (start_date.getMonth() + 1);
            if (month < 10) {
                month = '0' + (start_date.getMonth() + 1);
            }

            var day = start_date.getDate();
            if (day < 10) {
                day = '0' + start_date.getDate();
            }


            var friendly_string = start_date.getFullYear() + '-' + month + '-' + day;

//            console.log(friendly_string);

            $(input).val(friendly_string);

        };

        $('.date.date_week:eq(1)').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            language: "kr",
            weekStart: 1,
            daysOfWeekDisabled: "0, 1",
            todayHighlight: true,
//            endDate: '-1w'
        }).on('show', function (e) {

            var tr = $('body').find('.datepicker-days table tbody tr');

            tr.mouseover(function () {
                $(this).addClass('week');
            });

            tr.mouseout(function () {
                $(this).removeClass('week');
            });

            calculate_week_range2_class(e);

        }).on('hide', function (e) {
//            console.log('date changed');
            calculate_week_range2(e);
        });

        var calculate_week_range2_class = function (e) {
            // remove all active class
            $('body').find('.datepicker-days table tbody tr').removeClass('week-active');

            // add active class
            var tr = $('body').find('.datepicker-days table tbody tr td.active.day').parent();
            tr.addClass('week-active');

        };

        var calculate_week_range2 = function (e) {

            var input = e.currentTarget.children;
            // remove all active class
            $('body').find('.datepicker-days table tbody tr').removeClass('week-active');

            // add active class
            var tr = $('body').find('.datepicker-days table tbody tr td.active.day').parent();
            tr.addClass('week-active');

            // find start and end date of the week

            var date = e.date;

            var end_date = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);
//            console.log(start_date);
            // make a friendly string            

            var month = (end_date.getMonth() + 1);
            if (month < 10) {
                month = '0' + (end_date.getMonth() + 1);
            }

            var today = new Date();
            var today_day = (today.getDate() + 1);

            console.log(today_day);

            var day = (end_date.getDate() + 1);
//            if (day > today_day) {
//                day = (today_day - 2);
//            }

            if (day < 10) {
                day = '0' + (end_date.getDate() + 1);
            }

            var friendly_string = end_date.getFullYear() + '-' + month + '-' + day;

//            console.log(friendly_string);

            $(input).val(friendly_string);

        };

        $('.date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            language: "kr",
            todayHighlight: true,
            endDate: today
        });

        $(".daterangepicker").daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            singleDatePicker: true,
            locale: {
                format: 'YYYY-MM-DD H:mm'
            }
        });

        $('.datatable_default').dataTable({
            dom: '<"datatable_header"fl>t<"datatable_footer"p>'
        });
        $('.datatable_check').dataTable({
            dom: '<"datatable_header"fl>t<"datatable_footer"Bp>',
            columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }],
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            order: [[1, 'asc']],
            buttons: [
                {
                    className: 'btn btn-sm btn-warning',
                    text: '제외',
                    action: function (e, dt, node, config) {
                        $("#businessDelModal").modal();
                    }
                }
            ]
        });
        $('.datatable_check_moved').dataTable({
            dom: '<"datatable_header"fl>t<"datatable_footer"Bp>',
            columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }],
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            order: [[1, 'asc']],
            buttons: [
                {
                    className: 'btn btn-sm btn-warning',
                    text: '<i class="fa fa-chevron-up"></i>',
                    action: function (e, dt, node, config) {
                        $("#businessDelModal").modal();
                    }
                },
                {
                    className: 'btn btn-sm btn-warning',
                    text: '<i class="fa fa-chevron-down"></i>',
                    action: function (e, dt, node, config) {
                        $("#businessDelModal").modal();
                    }
                },
                {
                    className: 'btn btn-sm btn-warning',
                    text: '적용',
                    action: function (e, dt, node, config) {
                        $("#businessDelModal").modal();
                    }
                }
            ]
        });
        $('.datatable_check_moved_side').dataTable({
            dom: '<"datatable_header"fl>t<"datatable_footer"Bp>',
            columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }],
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            order: [[1, 'asc']],
            buttons: [
                {
                    className: 'btn btn-sm btn-warning',
                    text: '<i class="fa fa-chevron-up"></i>',
                    action: function (e, dt, node, config) {
                        $("#businessDelModal").modal();
                    }
                },
                {
                    className: 'btn btn-sm btn-warning',
                    text: '<i class="fa fa-chevron-down"></i>',
                    action: function (e, dt, node, config) {
                        $("#businessDelModal").modal();
                    }
                },
                {
                    className: 'btn btn-sm btn-warning',
                    text: '<i class="fa fa-chevron-left"></i>',
                    action: function (e, dt, node, config) {
                        $("#businessDelModal").modal();
                    }
                },
                {
                    className: 'btn btn-sm btn-warning',
                    text: '<i class="fa fa-chevron-right"></i>',
                    action: function (e, dt, node, config) {
                        $("#businessDelModal").modal();
                    }
                },
                {
                    className: 'btn btn-sm btn-warning',
                    text: '적용',
                    action: function (e, dt, node, config) {
                        $("#businessDelModal").modal();
                    }
                }
            ]
        });
    });

</script>

</body>
</html>