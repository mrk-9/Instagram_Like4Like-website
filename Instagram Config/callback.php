<?php
session_start();

require_once('instagram-api-settings.php');
require_once('instagram-login-api.php');

// Instagram passes a parameter 'code' in the Redirect Url
if(isset($_GET['code'])) {
	try {
		$instagram_ob = new InstagramApi();
		
		// Get the access token 
		$access_token = $instagram_ob->GetAccessToken(INSTAGRAM_CLIENT_ID, INSTAGRAM_REDIRECT_URI, INSTAGRAM_CLIENT_SECRET, $_GET['code']);
		
		// Get user information
		$user_info = $instagram_ob->GetUserProfileInfo($access_token);

		// Echo user information for display
		$html = '<div style="margin:40px auto 0 auto;width:350px">';
			$html .= '<div style="margin:0 0 20px 0">';
				$html .= '<div style="display:inline-block; width:150px;vertical-align:middle;font-weight:700">Name</div><div style="display:inline-block; vertical-align:middle">' . $user_info['full_name'] . '</div>';
			$html .= '</div>';
			$html .= '<div style="margin:0 0 20px 0">';
				$html .= '<div style="display:inline-block; width:150px;vertical-align:middle;font-weight:700">ID</div><div style="display:inline-block; vertical-align:middle">' . $user_info['id'] . '</div>';
			$html .= '</div>';
			$html .= '<div style="margin:0 0 20px 0">';
				$html .= '<div style="display:inline-block; width:150px;vertical-align:middle;font-weight:700">Username</div><div style="display:inline-block; vertical-align:middle">' . $user_info['username'] . '</div>';
			$html .= '</div>';
			$html .= '<div style="margin:0 0 20px 0">';
				$html .= '<div style="display:inline-block; width:150px;vertical-align:middle;font-weight:700">Picture</div><img style="display:inline-block; vertical-align:middle;width:75px" src="' . $user_info['profile_picture'] . '" />';
			$html .= '</div>';
		$html .= '</div>';
		echo $html;

		// Now that the user is logged in you may want to start some session variables
		// $_SESSION['logged_in'] = 1;

		// You may now want to redirect the user to the home page of your website
		// header('Location: home.php');
	}
	catch(Exception $e) {
		echo $e->getMessage();
		exit;
	}
}

?>