<?php

use Phinx\Migration\AbstractMigration;

class UsersMigration extends AbstractMigration
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
        $table = $this->table('users', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', ['signed' => false, 'identity' => true,  'limit' => 10])
            ->addColumn('nick', 'string', ['limit' => 20, 'comment' => '昵称'])
            ->addColumn('name', 'string', ['limit' => 20, 'null' => true, 'comment' => '用户名'])
            ->addColumn('phone', 'string', ['limit' => 20, 'null' => true, 'comment' => '电话号码'])
            ->addColumn('email', 'string', ['limit' => 60, 'null' => true, 'comment' => '邮箱地址'])
            ->addColumn('password', 'string', ['limit' => 255, 'comment' => '密码'])
            ->addColumn('avatar_path', 'string', ['limit' => 255, 'null' => true, 'comment' => '头像地址'])
            ->addColumn('token', 'text', ['null' => true, 'comment' => '令牌'])
            ->addColumn('is_active', 'boolean', ['default' => true, 'comment' => '是否激活'])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['name'], ['unique' => true])
            ->addIndex(['phone'], ['unique' => true])
            ->addIndex(['email'], ['unique' => true])
            ->create();
    }
}
