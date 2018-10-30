PHP PSR-1 Naming conventions

- Class names MUST be declared in StudlyCaps.
- Class constants MUST be declared in all upper case with underscore separators.
- Method names MUST be declared in camelCase.

DONE

- ArtistDaoBD does Add, getAll, Update(edit) and Delete, exception handling working
- Models
- Composer Autoload working
- Build Data Base
- ArtistDao converted and automated as much as posible
- Change SteasByEvent enum to Class (load list when program starts)
- SteasByEvent is loading list when program starts, change that to a controller call only when needed
- Change Category enum to Class
- add image to events (then to bd)
- rename properties to attributes when calling magic set
- Fix BD for SeatsByEvent and purchaes, n:n table in between (purchases connected to purchase lines)
- SeatsByEvent has new atribute private $eventByDate; // Class EventByDate, fix everyting that implies that

TO DO

- Typehint models with object
- CreditCard should check if null when making a payment

- fix theaterDao try-catchs (use eventByDate as reference), remake theaterDao with improvementes from eventByDateDao

- Check for clean up EventDao


- login (hash+salt: https://www.sitepoint.com/hashing-passwords-php-5-5-password-hashing-api/)
- user logged checks in controller contructor for admin

 
