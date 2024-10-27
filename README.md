A simple recipe to start a local PHP application with Docker

Make the necessary changes:

- replace the sitename `mysite.local` with name you need
- (optional) add the nginx proxy configuration to the Nginx installation on the host machine, given in the file `nginx_proxy.conf`
- delete the `index.php` file in the `app/public/` folder with the files of your app.
- replace `yourpcusername` in PHP.Dockerfile with the corresponding name of your pc user.
- it's a local installation, but still you can mysql username and passwords in `compose.yml`