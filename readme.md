# Toilet Rebates App


Fix Date Range Filter bug:

in /vendor/ampeco/nova-date-range-filter/dist/js/date-range-filter.js

Change to

`t.$refs.datePicker.parentNode && t.$refs.datePicker.parentNode.classList ...`

## External Bugs
There is a PHP 7.3 bug that affects the Laravel Mailer with `sync` queue connection. Using the database connection in dev fixes it for me when sending mail but the problem persists when previewing mail. See solution below to preview mailables.

* https://github.com/laravel/framework/issues/26819
* https://bugs.php.net/bug.php?id=77310

```
Quick and dirty, commenting out the following in the MailServiceProvider solves the issue.
if ($app->bound('queue')) { $mailer->setQueue($app['queue']); }
// uncomment
```

## Custom Nova Components
* See [nova-components](nova-components)
* Each component has a readme
* `bash build.sh` to compile all of them to prod

## Setup
1. clone the repo
1. create mysql db
1. `cp .env.example .env`
1. `composer install`
1. `npm install`
1. `artisan migrate:fresh --seed`
1. `artisan self-diagnosis`
1. `artisan queue:work --tries=1` if not using sync connection
1. `artisan tail -H` Tail the latest log file with no stacktraces

## Test Setup
* See `config/database.php`
* See `phpunit.xml`
* Create test database using info from config/database 'testing' connection
* `./vendor/bin/phpunit`

## Dev Tools
* https://github.com/vuejs/vue-devtools
* `artisan dump-server`
* `tail -f storage/logs/query_logger.log`
* https://github.com/hansvn/laravel-querylogger
* `artisan self-diagnosis`

## Deployment
1. Use supervisor to run queue
1. Create a cron to run the scheduler every minute
    * `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`

## Notes
See [notes directory](notes)

## External Form

The conservationpays.com website has a form for submitting
a rebate application to this application.

https://conservationpays.com/rebate-application/

## Server Config
This app uses the [spatie/image-optimizer](https://github.com/spatie/image-optimizer) for compressing uploaded images.

The optimizer requires certain binaries to be installed on the system.

```bash
# Ubuntu
sudo apt-get install jpegoptim
sudo apt-get install optipng
sudo apt-get install pngquant
sudo npm install -g svgo
sudo apt-get install gifsicle

# Mac
brew install jpegoptim
brew install optipng
brew install pngquant
brew install svgo
brew install gifsicle
```

## Cool Stuff To Consider Using
* https://github.com/fat4lix/nova-element-ui
* https://binarcode.github.io/vue-form-wizard/#/?id=npm
* https://github.com/Maatwebsite/Laravel-Nova-Excel
* https://github.com/christophrumpel/nova-notifications

## Nova Features Not Built In
* [Cards on Lens](https://github.com/laravel/nova-issues/issues/613)
* [Button Actions](https://github.com/laravel/nova-issues/issues/451)
* [Default Order By](https://github.com/laravel/nova-issues/issues/156)
* [Sort on Lens Issues](https://github.com/laravel/nova-issues/issues/813)

## Button Actions
* https://github.com/laravel/nova-issues/issues/786

## Updating Nova
```bash
composer update
php artisan nova:publish
```

## Cross ENV error or npm run prod error
* When someone adds a new nova component you need to cd to the comp. and `npm install`