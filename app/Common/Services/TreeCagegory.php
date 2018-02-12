<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/5
 * Time: 16:32
 */

namespace App\Common\Services;

use App\Models\Category;

(new TreeCagegory())->test();

class TreeCagegory
{
    //获取某分类的直接子分类
    function getSons($categorys,$catId=0){
        $sons=array();
        foreach($categorys as $item){
            if($item['parent_id']==$catId)
                $sons[]=$item;
        }
        return $sons;
    }

    //获取某个分类的所有子分类
    function getSubs($categorys,$catId=0,$level=1){
        $subs=array();
        foreach($categorys as $item){
            if($item['parent_id']==$catId){
                $item['level']=$level;
                $subs[] = $item;
                $subs   = array_merge($subs,$this->getSubs($categorys,$item['id'],$level+1));
            }
        }
        return $subs;
    }

    //获取某个分类的所有父分类
    //方法一，递归
    function getParents($categorys,$catId){
        $tree=array();
        foreach($categorys as $item){
            if($item['parent_id']==$catId){
                if($item['parent_id']>0)
                    $tree=array_merge($tree,$this->getParents($categorys,$item['parent_id']));
                $tree[]=$item;
                break;
            }
        }
        return $tree;
    }

    //方法二,迭代
    function getParents2($categorys,$catId){
        $tree=array();
        while($catId != 0){
            foreach($categorys as $item){
                if($item['id']==$catId){
                    $tree[]=$item;
                    $catId=$item['parent_id'];
                    break;
                }
            }
        }
        return array_reverse($tree);
    }


    function test(){
        //测试 部分
        $categorys = Category::find()->select('id,name,parent_id') ->where(['type_id'=>9])->asArray()->all();
       /* $result= $this->getSons($categorys,66);
        foreach($result as $item)
            echo $item['name'].'<br>';
        echo '<hr>';*/
        $result  = [];
        foreach($categorys as $k=> $val){
            $subs = $this->getSubs($categorys,$val['id']);
            if(count($subs) ==0){
                $result[$k] = ['id'=>$categorys[$k]['id'],'name'=>$categorys[$k]['name']];
            }else{
                foreach($subs as $key => $sub){
                    if($sub['level']==1){
                        $result[$k] = ['id'=>$val['id'],'name'=>$val['name'].'>>'.$sub['name']];
                    }
                }
            }
        }
        //print_r($result);
        /*$result = $this->getSubs($categorys,0);
        foreach($result as $item)
            echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$item['level']).$item['name'].'<br>';*/
        //echo '<hr>';

        /*$result= $this->getParents($categorys,77);
        foreach($result as $item)
            echo $item['name'].' >> ';
        echo '<hr>';

        $result=$this->getParents2($categorys,75);
        foreach($result as $item)
            echo $item['name'].' >> ';*/
    }
}