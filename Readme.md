PHP PSR-1 Naming conventions

- Class names MUST be declared in StudlyCaps.
- Class constants MUST be declared in all upper case with underscore separators.
- Method names MUST be declared in camelCase.

Passwords Hashed and Salted

- hash+salt: https://www.sitepoint.com/hashing-passwords-php-5-5-password-hashing-api/

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
- SeatsByEvent has new attribute private $eventByDate; // Class EventByDate, fix everyting that implies that
- change models object attributes to private, no longer array_pop everywhere
- fix where clause in daos, change to parameters var
- Typehint models with object
- fix theaterDao try-catchs (use eventByDate as reference), remake theaterDao with improvementes from eventByDateDao [part of dao code clean up]


TO DO

- CreditCard should check if null when making a payment
- Put methods in interfaces once all daos are complete
- singleton only for connection, remove for every other class
- change getall() to getAttributes() where necessary, done in daos, check controllers
- Test dao methods after dao code clean up

- userLogged checks in controller constructor for admin pages, and check for sensitive areas in user zone

 
