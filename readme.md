Emoncms is an open-source web application for processing, logging and visualising energy, 
temperature and other environmental data and is part of the [OpenEnergyMonitor project](
http://openenergymonitor.org).

# ServiceAPI module
Get access to user's data in an emonCMS installation by providing a master apikey (aka service_apikey)
and the username or email address of the user.
The service_apikey can be sent in the query string of the URL or as a POST field (recommended)
Developed by [Carbon Co-op](https://carbon.coop/)

## Example
The address to use would look like
```
https://youremoncms.org/feed/list.json?service_apikey=the_key_in_your_settings&usernameaccess=a_user_name
https://youremoncms.org/feed/list.json?service_apikey=the_key_in_your_settings&emailaccess=a_user_email
```

## License
This module is released under the GNU Affero General Public License

## Installation
In the modules directory of your emonCMS installation run
```
git clone https://github.com/carboncoop/emonCMS_serviceapi serviceapi
```
Generate a 32 bit apikey by running `openssl rand -hex 32`. 
Copy the generated key into settings.php:
```
$service_apikey = "YOUR_APIKEY"
```

Grant Write or Read API access in settings.php:
```
$serviceapi_mode = "write || read"; // if omitted it will be "read" by default
```

