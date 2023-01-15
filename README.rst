<h1>HandlingDB</h1>

## Documentation
Package for easy and fast manipulation of the database


Installation
------------

Add ``fabpot/goutte`` as a require dependency in your ``composer.json`` file:

.. code-block:: bash

    composer require joan-ramirez/handling-db

Usage
-----
Crea una instancia de handling-db

.. code-block:: php

    use JoanRamirez/HandlingDB;

    $handlingDB = new HandlingDB();

Export a record from a table with the table()->export() methods:

.. code-block:: php

    // export table
    $handlingDB->table('users')->export();


require 'vendor/autoload.php';

//['contacto.user_id' => 'users.id', ]
// $mode: r = read, w = write, a = append


License
-------

Handling-DB is licensed under the MIT license.
