USE neonlab1_gotoevent;

/*insert into SeatTypes (name, description)
values ('Palco', 'Palco');*/

/*alter table Clients
modify dni int unsigned not null;*/

/*delete from Users where idUser = 7;
select * from Users;*/

/*reset auto increment*/
#ALTER TABLE Clients AUTO_INCREMENT = 2;

/*delete column
ALTER TABLE CreditCards DROP COLUMN creditCardNumber;*/

/*ALTER TABLE CreditCards
ADD COLUMN creditCardNumber int unsigned not null;*/


USE neonlab1_gotoevent;
select *
from Artists
where CONCAT_WS(' ', name, lastname) like '%quijote de la mancha%'







