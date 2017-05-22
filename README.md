# HelperClass

### Description
PHP Helper Class & Function.

Most used Classes and Functions.

Lightweight, Fast, Useful.

### Tested
PHP    (5.5, 5.6, 7.0, 7.1)

Apache (2.2, 2.4)

Nginx  (1.9, 1.10)

### Version
Stable Version 2.2

### License
(C) 2013 - 2017 under GNU General Public License Version 2.

### Example
```php
require "helpers.php";
echo (is_var($var)) ? 'exists' : 'not exists';
echo encrypt('text', ['p' => 'PrimaryKey', 's' => 'SecondaryKey']);
echo rand_str();

## Upload File(s) Securely
require "upload.php";
class myClass extends upload {
    function __construct() {
        $this->file_name = 'FILE';      // input element name attribute's value
        $this->start();

        /*
         * Other Options
            $this->new_name = 'Name';
            $this->save = '/save/path';
            $this->min_size = '10';         // in byets
            $this->max_size = '2097152';    // in byets
            $this->multi = true;            // for multi files upload
            $this->overwrite;               // true/false
            $this->space;                   // space replace with character e.g. '-'
            $this->mime;                    // mime type has to be array e.g. [image/jpeg]
            $this->extension;               // has to be array e.g [jpg, jpeg]
         */
    }
}

## PDO Database Query
require "db_pdo.php";
class myClass extends db_pdo {
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
           'result' => true,                    // return rowcount, execute success or fail
           'fetch' => 'fetch'                   // 'fetch/fetchAll' NULL wont fetch database ,
           'fetch_arg' => PDO::FETCH_ASSOC,     // default is NULL
       ];
       $qb = $this->qber($query, $bind, $arg);
       var_dump($qb);
    }
}

## cURL Function
require "c_url.php";
c_url([
    'url' => 'https://google.com/'

    /*
     * Other Options
     * timeout              Default 300
     * max_redirect         Default 10
     * header               Default 1 (Bool)
     * nobody               Default 0 (Bool)
     * ssl_verify           Default 0 (Bool)
     * follow_location      Default 1 (Bool)
     * proxy                [IP, port, interface => (BOOL)]
     * host                 'Host Name'
     * headers              [Option 1, Option 2];
     * method               Default GET (GET/POST)
     */

], null, function($r) {
    print_r($r);
});

## cURL Extra Functions in 2nd Param
function($ch) {
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
}
```