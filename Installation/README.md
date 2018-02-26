Just put the files under Project in the destinated directories. After that you only have to call:

    DOMAIN + BASEPATH + /PermissionManager/install
    
e.g. your domain is `https://urdomain.com` and your base path `/CodeIgniter` the installation path would be `https://urdomain.com/CodeIgniter/PermissionManager/install`.

Then the database will be set up.

Afterwards call:

	DOMAIN + BASEPATH + /PermissionsManager/installation

Next you can choose your user, wich will get the `superuser`-Permission which allows you to add, delete and manage the permissions.