<!-- page content -->
<div class="right_col" role="main">
	<div class="page-title">
		<h3>공지사항 <small>공지사항을 등록하며 푸쉬를 발송 할 수 있습니다.</small></h3>
	</div>

	<div class="x_panel">
		<div class="x_title">
			<h2>공지 글 수 : <small id="rowtotal">0000건</small></h2>
			<div class="clearfix"></div>
		</div>

		<div class="x_content">
			<table id="noticeTable" class="table table-bordered bulk_action">
				<colgroup><col width="70px"><col width="*"><col width="100px"><col width="120px"><col width="160px"><col width="100px"></colgroup>
				<thead>
					<tr>
						<th>번호</th>
						<th>제목</th>
						<th>등록 날짜</th>
						<th>서비스 노출</th>
						<th>푸쉬 발송 일시</th>
						<th>푸쉬 발송</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- /page content -->


<script type="text/javascript">
	$(document).ready(function () {
		$('#noticeTable').dataTable({
			dom: '<"datatable_header"fl>t<"datatable_footer"Bp>',
			ajax: '/index.php/dataFunction/noticeLists',
			columns: [
			{data: "notice_seq"},
			{data: "title"},
			{data: "reg_date"},
			{data: "notice_status"},
			{data: "push_date"},
			{data: "push_type"}
			],
			columnDefs: [
			{
				orderable: true,
				className: 'cursor',
				targets: 1
			}],
			select: {
				style: 'os',
				selector: 'td:first-child'
			},
			order: [],
			buttons: [
			{
				className: 'btn btn-sm btn-primary',
				text: '공지사항 등록',
				action: function () {
					location.href = '/index/notice_add';
				}
			}
			],
			footerCallback: function (row, data, start, end, display) {
				$("#rowtotal").html(numberWithCommas(data.length) + '건');
			},
			language: {
				lengthMenu: "_MENU_",
				search: "",
				paginate: {
					"next": "&raquo;",
					"previous": "&laquo;"
				}
			}
		});

		$('#noticeTable tbody').on('click', 'td.cursor', function () {
			var index = $(this).index('#noticeTable tbody td.cursor');
			var stock_seq = $(".idx:eq(" + index + ")").val();
			location.href = '/index/notice_view/' + stock_seq + '';
		});
	});

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
</script>