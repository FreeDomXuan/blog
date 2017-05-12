<?php

// 添加命名空间映射关系
// 静态方法addMaps		空间名		路径
// Start::$auto->addMaps('model', 'app/model');
Start::$auto->addMaps('controller', 'app/controller');
Start::$auto->addMaps('model', 'app/model');
Start::$auto->addMaps('vendor', 'vendor/lib');