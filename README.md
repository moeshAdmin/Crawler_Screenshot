
# Crawler_Screenshot

## About Project
A Crawler with Screenshot base on Laravel+Vuejs+html2canvas

## 10 Step Setup Guide
1) Check Composer/PHP(>=7.4) environment.
2) git clone this repo.
3) Change `.env.sample` to `.env`.
4) Laravel KeyGen.
5) Make a sqlite file.
6) Change `DB_CONNECTION` and `DB_DATABASE` path with sqlite.
7) Laravel Migrate.
8) Setup Your Web Server. (or use Laravel build-in server but no multi-thread)
9) Set Web Server htdocs to Laravel public folder.
10) Ready to go :)

## How-To
1) `composer -v`
2) `php -v`
3) `git clone https://github.com/moeshAdmin/Crawler_Screenshot.git`
4) `cd Crawler_Screenshoot`
5) `composer install --optimize-autoloader --no-dev`
6) `php -r "file_exists('.env') || copy('.env.example','.env');"` to make a `.env` file.
7) `php artisan key:generate --ansi`
8) `> /database/db.sqlite3` to make a sqlite db.
9) `vi .env`, change `DB_CONNECTION=mysql` to `DB_CONNECTION=sqlite` and `DB_DATABASE=laravel` to `DB_DATABASE=database/db.sqlite3`.
10) `php artisan migrate` to migrate db.
11) `vi .env`, change `DB_DATABASE=database/db.sqlite3` to `DB_DATABASE=../database/db.sqlite3`. 
12) Setup Web Server, set htdocs to Crawler_Screenshot/public folder. (Optional, or use `php artisan serve` run build-in server)
13) Ready to go :)
