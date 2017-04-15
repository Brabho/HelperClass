# HelperClass

### Description
PHP Helper Class

Most used Classes and Functions.

LightWeight & Fast.

### Tested
PHP    (5.5, 5.6, 7.0, 7.1)

Apache (2.2, 2.4)

Nginx  (1.9, 1.10)

### Version 
Stable Version 2.0

### License
(C) 2013 - 2017 under GNU General Public License Version 2.

### Example
```php
// Generate Number, String, Crypto String
require_once "rand.php"
class myClass extends rand {
    function __construct() {
        echo $this->str();
    }
}

// Upload File(s) Securely 
require_once "upload.php"
class myClass extends upload {
    function __construct() {
        $this->file_name = 'FILE'; // input element name attribute
        $this->Start();
        
        /*
        Other Options
        $this->new_name = 'Name';
        $this->save = '/save/path';
        $this->min_size = '10'; // in byets
        $this->max_size = '2097152'; // in byets
        $this->multi = true; // for multi file upload
        $this->overwrite; // true/false
        $this->space; // space replace with character e.g. '-'
        $this->mime; // mime type has to be array e.g. [image/jpeg]
        $this->extension; // has to be array e.g [jpg, jpeg]
         */
    }
}

// Database Query
require_once "db.php"
class myClass extends db {
    function __construct() {
        $details = [
            'host' => '127.0.0.1',
            'db_name' => 'db_name',
            'user' => 'root',
            'pass' => '',
        ];

       $this->connect($details);

       $query = 'SELECT * FROM users WHERE id=:id';
       $bind = [
           ':id' => '4'
       ];
       $arg = [
           'result' => true, // return rowcount, execute success or fail
           'fetch' => 'fetch' // 'fetch/fetchAll' NULL wont fetch database ,
           'fetch_arg' => PDO::FETCH_ASSOC, // default is NULL
       ];
       $qb = $this->qber($query, $bind, $arg);
       var_dump($qb);
    }
}
```