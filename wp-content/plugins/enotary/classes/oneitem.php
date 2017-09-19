<?php

/**
 * @package OneItem
 * @version   1.1
 */


/**
 * Used for class version and check for existing
 */
define('ONEITEM', '1');

define('INNER_TAG_VALUE', 'itv');
define('COLLECTION_ITEM_ID', 'coll-item-id');

define('OI_NL', "\n");

define('OI_NAME', 'name');
define('OI_VALUE', 'value');
define('OI_TITLE', 'title');

define('OI_DEBUG', false);

/**
 * Get text version of boolean variable
 * 
 * @param bool $need
 * @param string $txt
 * @return string
 */
function oi_text_by_bool($need, $txt) {
    if ($need) {
        return $txt;
    } else {
        return '';
    }
}

/**
 * Return text before separator
 * 
 * @param string $separator
 * @param string $text
 * @return string
 */
function oi_str_before($separator, $text) {
    $arr = explode($separator, $text, 2);
    return $arr[0];
}

function oi_str_after() {
    
}

function oi_str_between() {
    
}

/**
 * Check subtring in string
 * 
 * @param string $txt_src
 * @param string $txt_search
 * @param boolean $skip_case
 * @return boolean
 */
function oi_substr_exists($txt_src, $txt_search, $skip_case = true) {
    if ($skip_case) {
        $txt_src = mb_strtolower($txt_src);
        $txt_search = mb_strtolower($txt_search);
    }

    $pos = mb_strpos($txt_src, $txt_search);
    if ($pos === false) {
        return false;
    } else {
        return true;
    }
}

/**
 * Generate class name with index
 * 
 * @param string $auto_class
 * @param integer $index
 * @return string
 */
function oi_auto_class($auto_class, $index) {
    if ($auto_class == '') {
        return '';
    } else {
        return $auto_class . '-' . $index;
    }
}

/**
 * For logging and debugging
 * @param string $mess
 * @param string $tag
 */
function oi_log($mess, $tag = '') {
    // debug($mess);
}

/**
 * Build parameter name="value"
 * 
 * @since 1.0
 * 
 * @param string $name
 * @param string $value
 * @return string
 */
function oi_build_param($name, $value) {
    if ($value == '') {
        return '';
    } else {
        return ' ' . $name . '="' . $value . '" ';
    }
}

function oi_get_ne_str($str1, $str2) {
    if ($str1 != '') {
        return $str1;
    }
    if ($str2 != '') {
        return $str2;
    }
    return '';
}

function oi_check_num($number, $min_value, $max_value) {
    if ($number < $min_value) {
        return;
        $min_value;
    } else
    if ($number > $max_value) {
        return $max_value;
    } else
        return $number;
}

/**
 * Simple and powerfull HTML structure generator
 * 
 * @package OneItem
 * @version 1.1
 */
class OneItem {

    /**
     * ID for html element
     * 
     * @see set_id()
     * @var string
     */
    public $id;

    /**
     * Value for html element (inner text)
     * 
     * @see set_value()
     * @var string
     */
    public $value;

    /**
     * Link for html element (href)
     * 
     * @var string
     */
    public $url;

    /**
     * Classes list for html element
     * 
     * @example class="class1, class2"
     * @see set_classes()
     * @var string
     */
    public $classes;

    /**
     * Tag for html item (div, h1, span...)
     * @see set_tag()
     * @var string
     */
    public $tag;

    /**
     * Is single html tag or not
     * @example  Single: <img ... />  
     * @var boolean
     */
    public $single;

    /**
     * OneItems list
     * @see add_item()
     * @see add_ex()
     * @var array of OneItem
     */
    public $items;

    /**
     * Parameters for html item
     * @see OneParams
     * @var OneParams
     */
    public $params;

    /**
     * Parent node for item
     * @var OneItem 
     */
    public $parent;

    /**
     * Level of item
     * @var integer 
     */
    public $level = 0;

    /**
     * Show item or not
     * @var boolean 
     */
    public $visible = true;

    /**
     * Hide this tag if no child items
     * 
     * @var boolean 
     */
    public $hide_empty = false;

    /**
     * Custom raw data can be used as cache or non standard html data
     * 
     * @var string 
     */
    public $raw = '';

    /**
     * OneItem constructor
     * 
     * @param OneItem $parent Parent of element
     * @param string $id ID for element
     * @param string $classes List of classes for element
     * @param string $tag HTML tag for element
     * @param string $value Default value for element 
     * @param string $title Element title (if used)
     * @param string $url Element link (if exists)
     */
    function __construct($parent = null, $id = '', $classes = '', $tag = '', $value = '', $title = '', $url = '') {

        oi_log('__construct: ' . "id: $id, class: $classes, tag: $tag, value: $value");
        $this->params = new OneParams();

        if (($parent != null) && ($parent != '')) {
            $this->set_parent($parent);
        }

        $tag = trim($tag);

        $this->id = $id;
        $this->classes = $classes;
        $this->value = $value;
        $this->tag = $tag;
        $this->url = $url;
        $this->set_title($title);

        $this->init_tag_wizard($tag);
    }

    /**
     * Setup common paramaters by tag (input, select, button...)
     * 
     * @param string $tag_name
     */
    private function init_tag_wizard($tag_name) {
        if ($tag_name == '') {
            return false;
        }
        if (OI_DEBUG) {
            oi_log('init_tag_wizard 1: ' . $this->get_debug_info());
        }
        $arr_search = array('/', '{', '[', ',');
        $clear_tag = str_replace($arr_search, ' ', $tag_name);
        $arr_tags = explode(' ', $clear_tag);
        $this->tag = $arr_tags[0];


        if (oi_substr_exists($tag_name, 'input')) {
            $this->_wizard_forms($tag_name);
        } else
        if (oi_substr_exists($tag_name, 'select')) {
            $this->_wizard_forms($tag_name);
        } else
        if (oi_substr_exists($tag_name, 'option')) {
            $this->_wizard_forms($tag_name);
        } else
        if (oi_substr_exists($tag_name, 'textarea')) {
            $this->_wizard_forms($tag_name);
        } else

        if ((oi_substr_exists($tag_name, '{')) || (oi_substr_exists($tag_name, '/'))) {
            $this->_wizard_struct($tag_name);
        } else

        if (oi_substr_exists($tag_name, '[')) {
            $this->_wizard_params($tag_name);
        }

        if ($this->is_single_tag($this->tag)) {
            $this->single = true;
        }

        $this->_wizard_params_sync();
        if (OI_DEBUG) {
            oi_log('init_tag_wizard 2: ' . $this->get_debug_info());
        }
    }

