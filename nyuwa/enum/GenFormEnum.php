<?php


namespace nyuwa\enum;


use MabeEnum\Enum;

/**
 * @method static GenFormEnum TEXT()
 * @method static GenFormEnum TEXTAREA()
 * @method static GenFormEnum SELECT()
 * @method static GenFormEnum RADIO_XMSELECT()
 * @method static GenFormEnum CHECKBOX_XMSELECT()
 * @method static GenFormEnum RADIO_TABLE_SELECT()
 * @method static GenFormEnum CHECKBOX_TABLE_SELECT()
 */
class GenFormEnum extends Enum
{

    const TEXT = 'text'; //文本
    const TEXTAREA = 'textarea'; //多行文本域
    const SELECT = 'select'; //选择器，暂不考虑
    const RADIO_XMSELECT = 'radio_xmselect'; //单选xm select，字典使用
    const CHECKBOX_XMSELECT = 'checkbox_xmselect';//多选xm select，字典使用
    const RADIO_TABLE_SELECT = 'radio_table_select';//单选表格选择器,用于数据表关联
    const CHECKBOX_TABLE_SELECT = 'checkbox_table_select';//多选表格选择器,用于数据表关联

    





}