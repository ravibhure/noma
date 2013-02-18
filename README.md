Noma: Node Management with good taste!
======================================

A web interface for managing the configuration of (large) collections of nodes

Current status
--------------

Some screens are working, but there's no support for configuration management
system yet. In other words, Noma is not yet usable.

We do check our code using unit and functional tests, and monitor it using travis-ci:
[![Build Status](https://travis-ci.org/jkossen/noma.png)](https://travis-ci.org/jkossen/noma)

About
-----

Noma will be a webbased interface for managing large collections of nodes.

We're currently using the following technologies:

 * PHP 5
 * Symfony 2 (with Doctrine, Twig, phpunit, ...)
 * jQuery
 * Twitter Bootstrap

We develop using a MySQL database, but the software should be able to support
any database the framework (Symfony / Doctrine) supports.

Initially the project will provide support for just CFEngine, but the database
design is pretty flexible, and support for other backends should be relatively
easy to add.

Licensing and copyright
-----------------------

Code for the Noma project is released under the GPLv3. Third party code
distributed with Noma should include licensing documentation applying to that
code.

A copy of the GPLv3 is distributed with Noma in the doc/GPLv3.txt file. It's
available online as well at http://www.gnu.org/licenses/gpl-3.0.txt

Noma makes use of the Symfony2 PHP framework. Symfony2 is released under the
MIT license. For clarity reasons, a copy of symfony's license is distributed in
the doc/symfony2.license.txt file.

If you have any doubts regarding the used license for a piece of code, please
ask the Noma authors.

Noma Authors
------------

* Jochem Kossen <jochem@jkossen.nl>
* Ivo Schooneman <ivo@schooneman.net>
* Thomas van der Jagt

Noma Copyright
--------------
Copyright (c) 2012 Jochem Kossen, Ivo Schooneman, Thomas van der Jagt

Symfony2 Copyright
------------------
Copyright (c) 2004-2012 Fabien Potencier

