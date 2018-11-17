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
- CreditCard should check if null when making a payment
- singleton only for connection, remove for every other class
- userLogged checks in controller constructor for admin pages, and check for sensitive areas in user zone


TO DO

- Put methods in interfaces once all daos are complete
- decomment adminLogged in admin and management areas when finished
- change getall() to getAttributes() where necessary, done in daos, check controllers
- cleanup controllers, set correct try-catch, check for null and empty arrays, and other proper checks
- Test dao methods after dao code clean up, need a second test after loadtype changes

- Theater Managment add -> transform to javascript (not a priority)
- Theater Managment list -> javascript that expands seatTypelist

- For events, make a search by event name, by artist name, and by date, and by categories (combobox) (low prority)
- user menu (+view tickets, view data, modify data, change password, etc)
 
