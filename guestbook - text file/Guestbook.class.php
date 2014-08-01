<?php
	class Guestbook{
		const STORAGE_FILE = "guestbook.txt";	//The location and name of the storage file
		const GB_COMMENT_LIMIT = 15;	//The number of comments to display
		
		const DATE_FORMAT = "M j, Y";	//How the date should be formatted
		const ADD_COMMENT_ACTION = "index.php";	//The url for the add comment form's action attribute
		
		function AddComment($name, $email, $comment){
			$serialized = $name . "," . time() . "," . $email . "," . $comment. "\n";
			
			if(file_exists(self::STORAGE_FILE)){
				$content = file_get_contents(self::STORAGE_FILE);
				$content = $serialized . $content;
			}
			else{
				$content = $serialized;
			}
			
			file_put_contents(self::STORAGE_FILE, $content);
		}
		
		function LoadComments(){
			if(!file_exists(self::STORAGE_FILE)){
				//No comments yet.
				$output = '<div class="gb-error">No comments available.</div>';
			}
			else{
				$content = file_get_contents(self::STORAGE_FILE);
				$dataSet = explode("\n", $content);
				
				$output = '<table class="gb-table">';
				foreach($dataSet as $key=>$value){
					if($key >= self::GB_COMMENT_LIMIT){
						break;
					}
					elseif($value == ""){
						continue;
					}
					
					$row = explode(",",$value);
					$output .= '<tr>
									<td>
										<span class="gb-header">
											Posted by <span class="gb-title">' .$row[0] .'</span> on <span class="gb-date">' .date(self::DATE_FORMAT, $row[1]). '</span>
										</span>
										<p class="gb-comment">' .$row[3]. '</p>
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
	}
?>