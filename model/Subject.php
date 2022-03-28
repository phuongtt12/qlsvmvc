<?php 
	class Subject {
		protected $id; 
		protected $name; 
		protected $number_of_credit;
		

		function __construct($id, $name, $number_of_credit) {
			$this->id = $id; //$this: truy xuất vào thuộc tính id của class
			$this->name = $name;
			$this->number_of_credit = $number_of_credit;
		}
		
		// mặc định tầm vực truy xuất của thuộc tính là hàm là public
		function getId() {
			return $this->id;
		}

		function getName() {
			return $this->name;
		}
		function getNumberOfCredit() {
			return $this->number_of_credit;
		}

		function setName($name) {
			$this->name = $name;
			return $this;
		}
		function setNumberOfCredit($number_of_credit) {
			$this->number_of_credit = $number_of_credit;
		}

	}
 ?>