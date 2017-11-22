<!-- page content -->
<div class="right_col" role="main">
	<div class="page-title">
		<h3>관리자 계정 <small>관리자 계정을 생성, 삭제 할 수 있습니다.</small></h3>
	</div>

	<div class="x_panel">
		<div class="x_title">
			<h2>계정 수<small id="rowtotal">0000건</small></h2>
			<div class="clearfix"></div>
		</div>

		<div class="x_content">

			<table id="adminTable" class="table table-bordered datatable">
				<colgroup><col width="30px"><col width="*"><col width="*"><col width="*"><col width="*"><col width="*"><col width="*"><col width="*"></colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check-all"></th>
						<th>번호</th>
						<th>이름</th>
						<th>아이디</th>
						<th>패스워드</th>
						<th>접속 권한</th>
						<th>접속 허용</th>
						<th>접속일</th>
					</tr>
				</thead>

				<tbody>
				</tbody>
			</table>



		</div><!--업종삭제모달-->
		<div id="optionDelModal" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">삭제경고</h4>
					</div>
					<div class="modal-body">
						<p>
							관리자를 삭제하시겠습니까?
						</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
						<button type="button" class="btn btn-primary antosubmit" onclick="delSubmit();">삭제처리</button>
						<input type="hidden" id="idxs" value="">
					</div>
				</div>

			</div>

		</div>
	</div>
</div>

<!-- /page content -->



<script type="text/javascript">
	$(document).ready(function () {
		$('#adminTable').dataTable({
			dom: '<"datatable_header"fl>t<"datatable_footer"Bp>',
			ajax: '/index.php/dataFunction/adminList',
			columns: [
			{data: "checkbox"},
			{data: "admin_seq"},
			{data: "admin_name"},
			{data: "admin_id"},
			{data: "admin_pass"},
			{data: "admin_level"},
			{data: "acept"},
			{data: "reg_date"}
			],
			columnDefs: [{
				orderable: false,
				className: 'select-checkbox',
				targets: 0
			},
			{className: "cursor", "targets": [2]}
			],
			select: {
				style: 'os',
				selector: 'td:first-child'
			},
			order: [],
			buttons: [
			{
				className: 'btn btn-sm btn-primary',
				text: '삭제',
				action: function () {
					delContents();
				}
			},
			{
				className: 'btn btn-sm btn-primary',
				text: '관리자 추가',
				action: function () {
					location.href = '/index/admin_add';
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
					"next": "&raquo",
					"previous": "&laquo"
				}
			}
		});

		$('#adminTable tbody').on('click', 'td.cursor', function () {
			var index = $(this).index('#adminTable tbody td.cursor');
			var idx = $(".row_check:eq(" + index + ")").val();
			location.href = '/index/admin_mod/' + idx + '';
		});
	});

	function delContents() {
		var check = $('.row_check:checked').length;
		var check_number = "";
		if (check == 0) {
			alert('회원을 체크 해주세요.');
			return false;
		} else {
			$(".row_check:checked").each(function (index) {
				check_number += $(this).val() + ",";
			});
			var appNum = check_number.substr(0, check_number.length - 1);
			$("#idxs").val(appNum);
			$("#optionDelModal").modal();
		}
	}

	function delSubmit() {
		var data = {idx: $("#idxs").val()};
		$.ajax({
			dataType: 'text',
			url: '/index.php/dataFunction/delAdmin',
			data: data,
			type: 'POST',
			success: function (data, status, xhr) {
				alert("삭제 되었습니다.");
				location.reload();
			}
		});
	}

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
</script>