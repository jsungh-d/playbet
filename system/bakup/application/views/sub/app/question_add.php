<?php include_once $_SERVER['DOCUMENT_ROOT'] ."/inc/header.php"; ?>

<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<h3>문의하기 <small>사용자 1:1문의 및 제휴 문의를 관리 할 수 있습니다.</small></h3>
		</div>

		<div class="clearfix"></div>

		<div class="row">
			<div class="col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<ul class="nav navbar-right panel_toolbox">
							<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="#">Settings 1</a>
									</li>
									<li><a href="#">Settings 2</a>
									</li>
								</ul>
							</li>
							<li><a class="close-link"><i class="fa fa-close"></i></a>
							</li>
						</ul>
						<div class="clearfix"></div>
					</div>
					
					<div class="x_content">
						<form id="demo-form2" data-parsley-validate>
							<colgroup><col width="300px"><col width="*"></colgroup>
							<table class="table table-striped table-bordered bulk_action">

								<tbody>
									<tr>
										<th>문의구분</th>
										<td>
											<select class="form-control" required>
												<option value="일반문의">일반문의</option>
												<option value="계정문의">계정문의</option>
												<option value="뉴스문의">뉴스문의</option>
												<option value="제휴문의">제휴문의</option>
											</select>
										</td>
									</tr>
									<tr>
										<th>회원 이름</th>
										<td><input type="text" class="form-control" name="name" required readonly /></td>
									</tr>
									<tr>
										<th>회원 이메일</th>
										<td><input type="text" class="form-control" name="email" required  readonly /></td>
									</tr>
									<tr>
										<th>회원 휴대폰 번호</th>
										<td><input type="text" class="form-control" name="number" required  readonly /></td>
									</tr>
									<tr>
										<th>문의내용</th>
										<td>
											<textarea readonly required="required" class="form-control" name="message" style="resize: none; height:100px;"></textarea>
										</td>
									</tr>
									<tr>
										<th>첨부이미지</th>
										<td><a href="">확인해주세용.jpg</a></td>
									</tr>
									<tr>
										<th>답변내용</th>
										<td>
											<textarea required="required" class="form-control" name="answer" style="resize: none; height:100px;"></textarea>
										</td>
									</tr>
								</tbody>
							</table>
							<div class="form-group">
								<div class="col-md-6 col-sm-6 col-xs-12">
									<button class="btn btn-primary" type="button" onclick="location.href='/sub/app/notice.php'">취소</button>
									<button class="btn btn-primary" type="button">임시저장</button>
									<button type="submit" class="btn btn-success">답변완료</button>
								</div>
							</div>
						</form>
					</div>

				</div>

			</div>
		</div>
	</div>

</div>

<!-- /page content -->



<?php include_once $_SERVER['DOCUMENT_ROOT'] ."/inc/footer.php"; ?>