<p align="center"><a href="https://www.cloudweb.ch" target="_blank" rel="noopener noreferrer"><img src="https://www.cloudweb.ch/wp-content/uploads/2023/02/cloudweb-10-jahre-logo.webp" width="400" alt="Laravel Logo" /></a></p>

## About the Bexio API CSV exporter

Based on Laravel 10, the app features a simple [Bexio API](https://www.bexio.com) client that allows you to read customers, projects and projects timesheets and to download the timesheets in CSV format.<br/>
The Bexio API client is using the recommended [OpenID client](https://github.com/jumbojett/OpenID-Connect-PHP) to connect.<br/>
The client features a complete OAuth2 flow, including refresh tokens logic.
The app is in ongoing development.

## Requirements

- PHP 8.2;
- MySQL;
- NodeJS 18+.

## Installation

- Copy repository to your server or local environment;
- Create a database to use with the app;
- Create an .env file based on the .env.example file;
- Add BEXIO_CLIENT_ID and BEXIO_CLIENT_SECRET in the .env file. Take these from the [Bexio Developer center](https://developer.bexio.com/) after creating an app to support your integration
- Run composer install;
- Run npm install;
- Run php artisan migrate;
- Run php artisan optimize;
- Run php artisan db:seed --class=FirstAdminSeeder

## Usage

- Login with email <strong>admin@admin.com</strong> and password <strong>password</strong>
- Navigate to /profile and change your password.
- From the left hand side menu you will be able to access the sections that are available.
- You can set the [Bexio API scopes](https://docs.bexio.com/#section/Authentication/API-Scopes) for each user from the Users section.

## About Us

- [cloudWEB GmbH](https://www.cloudweb.ch/agentur/)
