<?php
require_once 'db.php';

$db = new Db();




function getNewUserName(){
	if(!file_exists('name_rus.txt')) return;
	$filenames = file('name_rus.txt');

	return trim($filenames[rand(0, count($filenames)-1)]);
}




function printTop(){
	echo '
		<!DOCTYPE HTML>
		<html>
			<head>
				<title>tz1</title>
				<meta charset="utf-8">
			</head>
			<body>
	';
}




function printBottom(){
	echo '
				<script src="script.js"></script>
			</body>
		</html>
	';
}




function printButtons(){
	echo '
		<br>
		<form method="POST">
			<input type="hidden" name="action" value="new">
			<input type="submit" value="Добавить (FORM)">
		</form>
		<input type="button" id="addAjaxBtn" value="Добавить (AJAX)">
		<br>
	';
}




function printUsers($users){
	echo '<div id="userContainer">';
	foreach($users as $u){
		echo "
				<strong>Имя: </strong>{$u['user_name']}.
				<strong>Место жительства: </strong> {$u['city_name']}.
				<strong>Навыки: </strong> {$u['skill_names']};
			<br>
		";
	}
	echo '</div>';
}




function addUserWithSkills($db){
	$randomCityId = $db -> getRandomCityId()[0];
	$userId = $db -> addUser(
		getNewUserName(),
		$randomCityId
	);

	if($userId){
		$randomSkillsIds = $db -> getRandomSkillIds(rand(1, 5));
		foreach($randomSkillsIds as $skill){
			$db -> addUserSkill($userId, $skill);
		}
	}
}




if(isset($_POST['action']) && ($_POST['action'] === 'new' || $_POST['action'] === 'newAJAX')){
	addUserWithSkills($db);

	$users = $db -> getUsers();

	if($_POST['action'] === 'new'){
		printTop();
		printUsers($users);
		printButtons();
		printBottom();
	}
	else{
		printUsers($users);
	}
}
else{
	$users = $db -> getUsers();
	printTop();
	printUsers($users);
	printButtons();
	printBottom();
}
