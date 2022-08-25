<?php


use Phinx\Seed\AbstractSeed;

class SystemOperLogInit extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $data = [
                   [
      'id' =>        '16653885734048' ,
      'username' =>       'superAdmin',
      'method' =>       'POST',
      'router' =>       '/setting/code/loadTable',
      'service_name' =>       '装载数据表',
      'ip' =>       '127.0.0.1',
      'ip_location' =>       '未知',
      'request_data' =>       '{"names":[{"name":"platform_info","comment":"平台表"}]}',
      'response_code' =>       '200',
      'response_data' =>       '{"success":true,"message":"请求成功","code":200,"data":[]}',
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-05-23 11:49:26' ,
      'updated_at' =>        '2022-05-23 11:49:26' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],
       [
      'id' =>        '16654031506080' ,
      'username' =>       'superAdmin',
      'method' =>       'POST',
      'router' =>       '/setting/code/loadTable',
      'service_name' =>       '装载数据表',
      'ip' =>       '127.0.0.1',
      'ip_location' =>       '未知',
      'request_data' =>       '{"names":[{"name":"order_info","comment":"订单表"},{"name":"order_goods","comment":"订单商品表"}]}',
      'response_code' =>       '200',
      'response_data' =>       '{"success":true,"message":"请求成功","code":200,"data":[]}',
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-05-23 11:54:11' ,
      'updated_at' =>        '2022-05-23 11:54:11' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],
       [
      'id' =>        '16654033928352' ,
      'username' =>       'superAdmin',
      'method' =>       'POST',
      'router' =>       '/setting/code/loadTable',
      'service_name' =>       '装载数据表',
      'ip' =>       '127.0.0.1',
      'ip_location' =>       '未知',
      'request_data' =>       '{"names":[{"name":"order_info","comment":"订单表"},{"name":"order_goods","comment":"订单商品表"}]}',
      'response_code' =>       '200',
      'response_data' =>       '{"success":true,"message":"请求成功","code":200,"data":[]}',
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-05-23 11:54:16' ,
      'updated_at' =>        '2022-05-23 11:54:16' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],
       [
      'id' =>        '16654049649824' ,
      'username' =>       'superAdmin',
      'method' =>       'DELETE',
      'router' =>       '/setting/code/delete/16654029833376,16654032332448',
      'service_name' =>       '删除业务表',
      'ip' =>       '127.0.0.1',
      'ip_location' =>       '未知',
      'request_data' =>       '[]',
      'response_code' =>       '200',
      'response_data' =>       '{"success":true,"message":"请求成功","code":200,"data":[]}',
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-05-23 11:54:46' ,
      'updated_at' =>        '2022-05-23 11:54:46' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],
       [
      'id' =>        '16660139393184' ,
      'username' =>       'superAdmin',
      'method' =>       'PUT',
      'router' =>       '/setting/module/save',
      'service_name' =>       '新增模块',
      'ip' =>       '127.0.0.1',
      'ip_location' =>       '未知',
      'request_data' =>       '{"name":"authorize","label":"授权","version":"1.0","description":"1"}',
      'response_code' =>       '200',
      'response_data' =>       '{"success":true,"message":"请求成功","code":200,"data":[]}',
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-05-23 15:13:00' ,
      'updated_at' =>        '2022-05-23 15:13:00' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],
       [
      'id' =>        '16662600870560' ,
      'username' =>       'superAdmin',
      'method' =>       'POST',
      'router' =>       '/setting/code/loadTable',
      'service_name' =>       '装载数据表',
      'ip' =>       '127.0.0.1',
      'ip_location' =>       '未知',
      'request_data' =>       '{"names":[{"name":"shop_info","comment":"店铺表"}]}',
      'response_code' =>       '200',
      'response_data' =>       '{"success":true,"message":"请求成功","code":200,"data":[]}',
      'created_by' =>        '16128263327904' ,
      'updated_by' =>        '16128263327904' ,
      'created_at' =>        '2022-05-23 16:33:08' ,
      'updated_at' =>        '2022-05-23 16:33:08' ,
      'deleted_at' =>        null,
      'remark' =>       '',
          ],

        ];

        $system_oper_log = $this->table('system_oper_log');
        $system_oper_log->insert($data)
            ->save();

        // empty the table
        // $system_oper_log->truncate();
    }
}
