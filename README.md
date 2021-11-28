# PHP CLI Web parser
This is simple script that as first param use existing url and get html content and parse it ,after return JSON with tags a,img,script,link with array of links
# Insalation 
For install use this command
```
git clone https://github.com/RRanar/dukascopy_test_task
```
# How to use 
If you have instaled docker and docker-compose you can runthis command
```
docker-compose up -d
```
After run this command
```
docker-compose run app php index.php [url] # where [url] your URL for get JSON
```
Or if you haven`t docker and docker-compose you must have installed PHP version 7.2.34 and PHP extension zip , also you must have instaled composer
Than go to directory app
```
cd app
```
Install composer packages
```
composer install
```
And run script
```
php index.php [url] # where [url] your URL for get JSON
```