    /**
     * 
     * @param string $tag_name
     */
    private function _wizard_forms($tag_name) {
        if (OI_DEBUG) {
            oi_log('_wizard_forms:' . $tag_name);
        }
        $tag_name_src = $tag_name;

        $start1 = strpos($tag_name, '[');
        $start2 = strpos($tag_name, '{');
        $separator = '[';
        if ($start2 < $start1) {
            $separator = '{';
        }

        $tag_name = oi_str_before($separator, $tag_name);
        $tag_name = mb_strtolower($tag_name);

        $arr_tags = explode(' ', $tag_name);
        if (count($arr_tags) > 0) {
            $this->tag = $arr_tags[0];
        }

        if (oi_substr_exists($tag_name_src, '[')) {
            $this->_wizard_params($tag_name);
        }

        $need_name = false;
        $need_value = false;

        if (oi_substr_exists($tag_name, 'selected')) {
            $this->params->add('selected', 'selected');
        }
        if (oi_substr_exists($tag_name, 'checked')) {
            $this->params->add('checked', 'checked');
        }

        if ($arr_tags[0] == 'input') {
            $this->tag = 'input';
            $this->single = true;

            $need_name = true;
            $need_value = true;

            if (count($arr_tags) > 1) {
                $param_type = $arr_tags[1];
                $pos = strpos($param_type, '[');
                if ($pos === false) {
                    $this->params->add('type', $arr_tags[1]);
                }// For: hidden, checkbox, radio, submit, button, text   
            }
        } else

        if ($arr_tags[0] == 'textarea') {
            $need_name = true;
            $need_value = true;
        } else

        if ($arr_tags[0] === 'select') {
            $need_name = true;
        } else

        if ($arr_tags[0] === 'option') {
            $this->params->add('value', $this->value);
            $this->value = $this->get_param('title');
            $this->params->remove('title');
        }


        if ($need_name) {
            $this->params->add('name', oi_get_ne_str($this->id, $this->params->get_value('id')));
        }
        if ($need_value) {
            $this->params->add('value', $this->value);
        }
    }

    /**
     * 
     * @param string $tag_name
     */
    private function _wizard_params($tag_name) {
        if (OI_DEBUG) {
            oi_log('_wizard_params:' . $tag_name);
        }
        // extract all possible parameters [param1=qwe,param2=asd,param3=zxc]
        if ($tag_name == '') {
            return '';
        }
        $start = strpos($tag_name, '[');
        if ($start == false) {
            $start = 0;
        }
        $end = strpos($tag_name, ']', $start + 1);
        $length = $end - $start;

        if (($start >= 0) && ($start < $end)) {
            // exists some parameters	 
            $res_params = mb_substr($tag_name, $start + 1, $length - 1);
            $arr_params = explode(',', $res_params);

            foreach ($arr_params as $item) {
                $arr_curr_param = explode('=', $item);
                if (strpos($arr_curr_param[1], ']') > 0) {
                    $arr_curr_param[1] = str_replace(']', '', $arr_curr_param[1]);
                } // fix for unicode symbols
                $this->params->add($arr_curr_param[0], $arr_curr_param[1]);
                //oi_log('add param: [' . $arr_curr_param[0] . '] = ' . $arr_curr_param[1]);
            }
        }
        //oi_log('_wizard_params:' . $this->params->as_list());  
    }

    /**
     * Sync common parameters in properties and parameters
     */
    private function _wizard_params_sync() {
        if (OI_DEBUG) {
            oi_log('_wizard_params_sync');
        }
        if ($this->params->param_exists(INNER_TAG_VALUE)) {
            $this->set_value($this->params->get_value(INNER_TAG_VALUE));
            //oi_log('sync itv: ' . $this->params->get_value(INNER_TAG_VALUE));
            $this->params->remove(INNER_TAG_VALUE);
        }

        if ($this->params->param_exists('class')) {
            $this->class_apply($this->params->get_value('class'));
            //oi_log('sync class: ' . $this->params->get_value('class'));
        }

        if ($this->params->param_exists('id')) {
            if ($this->id == '') {
                $this->id = $this->params->get_value('id');
            }
            //oi_log('sync id: ' . $this->params->get_value('id'));
        }


        if ($this->tag == 'img') {
            $this->init_image($this->value);
        } else if ($this->tag == 'textarea') {
            $this->single = false;
            $need_name = true;
        } else
        if ($this->tag == 'form') {
            $this->params->add('method', 'post');
        }



        if ($this->tag == 'option') {
            if ($this->value == '') {
                $this->value = $this->params->get_value('title');
                $this->params->remove('title');
            }
        }
    }

    /**
     * 
     * @param string $tag_name
     */
    private function _wizard_struct($tag_name) {
        if (OI_DEBUG) {
            oi_log('_wizard_struct: ' . $tag_name);
        }
        $this->_struct_parser($this, $tag_name);
    }

