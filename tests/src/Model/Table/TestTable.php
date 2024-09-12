<?php
namespace Raul338\Phpstan\Tests\Model\Table;

use Cake\ORM\Table;

/**
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\TranslateBehavior
 */
class TestTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->addBehavior('Timestamp');
        $this->addBehavior('Translate', ['fields' => ['title', 'body']]);
    }
}
