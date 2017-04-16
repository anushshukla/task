Ultimate Site for All
=====================

Managment of Everything.

Products and Services.

Blogging.

Creating CMS Websites with customization via manual development support as well.

## Badges
## Description
## Requirements

# Apache2 Server Setup
* Enable Apache2 Rewrite Module : sudo a2enmod rewrite
* Enable Apache2 Rewrite SSL :sudo a2enmod ssl
* Enable Apache2 Rewrite Proxy :sudo a2enmod proxy

# SSL Key and Certificate
openssl req -x509  -sha256 -nodes -days 365 -newkey rsa:2048 -in /etc/php5/cacert.pem  -keyout ./certificates/task.net.key -out ./certificates/task.net.crt -config ./config/openssl.cnf

# PHP

* OpenSSL PHP Extension
<!-- PDO PHP Extension -->
* sudo apt-get install php7.1-mysql
<!-- Mbstring PHP Extension -->
* sudo apt-get install php7.1-mbstring
<!-- XML PHP Extension -->
* sudo apt-get install php7.1-xml 
<!-- PDO PHP Extension -->
* sudo apt-get install php7.1-pdo 

# MySQL Database Setup

mysql -u root -p
CREATE DATABASE `task.net`;
CREATE USER 'task'@'localhost' IDENTIFIED BY 'password@task.net';
GRANT ALL PRIVILEGES ON * . * TO 'task'@'localhost';
FLUSH PRIVILEGES;

# Dependencies Installation

cd config
composer install
npm install
bower install

## Installation

composer require anush/ultimate

## Usage

## Credits

* Myself for now

php7.1

Laravel has the most extensive and thorough documentation and video tutorial library of any modern web application framework. The [Laravel documentation](https://laravel.com/docs) is thorough, complete, and makes it a breeze to get started learning the framework.

If you're not in the mood to read, [Laracasts](https://laracasts.com) contains over 900 video tutorials on a range of topics including Laravel, modern PHP, unit testing, JavaScript, and more. Boost the skill level of yourself and your entire team by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for helping fund on-going Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](http://patreon.com/taylorotwell):

- **[Vehikl](http://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[British Software Development](https://www.britishsoftware.co)**
- **[Styde](https://styde.net)**
- **[Codecourse](https://www.codecourse.com)**
- [Fragrantica](https://www.fragrantica.com)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
