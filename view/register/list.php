<?php require "layout/header.php" ?>

<h1>Danh sách sinh viên đăng ký môn học</h1>
<a href="/?c=register&a=add" class="btn btn-info">Add</a>
<a href="/?c=register&a=formImport" class="btn btn-primary">Import</a>
<a href="/?c=register&a=export" class="btn btn-primary">Export</a>
<form action="/" method="GET">
	<label class="form-inline justify-content-end">Tìm kiếm: <input type="search" name="search" class="form-control" value="<?=$search?>">
		<button class="btn btn-danger">Tìm</button>
	</label>
	<input type="hidden" name="c" value="register">
</form>
<table class="table table-hover">
	<thead>
		
		<tr>
			<th>#</th>
			<th>Mã SV</th>
			<th>Tên SV</th>
			<th>Mã MH</th>
			<th>Tên MH</th>
			<th>Điểm</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$order = 0;
			foreach ($registers as $register):
							//dấu : và endforeach thày vì {}
				$order++;
				?>
		<tr>
			<td><?=$order?></td>
			<td><?=$register->getStudentId()?></td>
			<td><?=$register->getStudentName()?></td>
			<td><?=$register->getSubjectId()?></td>
			<td><?=$register->getSubjectName()?></td>
			<td><?=$register->getScore()?></td>
			<td><a class="btn btn-info" href="/?c=register&a=edit&student_id=<?=$register->getStudentId()?>&subject_id=<?=$register->getSubjectId()?>">Cập nhật điểm</a></td>
						<td><button class="delete btn btn-danger" url="/?c=register&a=delete&student_id=<?=$register->getStudentId()?>&subject_id=<?=$register->getSubjectId()?>">Xóa</button></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
<div>
	<span>Số lượng: <?=count($registers)?></span>
</div>
<?php require "layout/footer.php" ?>