    /**
     * 
     * @param OneItem $level_item
     * @param string $txt
     * @return OneItem
     */
    private function _struct_parser($level_item, $txt) {
        $arr_tags = array();
        $buff_item = '';
        $buff_subitem = '';
        $level = 0;
        $level_slash = 0;
        $arr_subitems = array();
        $arr_symbols = str_split($txt);
        $hook_params = false;
        $hook_path = false;

        foreach ($arr_symbols as $key => $symbol) {

            if ($symbol == '{') {
                if ($level <= 0) {
                    $level = 1;
                    $symbol = '';
                } else {
                    $level++;
                }
            } else // if($symbol == '{')


            if ($symbol == '}') {
                if ($level > 0) {
                    if ($level == 1) {
                        if (OI_DEBUG) {
                            oi_log('apply extracted subitem: ' . $buff_subitem);
                        }
                        $level = 0;
                        $arr_subitems[] = $buff_subitem;
                        $buff_subitem = '';
                        $symbol = '';
                    } else {
                        $level = $level - 1;
                    }
                } else {
                    // error syntax
                }
            } else // if($symbol == '}')	

            if ($symbol == '[') {
                $hook_params = true;
            } else
            if ($symbol == ']') {
                $hook_params = false;
            }


            if (($symbol == '/') && ($level <= 0) && (!$hook_params)) {
                if (OI_DEBUG) {
                    oi_log('s-delimeter /' . "item: $buff_item, subitem: $buff_subitem");
                }
                if (!is_object($level_item)) {
                    if (OI_DEBUG) {
                        oi_log('create root item: ' . $buff_item);
                    }
                    $level_item = $this->create_item('', '', '', $buff_item);
                } else {
                    if ($level_slash <= 0) {
                        if (OI_DEBUG) {
                            oi_log('assign params: ' . $buff_item);
                        }
                        $this->_wizard_params($buff_item);
                    } else {
                        if (OI_DEBUG) {
                            oi_log('add subitem (change level): ' . $buff_item);
                        }
                        $level_item = $level_item->add_ex('', '', $buff_item);
                    }
                }

                if (count($arr_subitems) > 0) {
                    foreach ($arr_subitems as $si) {
                        $this->add_tag($si);
                    }
                }

                $arr_subitems = array();
                $buff_item = '';
                $buff_subitem = '';
                $hook_path = true;
                $level_slash++;
            } else {
                if ($level > 0) {
                    $buff_subitem .= $symbol;
                } else {
                    $buff_item .= $symbol;
                }
            }
        } // foreach($arr_txt as $key => $symbol)


        $buff_item = trim($buff_item);
        if (($buff_item == '') && (count($arr_subitems) > 0)) {
            if (OI_DEBUG) {
                oi_log('v1: ' . $buff_item . ' si: ' . $buff_subitem);
            }
            // if only subitems without owner
            foreach ($arr_subitems as $si) {
                $this->add_tag($si);
            }
        }


        if ($buff_item != '') {
            if (OI_DEBUG) {
                oi_log('v2: $buff_item: ' . $buff_item . ' $buff_subitem: ' . $buff_subitem . ', count-si: ' . count($arr_subitems));
            }
            if (!is_object($level_item)) {
                if (OI_DEBUG) {
                    oi_log('create root level item: ' . $buff_item);
                }
                $level_item = $this->create_item('', '', '', $buff_item);
            } else {
                if ($hook_path) {
                    if (OI_DEBUG) {
                        oi_log('add item and change level: ' . $buff_item);
                    }
                    $level_item = $level_item->add_tag($buff_item); // need change level
                } else {
                    if (OI_DEBUG) {
                        oi_log('assign item params: ' . $buff_item);
                    }
                    $this->_wizard_params($buff_item);
                }
            }

            if (count($arr_subitems) > 0) {
                if (OI_DEBUG) {
                    oi_log('add childs: ' . count($arr_subitems));
                }
                foreach ($arr_subitems as $si) {
                    $level_item->add_tag($si);
                }
            }
        }

        return $level_item;
    }

    /**
     * Assign properties from other item
     * 
     * @param OneItem $item
     */
    public function assign($item) {
        $this->tag = $item->tag;
        $this->classes = $item->classes;
        $this->id = $item->id;
        $this->value = $item->value;
        $this->single = $item->single;
        $this->url = $item->url;

        $this->hide_empty = $item->hide_empty;
        $this->visible = $item->visible;

        $this->params->assign($item->params); // need assign params
    }

    /**
     * Get item as open tag
     * @since 1.0
     * 
     * @param string $curr_tag
     * @return string
     */
    public function as_open($curr_tag = '') {

        if (($this->hide_empty) && ($this->total_count() <= 0)) {
            return '';
        }

        if ($curr_tag == '') {
            $this->check_default_tag();
            $curr_tag = $this->tag;
        }

        $params = '';
        $lvl_offset = $this->get_level_offset();

        if ($curr_tag == 'a') {
            $params = ' href="' . $this->url . '" ';
        }

        if ($this->total_count() > 0) {
            $nl = OI_NL;
        } else {
            $nl = '';
        }

        return $lvl_offset . '<' . $curr_tag . oi_build_param('id', $this->id) .
                oi_build_param('class', $this->classes) . $this->params->as_list() . $params . '>' . $nl;
    }

    /**
     * Get item as close tag
     * @since 1.0
     * 
     * @param string $curr_tag
     * @return string
     */
    public function as_close($curr_tag = '') {
        if (($this->hide_empty) && ($this->total_count() <= 0)) {
            return '';
        }

        if ($curr_tag == '') {
            $this->check_default_tag();
            $curr_tag = $this->tag;
        }

        if ($this->total_count() > 0) {
            $lvl_offset = $this->get_level_offset();
        } else {
            $lvl_offset = '';
        }


        return $lvl_offset . '</' . $curr_tag . '>' . OI_NL;
    }

    /**
     * Get offset for html text
     * @since 1.0
     * 
     * @return string
     */
    public function get_level_offset() {
        $res = '';
        if ($this->level > 0) {
            for ($i = 0; $i < $this->level; $i++) {
                $res .= '  ';
            }
        }
        return $res;
    }

    /**
     * Get item value (with subitems)
     * 
     * @since 1.0
     * @return string
     */
    public function as_value() {
        if (($this->hide_empty) && ($this->total_count() <= 0)) {
            return '';
        }

        $html = $this->value;

        if (count($this->items) > 0) {
            foreach ($this->items as $item) {
                $html .= $item->as_html();
            }
        }
        return $html;
    }

    /**
     * Get item as html string
     * 
     * @since 1.0
     */
    public function as_html() {
        if (!$this->visible) {
            return '';
        }
        if (($this->hide_empty) && ($this->total_count() <= 0)) {
            return '';
        }
        $this->check_default_tag();
        $html = '';
        if ($this->raw != '') {
            $html = $this->raw . OI_NL;
        }


        if ($this->single) {
            $lvl_offset = $this->get_level_offset();
            $html .= $lvl_offset . '<' . $this->tag . oi_build_param('id', $this->id) .
                    oi_build_param('class', $this->classes) . $this->params->as_list() . '/>' . OI_NL;
        } else {

            if (($this->url != '') && !$this->compare_tag('a')) {
                $a = $this->create_item($this, '', '', 'a', $this->as_value(), '', $this->url);
                $html .= $this->as_open() . $a->as_html() . $this->as_close();
            } else {
                $html .= $this->as_open() . $this->as_value() . $this->as_close();
            }
        }

        return $html;
    }

