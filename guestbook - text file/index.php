<!DOCTYPE html>
<html>
	<head>
		<title>The Great Guestbook</title>
		<link rel="stylesheet" href="stylesheet.css">
	</head>
	<body>
		<?php
			require_once("Guestbook.class.php");
			$guestbook = new Guestbook;
			
			$page = isset($_GET['p']) ? $_GET['p'] : null;
			
			if(isset($_POST['mode']) && $_POST['mode'] == "add_comment"){
				$guestbook->AddComment($_POST['name'], $_POST['email'], $_POST['comment']);
			}
			
			echo '<h1 class="gb-headding">Guestbook</h1>';
			echo $guestbook->LoadComments();
			
			echo '<h1 class="gb-headding">Add a Comment</h1>';
			echo $guestbook->CommentForm();
		?>
	</body>
</html>