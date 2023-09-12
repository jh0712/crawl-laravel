# Crawl-Laravel  
>this project use Laravel+React
>folder management use Module `nwidart/laravel-modules` plugin.

## Dev Env
- Backend
	- Composer  v2.5.8 
	- PHP v8.1
	- Mysql v8.0.33
- Frontend
	- Node v16.13.0 / v12.22.12
	- Npm v8.1.0 / v6.14.16
- Container
	- Docker Compose, Docker
- Web Server
	- Nginx
## This Project Include 
- Auth ( Login, Register, Logout, Update Email, Update User Data )
- Document System
	- 2 table : document type + documents( use morph connect another Model )
- Crawl Function
	- Datatable List Page ( index and datatable )
		- Can filter all column ( id, title, description, created at)
		- Can pagination 
		- Can choose how many row you want to show ( 10, 20, 30, 40, 50 )
	- Crawl Search Page ( create / store )
	- Crawl Success Page ( show )
		- Show screenshot, title, url, description, created_at
		- Can not update ,only show information
	- Crawal Edit Page ( edit / update ) ( Detail Page )
		- Show screenshot, title, url, description, body, created_at
		- Can edit and update
- Crawl Function can do two things
	- Get url content
	- Screenshot
> Route Path        : \\crawl-laravel\\Modules\\Crawl\\Routes\\web.php
> Controller Path : \\crawl-laravel\\Modules\\Crawl\\Http\\Controllers\\CrawlController.php
> View Path         :  \\crawl-laravel\\resources\\js\\Pages\\Crawl\\
## Install
```
# laravel 
composer install
php artisan config:ca
php artisan migrate --seed  

# react  
npm install
npm run build # or npm run dev
```  
## Unit Test   
- Command
```  
#Crawl function test  
php artisan test  --filter=CrawlFunctionTest  
```  
- There are 4 test 
	- crawl url content
	- crawl screenshot
	- edit crawled result data
	- the main function 
- When this following error
```
/crawl-laravel/node_modules/puppeteer/.local-chromium/linux-869685/chrome-linux/chrome: error while loading shared libraries: libnss3.so: cannot open shared object file: No such file or directory.  
```
- Run this to update chrome related file 
```
apt-get install -y wget gnupg \
    && wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add - \
    && sh -c 'echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google.list' \
    && apt-get update \
    && apt-get install -y google-chrome-unstable fonts-ipafont-gothic fonts-wqy-zenhei fonts-thai-tlwg fonts-kacst fonts-freefont-ttf \
      --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*
```
> Unit Test Path: \\crawl-laravel\\tests\\Unit\\CrawlFunctionTest.php
