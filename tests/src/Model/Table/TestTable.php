<?php
namespace Raul338\Phpstan\Tests\Model\Table;

use Cake\ORM\Table;

/**
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TestTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->addBehavior('Timestamp');
    }
}
