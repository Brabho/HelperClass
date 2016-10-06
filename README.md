# HelperClass

### Description
PHP Helper Class

Most used classes and functions

LightWeight & Fast

### Version 
Stable Version 1.3

### Tested
PHP    (5.5, 5.6)

Apache (2.2, 2.4)

Nginx  (1.9, 1.10)

### License
(C) 2013 - 2016 under GNU General Public License Version 2.

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
```