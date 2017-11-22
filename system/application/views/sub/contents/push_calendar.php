<!-- page content -->
<div class="right_col" role="main">
	<div class="page-title">
		<h3>푸쉬발송 <small>종목별 발송된 푸쉬 건수를 확인 할 수 있습니다. (최대 7일 선택 가능)</small></h3>
	</div>

	<div class="x_panel">
		<div class="x_content">

			<form class="form-group" method="get" action="/index/push_calendar">
				<div class="form-inline">
					<div class="input-group">
						<label>발송 일자</label>
					</div>
					<div class="input-group date push" data-provide="datepicker">

						<div class="input-group-addon">
							<span class="fa fa-calendar"></span>
						</div>
						<input type="text" name="sdate" id="sdate" class="form-control" value="<?= $sdate ?>" placeholder="날짜지정" required>
					</div>

					<div class="input-group date push" data-provide="datepicker">
						<div class="input-group-addon">
							<span class="fa fa-calendar"></span>
						</div>
						<input type="text" name="edate" id="edate" class="form-control" value="<?= $edate ?>" placeholder="날짜지정" required>
					</div>

					<div class="input-group">
						<select class="form-control" name="news_kind">
							<option value="">전체</option>
							<option value="K" <?php if ($news_kind == 'K') echo 'selected'; ?>>코스닥</option>
							<option value="N" <?php if ($news_kind == 'N') echo 'selected'; ?>>코넥스</option>
							<option value="Y" <?php if ($news_kind == 'Y') echo 'selected'; ?>>코스피</option>
							<option value="E" <?php if ($news_kind == 'E') echo 'selected'; ?>>기타</option>
						</select>
					</div>

					<div class="input-group">
						<button class="btn btn-primary" type="submit">검색</button>
					</div>

				</div>
			</form>

		</div>
	</div>

	<div class="x_panel">
		<div class="x_content">
			<table id="push_table" class="table table-bordered">
				<colgroup><col width="70px"><col width="120px"></colgroup>
				<thead>
					<tr>
						<td>번호</td>
						<td>종목명</td>
						<?php foreach ($dayList as $row) { ?>
							<td><?= $row['DAY_NAME'] ?></td>
						<?php } ?>
						<td>합계</td>
					</tr>
				</thead>
				<tbody>
					<?php
					if ($this->uri->segment(3) == 'q') {
						$num = $total_rows - $this->uri->segment(9);
						$page = $this->uri->segment(9);
						$gubun = $this->uri->segment(5);
						$title = $this->uri->segment(7);
					} else {
						$num = $total_rows - $this->uri->segment(4);
						$page = $this->uri->segment(4);
						$gubun = "none";
						$title = "none";
					}
					foreach ($lists as $row) {
						?>
						<tr>
							<td><?= $num ?></td>
							<td><?= $row['company_name_i'] ?></td>
							<?php
							$value = 0;
							foreach ($dayList as $subRow) {
								?>
								<td><?= number_format(${'send_cnt' . $row['send_id'] . $subRow['DAY_FORMAT']}->send_cnt) ?></td>
								<?php
								$value += ${'send_cnt' . $row['send_id'] . $subRow['DAY_FORMAT']}->send_cnt;
							}
							?>
							<td><?= $value ?></td>
						</tr>
						<?php
						$num--;
					}
					?>
				</tbody>
			</table>
			<?= $pagination ?>
		</div>
	</div>
</div>


<!-- /page content -->