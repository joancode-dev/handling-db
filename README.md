<h1>HandlingDB</h1>

## Documentation
Package for easy and fast manipulation of the database

```html
composer require joan-ramirez/handling-db
```

### The Aasiest Way To Export Data

```html
(new App\JoaRramirez\HandlingDB)->table('users')->export();
```

require 'vendor/autoload.php';

//['contacto.user_id' => 'users.id', ]
// $mode: r = read, w = write, a = append
