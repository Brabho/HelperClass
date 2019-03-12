# Change Log & History

> ##### 12-03-2019 ~ _v3.0_ : _Stable_

	Bugs Fixed
	Stability Improved
    :: Changes ::
        helpers.php     > functions.php
        db_pdo.php      > db.php    (Include Object Name)
        c_url.php       > curl.php  (Include Function Name)
    
    :: Removed ::
        db_mysqli.php

> ##### 24-10-2018 ~ _v2.8_ : _Stable_

	Bugs Fixed
	Stability Improved
	Minor Changes

> ##### 11-08-2018 ~ _v2.7_ : _Stable_

	:: Improved ::
		captcha.php
		helpers.php

> ##### 04-07-2018 ~ _v2.6_ : _Stable_

	:: Added ::
		valid_phone()						"Get Valid Phone Number(s)"

	:: Improved ::
		redirect()
		uri_info()
		encrypt(), decrypt()				"Replace `mcrypt` with 'openssl'. Complete Rewrite. Usage Same"

	:: Changed ::
		valid_ip(), usr_ip() >> ip_d()		"valid_ip & usr_ip marge with ip_d"
		mod_strip_tags() >> tags_strip()
		local_date() >> date_time()

> ##### 30-06-2018 ~ _v2.5_ : _Stable_

    Stability Improved
    Major Bugs Fixed
    :: Added ::
	    Support PHP 7.2
        User Timezone
        Virus Check
        SSH SFTP
        Random Characters 	`rand_char`
		Extract Phone		`by_ptn`

> ##### 05-08-2017 ~ _v2.4_ : _Stable_

    :: Added ::
        `url2img`           > image.php

    :: Improved ::
        Stability
        Minify
        DOM Load

    :: Bugs Fixed ::
        `dom_metatags`      > helpers.php
        image.php

> ##### 18-06-2017 ~ _v2.3_ : _Stable_

    Stability Improved
    Bugs Fixed

    All Removed Files/Functions are in helpers.php or Completely Removed

> ##### 22-05-2017 ~ _v2.2_ : _Stable_

    Bugs Fixed
    :: Added ::
        Mod cURL

> ##### 02-05-2017 ~ _v2.1_ : _Stable_

    Stability Improved
    :: Bug Fixed ::
        DOM styles & DOM scripts

    :: Added ::
        `dump`      > db_pdo.php

> ##### 16-04-2017 ~ _v2.0_ : _Stable_

    :: Improved ::
        image.php
        minify.php
        visitor.php

    :: Removed ::
        benchmark.php
        cache.php

    All Removed Files/Functions are in helpers.php or Completely Removed

> ##### 02-03-2017 ~ _v1.13_ : _Stable_

    :: Improved ::
        captcha.php
        minify.php

    :: Added ::
        security.php
        `php`               > minify.php

> ##### 26-02-2017 ~ _v1.12_ : _Stable_

    :: Added ::
        Charset
        `slug`              > gets.php
        header_status.php
        pagination          > db_pdo.php

> ##### 12-02-2017 ~ _v1.9_ : _Stable_

    Checked All Files
    :: Improved ::
        `is_var`            > cf.php

    :: Added ::
        ftp.php

> ##### 04-02-2017 ~ _v1.8_ : _Stable_

    Review All Files
    :: Improved ::
        cf.php

> ##### 27-01-2017 ~ _v1.7_ : _Stable_

    :: Improved ::
        rand.php
        cookie.php

    :: Added ::
        `pass`              > hash.php

> ##### 10-01-2017 ~ _v1.6_ : _Stable_

    :: Improved ::
        benchmark.php
        db_pdo.php
        `trims`             > str.php

    :: Changed ::
        BenchMark.php       > benchmark.php

> ##### 09-11-2016 ~ _v1.5_ : _Stable_

    Major Bugs Fixed
    Review All Files

    :: Added ::
        Email Class `email` (Mail Function)
        MySQLi Class `db_mysqli`
        charset in db

    :: Replace ::
        db to `db_pdo`
        `dir` to `dirs`

> ##### 01-11-2016 ~ _v1.4_ : _Stable_

    Major Bugs Fixed

> ##### 26-10-2016 ~ _v1.3.3_ : _Stable_

	Stability Improved

> ##### 08-10-2016 ~ _v1.3.2_ : _Stable_

	:: Improved ::
		db.php

	:: Added ::
		ping in db.php

	:: Bug Fixed ::
		db.php

> ##### 06-10-2016 ~ _v1.3_ : _Stable_

	Review All Files
	:: Improved ::
		localDate in cf.php
		varClean in cf.php

> ##### 24-09-2016 ~ _v1.2_ : _Stable_
	
	:: Improved ::
		Stability
		captcha.php

    :: Added ::
        `marge` in image.php
	
	:: Bug Fixed ::
		minify.php
		captcha.php

> ##### 19-09-2016 ~ _v1.1_ : _Stable_

    Review All Files
	:: Changed ::
	    `random` Functions move from cf.php to rand.php

	:: Improved ::
		Random Number
		Random String

	:: Added ::
		Random Crypto

> ##### 14-09-2016 ~ _v1.0_ : _Stable_
	
	First made
	Initial Release