    /**
     * Compare tag name with case options
     * 
     * @param string $tag_name
     * @param string $skip_case
     * @return boolean
     */
    public function compare_tag($tag_name, $skip_case = false) {
        if ($skip_case) {
            return mb_strtolower($this->tag) == mb_strtolower($tag_name);
        } else {
            return $this->tag == $tag_name;
        }
    }

    /**
     * Get item as other tag
     * 
     * @param string $tag
     * @param string $id
     * @param string $classes
     * @return OneItem
     */
    public function as_tag($tag, $id = '', $classes = '') {
        $item = $this->create_item('', $id, $classes, $tag);
        $item->add_item($this);
        return $item;
    }

    /**
     * Get items as custom subtag tag1 / tag2
     * 
     * @param string $tag
     * @param string $subtag
     * @param string $id
     * @param string $classes
     * @return OneItem
     */
    public function as_subtag($tag, $subtag, $auto_class = '') {
        $list_item = $this->create_item('', '', '', $tag);
        $list_item->params->assign($this->params);
        $i = 0;

        foreach ($this->items as $item) {
            $subitem = $list_item->add_ex('', oi_auto_class($auto_class, $i), $subtag);
            $subitem->add_item($item);
            $i++;
        }

        return $list_item;
    }

    /**
     * Get items as grid structure owner / row / columns
     * 
     * @param string $tag
     * @param string $tag_row
     * @param string $tag_column
     * @param string $cols
     * @return OneItem
     */
    public function as_grid_cols($tag, $tag_row, $tag_column, $cols, $class_row = '', $class_col = '') {
        $grid = $this->create_item('', '', '', $tag);
        $grid->params->assign($this->params);
        $ir = 0;
        $ic = 0;

        foreach ($this->items as $item) {
            if ($ic == 0) {
                $row = $grid->add_ex('', oi_auto_class($class_row, $ir), $tag_row);
                $ir++;
            }
            $row->add_item($item->as_tag($tag_column, '', oi_auto_class($class_col, $ic)));
            if ($ic >= ($cols - 1)) {
                $ic = 0;
            } else {
                $ic++;
            }
        }
        return $grid;
    }

    /**
     * Get item as LI
     * 
     * @since 1.0
     * @param string $id
     * @param string $classes
     * @return string
     */
    public function as_li($id = '', $classes = '') {
        return as_tag('li', $id, $classes);
    }

    /**
     * Get all items as UL list
     * 
     * @since 1.0
     * @param string $id
     * @param string $classes
     * @return string
     */
    public function as_ul($auto_class = '') {
        return $this->as_subtag('ul', 'li', $auto_class);
    }

    /**
     * Get all items as OL list
     * 
     * @since 1.0
     * @param string $id
     * @param string $classes
     * @return string
     */
    public function as_ol($auto_class = '') {
        return $this->as_subtag('ol', 'li', $auto_class);
    }

    /**
     * Get all items as TR (Table Row)
     * 
     * @since 1.0
     * @param string $id
     * @param string $classes
     * @return string
     */
    public function as_tr($auto_class = '') {
        return $this->as_subtag('tr', 'td', $auto_class);
    }

    /**
     * Get all items as TABLE
     * 
     * @since 1.0
     * @param string $id
     * @param string $classes
     * @param integer $columns
     * @return string
     */
    public function as_table($cols, $auto_row = '', $auto_col = '') {
        return $this->as_grid_cols('table', 'tr', 'td', $cols, $auto_row, $auto_col);
    }

    /**
     * Set Tag for all child items
     * 
     * @param string $tag
     */
    public function set_items_tag($tag) {
        if (count($this->items) > 0) {
            foreach ($this->items as $item) {
                $item->set_tag($tag);
            }
        }
    }

    /**
     * Apply new class if not exists
     * 
     * @param string $classes
     */
    public function class_apply($classes) {
        if ($this->classes == '') {
            $this->classes = $classes;
        } else {
            if (!$this->class_exists($classes)) {
                $this->classes .= ' ' . $classes;
            }
        }
    }

    /**
     * Check class exists or not
     * 
     * @param string $classes
     */
    public function class_exists($classes) {
        //return oi_substr_exists($this->classes, $classes, false);
        $txt_src = mb_strtolower($this->classes);
        $txt_search = mb_strtolower($classes);

        $res = false;
        $arr = explode(' ', $txt_src);
        foreach ($arr as $curr_class) {
            if ($curr_class == $txt_search) {
                $res = true;
                return $res;
            }
        }

        return $res;
    }

    /**
     * Show item (full html structure)
     * 
     * @since 1.0
     */
    public function show() {
        echo $this->as_html();
    }

    /**
     * Init current item as DIV
     * 
     * @since 1.0
     */
    public function init_div() {
        $this->tag = 'div';
    }

    /**
     * Init current item as IMG
     *
     * @since 1.0
     * @param string $img_file
     */
    public function init_image($img_file) {
        $this->set_tag('img');
        $this->params->add('src', $img_file);
        $this->single = true;
    }

    /**
     * Init current item as HR
     * 
     * @since 1.0
     */
    public function init_hr() {
        $this->set_tag('hr');
        $this->single = true;
    }

    /**
     * Add item
     * 
     * @since 1.0
     * @param OneItem $item
     */
    public function add_item($item) {
        if (is_object($item)) {
            $this->rebuild_indexes($item);
            $item->parent = $this;
            $this->items[] = $item;
        }
    }

    /**
     * Insert owner tag
     * 
     * @param string $tag
     * @return \OneItem
     */
    public function add_owner($tag = 'div') {
        return $this->add_owner_ex('', '', $tag);
    }

    /**
     * Insert owner tag with id and classes
     * 
     * @param string $id
     * @param string $class
     * @param string $tag
     * @return \OneItem
     */
    public function add_owner_ex($id, $class = '', $tag = 'div') {
        $item_owner = $this->create_item($this->parent, $id, $class, $tag); // create new owner

        $item_curr = $this->create_item(); // create tmp item
        $item_curr->assign($this); // remember current root
        $item_curr->items = $this->items; // remember current items

        $this->clear_items();
        $this->assign($item_owner); // assign new owner

        $this->add_item($item_curr);
        return $this;
    }

