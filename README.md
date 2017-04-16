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

* mysql -u root -p
* CREATE DATABASE `task.net`;
* CREATE USER 'task'@'localhost' IDENTIFIED BY 'password@task.net';
* GRANT ALL PRIVILEGES ON * . * TO 'task'@'localhost';
* FLUSH PRIVILEGES;

# Dependencies Installation

* cd config
* composer install
* npm install
* bower install

## Installation

composer require anush/ultimate

## Usage

## Credits

* Myself for now