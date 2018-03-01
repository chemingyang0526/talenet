talenet
=======

A Symfony project created on February 23, 2018, 8:08 pm.

After cloning this project from Git, please create a database first, and fill in the DB settings in parameters.yml.

To create the tables in the DB, please run php bin/console doctrine:schema:update --force at the project's root directory.

To see the data provided, please run php bin/console doctrine:fixtures:load at the project's root directory.

The seeder codes can be found in these files: src/AppBundle/DataFixtures/CategoryFixtures.php, src/AppBundle/DataFixtures/ProductFixtures.php, and src/AppBundle/DataFixtures/UserFixtures.php

This project uses the lexikJWTAuthenticationBundle, and requires private and public SSH keys in the /var/jwt directory, please follow the instruction on lexikJWTAuthenticationBundle's github.com page.

Please also note that this project uses PHPUnit for Unit Testing of the REST API. Please install PHPUnit.  You may follow the instruction on https://phpunit.de

The Unit Test codes can be found in these files: src/AppBundle/Tests/Controller/Api/CategoryControllerTest.php and src/AppBundle/Tests/Controller/Api/ProductControllerTest.php

To run the Unit Test for each of Prodcut api and Category api, please run php phpunit app %path_to_the_test_file%

Thank you.