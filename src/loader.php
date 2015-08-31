<?php
// +----------------------------------------------------------------------
// | Leaps Framework [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2011-2014 Leaps Team (http://www.tintsoft.com)
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author Wangxb <wxb0328@gmail.com>
// +----------------------------------------------------------------------

namespace Load;

/*
 * 自动载入类
 * 根据命名空间调用类时，自动加载类文件
 */

class Loader{
    private static $file_dir = __DIR__;

    static function autoload($class){
        require self::$file_dir.'/'.str_replace('\\', '/', $class).'.php';
    } 
}