    /**
     * Clear all items
     */
    public function clear_items() {
        $this->items = array();
    }

    /**
     * Add item with parameters
     * 
     * @since 1.0
     * 
     * @param string $id
     * @param string $classes
     * @param string $tag
     * @param string $value
     * @param string $title
     * @param string $url
     * @return \OneItem
     */
    public function add_ex($id = '', $classes = '', $tag = '', $value = '', $title = '', $url = '') {
        $item = $this->create_item($this, $id, $classes, $tag, $value, $title, $url);

        $this->rebuild_indexes($item);
        $this->items[] = $item;
        return $item;
    }

    /**
     * Add item with tag and value
     * 
     * @param string $tag
     * @param string $value
     * @return \OneItem
     */
    public function add_tag($tag, $value = '') {
        if (OI_DEBUG) {
            oi_log("add_tag: tag: $tag, value: $value");
        }
        return $this->add_tag_ex('', '', $tag, $value);
    }

    /**
     * Add item with advanced parameters
     * 
     * @param string $tag
     * @param string $value
     * @return \OneItem
     */
    public function add_tag_ex($id, $classes, $tag, $value = '') {
        if (OI_DEBUG) {
            oi_log("add_tag_ex: id: $id, classes: $classes, tag: $tag, value: $value");
        }
        $item = $this->create_item($this, $id, $classes, $tag, $value);
        $this->rebuild_indexes($item);
        $this->items[] = $item;
        return $item;
    }

    /**
     * Add arguments as tags
     * 
     * @param string $tag
     * @param string $arg1
     * @param string $arg2
     */
    public function add_tags($tag, $arg1 = '', $arg2 = '') {
        if (func_num_args() > 1) {
            $max_arg = func_num_args();
            for ($i = 1; $i < $max_arg; $i++) {
                $curr_arg = func_get_arg($i); // new var - fix bug in php < 5.3
                $this->add_ex('', 'auto-tag-' . ($i - 1), $tag, $curr_arg);
            }
        }
    }

    public function add_tag_pairs($tag1, $value1, $tag2 = '', $value2 = '') {
        $max_arg = func_num_args();
        for ($i = 0; $i < $max_arg; $i+=2) {
            $arg1 = func_get_arg($i); // new var - fix bug in php < 5.3
            $arg2 = func_get_arg($i + 1); // new var - fix bug in php < 5.3
            $this->add_tag($arg1, $arg2);
        }
    }

    public function add_tag_pairs_ex($gr_id, $gr_class, $gr_tag = 'div', $tag1 = '', $value1 = '') {
        $group = $this->add_tag($gr_tag);

        $max_arg = func_num_args();
        for ($i = 3; $i < $max_arg; $i+=2) {
            $arg1 = func_get_arg($i); // new var - fix bug in php < 5.3
            $arg2 = func_get_arg($i + 1); // new var - fix bug in php < 5.3           
            $group->add_tag($arg1, $arg2);
        }
    }

    /**
     * Add item as sub-item of another tag
     * 
     * @since 1.1
     * @param string $tag
     * @param string $subtag
     * @param string $subvalue
     * @return OneItem
     */
    public function add_sub_tag($tag, $subtag, $subvalue = '') {
        $item = $this->add_tag($tag);
        return $item->add_tag($subtag, $subvalue);
    }

    /**
     * Add item as sub-item of another tag with id and classes
     * 
     * @param string $tag_id
     * @param string $tag_class
     * @param string $tag
     * @param string $sub_id
     * @param string $sub_class
     * @param string $subtag
     * @param string $subvalue
     * @return OneItem
     */
    public function add_sub_tag_ex($tag_id, $tag_class, $tag, $sub_id, $sub_class, $subtag, $subvalue = '') {
        $item = $this->add_tag_ex($tag_id, $tag_class, $tag);
        return $item->add_tag_ex($sub_id, $sub_class, $subtag, $subvalue);
    }

    /**
     * add arguments as subitems for tag and subtag
     * 
     * @param string $tag
     * @param string $subtag
     * @param string $arg1
     * @param string $arg2
     */
    public function add_subtags($tag, $subtag, $arg1 = '', $arg2 = '') {
        if (func_num_args() > 2) {
            $max_arg = func_num_args();
            $item = $this->add_ex('', oi_auto_class('auto-tag', $this->total_count()), $tag, '');
            for ($i = 2; $i < $max_arg; $i++) {
                $curr_arg = func_get_arg($i);
                $item->add_ex('', oi_auto_class('auto-sub', $i - 2), $subtag, $curr_arg);
            }
        }
    }

    /**
     * Add default item with value
     * 
     * @param string $value
     * @return \OneItem
     */
    public function add_value($value) {
        return $this->add_ex('', '', '', $value);
    }

    /**
     * Add P item
     * 
     * @since 1.0
     * 
     * @param string $value
     * @return OneItem
     */
    public function add_p($value) {
        return $this->add_ex('', '', 'p', $value);
    }

    /**
     * Add P item with id and class
     * 
     * @since 1.0
     * @param string $id
     * @param string $classes
     * @param string $value
     * @return OneItem
     */
    public function add_p_ex($id, $classes, $value) {
        return $this->add_ex($id, $classes, 'p', $value);
    }

    /**
     * Add radio item
     * 
     * @param string $id
     * @param string $group
     * @param string $value
     * @param string $title
     * @param string $classes
     */
    public function add_radio($id, $group, $value, $title, $checked = false, $classes = '', $wrap = false) {
        if ($id == 'random') {
            $id = 'radio' . rand(1, 9999);
        }
        if ($wrap) {
            $panel = $this->add_div('group' . $id, 'group-radio', '');
            $radio = $panel->add_ex($id, $classes, 'input radio');
            $lbl = $panel->add_ex('lbl-' . $id, $classes, 'label', $title);
        } else {
            $radio = $this->add_ex($id, $classes, 'input radio');
            $lbl = $this->add_ex('lbl-' . $id, $classes, 'label', $title);
        }

        $radio->set_param('name', $group);
        $radio->set_param('value', $value);
        if ($checked) {
            $radio->set_param('checked', 'checked');
        }

        $lbl->set_param('for', $id);
        return $radio;
    }

