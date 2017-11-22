<!-- page content -->
<div class="right_col" role="main">
	<div class="page-title">
		<h3>팝업 관리 <small>사용자에게 팝업을 발송, 발송 된 팝업을 관리 할 수 있습니다.</small></h3>
	</div>

	<div class="x_panel">
		<div class="x_title">
			<h2>팝업 글 수 : <small id="rowtotal">0000건</small></h2>
			<div class="clearfix"></div>
		</div>

		<div class="x_content">
			<table id="popupTable" class="table table-bordered">
				<colgroup><col width="70px"><col width="*"><col width="100px"><col width="200px"><col width="100px"></colgroup>
				<thead>
					<tr>
						<th>번호</th>
						<th>제목</th>
						<th>등록 날짜</th>
						<th>팝업 노출기간</th>
						<th>팝업 노출</th>
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
		$('#popupTable').dataTable({
			dom: '<"datatable_header"fl>t<"datatable_footer"Bp>',
			ajax: '/index.php/dataFunction/popupLists',
			columns: [
			{data: "popup_seq"},
			{data: "popup_title"},
			{data: "reg_date"},
			{data: "popup_day"},
			{data: "popup_status"}
			],
			columnDefs: [{
				orderable: false,
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
				text: '팝업 등록',
				action: function () {
					location.href = '/index/popup_add';
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

		$('#popupTable tbody').on('click', 'td.cursor', function () {
			var index = $(this).index('#popupTable tbody td.cursor');
			var stock_seq = $(".idx:eq(" + index + ")").val();
			location.href = '/index/popup_view/' + stock_seq + '';
		});
	});

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
</script>