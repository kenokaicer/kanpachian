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

TO DO

- CreditCard should check if null when making a payment
- add image to events (then to bd)
- Complete Daos

- Fix BD for SeatsByEvent
- Start with EventDao, then EventByDate (see how SeatsByEvent are made here)
- EventDao needs to return with EventByDate, not doing it right now

- SeatsByEvent has new atribute private $eventByDate; // Class EventByDate
fix everyting that implies that


- login (hash+salt: https://www.sitepoint.com/hashing-passwords-php-5-5-password-hashing-api/)

 
