<!-- page content -->
<div class="right_col" role="main">
		<div class="page-title">
			<h3>푸쉬관리(뉴스/공시) <small>시스템에서 발송된 뉴스와 공시 푸쉬 내역을 확인합니다.</small></h3>
		</div>

		<div class="x_panel">
			<div class="x_title">
				<h2>푸쉬 발송 수 <small id="rowtotal">00건</small></h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<br />
				<form class="form-horizontal form-label-left">
					<div class="form-group">
						<div class="form-inline">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">전일뉴스</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select class="form-control input-sm">
									<option value="1">1건</option>
									<option value="2">2건</option>
									<option value="3">3건</option>
									<option value="4">4건</option>
									<option value="5">5건</option>
								</select>
								이상인 종목은 핵심 키워드를 통한 2차 분류를 진행. 
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="form-inline">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">당일뉴스</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select class="form-control input-sm">
									<option value="1">1건</option>
									<option value="2">2건</option>
									<option value="3">3건</option>
									<option value="4">4건</option>
									<option value="5">5건</option>
								</select>
								이상인 종목 뉴스는 푸쉬 발송
								<button type="button" class="btn btn-sm btn-primary">적용</button>
							</div>
						</div>
					</div>
				</form>

				<div class="ln_solid"></div>


				<form class="form-horizontal form-label-left">
					<div class="form-group">
						<div class="form-inline">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">푸쉬 발송 시점</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class="input-group date" data-provide="datepicker">
									<div class="input-group-addon">
										<span class="fa fa-calendar"></span>
									</div>
									<input type="text" class="form-control" placeholder="날짜지정">
								</div>
								<div class="input-group date" data-provide="datepicker">
									<div class="input-group-addon">
										<span class="fa fa-calendar"></span>
									</div>
									<input type="text" class="form-control" placeholder="날짜지정">
								</div>
								
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="form-inline">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">1차 범위</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select class="form-control min-width">
									<option value="전체">전체</option>
									<option value="온라인 뉴스">온라인 뉴스</option>
									<option value="신문">신문</option>
									<option value="방송">방송</option>
									<option value="공시">공시</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="form-inline">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">2차 범위</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select class="form-control min-width">
									<option value="전체">전체</option>
									<option value="온라인 뉴스">온라인 뉴스</option>
									<option value="신문">신문</option>
									<option value="방송">방송</option>
									<option value="공시">공시</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="form-inline">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">제목명</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class="input-group">
									<input type="text" id="search" class="form-control optionSearchText" placeholder="">
									<span class="input-group-btn">
										<button class="btn btn-primary" id="searchBtn" type="button">검색</button>
									</span>
								</div>
							</div>
						</div>
					</div>
				</form>

				
			</div>
		</div>

		<div class="x_panel">
			<div class="x_content">
				<table id="push_table" class="table table-bordered">
					<colgroup><col width="70px"><col width="100px"><col width="180px"><col width="*"><col width="160px"><col width="140px"></colgroup>
					<thead>
						<tr>
							<td>번호</td>
							<td>뉴스/공시</td>
							<td>종목</td>
							<td>제목</td>
							<td>뉴스.공시 등록 시점</td>
							<td>발송 시간</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td>온라인 뉴스</td>
							<td>삼성전자,LG전자</td>
							<td><a href="/sub/contents/push_detail.php">코스피 장중 올해 최고가 기록</a></td>
							<td>2017-03-02 09:05</td>
							<td>2017-03-02 09:05</td>
						</tr>
						<tr>
							<td>2</td>
							<td>공시</td>
							<td>삼성전자</td>
							<td><a href="/sub/contents/push_detail.php">(삼성전자)주 (정정)유상증자결정</a></td>
							<td>2017-03-01 08:55</td>
							<td>2017-03-01 08:50</td>
						</tr>
						<tr>
							<td>3</td>
							<td>신문</td>
							<td>삼성전자</td>
							<td><a href="/sub/contents/push_detail.php"></a></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>4</td>
							<td>방송</td>
							<td>삼성전자</td>
							<td><a href="/sub/contents/push_detail.php"></a></td>
							<td></td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		var table = $('#push_table').dataTable({
			dom: '<"datatable_header"fl>t<"datatable_footer"p>',
			ajax: '/index.php/dataFunction/pushLists',
			columns: [
			{data: "send_id"},
			{data: "news_kind"},
			{data: "stock_name"},
			{data: "news_title"},
			{data: "reg_date"},
			{data: "confirm_date"}
			],
			columnDefs: [{
				orderable: false,
				className: 'select-checkbox',
				targets: 0
			}],
			select: {
				style: 'os',
				selector: 'td:first-child'
			},
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
	});
	
	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	
</script>


<!-- /page content