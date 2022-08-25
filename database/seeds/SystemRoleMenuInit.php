<?php


use Phinx\Seed\AbstractSeed;

class SystemRoleMenuInit extends AbstractSeed
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
      'role_id' =>        '370870465071153152' ,
      'menu_id' =>        '370408699560198144' ,
          ],
       [
      'role_id' =>        '370870465071153152' ,
      'menu_id' =>        '370408699560198145' ,
          ],
       [
      'role_id' =>        '370870465071153152' ,
      'menu_id' =>        '370408699560198146' ,
          ],
       [
      'role_id' =>        '370870465071153152' ,
      'menu_id' =>        '370408699560198147' ,
          ],
       [
      'role_id' =>        '370870465071153152' ,
      'menu_id' =>        '370408699560198148' ,
          ],
       [
      'role_id' =>        '370870465071153152' ,
      'menu_id' =>        '370408699560198149' ,
          ],
       [
      'role_id' =>        '370870465071153152' ,
      'menu_id' =>        '370408699560198150' ,
          ],
       [
      'role_id' =>        '370870465071153152' ,
      'menu_id' =>        '370408699560198151' ,
          ],
       [
      'role_id' =>        '370870465071153152' ,
      'menu_id' =>        '370408699560198152' ,
          ],
       [
      'role_id' =>        '370870465071153152' ,
      'menu_id' =>        '370408699560198153' ,
          ],
       [
      'role_id' =>        '370870465071153152' ,
      'menu_id' =>        '370408699560198154' ,
          ],
       [
      'role_id' =>        '370870465071153152' ,
      'menu_id' =>        '370455469044531200' ,
          ],
       [
      'role_id' =>        '370870465071153152' ,
      'menu_id' =>        '370461502013964288' ,
          ],

        ];

        $system_role_menu = $this->table('system_role_menu');
        $system_role_menu->insert($data)
            ->save();

        // empty the table
        // $system_role_menu->truncate();
    }
}
