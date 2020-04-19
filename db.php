<?php
class Db
{
	private $dsn = "mysql:host=localhost;dbname=tz1;charset=utf8";
	private $opt = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false,
	];
	private $user = 'tz1';
	private $pass = 'tz1';
	
	private $db;
	
	function __construct(){
		$this -> db = new PDO($this -> dsn, $this -> user, $this -> pass, $this -> opt);
	}




	public function getRandomCityId(){
		return $this -> db -> query("
			SELECT
				id
			FROM
				city
			ORDER BY
				RAND()
			LIMIT 1
		")
		-> fetchAll(PDO::FETCH_COLUMN);
	}




	public function getRandomSkillIds($count){
		$query =  $this -> db -> prepare("
			SELECT
				id
			FROM
				skills
			ORDER BY
				RAND()
			LIMIT ?
		");
		
		$query -> bindValue(1, $count, PDO::PARAM_INT);
		$query -> execute();
		
		return $query -> fetchAll(PDO::FETCH_COLUMN);
	}




	public function addUserSkill($user_id, $skill_id){
		if(!$user_id || !$skill_id) return null;
			
		$this -> db -> prepare("
			INSERT
			INTO
				`user_skills` (`user_id`, `skill_id`)
			VALUES (
				:user_id,
				:skill_id
			)
		")
		-> execute([
			'user_id' => $user_id,
			'skill_id' => $skill_id
		]);
	}




	public function addUser($name, $city_id){
		if(!$name || !$city_id) return null;
			
		$this -> db -> prepare("
			INSERT INTO
				users (name, city_id)
			VALUES (
				:name,
				:city_id
			)
		")
		-> execute([
			'name' => $name,
			'city_id' => $city_id
		]);
		
		return $this -> db -> lastInsertId();
	}




	public function getUsers(){
		$users = $this -> db -> query("
			SELECT 
				u.id `user_id`, u.name `user_name`, u.city_id,
				c.id, c.name `city_name`,
				us.user_id, us.skill_id,
				s.id, s.name `skill_name`,
				GROUP_CONCAT(s.name SEPARATOR ', ') as skill_names
			FROM
				users u
			LEFT JOIN `city` c ON u.city_id = c.id
			LEFT JOIN `user_skills` us ON u.id = us.user_id
			LEFT JOIN `skills` s ON s.id = us.skill_id
			GROUP BY u.id
		")
		-> fetchAll();
		
		return $users;
	}
}
