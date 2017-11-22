<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<h3>사전 등록 <small>새로운 단어(이슈 단어)를 사전으로 등록, 관리합니다.</small></h3>
		</div>

		<div class="x_panel">
			<div class="x_content">
				<br />
				<form class="form-horizontal form-label-left" method="post" onsubmit="chkForm(this);return false;" action="/index.php/dataFunction/ins_dictionary2">

					<div class="form-group">
						<label class="control-label col-xs-3" for="exSel">동의어
						</label>
						<div class="col-xs-6">
							<input type="text" id="tags_1" class="tags form-control" name="synonym" placeholder="">
							여러 키워드 입력 가능, (콤마)로 구분, 띄어쓰기 없이 입력
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-xs-3" for="exAdd">단어명
							<span class="required">*</span>
						</label>
						<div class="col-xs-6">
							<input type="text" id="keyword" class="form-control" name="keyword"  placeholder="" required>
						</div>
					</div>
					<div class="ln_solid"></div>
					<div class="form-group">
						<div class="col-xs-6 col-xs-offset-3">
							<button class="btn btn-default" type="button" onclick="location.href = '/index/keyword_prev'">취소</button>
							<button type="submit" class="btn btn-primary">저장</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- /page content -->
<script type="text/javascript">

	function chkForm(obj) {
		var keyword = $("#keyword").val();
		var data = {keyword: keyword};
		$.ajax({
			dataType: 'text',
			url: '/index.php/dataFunction/chk_keyword',
			data: data,
			type: 'POST',
			success: function (data, status, xhr) {
				if (data == 'DUPLE') {
					alert("이미 등록된 단어명입니다.");
					$('#keyword').val('');
					return false;
				} else {
					obj.submit();
				}
			}
		});
	}
</script>