<?php require "layout/header.php" ?>

<h1>Danh sách sinh viên</h1>
<a href="/?a=add" class="btn btn-info">Add</a>
<a href="/?a=formImport" class="btn btn-primary">Import</a>
<a href="/?a=export" class="btn btn-primary">Export</a>
<form action="/" method="GET">
	<label class="form-inline justify-content-end">Tìm kiếm: <input type="search" name="search" class="form-control" value="<?=$search?>">
		<button class="btn btn-danger">Tìm</button>
	</label>
</form>
<table class="table table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>Mã SV</th>
			<th>Tên</th>
			<th>Ngày Sinh</th>
			<th>Giới Tính</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$order = 0;
		foreach ($students as $student):
						//dấu : và endforeach thày vì {}
			$order++;
			?>
			<tr>
				<td><?=$order?></td>
				<td><?=$student->getId()?></td>
				<td><?=$student->getName()?></td>
				<td><?=$student->getBirthday()?></td>
				<td><?=$student->getGenderName()?></td>
				<td><a href="/?a=edit&id=<?=$student->getId()?>">Sửa</a></td>
				<td><button class="delete btn btn-danger" url="/?a=delete&id=<?=$student->getId()?>">Xóa</button></td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
<div>
	<span>Số lượng: <?=count($students)?></span>
</div>

<?php require "layout/footer.php" ?>