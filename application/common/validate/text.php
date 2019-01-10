<?
/**
 * 
 */
class ClassName
{
	//递归分类
	public function digui($value='')
	{

	    function getTree($array, $pid =0, $level = 0){
	        //声明静态数组,避免递归调用时,多次声明导致数组覆盖
	        static $list = [];
	        foreach ($array as $key => $value){
	            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
	            if ($value['referrer'] == $pid){
	                //父节点为根节点的节点,级别为0，也就是第一级
	                $value['level'] = $level;
	                //把数组放到list中
	                $list[] = $value;
	                //把这个节点从数组中移除,减少后续递归消耗
	                unset($array[$key]);
	                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
	                getTree($array, $value['id'], $level+1);
	            }
	        }
	        return $list;
	    }
        $n = 21;
        $user = model('User')->order('referrer asc')->where('path','like',"%".$n."%")->select();
        // $user = model('User')->order('referrer asc')->select();
        $user = collection($user)->toArray();
         dump($user);
        $array = getTree($user,$n);
        foreach($array as $value){
            echo str_repeat('-->', $value['level']), $value['us_name'].'<br />';
        }
	}
}