    /**
     * Add select item as listbox or combobox
     * 
     * @return OneItem
     */
    public function add_select($items = false, $isplitter = ',', $iselected = '...') {
        return $this->add_select_ex('', '', 0, $items, $isplitter, $iselected);
    }

    /**
     * Add select item as listbox or combobox
     * 
     * @param string $id
     * @param string $classes
     * @param int $size
     * @return OneItem
     */
    public function add_select_ex($id, $classes = '', $size = 0, $items = false, $isplitter = ',', $iselected = '...') {
        $sel = $this->add_ex($id, $classes, 'select');
        $size = intval($size);
        if ($size > 0) {
            $sel->set_param('size', $size);
        }

        // if items is array ................
        if ($items != false) {
            if (is_string($items)) {
                $items = explode($isplitter, $items);
            }
            if (count($items) > 0) {
                foreach ($items as $item) {
                    if ($item == $iselected) {
                        $sel->add_ex('', '', 'option [selected=selected]', '', $item);
                    } else {
                        $sel->add_ex('', '', 'option', '', $item);
                    }
                }
            }
        }

        return $sel;
    }

    /**
     * 
     * @param string $value
     * @param boolean $selected
     * @param boolean $disabled
     * @return OneItem
     */
    public function add_option($value, $title = '', $selected = false, $disabled = false) {
        if ($title == '') {
            $item = $this->add_ex('', '', 'option');
            $item->value = $value;
        } else {
            $item = $this->add_ex('', '', 'option');
            $item->value = $title;
            $item->set_param('value', $value);
        }

        if ($selected) {
            $item->set_param('selected', '1');
        }
        if ($disabled) {
            $item->set_param('disabled', '1');
        }
        return $item;
    }

    /**
     * Add hidden field
     * 
     * @param string $name
     * @param string $value
     * @return OneItem
     */
    public function add_hf($name, $value) {
        return $this->add_ex($name, '', 'input hidden', $value);
    }

    /**
     * Add H item
     * 
     * @since 1.0
     * 
     * @param string $value
     * @param string $h_number
     * @return OneItem
     */
    public function add_h($value, $h_number = 2) {
        return $this->add_h_ex('', '', $value, $h_number);
    }

    /**
     * Add H item with id and class
     * 
     * @since 1.0
     * 
     * @param string $id
     * @param string $classes
     * @param string $value
     * @param string $hear_number
     * @return OneItem
     */
    public function add_h_ex($id, $classes, $value, $h_number = 2) {
        $h_number = oi_check_num(intval($h_number), 1, 10);
        return $this->add_ex($id, $classes, 'h' . $h_number, $value);
    }

    /**
     * Add DIV item
     * @since 1.0
     * 
     * @param string $value
     * @return OneItem
     */
    public function add_div($value = '') {
        return $this->add_div_ex('', '', $value);
    }

    /**
     * Add DIV item with id and class
     * @since 1.0
     * 
     * @param string $id
     * @param string $classes
     * @param string $value
     * @return OneItem
     */
    public function add_div_ex($id = '', $classes = '', $value = '') {
        return $this->add_ex($id, $classes, 'div', $value);
    }

    /**
     * Add IMG item
     * @since 1.0
     * 
     * @param string $src
     * @param string $title
     * @return OneItem
     */
    public function add_img($src, $title = '') {
        return $this->add_img_ex('', '', $src, '', $title);
    }

    /**
     * Add IMG item with parameters
     * @since 1.0
     * 
     * @param string $id
     * @param string $classes
     * @param string $src
     * @param string $link
     * @param string $title
     * @return OneItem
     */
    public function add_img_ex($id, $classes, $src, $link = '', $title = '') {
        if ($link != '') {
            $item_img = $this->create_item($this, '', '', '', '', $title, '');
            $item_img->init_image($src);


            $item = $this->create_item($this, $id, $classes, 'a', '', $title, $link);
            $item->add_item($item_img);
            $item = $item_img;
        } else {
            $item = $this->create_item($this, $id, $classes, '', '', $title, $link);
            $item->init_image($src);
        }

        $this->rebuild_indexes($item);
        $this->add_item($item);
        return $item;
    }

    /**
     * Add SPAN item
     * 
     * @param type $value
     * @param type $title
     * @return OneItem
     */
    public function add_span($value = '', $title = '') {
        return $this->add_ex('', '', 'span', $value, $title);
    }

    /**
     * Add SPAN item with id and class
     * @since 1.0
     * 
     * @param string $id
     * @param string $classes
     * @param string $value
     * @return OneItem
     */
    public function add_span_ex($id = '', $classes = '', $value = '', $title = '') {
        return $this->add_ex($id, $classes, 'span', $value, $title);
    }

    /**
     * Add UL item
     * @return OneItem
     */
    public function add_ul() {
        return $this->add_ex('', '', 'ul');
    }

    /**
     * Add UL item with id and class
     * @since 1.0
     * 
     * @param string $id
     * @param string $classes
     * @return OneItem
     */
    public function add_ul_ex($id = '', $classes = '') {
        return $this->add_ex($id, $classes, 'ul');
    }

    /**
     * Add OL item
     * 
     * @return OneItem
     */
    public function add_ol() {
        return $this->add_ex('', '', 'ol');
    }

    /**
     * Add OL item with id and class
     * @since 1.0
     * 
     * @param string $id
     * @param string $classes
     * @return OneItem
     */
    public function add_ol_ex($id = '', $classes = '') {
        return $this->add_ex($id, $classes, 'ol');
    }

    /**
     * Add LI item
     * 
     * @param string $value
     * @return OneItem
     */
    public function add_li($value = '') {
        return $this->add_ex('', '', 'li', $value);
    }

    /**
     * Add LI item with id and class
     * @since 1.0
     * 
     * @param string $id
     * @param string $classes
     * @param string $value
     * @return OneItem
     */
    public function add_li_ex($id = '', $classes = '', $value = '') {
        return $this->add_ex($id, $classes, 'li', $value);
    }

    /**
     * Add HR (Horizontal line) item
     * 
     * @return OneItem
     */
    public function add_hr() {
        return $this->add_ex('', '', 'hr');
    }

