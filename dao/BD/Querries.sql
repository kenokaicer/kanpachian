USE neonlab1_gotoevent;

/*insert into SeatTypes (name, description)
values ('Palco', 'Palco');*/

/*select * 
from SeatTypes;*/

select *, ST.name
from Theaters T
inner join SeatTypes_x_Theater STT
on T.idTheater = STT.pfkTheater 
inner join SeatTypes ST
on STT.pfkSeatType = ST.idSeatType;




