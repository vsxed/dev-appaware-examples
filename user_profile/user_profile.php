<?php

/**
 * This is an example of how to integrate AppAware in your application. Read the tutorial and follow the steps described at:
 *
 * http://dev.appaware.com/1/doc/examples/example1.php
 *
 * @author 42matters AG
 * All rights reserved
 */

// your client id which you receive per email after your registration
$myClientId = 'YOUR_CLIENT_ID';

// user data access scope - ask for access to basic profile info and apps of the user
$scope = 'basic apps';

// redirect url that was registered with the app
$redirect_uri = 'YOUR_REDIRECT_URI';

// user login url
$oauthLoginUrl = 'http://appaware.com/oauth/authorize?client_id=' . $myClientId . '&scope=' . $scope . '&redirect_uri=' . $redirect_uri;


// auth failed
if (isset($_GET['error']) && $_GET['error'] == "access_denied") {
    echo "Please authorize access to use this service.";
    exit();
}


if (!isset($_GET['access_token'])){
    echo '<a href="' . $oauthLoginUrl . '">Connect with your AppAware Account</a> </br></br></br>';
}else{

    // auth succeeded
    echo 'Connected with AppAware';
    echo '</br>';

    try {

        // access user data
        $accessUserProfileUrl = 'http://dev.appaware.com/1/user/show.json?access_token=' . $_GET['access_token'] . '&preview_apps=true&likes=true&app_info=extended';

        $response = file_get_contents($accessUserProfileUrl);
        $data = json_decode($response);

        $id = $data->id;
        $username = $data->username;
        $bio = $data->bio;
        $user_url = $data->user_url;
        $device = $data->device;

        $apps_count = $data->apps_count;
        $apps = $data->preview_apps;

        $likes = $data->likes;
        $likes_count = $data->likes_count;

        // display user data
        echo 'Username: <a href="' . $user_url . '"> ' . $data->username . '</a>';
        echo '</br>';

        echo 'Id: ' . $id ;
        echo '</br>';

        echo 'Bio: ' . $bio ;
        echo '</br>';

        echo 'Device: ' . $device ;
        echo '</br>';

        echo 'Apps Count: ' . $apps_count ;
        echo '</br>';

        echo "<h2>Likes: </h2>";
        echo '</br>';

        if (!is_null($likes)) {
            foreach ($likes as $app) {

                $appName = $app->name;
                $appIcon = $app->icon;
                $appUrl = $app->url;

                echo '<a href="' . $appUrl . '">';
                echo '<img src="' . $appIcon . '"/>' . $appName;
                echo '</a></br>';

            }
        }


        echo '<h2>Preview apps:</h2>';
        echo '</br>';

        if (!is_null($apps)) {
            foreach ($apps as $app) {

                $appName = $app->name;
                $appIcon = $app->icon;
                $appUrl = $app->url;

                echo '<a href="' . $appUrl . '">';
                echo '<img src="' . $appIcon . '"/>' . $appName;
                echo '</a></br>';

            }
        }


    } catch (Exception $e) {
        echo 'An error occured while fetching your data: ' . $e->getMessage();
    }

}

?>