<?php require "layout/header.php" ?>
<h1>Cập nhật điểm</h1>
			<script src="../lib/jquery/jquery.min.js"></script>
			<script src="../lib/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
			<form action="/?c=register&a=update" method="POST">
				<input type="hidden" name="student_id" value="<?=$register->getStudentId()?>">
				<input type="hidden" name="subject_id" value="<?=$register->getSubjectId()?>">
				<div class="container">
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								<label>Tên sinh viên</label>
								<span><?=$register->getStudentName()?></span>
							</div>
							<div class="form-group">
								<label>Tên môn học</label>
								<span><?=$register->getSubjectName()?></span>
							</div>
							<div class="form-group">
								<label for="score">Điểm</label>
								<input type="text" name="score" id="score" value="<?=!empty($register->getScore()) ? $register->getScore() : ""?>">
							</div>
							<div class="form-group">
								<button class="btn btn-success" type="submit">Lưu</button>
							</div>
						</div>
					</div>
				</div>
			</form>
<?php require "layout/footer.php" ?>