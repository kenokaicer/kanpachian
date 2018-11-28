GoToEvent

A project for the subjects "Laboratory 4" and "Systems Metodology I" for the degree "Senior Technician in Programming".
The site would allow an administrator to load events, dates, seats for each event, and all info requied to make a sale.
The user can register/login and purchase a ticket for an event online.
More info in the documentation.
https://docs.google.com/document/d/18zjxM33cZFlJmpqZ5Vk4RvdU8D8j48RYrXVgIAA8G0I

Template used for the site:

- Zurb Foundation (Zurb Foundation)

PHP PSR-1 Naming conventions

- Class names MUST be declared in StudlyCaps.
- Class constants MUST be declared in all upper case with underscore separators.
- Method names MUST be declared in camelCase.

Passwords Hashed and Salted

- hash+salt: https://www.sitepoint.com/hashing-passwords-php-5-5-password-hashing-api/

Packages and APIs

- PHPMailer (https://github.com/PHPMailer/PHPMailer)
Used to mail tickets to clients when sale is done. Expects a SMTP server.

- Chillerlan - Php-Qrcode (https://github.com/chillerlan/php-qrcode/releases)
Used to generate Qrcodes for tickets each time they are loaded. Min PHP ver 7.2

- Karriere - Json-Decoder (https://github.com/karriereat/json-decoder)
Used to decode Json while supplying a Class and/or Sub-Classes. Capable of making a class public temporarly while decoding.

- Sweet Alert (https://sweetalert.js.org)
Javascript alerts with many options and visually better.

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
- purchase process ready
- user menu (+view tickets, view data, modify data, change password, etc)
- decomment adminLogged in admin and management areas when finished
- cleanup controllers, set correct try-catch, check for null and empty arrays, and other proper checks
- Theater Managment add -> transform to javascript (not a priority)
- Theater Managment list -> javascript that expands seatTypelist
- For events, make a search by event name, by artist name, and by date, and by categories (combobox) (low prority)
- change getall() to getAttributes() where necessary, done in daos, check controllers
- Put methods in interfaces once all daos are complete
- Test dao methods after dao code clean up, need a second test after loadtype changes
- busqueda eventos por fecha
- update checks future
- Last test for management clases
- 404 added, with cats!
- email
- finish admin check sales by category AND date
- lolapalooza, add calendar thats a sale, various dates
- fix edit to SeatsByEvent
- SeatsByEvent add all at the same time

TO DO






 
