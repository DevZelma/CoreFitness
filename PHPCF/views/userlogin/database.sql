CREATE DATABASE CoreFitness;
USE CoreFitness;

CREATE TABLE Users(
    UserID int not null AUTO_INCREMENT,
    UserName varchar(128),
    Password varchar(16),
    PRIMARY KEY (UserID)
);

CREATE TABLE Customer_Details (
    CustomerID int not null AUTO_INCREMENT,
    CustomerName varchar(128),
    CustomerSurname varchar(128),
    CustomerPhone varchar(12),
    CustomerEmail varchar(200),
    CustomerAddress varchar(200),
    CustomerGender varchar(10),
    UserID int,
    PRIMARY KEY (CustomerID),
    CONSTRAINT FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE Membership(
    MembershipID int not null AUTO_INCREMENT,
    CustomerID int,
    MembershipType varchar(50),
    StartDate date,
    EndDate date,
    PRIMARY KEY (MembershipID),
    CONSTRAINT FOREIGN KEY (CustomerID) REFERENCES Customer_Details(CustomerID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Store_Item(
    ItemID int not null AUTO_INCREMENT,
    ItemName varchar(100),
    ItemDescription varchar(255),
    ItemPrice decimal(10,2),
    PRIMARY KEY (ItemID)
);

CREATE TABLE Orders (
    OrderID int not null AUTO_INCREMENT,
    CustomerID int,
    ItemID int,
    DatePurchase datetime,
    Quantity int,
    TotalPrice double(12,2),
    PRIMARY KEY (OrderID),
    CONSTRAINT FOREIGN KEY (ItemID) REFERENCES Store_Item(ItemID) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (CustomerID) REFERENCES Customer_Details(CustomerID) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE Cart(
    CartID int not null AUTO_INCREMENT,
    CustomerID int,
    ItemID int,
    Price double(12,2),
    Quantity int,
    TotalPrice double(12,2),
    PRIMARY KEY (CartID),
    CONSTRAINT FOREIGN KEY (ItemID) REFERENCES Store_Item(ItemID) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (CustomerID) REFERENCES Customer_Details(CustomerID) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Dummy data for Users table
INSERT INTO Users (UserName, Password) VALUES
('john_doe', 'password123'),
('jane_smith', 'securepass'),
('mike_jackson', 'fitnesslover');

-- Dummy data for Customer_Details table
INSERT INTO Customer_Details (CustomerName, CustomerPhone, CustomerEmail, CustomerAddress, CustomerGender, UserID) VALUES
('John Doe', '1234567890', 'john@example.com', '123 Main St, Cityville', 'Male', 1),
('Jane Smith', '9876543210', 'jane@example.com', '456 Oak St, Townsville', 'Female', 2),
('Mike Jackson', '5555555555', 'mike@example.com', '789 Elm St, Villageton', 'Male', 3);

-- Dummy data for Membership table
INSERT INTO Membership (CustomerID, MembershipType, StartDate, EndDate) VALUES
(1, 'Premium', '2024-01-01', '2024-12-31'),
(2, 'Basic', '2024-02-15', '2025-02-14'),
(3, 'Gold', '2024-03-01', '2025-03-01');

-- Dummy data for Store_Item table
INSERT INTO Store_Item (ItemName, ItemDescription, ItemPrice) VALUES
('Yoga Mat', 'Premium quality yoga mat', 29.99),
('Dumbbells Set', 'Adjustable dumbbells set', 99.99),
('Resistance Bands', 'Set of resistance bands', 19.99);

-- Dummy data for Order table
INSERT INTO Orders (CustomerID, ItemID, DatePurchase, Quantity, TotalPrice) VALUES
(1, 1, '2024-05-01 10:30:00', 2, 59.98),
(2, 2, '2024-05-02 11:45:00', 1, 99.99),
(3, 3, '2024-05-03 12:15:00', 3, 59.97);

-- Dummy data for Cart table
INSERT INTO Cart (CustomerID, ItemID, Price, Quantity, TotalPrice) VALUES
(1, 2, 99.99, 1, 99.99),
(2, 3, 19.99, 2, 39.98),
(3, 1, 29.99, 1, 29.99);
