<?php


use Phinx\Seed\AbstractSeed;

class SystemRoleDeptInit extends AbstractSeed
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
      'dept_id' =>        '16384726752416' ,
          ],
       [
      'role_id' =>        '370870465071153152' ,
      'dept_id' =>        '370870281687793664' ,
          ],

        ];

        $system_role_dept = $this->table('system_role_dept');
        $system_role_dept->insert($data)
            ->save();

        // empty the table
        // $system_role_dept->truncate();
    }
}
