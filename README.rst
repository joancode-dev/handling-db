HandlingDB
================================
Library for easy and fast manipulation of the database


Installation
------------

Add ``joan-ramirez/handling-db`` as a require dependency in your ``composer.json`` file:

.. code-block:: bash

    composer require joan-ramirez/handling-db

Usage
-----
Create a handling-db instance

.. code-block:: php

    use JoanRamirez/HandlingDB/HandlingDB;

    $handlingDB = new HandlingDB();

Export a record from a table with the table()->export() methods:

.. code-block:: php

    // export table
    $handlingDB->table('users')->export();
    
conditional that must be met by the records to be exported:

.. code-block:: php

    $handlingDB->table('users', where: ['role_id' => 4, 'name' => "Joan Ramirez"])->export();

select only the column you want to get:

.. code-block:: php

    $handlingDB->table('users', where: ['role_id' => 4, 'name' => "Joan Ramirez"], select: ['role_id', 'name', 'created_at'])->export();


get data from table joins:

In this case we join the data from the publications and users table in the same way we can continue joining more data from other tables.

.. code-block:: php

    $handlingDB->joins('users', joins:['posts.user_id' => 'users.id'], where: ['users.role_id' => 4, 'posts.category_id' => 2], select: ['users.name', 'posts.title'])->export();
    

import csv into a database table:

the file to be exported has to be in the following location: ``database\handling-db\users-data.csv``

@param array $columns You must write each column of the table according to the position found in the csv

.. code-block:: php

    $handlingDB->import(fileName:'users-data', table: 'users', columns: ['email', 'name', 'password'], separator: '|');


License
-------

Handling-DB is licensed under the MIT license.
