/**
 * Notes:分页删除key
 * User: lf
 * Date: 2019/11/20 14:10
 * @param $key
 */
    public function delKeys($key){
    if(empty($key)){
        return;
    }
    self::$redis->setOption(\Redis::OPT_SCAN,\Redis::SCAN_RETRY);
    $match =  $key .'*';
    $iterator = null;
    do{
        $tmp_keys = array();
        $keys = self::$redis->scan($iterator, $match);
        if ($keys === false) {
            return;
        }
        foreach ($keys as $key) {
            $tmp_keys[] = $key;
        }
        self::$redis->del($tmp_keys);
    }while($iterator > 0);

}
