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

**-p**DbNamePrefix db and user names prefix
**-h**localhost (optional) db host name
**-t**1 (optioal) setup additional _test database

### Use on a web server

Build-in PHP web server example usage:

    php -S localhost:8000
    
make user TCP port 8000 is free or use any other available port.
Then open [http://localhost:8000](http://localhost:8000) to generate MySQL access information.
It will serve index.php in the generate-mysql-db-access directory.
