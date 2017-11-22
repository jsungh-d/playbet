
<!-- page content -->
<div class="right_col" role="main">
	<div class="page-title">
		<h3>종목추가 <small>서비스 종목을 관리합니다.</small></h3>
	</div>


	<div class="x_panel">
		<div class="x_content">
			<br />
			<form class="form-horizontal form-label-left" method="post" action="/index.php/dataFunction/insOption">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">서비스 노출</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select class="form-control" name="stock_status">
							<option value="1">노출</option>
							<option value="0">미노출</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">구분<span class="required">*</span></label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select class="form-control" name="kind">
							<option value="Y">유가</option>
							<option value="K">코스닥</option>
							<option value="N">코넥스</option>
							<option value="E">기타</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">종목명<span class="required">*</span></label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control" name="company_name" placeholder="" required>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">종목명(영어)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control" name="company_name_e" placeholder="">
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">종목명(축약)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control" name="company_name_i" placeholder="">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">종목코드</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control" name="crp_cd" placeholder="">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">업종<span class="required">*</span></label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select class="form-control" name="business_seq">
							<?php foreach($lists as $row){?>
							<option value="<?= $row['business_seq']?>"><?= $row['description']?></option>
							<?php }?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">법인등록번호</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control" name="crp_no" placeholder="">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">사업자등록번호</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control" name="bsn_no" placeholder="">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">1차 분류어</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input id="tags_1" type="text" class="tags form-control" name="keyword" value="" />
						<div id="suggestions-container" style="position: relative; float: left; width: 250px;"></div>
					</div>
				</div>
				<div class="ln_solid"></div>
				<div class="form-group">
					<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
						<button type="button" class="btn btn-default" onclick="location.href='/index/option'">취소</button>
						<button type="submit" class="btn btn-primary">등록</button>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>
<!-- /page content -->
