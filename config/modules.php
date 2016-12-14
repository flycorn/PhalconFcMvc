<?php
/**
 * 模块配置
 * author: flycorn
 * email: ym1992it@163.com
 * time: 16/12/5 下午2:10
 */
return [
    'modules' => [
        'api' => [
            'className' => 'App\Modules\Api\Module',
            'path'      => '../app/Modules/Api/Module.php',
            'name' => 'api',
            'isDefault' => 0,
        ],
        'backend'  => [
            'className' => 'App\Modules\Backend\Module',
            'path'      => '../app/Modules/Backend/Module.php',
            'name' => 'admin',
            'isDefault' => 0,
        ],
        'frontend' => [
            'className' => 'App\Modules\Frontend\Module',
            'path'      => '../app/Modules/Frontend/Module.php',
            'name' => '',
            'isDefault' => 1,
        ],
    ]
];