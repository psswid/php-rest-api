# php rest api


PHP Rest API based on https://www.codeofaninja.com/2017/02/create-simple-rest-api-in-php.html

Writen OOP way.

Technologies used:
- MySQL,
- php


This is my first attempt to write very basic REST API in pure php to understand of api concept.
Database contains 1 tables: categories, products. See database.json

To see products from database type /product/read.php.
To see products with pagination output type /product/read_paging.php
To see single product type /product/read_one.php?id= and type 1-60
To see find product with word in description type /product/search.php?s={shirt, tablet, etc.}
To see categories type /category/read.php

There are also methods to create, delete and update products.
