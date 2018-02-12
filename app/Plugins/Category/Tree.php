<?php

namespace App\Plugins\Category;

/**
 * 通用的树型类，可以生成任何树型结构
 */
class Tree
{
    /**
     * 生成树型结构所需要的2维数组
     * @var array
     */
    public $arr = array();

    /**
     * 生成树型结构所需修饰符号，可以换成图片
     * @var array
     */
    public $icon = array('│', '├', '└');
    public $nbsp = "&nbsp;";

    /**
     * @access private
     */
    public $ret = '';

    /**
     * 构造函数，初始化类
     *
     * @param array $arr 2维数组，例如：
     * array(
     *      1 => array('id'=>'1','parent_id'=>0,'name'=>'一级栏目一'),
     *      2 => array('id'=>'2','parent_id'=>0,'name'=>'一级栏目二'),
     *      3 => array('id'=>'3','parent_id'=>1,'name'=>'二级栏目一'),
     *      4 => array('id'=>'4','parent_id'=>1,'name'=>'二级栏目二'),
     *      5 => array('id'=>'5','parent_id'=>2,'name'=>'二级栏目三'),
     *      6 => array('id'=>'6','parent_id'=>3,'name'=>'三级栏目一'),
     *      7 => array('id'=>'7','parent_id'=>3,'name'=>'三级栏目二')
     *      )
     *
     * @return bool
     */
    public function init($arr = [])
    {
        $this->arr = $arr;
        $this->ret = '';
        return is_array($arr);
    }

    /**
     * 得到父级数组
     *
     * @param int
     * @return array
     */
    public function get_parent($myid)
    {
        $newarr = array();
        if (!isset($this->arr[$myid])) return false;
        $pid = $this->arr[$myid]['parent_id'];
        $pid = $this->arr[$pid]['parent_id'];
        if (is_array($this->arr)) {
            foreach ($this->arr as $id => $a) {
                if ($a['parent_id'] == $pid) $newarr[$id] = $a;
            }
        }
        return $newarr;
    }

    /**
     * 得到子级数组
     * @param int
     * @return array
     */
    public function get_child($myid)
    {
        $a = $newarr = array();
        if (is_array($this->arr)) {
            foreach ($this->arr as $id => $a) {
                if ($a['parent_id'] == $myid) $newarr[$id] = $a;
            }
        }
        return $newarr ? $newarr : false;
    }

    /**
     * 得到当前位置数组
     * @param int
     * @return array
     */
    public function get_pos($myid, &$newarr)
    {
        $a = array();
        if (!isset($this->arr[$myid])) return false;
        $newarr[] = $this->arr[$myid];
        $pid = $this->arr[$myid]['parent_id'];
        if (isset($this->arr[$pid])) {
            $this->get_pos($pid, $newarr);
        }
        if (is_array($newarr)) {
            krsort($newarr);
            foreach ($newarr as $v) {
                $a[$v['id']] = $v;
            }
        }
        return $a;
    }

    /**
     * 得到树型结构
     *
     * @param $myid 表示获得这个ID下的所有子级
     * @param $str  生成树型结构的基本代码，例如："<option value=\$id \$selected>\$spacer\$name</option>"
     * @param int $sid 被选中的ID，比如在做树型下拉框的时候需要用到
     * @param string $adds
     * @param string $str_group
     * @return string
     */
    public function get_tree($myid, $str, $sid = 0, $adds = '', $str_group = '')
    {
        $number = 0;
        $child = $this->get_child($myid);
        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $id => $value) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds . $j : '';
                $selected = $id == $sid ? 'selected' : '';

                @extract($value);
                $parent_id == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");

