<!-- page content -->
<div class="right_col" role="main">
		<div class="page-title">
			<h3>푸쉬관리(뉴스/공시) <small>시스템에서 발송된 뉴스와 공시 푸쉬를 확인합니다.</small></h3>
		</div>

		<div class="x_panel">
			<div class="x_content">
				<form id="demo-form2" data-parsley-validate>
					<table class="table table-striped table-bordered bulk_action">
						<colgroup><col width="140px"><col width="*"></colgroup>
						<tbody>
							<tr>
								<th>뉴스/공시</th>
								<td><?= $info->news_kind_nm?></td>
							</tr>
							<tr>
								<th>언론사/제공처</th>
								<td><?= $info->media_name?></td>
							</tr>
							<tr>
								<th>뉴스/공시 등록 시점</th>
								<td><?= $info->reg_date?></td>
							</tr>
							<tr>
								<th>푸쉬 발송 시간</th>
								<td><?= $info->confirm_date?></td>
							</tr>
							<tr>
								<th>제목</th>
								<td><?= $info->news_title?></td>
							</tr>
							<tr>
								<th>내용</th>
								<td style="height:200px">
									<?= $info->news_contents?>
								</td>
							</tr>
							<tr>
								<th>해당 종목</th>
								<td><?= $info->stock_name?></td>
							</tr>
						</tbody>
					</table>
					<div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<button class="btn btn-primary" type="button" onclick="location.href = '/index/push'">목록</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- /page content -->