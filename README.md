# E3T

This project contains the files needed to run the webapplication made for E3T.

## Prerequisites

- An Apache webserver
- A MySQL databaseserver
- PHP

## Running

The following steps are required to make the webapplication accessible.

- Download all necessary files.
- Change constants.php.dist to constants.php and fill in the required constants. These required constants being the databasehost, databasename, databaseuser and databasepassword.

```json
    <?php
        $dbhost = "// Insert the databasehost here";
        $dbname = "// Insert the databasename here";
        $dbuser = "// Insert the username for the database here";
        $dbpassword = "// Insert the password here";
    ?>
```

Usually the databasehost is `mysql` and the user is `root`. For this repository the databasename is `e3t`.

- Import the .sql-file on the databaseserver. This can be done through the phpMyAdmin interface.
- The .php-files, .css-files and images need to be imported on the webserver. The location for this on the server is `/var/www/[domain]/`.


---

If you are planning on using this repository for contribution, then different steps are required.

## Prerequisites

- A Docker container
- PHP
- Git
- Cmder

## Running

For installing the Docker environment we refer to [Docker environment for NHL Stenden](https://github.com/Schmitzenbergh/NHL_Stenden_PHP_Docker_Env).

- Open Cmder and go to the correct directory using the `cd` command, which is a folder called `app/public` if the link above has been used.
- Clone the respository using Git into the Docker environment.

```json
git clone [SSH-link]
```

The SSH-link can be found on GitHub.

After cloning the repository, the files should appear in `app/public/e3t`. The files can then be edited locally.

To view the output of the files go to 127.0.0.1 in any browser.