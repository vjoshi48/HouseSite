CREATE TABLE UserTable (
    ID VARCHAR(50) PRIMARY KEY,
    firstname VARCHAR(50),
    lastname VARCHAR(50),
    email VARCHAR(100),
    password VARCHAR(50),
    userType ENUM('Admin', 'Seller', 'Buyer')
);