    /**
     * Add HR item with id and class
     * @since 1.0
     * 
     * @param string $id
     * @param string $classes
     * @return OneItem
     */
    public function add_hr_ex($id = '', $classes = '') {
        return $this->add_ex($id, $classes, 'hr');
    }

    /**
     * Add A item (hyperlink)
     * 
     * @param string $link
     * @param string $value
     * @param string $title
     * @return OneItem
     */
    public function add_a($link = '', $value = '', $title = '') {
        return $this->add_a_ex('', '', $link, $value, $title);
    }

    /**
     * Add A item with id and classes
     * @since 1.0
     * 
     * @param string $id
     * @param string $classes
     * @param string $link
     * @param string $value
     * @param string $title
     * @return OneItem
     */
    public function add_a_ex($id = '', $classes = '', $link = '', $value = '', $title = '') {
        return $this->add_ex($id, $classes, 'a', $value, $title, $link);
    }

    /**
     * Add BR item
     * 
     * @return OneItem
     */
    public function add_br() {
        return $this->add_ex('', '', 'br');
    }

    /**
     * Add BR item with id and classes
     * @since 1.0
     * 
     * @param string $id
     * @param string $classes
     * @return OneItem
     */
    public function add_br_ex($id = '', $classes = '') {
        return $this->add_ex($id, $classes, 'br');
    }

    /**
     * Add script item
     * 
     * @since 1.1
     * @param string $script
     * @param bool $add_type
     * @param string $id
     * @return OneItem
     */
    public function add_script($script, $add_type = false, $id = '') {
        $item = $this->add_ex($id, '', 'script', $script);
        if ($add_type) {
            $item->set_param('type', 'text/javascript');
        }
        return $item;
    }

    /**
     * Add CSS styles item
     * 
     * @since 1.1
     * @param string $css
     * @param bool $add_type
     * @param string $id
     * @return OneItem
     */
    public function add_style($css, $add_type = false, $id = '') {
        $item = $this->add_ex($id, '', 'style', $css);
        if ($add_type) {
            $item->set_param('type', 'text/css');
        }
        return $item;
    }

    /**
     * Add META item (parameters name and content)
     * @since 1.0
     * 
     * @param string $mname
     * @param string $mcontent
     * @return OneItem
     */
    public function add_meta($mname, $mcontent) {
        return $this->add_item_meta_ex('name', $mname, 'content', $mcontent);
    }

    /**
     * Add custom meta tag item with 2 parameters
     * @since 1.0
     * 
     * @param string $name1
     * @param string $value1
     * @param string $name2
     * @param string $value2
     * @return OneItem
     */
    public function add_meta_ex($name1, $value1, $name2 = '', $value2 = '') {
        $meta = $this->create_item($this, '', '', 'meta');
        $meta->single = true;
        $meta->params->add($name1, $value1);
        $meta->params->add($name2, $value2);
        $this->rebuild_indexes($meta);
        return $this->add_item($meta);
    }

    /**
     * Set item parameter
     * 
     * @param string $name
     * @param string $value
     */
    public function set_param($name, $value) {
        $this->params->add($name, $value);
    }

    /**
     * Get item parameter
     * 
     * @param string $name
     */
    public function get_param($name) {
        return $this->params->get_value($name);
    }

    /**
     * Set item parent
     * 
     * @param OneItem $item
     */
    public function set_parent($item) {
        $this->parent = $item;
    }

    /**
     * Setup item id
     * @since 1.0
     * 
     * @param string $id
     */
    public function set_id($id) {
        $this->id = $id;
    }

    /**
     * Set item classes
     * @since 1.0
     * 
     * @param string $classes
     */
    public function set_classes($classes) {
        $this->classes = $classes;
    }

    /**
     * Setup HTML tag
     * @since 1.0
     * 
     * @param string $tag
     */
    public function set_tag($tag) {
        $this->tag = $tag;
    }

    /**
     * Setup value
     * 
     * @param string $value
     */
    public function set_value($value) {
        $this->value = $value;
    }

    /**
     * Setup onclick function as parameter
     * 
     * @param string $value
     */
    public function set_onclick($value) {
        $this->params->add('onclick', $value);
    }

    /**
     * Setup title as parameter
     * @since 1.0
     * 
     * @param string $title
     */
    public function set_title($title) {
        $this->params->add('title', $title);
    }

    /**
     * Get total parameters count
     * @since 1.0
     * 
     * @return integer
     */
    public function total_count() {
        return count($this->items);
    }

    /**
     * Check for empty tags and set default DIV (if empty)
     * @since 1.0
     */
    public function check_default_tag() {
        if ($this->tag == '') {
            $this->tag = 'div';
        }
    }

    /**
     * Universal function for new item creation
     * 
     * @param \OneItem $parent
     * @param string $id
     * @param string $classes
     * @param string $tag
     * @param string $value
     * @param string $title
     * @param string $url
     * @return \OneItem
     */
    public function create_item($parent = null, $id = '', $classes = '', $tag = '', $value = '', $title = '', $url = '') {
        if (OI_DEBUG) {
            oi_log('create_item: ' . "id: $id, classes: $classes, tag: $tag, value: $value");
        }
        return new OneItem($parent, $id, $classes, $tag, $value, $title, $url);
    }

    // OK
    public function by_tag($tag, $collection = null, $level = null) {
        if (!is_object($collection)) {
            $collection = $this->create_item('', COLLECTION_ITEM_ID, '');
        } // result item
        if (!is_object($level)) {
            $level = $this;
        }

        if ($level->total_count() <= 0) {
            return $collection;
        }
        foreach ($level->items as $curr_item) {
            if ($curr_item->compare_tag($tag)) {
                $collection->add_item($curr_item);
            } else {
                if ($curr_item->total_count() > 0) {
                    $this->by_tag($tag, $collection, $curr_item);
                }
            }
        }
        return $collection;
    }

    // ok
    public function by_class($classes, $collection = null, $level = null) {
        if (!is_object($collection)) {
            $collection = $this->create_item('', COLLECTION_ITEM_ID, '');
        } // result item
        if (!is_object($level)) {
            $level = $this;
        }
        if ($level->total_count() <= 0) {
            return $collection;
        }
        foreach ($level->items as $curr_item) {

            if ($curr_item->class_exists($classes)) {
                $collection->add_item($curr_item);
            } else {
                if ($curr_item->total_count() > 0) {
                    $this->by_class($classes, $collection, $curr_item);
                }
            }
        }
        return $collection;
    }

