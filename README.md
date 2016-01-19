Pcws
===============
A chinese word segmentation tool,written by PHP.一个基于PHP和Web API的中文分词工具。

Installation
------------
Install the latest stable version using composer:

```
composer require guojikai/pcws
```
And add the require in your index file: (eg. index.php)

```php
require 'vendor/autoload.php';
```

Usege
-----
```php
<?php

use Pcws\Pcws;
use Pcws\PcwsException;

try {
	$words_array = Pcws::segment('Hi,Han Meimei!刘德华是会计师嘛？', 2); //String, Length(中文单词起始长度)
} catch (PcwsException $e) {
	echo $e->getMessage();
	exit;
}

var_dump($words_array);

?>
```

output:

```php
string(33) "hi han meimei 刘德华 会计师"
```


