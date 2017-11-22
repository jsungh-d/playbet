<!-- page content -->
<div class="right_col" role="main">
	<div class="page-title">
		<h3>가입회원 <small>서비스 가입회원을 관리합니다.</small></h3>
	</div>

	<div class="form-group">
		<form class="form-inline">
			<div class="form-group">
				<label for="sdate">가입일시</label>
				<div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
					<div class="input-group-addon">
						<span class="fa fa-calendar"></span>
					</div>
					<input type="text" class="form-control" id="sdate" placeholder="선택안함">
				</div>
				<div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
					<div class="input-group-addon">
						<span class="fa fa-calendar"></span>
					</div>
					<input type="text" class="form-control" id="edate" placeholder="선택안함">
				</div>
			</div>
			<div class="form-group">
				<select class="form-control" id="type_select">
					<option value="all">전체</option>
					<option value="login_path">로그인경로</option>
					<option value="pay">유료/무료</option>
				</select>
			</div>
			<div class="form-group" id="type_area" style="display: none">
				<select class="form-control type_select" id="type_location" style="display: none;">
					<option value="0">타이밍</option>
					<option value="2">페이스북</option>
					<option value="1">카카오</option>
					<option value="3">구글플러스</option>
				</select>
				<select class="form-control type_select" id="type_pay" style="display: none;">
					<option value="1">유료(IOS)</option>
					<option value="2">유료(Android)</option>
					<option value="0">무료</option>
				</select>
			</div>
			<div class="form-group">
				<input type="text" class="form-control" id="search_text" placeholder="검색어입력">
			</div>
			<button type="button" id="search_btn" class="btn btn-default">검색</button>
		</form>
	</div>

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h2>가입회원 수 <small id="rowtotal"></small></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<table id="user_list_table" class="table table-bordered">
						<thead>
							<tr>
								<th>번호</th>
								<th>이름</th>
								<th>이메일</th>
								<th>휴대폰</th>
								<th>로그인 경로</th>
								<th>가입일</th>
								<th>유료/무료</th>
							</tr>
						</thead>

						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>

		<!--user detail view Modal-->
		<div id="myModal" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<form id="antoform" class="form-horizontal calender" method="post" action="/index.php/dataFunction/modUser" role="form">
						<input type="hidden" name="customer_seq" id="customer_seq" value="">
						<div class="modal-header">
							<h4 class="modal-title" id="myModalLabel">회원정보 수정</h4>
						</div>
						<div class="modal-body">
							<div class="row form-group">
								<label class="col-sm-3 control-label">이름</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="user_name" name="user_name" value="" required>
								</div>
							</div>
							<div class="row form-group">
								<label class="col-sm-3 control-label">이메일</label>
								<div class="col-sm-9">
									<input type="email" class="form-control" id="email" name="email" value="" required>
								</div>
							</div>
							<div class="row form-group">
								<label class="col-sm-3 control-label">휴대폰</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="phone" name="phone" pattern="[0-9]*" value="" required>
								</div>
							</div>
							<div class="row form-group">
								<label class="col-sm-3 control-label">로그인경로</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="customer_type" name="customer_type" value="" required>
								</div>
							</div>
							<div class="row form-group">
								<label class="col-sm-3 control-label">가입일시</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="reg_date" name="reg_date" value="" required>
								</div>
							</div>
							<div class="row form-group">
								<label class="col-sm-3 control-label">유료/무료</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="status" name="status" value="" required>
								</div>
							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
							<button type="button" class="btn btn-primary antosubmit" onclick="$('#memberDelModal').modal('show');">탈퇴처리</button>
						</div>
					</form>
				</div>

			</div>
		</div>

		<div id="memberDelModal" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">탈퇴경고</h4>
					</div>
					<div class="modal-body">
						<p>
							회원을 탈퇴처리 하시겠습니까?
						</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default antoclose" data-dismiss="modal">취소</button>
						<button type="button" class="btn btn-primary antosubmit" onclick="delSubmit();">확인</button>
					</div>
				</div>

			</div>
		</div>

	</div>
</div>
<!-- /page content -->


