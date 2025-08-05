# Setup 

Before you can follow the steps from this guide to run this project you need to install the following software on your machine:

- `PHP 8`
- `PHP Composer`
- `git`
- `Docker` 
- `Docker-Compose` 
- `Node.js` 
- `npm`
- `MariaDB`

Useful Extensions / Tools:

- `DBeaver`: DB GUI
- `Portainer`: Docker GUI
- `PHPDebugbar`: Data Collector

## 1. Git

Clone the project from the git repository ( https://git.etes.de/edira/edira ):

`git clone git@git.etes.de:edira/edira.git`

or 

`git clone https://git.etes.de/edira/edira.git`

## 2. Docker

### 2.1 .env

Copy the `.env.docker` to `.env`:

`cp .env.docker .env`

To run the docker container in detached mode use:

`docker-compose up -d`

This means that they will continue to run even if you exit the terminal where you started them. 

---

### 2.2 .dockerignore 

To avoid the error:

```
ERROR [app 3/4] COPY ./docker/fpm/php.ini /usr/local/etc/php/php.ini                                                                                     0.0s
------
 > [app 3/4] COPY ./docker/fpm/php.ini /usr/local/etc/php/php.ini:
------
failed to solve: failed to compute cache key:
```

Delete or disable the `.dockerignore` - file

---

### 2.3 /etc/hosts

Add the following code to `/etc/hosts`:

```
# Edira docker development environment
10.6.1.3 edira.docker.de       
10.6.1.6 mailpit.edira.docker.de
```

This file is used to map hostnames to IP addresses.

## 3. Composer

Run `composer install` on the client machine to install the needed PHP Packages / Dependencies

You can run into the error:

`Your lock file does not contain a compatible set of packages.`

This is cause if some PHP extensions are missing. In my case it was:

- `iconv`
- `gd`

Some other packages depend on these two to work. In order two install these you need to add these lines to the `php.ini`

`/etc/php/php.ini`:

```
extension=gd
extension=iconv.so
```
After adding these extensions run `composer install` again

## 4. App Key

To generate an App Key for the Laravel Project run:

`php artisan key:generate`

This kex is essential for encrypting the project data and ensuring security inside the project. The key gets stored inside the `.env`

## 5. NPM

Install the needed npm packages:

`npm install` 

Run the npm scripts:

`npm run dev`


## 6. Database Migration 

To set up the database run:

`php artisan migrate:fresh --seed`

 
## 7. Opening the GUI

After everything is setup correctly you can open the appliction in your browser under this URL:

`edira.docker.de`

