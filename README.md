This is a demo blogging application.<br><br>
You need install Docker service https://github.com/serjga/blog-docker before.<br><br>
To install, run the following commands.

1) You need execute next commands in app directory:<br>
   `cd ../app`<br>
   `chmod 777 ./storage/`<br>
   `chmod 777 ./static/smarty/`

2) Install dependencies
   `composer install`

3) Build application
   `sudo bash scripts/run.sh build`

4) Install database **blog**<br>
   Import db_dump.sql

5) Create **.env** file (You can use **.env.example**)

6) Run application http://localhost
