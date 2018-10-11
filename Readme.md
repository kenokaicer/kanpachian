PHP PSR-1 Naming conventions

- Class names MUST be declared in StudlyCaps.
- Class constants MUST be declared in all upper case with underscore separators.
- Method names MUST be declared in camelCase.

DONE

- ArtistDaoBD does Add, RetrieveAll, Update(edit) and Delete, only missing Retrieve, but that needs to know what var is sent to the method
- Models
- Composer Autoload working
- Build Data Base
- ArtistDao converted and automated as much as posible
- Change SteasByEvent enum to Class (load list when program starts)

TO DO

- Move js, css, img folders to Views folder
- TRY CATCH FROM CONNECTION NOT WORKING

- Complete Daos
- See what is better, if passing variables by post or storing the object in session for modifying a deleting (Answer: pass only id, and make a call to dao by id. Still this brakes if another dao is implemented)

- Change Category enum to Class
- SteasByEvent is loading list when program starts, change that to a controller call only when needed
- Change Category enum to Class
- CreditCard should check if null when making a payment
- add image to events (then to bd)


- login (hash+salt: https://www.sitepoint.com/hashing-passwords-php-5-5-password-hashing-api/)

 