    // ok
    public function by_id($id, $collection = null, $level = null) {
        if (!is_object($collection)) {
            $collection = $this->create_item('', COLLECTION_ITEM_ID, '');
        } // result item
        if (!is_object($level)) {
            $level = $this;
        }

        if ($level->total_count() <= 0) {
            return $collection;
        }
        foreach ($level->items as $curr_item) {
            if ($curr_item->id == $id) {
                $collection->add_item($curr_item);
            } else {
                if ($curr_item->total_count() > 0) {
                    $this->by_id($id, $collection, $curr_item);
                }
            }
        }
        return $collection;
    }

    // ok
    public function by_param($param_name, $param_value, $collection = null, $level = null) {
        if (!is_object($collection)) {
            $collection = $this->create_item('', COLLECTION_ITEM_ID, '');
        } // result item
        if (!is_object($level)) {
            $level = $this;
        }

        if ($level->total_count() <= 0) {
            return $collection;
        }
        foreach ($level->items as $curr_item) {
            if ($curr_item->params->param_exists($param_name)) {
                if ($curr_item->params->get_value($param_name) == $param_value) {
                    $collection->add_item($curr_item);
                }
            } else {
                if ($curr_item->total_count() > 0) {
                    $this->by_param($param_name, $param_value, $collection, $curr_item);
                }
            }
        }
        return $collection;
    }

    // ok
    public function select($mask) {
        $collection = null;
        if ($mask == '') {
            return $this->create_item('', COLLECTION_ITEM_ID, '');
        }
        $arr_mask = str_split($mask);

        if ($arr_mask[0] == '#') {
            $arr_mask[0] = '';
            $mask = trim(implode('', $arr_mask));
            $collection = $this->by_id($mask);
        } else
        if ($arr_mask[0] == '.') {
            $arr_mask[0] = '';
            $mask = trim(implode('', $arr_mask));
            $collection = $this->by_class($mask);
        } else {
            $collection = $this->by_tag($mask);
        }

        if ($collection->total_count() == 1) {
            return $collection->items[0];
        } else {
            return $collection;
        }
    }

    public function is_single_tag($tag) {
        $tag = strtolower($tag);
        return ($tag == 'img') || ($tag == 'hr') || ($tag == 'br');
    }

    /**
     * Get item debug information
     * 
     * @param bool $id
     * @param bool $classes
     * @param bool $value
     * @param bool $url
     * @return string
     */
    public function get_debug_info($id = true, $classes = true, $value = false, $url = false) {
        return $this->tag .
                oi_text_by_bool($id, ' [id:' . $this->id . ']') .
                oi_text_by_bool($classes, ' [classes:' . $this->classes . ']') .
                oi_text_by_bool($value, ' [value:' . $this->value . ']') .
                oi_text_by_bool($url, ' [url:' . $this->url . ']');
    }

    /**
     * Rebuild indexes and level for inserted or updated items
     * @since 1.0
     * 
     * @param OneItem $item
     */
    public function rebuild_indexes($item) {
        $item->level = $this->level + 1;
        if ($item->total_count() > 0) {
            foreach ($item->items as $curr_item) {
                $curr_item->level = $item->level + 1;
                if ($curr_item->total_count() > 0) {
                    $this->rebuild_indexes($curr_item);
                }
            }
        }
    }

}

/**
 * Internal class for managing of tag attributes and parameters
 * 
 * @package OneItem
 * @version 1.0
 */
class OneParams {

    public $items = array();

    /**
     * Contructor for Parameters class
     */
    function __construct() {
        
    }

    /**
     * Add new parameter
     * @since 1.0
     * 
     * @param string $name
     * @param string $value
     */
    public function add($name, $value, $skip_if_empty = true) {
        $value = trim($value);
        $name = trim($name);
        if ($name == '') {
            return false;
        }
        $param_index = $this->get_index_by_name($name);

        if ($value != '') {
            // add: param = value
            if ($param_index <> -1) {
                $this->items[$param_index]['value'] = $value;
            } else {
                $this->_add_param($name, $value);
            }
        } else {
            // add empty parameter
            if (!$skip_if_empty) {
                if ($param_index <> -1) {
                    $this->items[$param_index]['value'] = $value;
                } else {

                    $this->_add_param($name, '');
                }
            }
        }
    }

    private function _add_param($pname, $pvalue) {
        $item = array();
        $item['name'] = $pname;
        $item['value'] = $pvalue;
        $this->items[] = $item;
    }

    public function param_exists($name) {
        return $this->get_index_by_name($name) <> -1;
    }

    public function get_index_by_name($name) {
        $res = -1;
        foreach ($this->items as $key => $item) {
            if ($item['name'] == $name) {
                return $key;
            }
        }
        return $res;
    }

    /**
     * Get parameters as line
     * @since 1.0
     * 
     * @return string
     */
    public function as_list() {
        if ($this->total_count() > 0) {
            $html = ' ';
            foreach ($this->items as $key => $value) {
                $html .= $this->as_param($key) . ' ';
                //echo ' [ ' . $this->items[$key]['value'] . '" ]';
            }
            return $html;
        } else {
            return '';
        }
    }

    public function as_param($index) {
        $param = $this->items[$index];
        if ($param['value'] == '') {
            return $param['name'];
        } else {
            return $param['name'] . '="' . $param['value'] . '"';
        }
    }

    /**
     * Get parameter value
     * @since 1.0
     * 
     * @param string $param_name
     * @return string
     */
    public function get_value($param_name) {
        $res = '';
        foreach ($this->items as $item) {

            if ($param_name == $item['name']) {
                return $item['value'];
            }
        }
        return $res;
    }

    public function remove($name) {
        $ind = $this->get_index_by_name($name);
        if ($ind >= 0) {
            unset($this->items[$ind]);
        }
    }

    /**
     * Clear all parameters
     * @since 1.0
     */
    public function clear() {
        $this->items = '';
        $this->items = array();
    }

    public function assign($params) {
        $this->clear();
        foreach ($params->items as $item) {
            $this->add($item['name'], $item['value']);
        }
    }

    /**
     * Get total parameters count
     * @since 1.0
     * 
     * @return integer
     */
    public function total_count() {
        return count($this->items);
    }

}
