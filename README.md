# Microblogging Service

## Installation

<br/>

First, create database with then name "**ibtikar_microblogging**" or change it in .env file

<br/>

This command is going to migrate database with seeders, setup laravel passport with fixed client secret, then run tests if exists

```
php artisan setup:app
```

<br/>

## Usage

Seeders created 2 users with the following credentials:
emails: **ahmdswerky@gmail.com**, **admin@ibtikar.net.sa**
password: **123456**

The website has 2 aspects:

- **Website area**
  <br />
  provides connecting twitter account to user, this is only to replace the mobile SDKs
- **API service**
  <br />
  provides an API authentication using laravel passport package, and the provided documentation in [API Docs](#api-docs)

<br/>

## API Docs

**NOTE:**
<br/>
Localization is applied to API through a header ex:
<br/>
**X-locale**: *en*
<br/>
**X-locale**: *ar*

*supported languages now are English, Arabic only.*

an API documentation with examples on the provided link below

[Microblogging API Postman Collection](https://documenter.getpostman.com/view/5216161/T1LV8PVB?version=latest)
