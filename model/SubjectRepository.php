<?php 
	class SubjectRepository {
		function getAll() {
			return $this->fetch();
		}

		function getByPattern($search) {
			$cond = "name LIKE '%$search%' OR number_of_credit LIKE '$search'";
			return $this->fetch($cond);
		}

		function fetch($cond = null) {
			global $conn;
			$sql = "SELECT * FROM subject";
			if (!empty($cond)) {
				$sql .= " WHERE $cond";
			}
			$result = $conn->query($sql);
			$subjects = [];
			if ($result->num_rows > 0) {
				//num_rows: thuộc tính của result, có sẵn
				while($row = $result->fetch_assoc()) {
					$subject = new Subject($row["id"], $row["name"], $row["number_of_credit"]);
					$subjects[] = $subject;
				}
			}
			return $subjects;
		}

		function save($data) {
			global $conn;
			$name = $data["name"];
			$number_of_credit = $data["number_of_credit"];
			if (!empty($data["id"])) {
				$id = $data["id"];
				$sql = "INSERT INTO subject (id, name, number_of_credit) VALUES ($id, '$name', $number_of_credit)"; //chuỗi có nháy đơn, số ko cần nháy đơn
			}
			else {
				$sql = "INSERT INTO subject (name, number_of_credit) VALUES ('$name', $number_of_credit)"; //chuỗi có nháy đơn, số ko cần nháy đơn
			}
			

			if($conn->query($sql)) {
				return true;
			}
			$this->error = "Error: " . $sql . "<br>" . $conn->error;
			return false;
		}
		function getError() {
			return $this->error;
		}
		function find($id) {
			$cond = "id=$id";
			$subjects = $this->fetch($cond);
			$subject = current($subjects); // lấy 1 sv
			return $subject;
		}
		function update($subject) {
			global $conn;
			$name = $subject->getName();
			$number_of_credit = $subject->getNumberOfCredit();
			$id = $subject->getId();

			$sql = "UPDATE subject SET name='$name', number_of_credit='$number_of_credit' WHERE id=$id";
			if ($conn->query($sql)) {
				return true;
			}
			$this->error = "Error: " . $sql . "<br>" . $conn->error;
			return false;

		}
		function delete($id) {
			global $conn;
			$sql = "DELETE FROM subject WHERE id = $id";
			if ($conn->query($sql)) {
				return true;
			}
			// echo $conn->errno; // lấy mã lỗi
			if ($conn->errno == 1451) {
				$this->error = "Môn học này đã được đăng ký môn học, không được xóa";
				return false;
			}
			
			$this->error = "Error: " . $sql . "<br>" . $conn->error;
			return false;
		}

		function getNotUnregistered($student_id) {
			$cond = "id NOT IN (SELECT subject_id FROM register WHERE student_id=$student_id)";
			$subjects = $this->fetch($cond);
			return $subjects;
		}


	}
 ?>