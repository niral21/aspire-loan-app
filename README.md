## Mini Aspire Loan App

A Laravel app that helps user to apply for loan for a specific term period and then repay the part of amount every week once admin approves the loan application.

The task is defined below:

- Build a simple API that allows to handle user loans
- Necessary entities will have to be : users, loans and repayments.
- The API should allow simple use cases, which include creating a new user, creating a new loan for a user with different attributes (e.g. principal, repayment frequency), and allowing a user to make repayments for the loan.
- The app logic should figure out and not allow obvious errors. For example a user cannot make a repayment for a loan that’s already been repaid.

## Installation Instructions

- `git clone https://github.com/niral21/aspire-loan-app.git`
- `composer update`
- rename the `.env.example` file as `.env`
- set 
    
       DB_DATABASE=YOURDBNAME
       DB_USERNAME=YOURDBUSERNAME
       DB_PASSWORD=YOURDBPASSWORD
      
- `php artisan key:generate`
- `php artisan migrate:fresh --seed`
- `php artisan passport:install`
- `php artisan config:cache`
- `php artisan serve`

Yup that's it you are ready to test postman collection 

postman collection link : https://drive.google.com/file/d/1BqYgMi3UYCKpouZZBmd6Egoegqu5A0yd/view?usp=sharing

Admin Login : admin@mail.com / 123456

Run All Tests : 
- php artisan config:clear
- php artisan test

