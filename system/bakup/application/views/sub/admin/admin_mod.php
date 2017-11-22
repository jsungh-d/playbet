<!-- page content -->
<div class="right_col" role="main">
	<div class="page-title">
		<h3>관리자 계정 <small>관리자 계정을 생성, 삭제 할 수 있습니다.</small></h3>
	</div>

	<div class="x_panel">
		<div class="x_content">
			<br />
			<form class="form-horizontal form-label-left" method="post" action="/index.php/dataFunction/modAdmin">
				<input type="hidden" name="admin_seq" value="<?= $info->admin_seq ?>">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">이름</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control" name="admin_name" value="<?= $info->admin_name ?>" placeholder="">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">아이디</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control" name="admin_id" value="<?= $info->admin_id ?>" placeholder="" readonly>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">패스워드</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="password" name="admin_pass" class="form-control" placeholder="">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">접속 권한</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select class="form-control" name="admin_level" style="margin-bottom: 15px;">
							<option value="A" <?php if ($info->admin_level == 'A') echo 'selected'; ?>>관리자</option>
							<option value="O" <?php if ($info->admin_level == 'O') echo 'selected'; ?>>운영자</option>
						</select>

						<?php
						$menu_exp = explode("|", $info->menu_seq_list);
						foreach ($top_menu_lists as $row) {
							?>
							<div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
								<p style="font-weight:600;"><?= $row['menu_name'] ?></p>
								<?php
								foreach (${'sub_menu_lists' . $row['menu_seq']} as $subRow) {
									?>
									<label style="display: block; font-weight:400;">
										<input type="checkbox" name="menu_seq_list[]" value="<?= $subRow['menu_seq'] ?>" <?php if(in_array($subRow['menu_seq'], $menu_exp)) echo 'checked';?>> <?= $subRow['menu_name'] ?>
									</label>
									<?php } ?>
								</div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">접속 허용</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select class="form-control" name="acept">
									<option value="1" <?php if ($info->acept == '1') echo 'selected'; ?>>허용</option>
									<option value="0" <?php if ($info->acept == '0') echo 'selected'; ?>>비허용</option>
								</select>
							</div>
						</div>


						<div class="form-group">
							<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
								<button type="button" class="btn btn-default" onclick="location.href = '/index/admin'">취소</button>
								<button type="submit" class="btn btn-primary">수정</button>
							</div>
						</div>

					</form>
				</div>

			</div>
		</div>
	</div>
	
</div>

<!-- /page content -->