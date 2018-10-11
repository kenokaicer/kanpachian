USE neonlab1_gotoevent;

/*create table Categories(
    idCategory int unsigned auto_increment,
    category varchar(50) not null unique,
    enabled bit default 1,
    constraint pkCategory primary key (idCategory)
);*/

/*create table Events(
    idEvent int unsigned auto_increment,
    eventName varchar(50) not null unique,
    idCategory int unsigned,
    constraint pkEvent primary key (idEvent),
    enabled bit default 1,
    constraint fkCategory foreign key (idCategory) references Categories (idCategory)
);*/

/*create table Theaters(
    idTheater int unsigned auto_increment,
    name varchar(50) not null,
    location varchar(80),
    image varchar(150),
    maxCapacity smallint unsigned,
    enabled bit default 1,
    constraint pkTheater primary key (idTheater)
);*/

/*create table EventsByDates(
    idEventsByDate int unsigned auto_increment,
    date date,
    idTheater int unsigned,
    idEvent int unsigned,
    constraint pkEventsByDate primary key (idEventsByDate),
    constraint fkTheater foreign key (idTheater) references Theaters (idTheater),
    constraint fkEvent foreign key (idEvent) references Events (idEvent)
);*/

/*create table Artists(
    idArtist int unsigned auto_increment,
    name varchar(50) not null,
    lastname varchar(50) not null,
    enabled bit default 1,
    constraint pkArtist primary key (idArtist)
);*/

/*create table Artists_x_EventByDate(
    pfkArtist int unsigned,
    pfkEventByDate int unsigned,
    primary key (pfkArtist, pfkEventByDate),
    foreign key (pfkArtist) references Artists (idArtist),
    foreign key (pfkEventByDate) references EventsByDates (idEventsByDate)
);*/

/*create table SeatTypes(
    idSeatType int unsigned auto_increment,
    name varchar(50) not null unique,
    description varchar(300),
    enabled bit default 1,
    constraint pkSeatType primary key (idSeatType)
);*/

/*create table SeatTypes_x_Theater(
    pfkSeatType int unsigned,
    pfkTheater int unsigned,
    primary key (pfkSeatType, pfkTheater),
    foreign key (pfkSeatType) references SeatTypes (idSeatType),
    foreign key (pfkTheater) references Theaters (idTheater)
);*/

/*create table CreditCards(
    idCreditCard int unsigned auto_increment,
    creditCardNumber smallint unsigned not null unique,
    expirationDate date,
    cardHolder varchar(50),
    enabled bit default 1,
    constraint pkCreditCard primary key (idCreditCard)
);*/

/*create table Users(
    idUser int unsigned auto_increment,
    username varchar(50) not null unique,
    password varchar(255) not null,
    email varchar(50) not null unique,
    role varchar(10) not null,
    enabled bit default 1,
    constraint pkUser primary key (idUser)
);*/

/*create table Clients(
    idClient int unsigned auto_increment,
    name varchar(50) not null,
    lastname varchar(50) not null,
    dni smallint unsigned not null,
    idUser int unsigned not null,
    idCreditCard int unsigned,
    enabled bit default 1,
    constraint pkClient primary key (idClient),
    constraint fkUser foreign key (idUser) references Users (idUser),
    constraint fkCreditCard foreign key (idCreditCard) references CreditCards (idCreditCard)
);*/

/*create table Purchases(
    idPurchase int unsigned auto_increment,
    date date,
    idClient int unsigned not null,
    enabled bit default 1,
    constraint pfPurchase primary key (idPurchase),
    constraint fkClient foreign key (idClient) references Clients (idClient)
);*/

/*create table SeatsByEvents(
    idSeatsByEvent int unsigned auto_increment,
    quantity smallint unsigned not null,
    price double unsigned not null,
    remnants smallint unsigned not null,
    idEventsByDate int unsigned not null,
    idSeatType int unsigned not null,
    enabled bit default 1,
    constraint pkSeatByEvent primary key (idSeatsByEvent),
    constraint fkEventsByDate foreign key (idEventsByDate) references EventsByDates (idEventsByDate),
    constraint fkSeatType foreign key (idSeatType) references SeatTypes (idSeatType)
);*/

/*create table PurchaseLines(
    idPurchaseLine int unsigned auto_increment,
    price double unsigned not null,
    idSeatsByEvent int unsigned not null,
    enabled bit default 1,
    constraint pkPurchaseLine primary key (idPurchaseLine),
    constraint fkSeatsByEvent foreign key (idSeatsByEvent) references SeatsByEvents (idSeatsByEvent)
);*/


show tables;
