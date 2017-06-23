<?php

use Phinx\Migration\AbstractMigration;

class LessonUserMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('lesson_user', ['id' => false, 'primary_key' => ['user_id', 'lesson_id']]);
        $table->addColumn('user_id', 'integer', ['signed' => false, 'limit' => 10, 'comment' => '用户ID'])
            ->addColumn('lesson_id', 'integer', ['signed' => false, 'limit' => 10, 'comment' => '课程ID'])
            ->addForeignKey('user_id', 'users', 'id', ['constraint'=>'lesson_user_user_id_foreign', 'delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
            ->addForeignKey('lesson_id', 'lessons', 'id', ['constraint'=>'lesson_user_lesson_id_foreign', 'delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
            ->create();
    }
}