<script type="text/javascript">
	$(document).ready(function () {
		var table = $('#user_list_table').dataTable({
			dom: '<"datatable_header"fl>t<"datatable_footer"p>',
			ajax: '/index.php/dataFunction/user_list',
			columnDefs: [
			{className: "hidden-xs", "targets": [0]},
			{className: "cursor", "targets": [1]},
			{className: "hidden-xs", "targets": [2]}
			],
			columns: [
			{data: "customer_seq"},
			{data: "user_name"},
			{data: "email"},
			{data: "phone"},
			{data: "customer_type"},
			{data: "reg_date"},
			{data: "status"}
			],
			language: {
				lengthMenu: "_MENU_",
				search: "",
				paginate: {
					"next": "&raquo;",
					"previous": "&laquo;"
				}
			},
			footerCallback: function (row, data, start, end, display) {
				$("#rowtotal").html(numberWithCommas(data.length) + '명');
			}
		});

		$('#user_list_table tbody').on('click', 'td.cursor', function () {
			var index = $(this).index('#user_list_table tbody td.cursor');
			var idx = $(".sorting_1:eq(" + index + ")").text();
			var data = {idx: idx};

			$.ajax({
				dataType: 'json',
				url: '/index.php/dataFunction/userView',
				data: data,
				type: 'POST',
				success: function (data, status, xhr) {
					$("#myModal").modal('show');

					$("#customer_seq").val(idx);
					$("#user_name").val(data.user_name);
					$("#email").val(data.email);
					$("#phone").val(data.phone);
					$("#customer_type").val(data.customer_type);
					$("#reg_date").val(data.reg_date);
					$("#status").val(data.status);
				}
			});
		});

		$("#type_select").change(function () {
			var value = $(this).select().val();
			if (value == 'login_path') {
				$("#type_area").show();
				$(".type_select").hide();
				$("#type_location").show();
			} else if (value == 'pay') {
				$("#type_area").show();
				$(".type_select").hide();
				$("#type_pay").show();
			} else {
				$("#type_area").hide();
				$(".type_select").hide();
			}
		});

		$('#search_btn').click(function () {
			var sdate = $("#sdate").val();
			var edate = $("#edate").val();
			var type_select = $("#type_select").select().val();
			var type_location = $('#type_location').select().val();
			var type_pay = $('#type_pay').select().val();
			var search_text = $("#search_text").val();

			if (type_select == 'all') {
				type_location = 'none';
				type_pay = 'none';
			}

			if (!$.trim(search_text)) {
				$("#search_text").val('');
				search_text = '';
			}

			table.fnReloadAjax('/index.php/dataFunction/user_list?sdate=' + sdate + '&edate=' + edate + '&type_select=' + type_select + '&type_location=' + type_location + '&type_pay=' + type_pay + '&search_text=' + search_text + '');
		});

		$("#search_text").keydown(function (key) {
			var sdate = $("#sdate").val();
			var edate = $("#edate").val();
			var type_select = $("#type_select").select().val();
			var type_location = $('#type_location').select().val();
			var type_pay = $('#type_pay').select().val();
			var search_text = $("#search_text").val();

			if (type_select == 'all') {
				type_location = 'none';
				type_pay = 'none';
			}

			if (!$.trim(search_text)) {
				$("#search_text").val('');
				search_text = '';
			}

			if (key.keyCode == 13) {
				table.fnReloadAjax('/index.php/dataFunction/user_list?sdate=' + sdate + '&edate=' + edate + '&type_select=' + type_select + '&type_location=' + type_location + '&type_pay=' + type_pay + '&search_text=' + search_text + '');
			}
		});
	});

	function delSubmit() {
		var idx = $("#customer_seq").val();
                var history_name = $("#user_name").val();
		var data = {customer_seq: idx, history_name: history_name};

		$.ajax({
			dataType: 'text',
			url: '/index.php/dataFunction/delUser',
			data: data,
			type: 'POST',
			success: function (data, status, xhr) {
				if (data == 'SUCCESS') {
					alert("탈퇴 되었습니다.");
					location.reload();
				}
				if (data == 'NONDATA') {
					alert("일치데이터가 없습니다.");
					return false;
				}

				if (data == 'FAILED') {
					alert("데이터 처리오류!!");
					return false;
				}

			}
		});
	}

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
</script>