                $this->ret .= $nstr;
                $nbsp = $this->nbsp;
                $this->get_tree($id, $str, $sid, $adds . $k . $nbsp, $str_group);
                $number++;
            }
        }
        return $this->ret;
    }

    /**
     * 同上一方法类似,但允许多选
     */
    public function get_tree_multi($myid, $str, $sid = 0, $adds = '')
    {
        $number = 1;
        $child = $this->get_child($myid);
        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $id => $a) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds . $j : '';

                $selected = $this->have($sid, $id) ? 'selected' : '';
                @extract($a);
                eval("\$nstr = \"$str\";");
                $this->ret .= $nstr;
                $this->get_tree_multi($id, $str, $sid, $adds . $k . '&nbsp;');
                $number++;
            }
        }
        return $this->ret;
    }

    /**
     * @param $myid 要查询的ID
     * @param $str 第一种HTML代码方式
     * @param $str2 第二种HTML代码方式
     * @param int $sid 默认选中
     * @param string $adds 前缀
     * @return string
     */
    public function get_tree_category($myid, $str, $str2, $sid = 0, $adds = '')
    {
        $number = 1;
        $child = $this->get_child($myid);
        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $id => $a) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds . $j : '';

                $selected = $this->have($sid, $id) ? 'selected' : '';
                @extract($a);
                if (empty($html_disabled)) {
                    eval("\$nstr = \"$str\";");
                } else {
                    eval("\$nstr = \"$str2\";");
                }
                $this->ret .= $nstr;
                $this->get_tree_category($id, $str, $str2, $sid, $adds . $k . '&nbsp;');
                $number++;
            }
        }
        return $this->ret;
    }


    /**
     * 同上一类方法，jquery treeview 风格，可伸缩样式（需要treeview插件支持）
     *
     * @param $myid    表示获得这个ID下的所有子级
     * @param string $effected_id 需要生成treeview目录数的id
     * @param string $str 末级样式
     * @param string $str2 目录级别样式
     * @param int $showlevel 直接显示层级数，其余为异步显示，0为全部限制
     * @param string $style 目录样式 默认 filetree 可增加其他样式如'filetree treeview-famfamfam'
     * @param int $currentlevel 计算当前层级，递归使用 适用改函数时不需要用该参数
     * @param bool $recursion 递归使用 外部调用时为FALSE
     *
     * @return string
     */
    function get_treeview($myid, $effected_id = 'example', $str = "<span class='file'>\$name</span>", $str2 = "<span class='folder'>\$name</span>", $showlevel = 0, $style = 'filetree ', $currentlevel = 1, $recursion = FALSE)
    {
        $child = $this->get_child($myid);
        if (!defined('EFFECTED_INIT')) {
            $effected = ' id="' . $effected_id . '"';
            define('EFFECTED_INIT', 1);
        } else {
            $effected = '';
        }
        $placeholder = '<ul><li><span class="placeholder"></span></li></ul>';
        if (!$recursion) $this->str .= '<ul' . $effected . '  class="' . $style . '">';
        foreach ($child as $id => $a) {

            @extract($a);
            if ($showlevel > 0 && $showlevel == $currentlevel && $this->get_child($id)) $folder = 'hasChildren'; //如设置显示层级模式@2011.07.01
            $floder_status = isset($folder) ? ' class="' . $folder . '"' : '';
            $this->str .= $recursion ? '<ul><li' . $floder_status . ' id=\'' . $id . '\'>' : '<li' . $floder_status . ' id=\'' . $id . '\'>';
            $recursion = FALSE;
            if ($this->get_child($id)) {
                eval("\$nstr = \"$str2\";");
                $this->str .= $nstr;
                if ($showlevel == 0 || ($showlevel > 0 && $showlevel > $currentlevel)) {
                    $this->get_treeview($id, $effected_id, $str, $str2, $showlevel, $style, $currentlevel + 1, TRUE);
                } elseif ($showlevel > 0 && $showlevel == $currentlevel) {
                    $this->str .= $placeholder;
                }
            } else {
                eval("\$nstr = \"$str\";");
                $this->str .= $nstr;
            }
            $this->str .= $recursion ? '</li></ul>' : '</li>';
        }
        if (!$recursion) $this->str .= '</ul>';
        return $this->str;
    }

    /**
     * 设置自定义菜单样式
     *
     * @param $myid
     * @param string $effected
     * @param string $str
     * @param string $str2
     * @param int $showlevel
     * @param string $style
     * @param string $currentlevel
     * @param bool $recursion
     * @return string
     */
    function get_customView($myid, $effected = 'example', $str = "<span class='file'>\$name</span>", $str2 = "<span class='folder'>\$name</span>", $showlevel = 0, $style = 'filetree ', $currentlevel = '', $recursion = FALSE)
    {
        $child = $this->get_child($myid);

        if (!$recursion) $this->str .= '<ul' . $effected . '  class="' . $style . '">';
        foreach ($child as $id => $a) {

            @extract($a);

            if ($children = $this->get_child($id)) {

                $class = '';
                foreach ($children as $row) {
                    $class = $this->isCurrentPath($currentlevel, $row['link']);
                    if($class){
                        break;
                    }
                }

                $floder_status = ' class="nav-item ' . $class . '"';
                $this->str .= $recursion ? '<ul class="sub-menu"><li' . $floder_status . ' id=\'' . $id . '\'>' : '<li' . $floder_status . ' id=\'' . $id . '\'>';
                $recursion = FALSE;

                eval("\$nstr = \"$str2\";");
                $this->str .= $nstr;
                $this->get_customView($id, $effected, $str, $str2, $showlevel, $style, $currentlevel, TRUE);

            } else {
                $floder_status = ' class="nav-item ' . $this->isCurrentPath($currentlevel, $link) . '"';
                $this->str .= $recursion ? '<ul class="sub-menu"><li' . $floder_status . ' id=\'' . $id . '\'>' : '<li' . $floder_status . ' id=\'' . $id . '\'>';
                $recursion = FALSE;

                eval("\$nstr = \"$str\";");
                $this->str .= $nstr;
            }

            $this->str .= $recursion ? '</li></ul>' : '</li>';
        }
        if (!$recursion) $this->str .= '</ul>';
        return $this->str;
    }

    /**
     * 获取子栏目json
     *
     * @param $myid
     * @param string $str
     * @return string
     */
    public function creat_sub_json($myid, $str = '')
    {
        $sub_cats = $this->get_child($myid);
        $n = 0;
        if (is_array($sub_cats)) foreach ($sub_cats as $c) {
            $data[$n]['id'] = iconv(CHARSET, 'utf-8', $c['catid']);
            if ($this->get_child($c['catid'])) {
                $data[$n]['liclass'] = 'hasChildren';
                $data[$n]['children'] = array(array('text' => '&nbsp;', 'classes' => 'placeholder'));
                $data[$n]['classes'] = 'folder';
                $data[$n]['text'] = iconv(CHARSET, 'utf-8', $c['catname']);
            } else {
                if ($str) {
                    @extract(array_iconv($c, CHARSET, 'utf-8'));
                    eval("\$data[$n]['text'] = \"$str\";");
                } else {
                    $data[$n]['text'] = iconv(CHARSET, 'utf-8', $c['catname']);
                }
            }
            $n++;
        }
        return json_encode($data);
    }

    /**
     * @param $list
     * @param $item
     * @return bool|int
     */
    private function have($list, $item)
    {
        return (strpos(',,' . $list . ',', ',' . $item . ','));
    }

    /**
     * @param $currentPath
     * @param $linkPath
     * @return string
     */
    private function isCurrentPath($currentPath, $linkPath)
    {
        $class = '';
        $str_route1 = trim($currentPath, '/');
        $str_route2 = trim($linkPath, '/');
        if (!stripos($str_route2, '/')) {
            if ($str_route1 == $str_route2 . '/index' || $str_route1 == $str_route2 . '/second'
                || $str_route1 == $str_route2 . '/third' || $str_route1 == $str_route2 . '/add'
                || $str_route1 == $str_route2 . '/'.$str_route2.'_'.'add'
                || $str_route1 == $str_route2 . '/role_add' || $str_route1 == $str_route2 . '/edit') {
                $class = 'active';
            }
        } else {
            if ($str_route1 == $str_route2 || str_replace('/role_add', '', $str_route1) == str_replace('/user_role', '', $str_route2)
                || str_replace('/role_privilege', '', $str_route1) == str_replace('/user_role', '', $str_route2)) {
                $class = 'active';
            }
        }
        return $class;
    }
}