CREATE TABLE users (
    id int(11) NOT NULL,
    fullname varchar(255) NOT NULL,

    PRIMARY KEY (id)
);


CREATE TABLE properties (
    id int(11) NOT NULL,  
    money float(30),  

    PRIMARY KEY (id)
);

CREATE TABLE banks (
    id int(11) NOT NULL,
    _password varchar(255),
    op_done int DEFAULT 0,
    trd_done int DEFAULT 0,
    tau1 float DEFAULT 0,
    tau2 float DEFAULT 0,
    tau3 float DEFAULT 0,

    
    PRIMARY KEY (id)
);

CREATE TABLE op_history (
    op_nbr int(20) AUTO_INCREMENT,
    ttime time ,
    trader int(11),
    SB varchar(10),
    bank int(11),
    stock varchar(255),
    nbr int(11),
    price float(30),
    fixed_price float(30),
    done int(11),

    PRIMARY KEY (op_nbr)
);

CREATE TABLE stocks_history (
    nbr int(20) AUTO_INCREMENT,
    ttime time,


    PRIMARY KEY (nbr)
);

CREATE TABLE stocks (
    stock varchar(255) NOT NULL,
    nbr int(11) DEFAULT 0,
    benefits float DEFAULT 0,
    value float(30) DEFAULT 0,
    rate float DEFAULT 0
);

CREATE TABLE _admin (
    phase int DEFAULT 0,
    day int DEFAULT 0,
    trader_cap float(30) DEFAULT 10000,
    bank_cap float(30) DEFAULT 300000,
    newspaper_price float DEFAULT 10,
    cnr_price float DEFAULT 10,
    ref_rate int DEFAULT 60000

);
INSERT INTO _admin (phase) 
    VALUES (0);







