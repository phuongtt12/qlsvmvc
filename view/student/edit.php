<?php require "layout/header.php" ?>

<h1>Chỉnh sửa sinh viên</h1>
<form action="/?a=update" method="POST">
	<input type="hidden" name="id" value="<?=$student->getId()?>">
	<div class="container">
		<div class="row">
			<div class="col-md-5">
				<div class="form-group">
					<label>Tên</label>
					<input type="text" class="form-control" placeholder="Tên của bạn" required name="name" value="<?=$student->getName()?>">
				</div>
				<div class="form-group">
					<label>Birthday</label>
					<input type="date" class="form-control" placeholder="Ngày sinh của bạn" required name="birthday" value="<?=$student->getBirthday()?>">
				</div>
				<div class="form-group">
					<label>Chọn Giới tính</label>
					<select class="form-control" id="gender" name="gender" required>
						<option value="0" <?=$student->getGender() == 0 ? "selected": ""?>>Nam</option>
						<option value="1" <?=$student->getGender() == 1 ? "selected": ""?>>Nữ</option>
						<option value="2" <?=$student->getGender() == 2 ? "selected": ""?>>Khác</option>
					</select>
				</div>
				<div class="form-group">
					<button class="btn btn-success" type="submit">Lưu</button>
				</div>
			</div>
		</div>
	</div>
</form>
<?php require "layout/footer.php" ?>