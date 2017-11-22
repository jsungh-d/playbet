<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/inc/header.php"; ?>

<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<h3>UV / PV <small>UV / PV 통계를 확인 할 수 있습니다.</small></h3>
		</div>
		<div class="clearfix"></div>

		<div class="row">
			<div class="col-xs-12">
				<div class="x_panel">

					<div class="x_content">
						<br />

						<div class="form-group">
							<div class="row">
								<div class="col-sm-12">
									<form class="form-inline">
										<div class="form-group">
											<label for="date01">조회 일자</label>
											<div class="input-group date" data-provide="datepicker">
												<div class="input-group-addon">
													<span class="fa fa-calendar"></span>
												</div>
												<input type="text" class="form-control" placeholder="시작일">
											</div> ~
											<div class="input-group date" data-provide="datepicker">
												<div class="input-group-addon">
													<span class="fa fa-calendar"></span>
												</div>
												<input type="text" class="form-control" placeholder="종료일">
											</div>
										</div>
										<div class="form-group">
										</div>
										<button type="button" class="btn btn-default">일간</button>
										<button type="button" class="btn btn-default">주간</button>
										<button type="button" class="btn btn-default">월간</button>
										<button type="button" class="btn btn-default">다운로드</button>
									</form>
								</div>	
							</div>
						</div>
						<colgroup><col width="*"><col width="*"><col width="*"><col width="*"><col width="*"><col width="*"><col width="*"><col width="*"></colgroup>
						<table class="table table-striped table-bordered bulk_action">
							<thead>
								<tr>
									<th rowspan="2">날짜</th>
									<th colspan="2">메인<br>(키워드)</th>
									<th colspan="2">메인<br>(리스트)</th>
									<th colspan="2">관심종목</th>
									<th colspan="2">랭킹<br>(인기종목)</th>
									<th colspan="2">랭킹<br>(인기키워드)</th>
									<th colspan="2">랭킹<br>(인기뉴스)</th>
									<th colspan="2">알림</th>
									<th colspan="2">스크랩</th>
									<th colspan="2">로그인</th>
								</tr>
							</thead>
							<tbody>
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
								<tr>
									<td>2017-03-26(일)</td>
									<td>10</td>
									<td>240</td>
									<td>79</td>
									<td>0</td>
								</tr>
								<tr>
									<td>2017-03-25(토)</td>
									<td>11</td>
									<td>230</td>
									<td>48</td>
									<td>1</td>
								</tr>
								<tr>
									<td>2017-03-24(금)</td>
									<td>9</td>
									<td>219</td>
									<td>50</td>
									<td>0</td>
								</tr>
							</tbody>
						</table>
						<div class="x_content2">
							신규회원 수
							<div id="line_chart" style="width:100%; height:300px;"></div>
						</div>
					</div>

				</div>

			</div>

		</div>
	</div>

	<!-- /page content -->



	<!-- FastClick -->
	<script src="/vendors/fastclick/lib/fastclick.js"></script>
	<!-- NProgress -->
	<script src="/vendors/nprogress/nprogress.js"></script>
	<!-- iCheck -->
	<script src="/vendors/iCheck/icheck.min.js"></script>
	<!-- jQuery Tags Input -->
	<script src="/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
	<!-- Switchery -->
	<script src="/vendors/switchery/dist/switchery.min.js"></script>
	<!-- Datepicker -->
	<script src="/vendors/datepicker/bootstrap-datepicker.js"></script>

	<!-- morris.js -->
	<script src="/vendors/raphael/raphael.min.js"></script>
	<script src="/vendors/morris.js/morris.min.js"></script>
	<script type="text/javascript">

		$(document).ready(function () {
			$('.datepicker').datepicker();


			new Morris.Line({
				element: 'line_chart',
				data: [
				{ d: '24일', value: 11 },
				{ d: '25일', value: 10 },
				{ d: '26일', value: 11 },
				{ d: '27일', value: 9 }
				],
				xkey: 'd',
				ykeys: ['value'],
				labels: ['Value'],
				parseTime: false
			});
		});
	</script>


	<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/inc/footer.php"; ?>