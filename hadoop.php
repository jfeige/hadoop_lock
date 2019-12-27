/**
 * Notes:               通过redis实现的分布式锁
 * User: lf
 * Date: 2019/12/27 11:20
 * @param $key
 */
function lock($key){
    $redis = new Redis();
    $redis->connect('127.0.0.1');
    $redis->auth('123456');
    $lockExpire = 2;                            //锁的过期时间
    $lockValue = time() + $lockExpire;          //将锁的过期时间点作为value值存储
    $ok = $redis->setnx($key,$lockValue);
    if(!empty($ok) || $redis->get($key) < time() || $redis->getSet($key,$lockValue) < time()){
        //加锁成功
        //设置锁的过期时间
        $redis->expire($key,$lockExpire);
        //执行业务逻辑
        //删除锁
        $redis->del($key);
    }else{
        //休眠/循环 等待或抛出异常
    }
}
