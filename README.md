Pcws
====
一个PHP写的基于Web API的中文分词工具。

安装
----
使用 Composer 安装：

```
composer require guojikai/pcws
```
在入口文件引入 Composer 启动脚本： (eg. index.php)

```php
require 'vendor/autoload.php';
```

使用
----
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

//output:
//string(33) "hi han meimei 刘德华 会计师"

?>
```

