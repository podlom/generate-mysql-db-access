# Generate MySQL access information

Generate MySQL database and user SQL queries generator

## Installation

    git clone git@github.com:podlom/generate-mysql-db-access.git
    cd generate-mysql-db-access
    composer install

## Usage

### PHP command line usage

PHP command line interface usage examples:

    php mysql.php -pDbNamePrefix[ -hlocalhost][ -t1]

**-p**DbNamePrefix db and user names prefix<br>
**-h**localhost (optional) db host name<br>
**-t**1 (optioal) setup additional _test database<br>

### Use on a web server

Build-in PHP web server example usage:

    php -S localhost:8000
    
make user TCP port 8000 is free or use any other available port.
Then open [http://localhost:8000](http://localhost:8000) to generate MySQL access information.
It will serve index.php in the generate-mysql-db-access directory.

### Docker setup

1. Clone your git repository source to a src folder using command below:

    git clone git@github.com:podlom/generate-mysql-db-access.git ./src

2. Copy Dockerfile using command:

    cp -fvp ./src/Dockerfile ./

3. Build docker image using command:

    docker build -t generate-mysql-access-docker .

4. Run docker image using:

    docker run -p 80:80 generate-mysql-access-docker

5. Open the app in your favorite browser by visiting URL: http://localhost/


[![ko-fi](https://ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/L3L5LJ3TB)
