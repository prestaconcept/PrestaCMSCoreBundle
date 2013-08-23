# Testing

## Configuration

Move to bundle root directory then install dev requirements using composer :

~~~
composer.phar require "symfony/symfony:2.3.*"
composer.phar install
vendor/symfony-cmf/testing/bin/console assets:install vendor/symfony-cmf/testing/resources/web
vendor/symfony-cmf/testing/bin/travis/phpcr_odm_doctrine.sh
~~~

## Unit

Start unit tests using phpunit

~~~
vendor/bin/phpunit
~~~

## Functionnal Frontend

Start functionnal tests :

~~~
_  O  _
 \[ ]/   TODO
~~~

## Functionnal Backend

The backend uses acceptance tests driven development and requires Behat
This can be launched with :

~~~
vendor/symfony-cmf/testing/bin/server & PID=$!
vendor/bin/behat @PrestaCMSCoreBundle
kill -9 $PID
~~~

### Requirements 

Since we test javascript, we use zombie.js, this need node.js :

Install node.js following [the instructions][1]. 
Then install zombie.js 1.4.1.
Don't forget to export NODE_PATH (you can add it to your .bashrc)

~~~
npm install zombie@1.4.1
export NODE_PATH="$(pwd)/node_modules"
~~~

[1]: https://github.com/joyent/node/wiki/Installing-Node.js-via-package-manager