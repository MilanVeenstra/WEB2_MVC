# ITVB21WEB2 Web Technologies II Base Project

This repository contains a base project to build a web application for the course ITVB21WEB2
Web Technologies II,  which is part of the HBO-ICT Software Engineering program at Hanze
University of Applied  Sciences in Groningen, Netherlands.

This repository contains a complete `composer.json` file for this project, as well as an
empty SQLite database in `database.db`. In addition, it contains an empty front controller
in `public/index.php`, an empty kernel implementation in `src/Framework/Kernel/Kernel.php`
and an empty controller in `src/App/IndexController.php`.

This repository also contains an implementation of `Psr\Http\Message\StreamInterface` in
`src/Framework/Http/Stream.php` which may be used when creating the framework, and an empty
dependency injection container with some hints as to its implementation in
`src/Framework/DependencyInjection/Container.php` which can also be used as a guide when
creating a dependency injection container. Finally, the file `src/Framework/Http/ServerRequest.php`
contains logic to convert the $_FILES superglobal to a PSR-7 compliant array that can be returned
from `getUploadedFiles`, which may also be used.

This code is licensed under the MIT license, see `LICENSE.md`. Questions and comments can
be directed to [Ralf van den Broek](https://github.com/ralfvandenbroek).