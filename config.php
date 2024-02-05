<?php
#https://qiita.com/satorunooshie/items/ca41f7c824c7ea747708

# configを読み込む側で以下のようなコードを入れる(config.phpの場所は随時変更)
# require_once '../../config.php';
# Config::setConfigDirectory(__DIR__ . '/config');
class Config {
    protected static $directory;
    public static function setConfigDirectory($directory) {
        self::$directory = $directory;
    }

    public static function getConfigDirectory() {
        return self::$directory;
    }

    public static function get($s) {
        $values = preg_split('/\./', $s, -1, PREG_SPLIT_NO_EMPTY);
        $key = array_pop($values);
        $file = 'common.php';
        $path = (!empty($values)) ? implode(DIRECTORY_SEPARATOR, $values) .
            DIRECTORY_SEPARATOR : '';
        $base_dir = self::getConfigDirectory() . DIRECTORY_SEPARATOR;
        $config = include($base_dir . $path . $file);
        return $config[$key];
    }
}
