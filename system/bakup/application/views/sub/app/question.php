<!-- page content -->
<div class="right_col" role="main">
	<div class="page-title">
		<h3>문의하기 <small>사용자 문의 및 제휴문의를 관리 할 수 있습니다.</small></h3>
	</div>

	<div class="form-group">
		<div class="form-inline">
			<div class="form-group">
				<select id="type" class="form-control">
					<option value="">전체</option>
					<option value="1">일반문의</option>
					<option value="2">계정문의</option>
					<option value="3">뉴스문의</option>
					<option value="4">제휴문의</option>
					<option value="5">랜딩페이지문의</option>
				</select>
			</div>
			<div class="form-group">
				<input type="text" class="form-control" id="search" placeholder="검색어를 입력해주세요.">
			</div>

			<button type="button" id="searchBtn" class="btn btn-default">검색</button>
		</div>
	</div>

	<div class="x_panel">
		<div class="x_title">
			<h2>문의글 수 <small id="rowtotal">00명</small></h2>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<table id="questionTable" class="table table-bordered datatable">
				<thead>
					<tr>
						<th class="hidden-xs">번호</th>
						<th>문의 구분</th>
						<th>문의내용</th>
						<th>회원이름</th>
						<th>등록일시</th>
						<th>답변여부</th>
						<th>처리자</th>
					</tr>
				</thead>

				<tbody></tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		var table = $('#questionTable').dataTable({
			dom: '<"datatable_header"fl>t<"datatable_footer"p>',
			ajax: '/index.php/dataFunction/questionLists',
			columns: [
			{data: "board_seq"},
			{data: "board_kind"},
			{data: "board_contents"},
			{data: "user_name"},
			{data: "reg_date"},
			{data: "status"},
			{data: "admin_name"}
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

		$('#searchBtn').click(function () {
			var text = $("#search").val();
			var type = $("#type").select().val();

			table.fnReloadAjax('/index.php/dataFunction/questionLists?type=' + type + '&text=' + text + '');
		});

		$("#search").keydown(function (key) {
			var text = $("#search").val();
			var type = $("#type").select().val();
			if (key.keyCode == 13) {
				table.fnReloadAjax('/index.php/dataFunction/questionLists?type=' + type + '&text=' + text + '');
			}
		});

	});

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
</script>