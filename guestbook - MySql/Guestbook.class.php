<?php
	class Guestbook extends mysqli{
		const DB_HOST = "localhost";	//The mysql host
		const DB_USERNAME = "root";	//The mysql username
		const DB_PASSWORD = "";		//The mysql passoword
		const DB_NAME = "guestbook";	//The mysql DB
		const GB_TABLE = "comments";	//The name of the mysql table
		const GB_COMMENT_LIMIT = 15;	//The number of comments to display
		
		const DATE_FORMAT = "M j, Y";	//How the date should be formatted
		const ADD_COMMENT_ACTION = "index.php";	//The url for the add comment form's action attribute
		
		function __Construct(){
			parent::__Construct(self::DB_HOST, self::DB_USERNAME, self::DB_PASSWORD, self::DB_NAME);
			
			if (mysqli_connect_error()) {
				die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
			}
		}
		
		function __Destruct(){
			parent::close();
		}
		
		function AddComment($name, $email, $comment){
			$stmt = "INSERT INTO " .self::GB_TABLE. " VALUES(NULL, '" .$name. "','" .$email. "','" .$comment. "','" .time(). "')";
			$query = $this->query($stmt);
			if(!$query){
				die("Error adding comment.  Please contact the webmaster.");
			}
		}
		
		function LoadComments(){
			$stmt = "SELECT * FROM " .self::GB_TABLE. " ORDER BY time ASC";
			$query = $this->query($stmt);
			if(!$query){
				die($this->error);
			}
			
			if($query->num_rows == 0){
				//No comments yet.
				$output = '<div class="gb-error">No comments available.</div>';
			}
			else{
				$output = '<table class="gb-table">';
				while($row = $query->fetch_assoc()){
					$output .= '<tr>
									<td>
										<span class="gb-header">
											Posted by <span class="gb-title">' .$row['name'] .'</span> on <span class="gb-date">' .date(self::DATE_FORMAT, $row['time']). '</span>
										</span>
										<p class="gb-comment">' .$row['comment']. '</p>
									</td>
								</tr>';
				}
				$output .= '</table>';
			}
			
			return $output;
		}
		
		function CommentForm(){
			$output = '<form class="gb-form" action="' .self::ADD_COMMENT_ACTION. '" method="post">
							<input type="hidden" name="mode" value="add_comment">
							<input class="gb-input" type="text" name="name" size="35" maxlength="20" placeholder="Your Name" required><br>
							<input class="gb-input" type="text" name="email" size="35" maxlength="80" placeholder="email@example.com" required><br>
							<textarea class="gb-input-area" name="comment" rows="7" cols="30" placeholder="Your comments..." required></textarea><br>
							<input class="gb-input-button" type="submit" value="Add Comment">
						</form>';
			return $output;
		}
		
		/*
		*	!!! Setup is used to prepare the database. !!!
		*
		*		All the user has to do to install the guestbook is add
		*		the database and make sure the values here are set peoperly.  
		*		The program does the rest.
		*/
		function Setup(){
			echo "Creating table...<br>";
			$sql = "CREATE TABLE IF NOT EXISTS " .self::GB_TABLE. "(
						id int(11) AUTO_INCREMENT,
						name varchar(20),
						email varchar(100),
						comment text,
						time int(64),
						PRIMARY KEY (id)
					)";
			$query = $this->query($sql);
			if(!$query){
				die($this->error);
			}
			else{
				echo "Database is ready.  Have fun!";
			}
		}
	}
?>