f<?php require "layout/header.php" ?>

<h1>Danh sách Môn Học</h1>
<a href="/?c=subject&a=add" class="btn btn-info">Add</a>
<a href="/?c=subject&a=formImport" class="btn btn-primary">Import</a>
<a href="/?c=subject&a=export" class="btn btn-primary">Export</a>
<form action="/" method="GET">
	<label class="form-inline justify-content-end">Tìm kiếm: <input type="search" name="search" class="form-control" value="<?=$search?>">
		<button class="btn btn-danger">Tìm</button>
	</label>
	<input type="hidden" name="c" value="subject">
</form>
<table class="table table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>Mã MH</th>
			<th>Tên</th>
			<th>Số tín chỉ</th>
			<th colspan="2">Tùy Chọn</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$order = 0;
			foreach ($subjects as $subject):
							//dấu : và endforeach thày vì {}
				$order++;
				?>
				<tr>
					<td><?=$order?></td>
					<td><?=$subject->getId()?></td>
					<td><?=$subject->getName()?></td>
					<td><?=$subject->getNumberOfCredit()?></td>
					<td><a href="/?c=subject&a=edit&id=<?=$subject->getId()?>">Sửa</a></td>
					<td><button class="delete btn btn-danger" url="/?c=subject&a=delete&id=<?=$subject->getId()?>">Xóa</button></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<div>
		<span>Số lượng: <?=count($subjects)?></span>
	</div>
	<?php require "layout/footer.php" ?>