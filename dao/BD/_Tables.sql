USE neonlab1_gotoevent;

/*create table Categories(
    idCategory int unsigned auto_increment,
    categoryName varchar(50) not null unique,
    enabled bool default '1',
    constraint pkCategory primary key (idCategory)
);*/

/*create table Events(
    idEvent int unsigned auto_increment,
    eventName varchar(50) not null unique,
    image varchar(255),
    descritpion varchar(300),
    idCategory int unsigned,
    enabled bool default '1',
    constraint pkEvent primary key (idEvent),
    constraint fkCategory foreign key (idCategory) references Categories (idCategory)
);*/

/*create table Theaters(
    idTheater int unsigned auto_increment,
    theaterName varchar(50) not null,
    location varchar(80),
    address varchar(80) not null,
    image varchar(150),
    maxCapacity smallint unsigned,
    enabled bool default '1',
    constraint pkTheater primary key (idTheater)
);*/

/*create table EventByDates(
    idEventByDate int unsigned auto_increment,
    date date not null,
    endPromoDate date,
    isSale bool default '0',
    idTheater int unsigned,
    idEvent int unsigned,
    enabled bool default '1',
    constraint pkEventByDate primary key (idEventByDate),
    constraint fkTheater foreign key (idTheater) references Theaters (idTheater),
    constraint fkEvent foreign key (idEvent) references Events (idEvent)
);*/

/*create table Artists(
    idArtist int unsigned auto_increment,
    name varchar(50) not null,
    lastname varchar(50) not null,
    enabled bool default '1',
    constraint pkArtist primary key (idArtist)
);*/

/*create table Artists_x_EventByDate(
    idArtist int unsigned,
    idEventByDate int unsigned,
    enabled bool default '1',
    constraint pkArtists_x_EventByDate primary key (idArtist, idEventByDate),
    constraint pfkArtist foreign key (idArtist) references Artists (idArtist),
    constraint pfkEventByDate foreign key (idEventByDate) references EventByDates (idEventByDate)
);*/

/*create table SeatTypes(
    idSeatType int unsigned auto_increment,
    seatTypeName varchar(50) not null unique,
    description varchar(300),
    enabled bool default '1',
    constraint pkSeatType primary key (idSeatType)
);*/

/*create table SeatTypes_x_Theater(
    idSeatType int unsigned,
    idTheater int unsigned,
    enabled bool default '1',
    constraint pkSeatTypes_x_Theater primary key (idSeatType, idTheater),
    constraint pfkSeatType foreign key (idSeatType) references SeatTypes (idSeatType),
    constraint pfkTheater foreign key (idTheater) references Theaters (idTheater)
);*/

/*create table CreditCards(
    idCreditCard int unsigned auto_increment,
    creditCardNumber varchar(16) not null,
    expirationDate date,
    cardHolder varchar(50),
    enabled bool default '1',
    constraint pkCreditCard primary key (idCreditCard)
);*/

/*create table Users(
    idUser int unsigned auto_increment,
    username varchar(50) not null unique,
    password varchar(255) not null,
    email varchar(50) not null unique,
    role varchar(10) not null,
    enabled bool default '1',
    constraint pkUser primary key (idUser)
);*/

/*create table Clients(
    idClient int unsigned auto_increment,
    name varchar(50) not null,
    lastname varchar(50) not null,
    dni int unsigned not null,
    idUser int unsigned not null,
    idCreditCard int unsigned,
    enabled bool default '1',
    constraint pkClient primary key (idClient),
    constraint fkUser foreign key (idUser) references Users (idUser),
    constraint fkCreditCard foreign key (idCreditCard) references CreditCards (idCreditCard)
);*/

/*create table Purchases(
    idPurchase int unsigned auto_increment,
    date date not null,
    totalPrice double unsigned not null,
    idClient int unsigned not null,
    enabled bool default '1',
    constraint pfPurchase primary key (idPurchase),
    constraint fkClient foreign key (idClient) references Clients (idClient)
);*/

/*create table SeatsByEvents(
    idSeatsByEvent int unsigned auto_increment,
    quantity smallint unsigned not null,
    price double unsigned not null,
    remnants smallint unsigned not null,
    idEventByDate int unsigned not null,
    idSeatType int unsigned not null,
    enabled bool default '1',
    constraint pkSeatByEvent primary key (idSeatsByEvent),
    constraint fkEventByDate foreign key (idEventByDate) references EventByDates (idEventByDate),
    constraint fkSeatType foreign key (idSeatType) references SeatTypes (idSeatType)
);*/

/*create table PurchaseLines(
    idPurchaseLine int unsigned auto_increment,
    price double unsigned not null,
    idSeatsByEvent int unsigned not null,
    idPurchase int unsigned not null,
    enabled bool default '1',
    constraint pkPurchaseLine primary key (idPurchaseLine),
    constraint fkSeatsByEvent foreign key (idSeatsByEvent) references SeatsByEvents (idSeatsByEvent),
    constraint fkPurchase foreign key (idPurchase) references Pruchases (idPurchase)
);*/

/*create table Tickets(
    idTicket int unsigned auto_increment,
    ticketCode varchar(255) not null,
    qrCode varchar(255),
    idPurchaseLine int unsigned not null,
    enabled bool default '1',
    constraint pkTicket primary key (idTicket),
    constraint fkPurchaseLine foreign key (idPurchaseLine) references PurchaseLines (idPurchaseLine)
);*/


show tables;
