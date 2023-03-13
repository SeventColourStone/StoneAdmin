<?php


use Phinx\Seed\AbstractSeed;

class SystemUserRoleInit extends AbstractSeed
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
      'user_id' =>        '16128263327904' ,
      'role_id' =>        '16128263327905' ,
          ],
       [
      'user_id' =>        '370872318156603392' ,
      'role_id' =>        '370870465071153152' ,
          ],

        ];

        $system_user_role = $this->table('system_user_role');
        $system_user_role->insert($data)
            ->save();

        // empty the table
        // $system_user_role->truncate();
    }
}
