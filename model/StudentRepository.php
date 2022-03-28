<?php 
	class StudentRepository {
		protected $error;

		function getAll() {
			return $this->fetch();
		}

		function getByPattern($search) {
			$cond = "name LIKE '%$search%'";
			return $this->fetch($cond);
		}

		function fetch($cond = null) {
			global $conn;
			$sql = "SELECT * FROM student";
			if (!empty($cond)) {
				$sql .= " WHERE $cond";
			}
			$result = $conn->query($sql);
			$students = [];
			if ($result->num_rows > 0) {
				//num_rows: thuộc tính của result, có sẵn
				while($row = $result->fetch_assoc()) {
					$student = new Student($row["id"], $row["name"], $row["birthday"], $row["gender"]);
					$students[] = $student;
				}
			}
			return $students;
		}
		function save($data) {
			global $conn;
			$name = $data["name"];
			$birthday = $data["birthday"];
			$gender = $data["gender"];
			if (!empty($data["id"])) {
				$id = $data["id"];
				$sql = "INSERT INTO student (id, name, birthday, gender) VALUES ($id, '$name', '$birthday', $gender)"; //chuỗi có nháy đơn, số ko cần nháy đơn
			}
			else {
				$sql = "INSERT INTO student (name, birthday, gender) VALUES ('$name', '$birthday', $gender)"; //chuỗi có nháy đơn, số ko cần nháy đơn
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
			$students = $this->fetch($cond);
			$student = current($students); // lấy 1 sv
			return $student;
		}
		function update($student) {
			global $conn;
			$name = $student->getName();
			$birthday = $student->getBirthday();
			$gender = $student->getGender();
			$id = $student->getId();

			$sql = "UPDATE student SET name='$name', birthday='$birthday', gender=$gender WHERE id=$id";
			if ($conn->query($sql)) {
				return true;
			}
			$this->error = "Error: " . $sql . "<br>" . $conn->error;
			return false;

		}
		function delete($id) {
			global $conn;
			$sql = "DELETE FROM student WHERE id = $id";
			if ($conn->query($sql)) {
				return true;
			}
			// echo $conn->errno; // lấy mã lỗi
			if ($conn->errno == 1451) {
				$this->error = "Sinh viên này đã được đăng ký môn học, không được xóa";
				return false;
			}
			
			$this->error = "Error: " . $sql . "<br>" . $conn->error;
			return false;
		}
	}
 ?>