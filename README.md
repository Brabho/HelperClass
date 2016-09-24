# HelperClass

### Description
PHP Helper Class

Most used classes and functions

LightWeight

    Let me know:
        your review.
        if you found any bug/error.

Thank You

### Tested
PHP    (5.5, 5.6)

Apache (2.2, 2.4)

Nginx  (1.9.4)

### Version 
Stable Version 1.2

### License
(C) 2013 - 2016 
under GNU General Public License Version 2.

### Example
```php
require_once 'rand.php'
class myClass extends rand {
    function __construct() {
        echo $this->str();
    }
}
```