Noma: Node Management with good taste!
======================================

a web interface for managing the configuration of (large) collections of nodes

Current status
--------------

We are currently in the process of setting up the project. There is no usable
code yet.

About
-----

Noma will be a webbased interface for managing large collections of nodes.

We're currently using the following technologies:

 * PHP 5
 * CodeIgniter
 * Datamapper ORM
 * Smarty
 * jQuery
 * DataTables

We develop using a MySQL database, but the software should be able to support
any database the framework (CodeIgniter) supports.

Initially the project will provide support for CFEngine, but the database
design is pretty flexible, and support for other backends should be relatively
easy to add.

Licensing and copyright
-----------------------

If a file contains specific licensing and/or copyright details, those apply to
that file. If a file does not contain licensing or copyright details:

* The actual Noma applcation code is written inside the
application/controllers, application/models, application/migrations and
application/templates directories. That code is licensed under GPLv3. A copy is
distributed at doc/GPLv3.txt. It's available online as well at
http://www.gnu.org/licenses/gpl-3.0.txt

* The system/ folder contains the CodeIgniter core. That is licensed using the
license described in system/license.txt.

* Everything under the application/datamapper and
application/third_party/datamapper folders uses the MIT license. See
http://opensource.org/licenses/mit-license.php

* Noma makes use of CIUnit for unit testing. CIUnit lives under
application/third_party/CIUnit. CIUnit is licensed under the MIT license. See
http://opensource.org/licenses/mit-license.php

* Noma makes use of Smarty for templating. Smarty lives under
application/third_party/smarty. Smarty is licensed under the LGPLv3 license.
See application/third_party/smarty/COPYING.lib

* If you have any doubts regarding the used license for a piece of code,
please ask the Noma authors.

Noma Authors
------------

* Jochem Kossen <jochem@jkossen.nl>
* Ivo Schooneman <ivo@schooneman.net>

