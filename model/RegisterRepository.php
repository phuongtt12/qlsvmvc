<?php 
	class RegisterRepository {
		function getAll() {
			return $this->fetch();
		}

		function getByPattern($search) {
			$cond = "student.name LIKE '%$search%' OR subject.name LIKE '%$search%'";
			return $this->fetch($cond);
		}

		function fetch($cond = null) {
			global $conn;
			$sql = "SELECT register.*, student.name AS student_name, subject.name AS subject_name FROM register
				JOIN student ON register.student_id = student.id
				JOIN subject ON register.subject_id = subject.id
			";
			if (!empty($cond)) {
				$sql .= " WHERE $cond";
			}
			$result = $conn->query($sql);
			$registers = [];
			if ($result->num_rows > 0) {
				//num_rows: thuộc tính của result, có sẵn
				while($row = $result->fetch_assoc()) {
					$register = new Register($row["student_id"], $row["subject_id"], $row["score"], $row["student_name"], $row["subject_name"]);
					$registers[] = $register;
				}
			}
			return $registers;
		}

		function save($data) {
			global $conn;
			$student_id = $data["student_id"];
			$subject_id = $data["subject_id"];
			if (!empty($data["score"])) {
				$score = $data["score"];
				$sql = "INSERT INTO register (student_id, subject_id, score) VALUES ($sudent_id, $subject_id, $score)"; 
			}
			else {
				$sql = "INSERT INTO register (student_id, subject_id) VALUES ($student_id, $subject_id)"; //chuỗi có nháy đơn, số ko cần nháy đơn
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
		function find($student_id, $subject_id) {
			$cond = "student_id=$student_id AND subject_id=$subject_id";
			$registers = $this->fetch($cond);
			$register = current($registers); // lấy 1 sv
			return $register;
		}
		function update($register) {
			global $conn;
			$student_id = $register->getStudentId();
			$subject_id = $register->getSubjectId();
			$score = $register->getScore();
			if (is_null($score) || $score === "") {
				$sql = "UPDATE register SET score=NULL WHERE student_id=$student_id AND subject_id=$subject_id";
			}
			else {
				$sql = "UPDATE register SET score=$score WHERE student_id=$student_id AND subject_id=$subject_id";
			}
			
			if ($conn->query($sql)) {
				return true;
			}
			$this->error = "Error: " . $sql . "<br>" . $conn->error;
			return false;

		}
		function delete($student_id, $subject_id) {
			global $conn;
			$sql = "DELETE FROM register WHERE student_id=$student_id AND subject_id=$subject_id";
			if ($conn->query($sql)) {
				return true;
			}
			$this->error = "Error: " . $sql . "<br>" . $conn->error;
			return false;
		}

		
	}
 ?>