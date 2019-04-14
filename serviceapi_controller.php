<?php

/*
  All Emoncms code is released under the GNU Affero General Public License.
  See COPYRIGHT.txt and LICENSE.txt.

  ---------------------------------------------------------------------
  Emoncms - open source energy visualisation
  Part of the OpenEnergyMonitor project:
  http://openenergymonitor.org
 
  Service API module developed by [Carbon Co-op](https://carbon.coop/)

 */

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

function serviceapi_controller() {

    global $user, $route, $path, $serviceapi_apikey, $serviceapi_mode;

    $result = false;

    if (prop('service_apikey') === $serviceapi_apikey) {

        if ($route->format !== 'json') {
            http_response_code(400);
            return "400 bad request";
        }

        $userid = 0;

        // 1. Get userid
        $username = prop('usernameaccess');
        if ($username)
            $userid = $user->get_id($username);
        else {
            $user_email = prop('emailaccess');
            if ($user_email) {
                $users = $user->get_usernames_by_email($user_email);
                $userid = $users[0]['id'];
            }
        }

        if ($userid > 0) {
            // 2. Get apikey
            if ($serviceapi_mode === "write")
                $apikey = $user->get_apikey_write($userid);
            else
                $apikey = $user->get_apikey_read($userid);

            // 3. Prepare curl request
            $URL = $path . str_replace("serviceapi/", '', get('q'));
            foreach ($_GET as $key => $value) {
                if ($key != "service_apikey" && $key != "q")
                    $URL .= "&" . $key . '=' . $value;
            }
            $ch = curl_init($URL);
            curl_setopt($ch, CURLOPT_POST, 1);
            $post_fields = 'apikey=' . $apikey;
            foreach ($_POST as $key => $value) {
                if ($key != "service_apikey")
                    $post_fields .= "&" . $key . '=' . $value;
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);

            // 4. execute curl request
            curl_exec($ch);
            curl_close($ch);
            die();
        }
    }

    if ($result === false) {
        http_response_code(401);
        return array('content' => "401 Unauthorized",);
    }
}
