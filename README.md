# HelperClass

**v3.0 : _Stable_**


> #### _Description_

PHP Helper Class & Function.

Most used Classes and Functions.

Lightweight, Fast, Useful.


> #### _Tested_

PHP    (5.6, 7.0, 7.1, 7.2, 7.3)

Nginx  (1.9, 1.10, 1.12, 1.14)

Apache (2.2, 2.4)


> #### _Few Examples_
```php
require "functions.php";
echo (is_var($var)) ? 'exists' : 'not exists';
echo encrypt('text', ['p' => 'PrimaryKey', 's' => 'SecondaryKey']);
echo rand_str();               				// Default & Max Length 40

# Upload File\s Securely #
require "upload.php";
class myClass extends upload {
    function __construct() {
        $this->file_name = 'FILE';          // input element name attribute's value
        $this->start();

        /*
         *  # Other Options #
            $this->new_name = 'Name';
            $this->save = '/save/path';
            $this->min_size = '10';         // in byets
            $this->max_size = '2097152';    // in byets
            $this->multi = $i;              // for multi files upload (Loop Value)
            $this->overwrite;               // true/false
            $this->space;                   // space replace with character e.g. '-'
            $this->mime;                    // mime type has to be array e.g. [image/jpeg]
            $this->extension;               // has to be array e.g [jpg, jpeg]
         */
    }
}

# PDO Database Query #
require "db.php";
class myClass extends db {
    function __construct() {
        $details = [
            'HOST' => '127.0.0.1',
            'DB_NAME' => 'db_name',
            'USER' => 'root',
            'PASS' => '',
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

# cURL Function #
require "curl.php";
curl([
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


> ## _License (C) 2019 under GNU GPL V2._
