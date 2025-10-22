# How to test the Assets REST API using the Bruno API client

(If you prefer Postman, see the official WoodWing Postman collections:
[ww-assets-postman-collection](https://github.com/WoodWing/ww-assets-postman-collection),
[ww-assets-10-postman-collection](https://github.com/WoodWing/ww-assets-10-postman-collection))

Download Bruno from https://www.usebruno.com/.

In Bruno, open the “Assets REST API” subfolder as a collection.

Under “Environments / Configure”, create a copy of the “assets.example.com” environment and set the `assetsUrl`, 
`assetsUsername` and `assetsPassword` variables.

Open the “Service / API login” request and send it. The JSON response should contain `"loginSuccess": true` and 
an `authToken`, which is automatically written into your environment’s `assetsAuthToken` variable.

Now you can execute any other request. “Service / search” and “Service / browse” are good starting points since
they are read-only and work out of the box, without having to set any parameters.

Note that the “PrivateApi” requests require a “superuser” Assets login, and are neither documented not supported
by WoodWing. Use at your own risk.
