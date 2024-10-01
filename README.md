# Bank Accounts & Transactions PHP Application

This is a small PHP application developed as a test task that implements basic operations with bank accounts and transactions. The application is built using PHP 8+ with strict typing, adhering to OOP principles, SOLID, and GoF design patterns.

## Features

- Add new bank accounts with a specific currency.
- Retrieve information about a bank account (balance and other details).
- Transfer funds between accounts with validation of sufficient balance.
- Retrieve transaction history for a given account.
- Built with clean architecture patterns: Repository, Service, and Factory.
- Optimized SQL queries for handling large datasets.
- Dockerized for easy setup and development.

## Project Structure
/project-root
/src            - Source code of the application
index.php     - Entry point of the application
docker-compose.yml - Docker orchestration file
nginx.conf      - NGINX server configuration
Dockerfile      - Dockerfile for PHP-FPM container

## Prerequisites

- Docker
- Docker Compose

## Setup Instructions

1. **Clone the repository**

    ```bash
    git clone git@github.com:adomanskij/PB-tz-dockerised.git
    cd PB-tz-dockerised
    ```

2. **Copy the environment file**

    Before starting the Docker containers, make sure to create the environment file from the example:

    ```bash
    cp .env.example .env
    ```
    
2. **Install Composer Dependencies**

    If using Composer for dependency management, install the packages in the PHP container:

    ```bash
    cd src 
    composer install
    cd ..
    ```
    
4. **Start Docker containers**

    Use Docker Compose to build and start the services:

    ```bash
    docker compose up -d
    ```

5. **Application Access**

    The application will be accessible at `http://localhost:8080`.

6. **Database Setup**

    The PostgreSQL database will be automatically created with the following credentials:

    - Database: `bank_accounts`
    - Username: `user`
    - Password: `password`
    
    Make sure to adjust the credentials if necessary in `docker compose.yml`.

## API Requests

### Create Account

To create a new bank account, send a POST request:

```bash
curl -X POST http://localhost:8080 -d "action=create_account&account_number=1234567890&balance=1000.00&currency=USD"
```

###Transfer Funds

To transfer funds between accounts, send a POST request:

```bash
curl -X POST http://localhost:8080 -d "action=transfer&account_from=1234567890&account_to=0987654321&amount=500.00"
```

###Get Account Details

To retrieve account details, send a GET request:

```bash
curl -X GET "http://localhost:8080?account_number=1234567890"
```

###Get Transaction History

To retrieve transaction history for an account, send a GET request:

```bash
curl -X GET "http://localhost:8080?action=transaction_history&account_number=1234567890"
```


## Development

Place all your PHP code in the `src` directory. The main entry point is `index.php`.

- **Business Logic**: Follow OOP principles, SOLID, and implement the design patterns as necessary.
- **Database Interaction**: Use plain SQL queries (PostgreSQL) for database interactions and implement repositories for separation of concerns.
- **Testing**: PHPUnit will be used to cover main functionalities:
  - Creating a new account
  - Transferring funds between accounts
  - Retrieving transaction history

## Running Tests


 **Run PHPUnit Tests**

    Execute PHPUnit tests inside the PHP container:

    ```bash
    docker exec -it php_app ./src/vendor/bin/phpunit --bootstrap ./src/vendor/autoload.php ./src/tests/AccountTest.php
    ```

## Technologies Used

- **PHP 8.1 (FPM)**: Backend language for business logic.
- **NGINX**: Web server for handling HTTP requests.
- **PostgreSQL**: Database for storing accounts and transaction data.
- **Docker & Docker Compose**: For containerization of the application.

## Additional Notes

- Make sure your Docker is running and has access to the necessary ports.
- You can modify the `nginx.conf` file for any specific server configuration.
- Follow the best practices for writing clean and maintainable code, including proper documentation and comments.
