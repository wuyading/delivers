<?php
use \Zilf\System\Zilf;
use \Zilf\Helpers\Arr;

/**
 * 注册 用户登录 的插件
 */
Zilf::$container->register('loginPlugin',function (){

    $obj =  new \App\Plugins\Login\LoginPlugin('userLoginToken');
    $obj -> setTableObj('App\Models\Sysadmin');

    return $obj;
});



/**
 * 注册 分类管理 的插件
 */
Zilf::$container->register('menuPlugin',function (){

    $obj = new \App\Plugins\Menu\MenuPlugin();
    $obj->setTableObj('App\Models\MenuCategory');

    return $obj;
});

/**
 * 注册 分类管理 的插件
 */
Zilf::$container->register('categoryPlugin',function (){

    $obj = new \App\Plugins\Category\CategoryPlugin();
    $obj->setTableObj('App\Models\Category');
    $obj->setTableTypeObj('App\Models\CategoryType');

    return $obj;
});


/**
 * 友情链接 的插件
 */
Zilf::$container->register('friendlyPlugin',function (){

    $obj = new \App\Plugins\Friendly\FriendlyPlugin();
    $obj->setTableObj('App\Models\Friendly');

    return $obj;

});

/**
 * 广告 的插件
 */
Zilf::$container->register('advertisePlugin',function (){

    $obj = new \App\Plugins\Advertise\AdvertisePlugin();
    $obj->setTableObj('App\Models\Advertise');

    return $obj;

});

/**
 * 广告版位 的插件
 */
Zilf::$container->register('positionPlugin',function (){

    $obj = new \App\Plugins\Advertise\PositionPlugin();
    $obj->setTableObj('App\Models\Position');

    return $obj;

});

/**
 * 评论插件
 */
Zilf::$container->register('commentPlugin',function (){

    $obj = new \App\Plugins\Comment\CommentPlugin();
    $obj->setTableObj('App\Models\Comment');

    return $obj;

});



/**
 * redis 的连接对象
 */
Zilf::$container->register('redis',function (){
    $config = Zilf::$container->getShare('config')->get('cache.redis');

    return new \Zilf\Redis\RedisManager(Arr::pull($config, 'client', 'predis'), $config);
});

/**
 * redis的连接服务
 */
Zilf::$container->register('redis.connection',function (){
    return Zilf::$container->get('redis')->connection();
});

Zilf::$container->register('cache.store', function ($app) {
    return Zilf::$container['cache']->driver();
});

Zilf::$container->register('memcached.connector', function () {
    return new \Zilf\Cache\MemcachedConnector();
});