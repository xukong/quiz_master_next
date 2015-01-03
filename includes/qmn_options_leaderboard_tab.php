<?php
function mlw_options_leaderboard_tab()
{
	echo "<li><a href=\"#tabs-4\">Leaderboard</a></li>";
}

function mlw_options_leaderboard_tab_content()
{
	global $wpdb;
	global $mlwQuizMasterNext;
	$quiz_id = $_GET["quiz_id"];
	///Submit saved leaderboard template into database
	if ( isset($_POST["save_leaderboard_options"]) && $_POST["save_leaderboard_options"] == "confirmation")
	{
		///Variables for save leaderboard options form
		$mlw_leaderboard_template = $_POST["mlw_quiz_leaderboard_template"];
		$mlw_leaderboard_quiz_id = $_POST["leaderboard_quiz_id"];
		$update = "UPDATE " . $wpdb->prefix . "mlw_quizzes" . " SET leaderboard_template='".$mlw_leaderboard_template."', last_activity='".date("Y-m-d H:i:s")."' WHERE quiz_id=".$mlw_leaderboard_quiz_id;
		$results = $wpdb->query( $update );
		if ($results != false)
		{
			$mlwQuizMasterNext->alertManager->newAlert('The leaderboards has been updated successfully.', 'success');
			
			//Insert Action Into Audit Trail
			global $current_user;
			get_currentuserinfo();
			$table_name = $wpdb->prefix . "mlw_qm_audit_trail";
			$insert = "INSERT INTO " . $table_name .
				"(trail_id, action_user, action, time) " .
				"VALUES (NULL , '" . $current_user->display_name . "' , 'Leaderboard Options Have Been Edited For Quiz Number ".$mlw_leaderboard_quiz_id."' , '" . date("h:i:s A m/d/Y") . "')";
			$results = $wpdb->query( $insert );
		}
		else
		{
			$mlwQuizMasterNext->alertManager->newAlert('There has been an error in this action. Please share this with the developer. Error Code: 0009.', 'error');
		}
	}
	
	if (isset($_GET["quiz_id"]))
	{
		$table_name = $wpdb->prefix . "mlw_quizzes";
		$mlw_quiz_options = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE quiz_id=%d LIMIT 1", $_GET["quiz_id"]));
	}
	?>
	<div id="tabs-4" class="mlw_tab_content">
		<h3>Template Variables</h3>
		<table class="form-table">
			<tr>
				<td><strong>%FIRST_PLACE_NAME%</strong> - The name of the user who is in first place</td>
				<td><strong>%FIRST_PLACE_SCORE%</strong> - The score from the first place's quiz</td>
			</tr>
		
			<tr>
				<td><strong>%SECOND_PLACE_NAME%</strong> - The name of the user who is in second place</td>
				<td><strong>%SECOND_PLACE_SCORE%</strong> - The score from the second place's quiz</td>
			</tr>
		
			<tr>
				<td><strong>%THIRD_PLACE_NAME%</strong> - The name of the user who is in third place</td>
				<td><strong>%THIRD_PLACE_SCORE%</strong> - The score from the third place's quiz</td>
			</tr>
			
			<tr>
				<td><strong>%FOURTH_PLACE_NAME%</strong> - The name of the user who is in fourth place</td>
				<td><strong>%FOURTH_PLACE_SCORE%</strong> - The score from the fourth place's quiz</td>
			</tr>
			
			<tr>
				<td><strong>%FIFTH_PLACE_NAME%</strong> - The name of the user who is in fifth place</td>
				<td><strong>%FIFTH_PLACE_SCORE%</strong> - The score from the fifth place's quiz</td>
			</tr>
			
			<tr>
				<td><strong>%QUIZ_NAME%</strong> - The name of the quiz</td>
			</tr>
		</table>
		<button id="save_template_button" class="button" onclick="javascript: document.quiz_leaderboard_options_form.submit();">Save Leaderboard Options</button>
		<?php
			echo "<form action='' method='post' name='quiz_leaderboard_options_form'>";
			echo "<input type='hidden' name='save_leaderboard_options' value='confirmation' />";
			echo "<input type='hidden' name='leaderboard_quiz_id' value='".$quiz_id."' />";
		?>
    	<table class="form-table">
			<tr>
				<td width="30%">
					<strong>Leaderboard Template</strong>
					<br />
					<p>Allowed Variables: </p>
					<p style="margin: 2px 0">- %QUIZ_NAME%</p>
					<p style="margin: 2px 0">- %FIRST_PLACE_NAME%</p>
					<p style="margin: 2px 0">- %FIRST_PLACE_SCORE%</p>
					<p style="margin: 2px 0">- %SECOND_PLACE_NAME%</p>
					<p style="margin: 2px 0">- %SECOND_PLACE_SCORE%</p>
					<p style="margin: 2px 0">- %THIRD_PLACE_NAME%</p>
					<p style="margin: 2px 0">- %THIRD_PLACE_SCORE%</p>
					<p style="margin: 2px 0">- %FOURTH_PLACE_NAME%</p>
					<p style="margin: 2px 0">- %FOURTH_PLACE_SCORE%</p>
					<p style="margin: 2px 0">- %FIFTH_PLACE_NAME%</p>
					<p style="margin: 2px 0">- %FIFTH_PLACE_SCORE%</p>
				</td>
				<td><textarea cols="80" rows="15" id="mlw_quiz_leaderboard_template" name="mlw_quiz_leaderboard_template"><?php echo $mlw_quiz_options->leaderboard_template; ?></textarea>
				</td>
			</tr>
		</table>
		<button id="save_template_button" class="button" onclick="javascript: document.quiz_leaderboard_options_form.submit();">Save Leaderboard Options</button>
		</form>
	</div>
	<?php
}
add_action('mlw_qmn_options_tab', 'mlw_options_leaderboard_tab');
add_action('mlw_qmn_options_tab_content', 'mlw_options_leaderboard_tab_content');
?>