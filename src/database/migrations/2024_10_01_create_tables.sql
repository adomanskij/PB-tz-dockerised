-- Create accounts table
CREATE TABLE IF NOT EXISTS accounts (
    id SERIAL PRIMARY KEY,
    account_number VARCHAR(20) UNIQUE NOT NULL,
    balance DECIMAL(15, 2) NOT NULL DEFAULT 0,
    currency VARCHAR(3) NOT NULL
);

-- Create transactions table
CREATE TABLE IF NOT EXISTS transactions (
    id SERIAL PRIMARY KEY,
    account_from VARCHAR(20) NOT NULL,
    account_to VARCHAR(20) NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) NOT NULL,
    FOREIGN KEY (account_from) REFERENCES accounts(account_number),
    FOREIGN KEY (account_to) REFERENCES accounts(account_number)
);