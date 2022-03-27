<?php


namespace nyuwa\enum;


use MabeEnum\Enum;

/**
 * @method static GenCodeEnum ApiController()
 * @method static GenCodeEnum ApiService()
 * @method static GenCodeEnum ApiMapper()
 * @method static GenCodeEnum ApiValidate()
 * @method static GenCodeEnum ApiModel()
 * @method static GenCodeEnum UiController()
 * @method static GenCodeEnum UiIndexPage()
 * @method static GenCodeEnum UiAddPage()
 * @method static GenCodeEnum UiEditPage()
 */
class GenCodeEnum extends Enum
{

    const ApiController = 'api_controller';
    const ApiService = 'api_service';
    const ApiMapper = 'api_mapper';
    const ApiValidate = 'api_validate';
    const ApiModel = 'api_model';

    const UiController = 'ui_controller';
    const UiIndexPage = 'ui_index_page';
    const UiAddPage = 'ui_add_page';
    const UiEditPage = 'ui_edit_page';





}