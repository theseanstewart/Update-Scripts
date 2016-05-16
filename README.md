[![Build Status](https://travis-ci.org/theseanstewart/Update-Scripts.svg?branch=master)](https://travis-ci.org/theseanstewart/Update-Scripts)

# Laravel Update Scripts

This Laravel 5 package makes it easy to run scripts that modify data in your database or perform other tasks and behaves just like migrations.

## How to install

Pull the package in through Composer.

```js
"require": {
    "seanstewart/update-scripts": "dev-master"
}
```

Include the service provider within `app/config/app.php`.

```php
'providers' => [
    Seanstewart\UpdateScripts\UpdateScriptsServiceProvider::class
];
```

Then you will need to run the following command to publish the migration responsilbe for setting up the "updates" table in your database.

```js
php artisan vendor:publish
```

After that, you'll need to migrate your database

```js
php artisan migrate
```

## How to Use

Let's say you need to run some scripts that modify content in your database. Doing this through migrations isn't a great idea as those should strictly handle changes to the database structure. Run the following command to generate a new update script:

```js
php artisan update:make some_description_for_your_update
```

A file will be generated in your laravel base directory called "updates" that looks like 2016_05_15_145375_some_description_for_your_update.php (again, it works just like migrations).

When you're ready to run the update script, you'd call the command....

```js
php artisan update:run
```

That's it! Updates are tracked in your database just like migrations so they don't run more than once.

# Why I created this

Previously we were running updates to our database data in migrations. It just felt dirty, so we decided to build something that handles updates but works just like migrations. We're using it in production for our election building service ([Election Runner](https://electionrunner.com)) and it has made the development/updating process much easier. Hopefully others will find this as useful as we do!