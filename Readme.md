# GoToEvent

This is a project for the subjects "Laboratory 4" and "Systems Metodology I" for the degree "Senior Technician in Programming".\
A website for online managment and sale of recreational events, the site would allow an administrator to load events, dates, seats for each event, and all info requied to make a sale.\
The user can register/login and purchase a ticket for an event online.

More info in the documentation.\
https://docs.google.com/document/d/18zjxM33cZFlJmpqZ5Vk4RvdU8D8j48RYrXVgIAA8G0I

###### Template used for the site:

- Zurb Foundation (https://foundation.zurb.com/)

## PHP PSR-1 Naming conventions

- Class names MUST be declared in StudlyCaps.
- Class constants MUST be declared in all upper case with underscore separators.
- Method names MUST be declared in camelCase.

## Passwords Hashed and Salted

- hash+salt: https://www.sitepoint.com/hashing-passwords-php-5-5-password-hashing-api/

## Packages and APIs

- PHPMailer (https://github.com/PHPMailer/PHPMailer)

Used to mail tickets to clients when sale is done. Expects a SMTP server.

- Chillerlan - Php-Qrcode (https://github.com/chillerlan/php-qrcode/releases)

Used to generate Qrcodes for tickets each time they are loaded. Min PHP ver 7.2

- Karriere - Json-Decoder (https://github.com/karriereat/json-decoder)

Used to decode Json while supplying a Class and/or Sub-Classes. Capable of making a class public temporarly while decoding.

- Sweet Alert (https://sweetalert.js.org)

Javascript alerts with many options and visually better than the default.

## Notes

- Proof of concept for complete automated object creation for magic set (__set) in EventByDateManagementController, line 71
- More security info for password in AccountController, line 131





 
