<?php


use Phinx\Seed\AbstractSeed;

class SystemUserPostInit extends AbstractSeed
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
      'user_id' =>        '370872318156603392' ,
      'post_id' =>        '370035832435769344' ,
          ],

        ];

        $system_user_post = $this->table('system_user_post');
        $system_user_post->insert($data)
            ->save();

        // empty the table
        // $system_user_post->truncate();
    }
}
