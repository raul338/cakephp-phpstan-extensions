# CakePHP phpstan extensions

![CI](https://github.com/raul338/cakephp-phpstan-extensions/workflows/CI/badge.svg?branch=master)

Services to aid phpstan analysis on CakePHP projects

| Version | CakePHP Version | phpstan version |
| ------- | --------------- | --------------- |
| 2.x | 3.x | 0.12 |
| 1.x | 3.x | 0.11 |

## Install
```sh
composer require --dev raul338/cakephp-phpstan-extensions
```

This extensions load automatically if you install [phpstan/extension-installer](https://github.com/phpstan/extension-installer)
```sh
composer require --dev phpstan/extension-installer
```

or if you don't use phpstan/extension-installer, include in your phpstan.neon

```
includes:
	- vendor/raul338/cakephp-phpstan-extensions/src/extension.neon
```

## How does this help me
This extension includes rules to analyze the following snippets wihout using var annotations

### Dynamic Finders
[Link to the Book](https://book.cakephp.org/3/en/orm/retrieving-data-and-resultsets.html#dynamic-finders)
```php
$query = $this->Users->findAllByUsername('joebob');
```

### Detect methods from behaviors
[Link to the Book](https://book.cakephp.org/3/en/orm/behaviors.html#defining-mixin-methods)

This only works if the method name is not modified in implementedMethods. Otherwise the analysis may be wrong, or
you'll have to decorate your code with dockblocks


```php
/**
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
public class UsersTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->addBehavior('Timestamp');
    }
}
// somewhere else - phpstan will know its the Timestamp touch method
$this->Users->touch($user);
```

### Load Model in controllers
[Link to the Book](https://book.cakephp.org/3/en/controllers.html#loading-additional-models)
```php
// phpstan will know $users is App\Model\UsersTable
$users = $this->loadModel('Users');
```

### Query functions getters/setters
Without this extensions phpstan will complain that contain returns `\Cake\ORM\Query|array` instad of just a `Query`
The same with
* formatResults  
* join

```php
$query = $this->Users->find('all')->contain(['Books']);
```

### FriendsOfCake/crud Actions & Listeners
This is for use with [FriendsOfCake/crud](https://github.com/FriendsOfCake/crud)

```php
public function add()
{
    // phpstan will know action() is a AddAction instead of BaseAction
    $this->Crud->action()->saveOptions([]);

    // phpstan will know it is a \Crud\Listener\RelatedModelsListener
    $this->Crud->listener('relatedModels')->relatedModels(true);
}
```

It will only work if you use one of the default action
* add  
* delete  
* edit  
* index  
* view  

And in spanish names:
* agregar  
* editar  
* borrar  
* ver  

If you use an action with crud you'll have to do something like this:
```php
public function custom()
{
    $this->Crud->mapAction('custom', 'Crud.Index');
    /** @var \Crud\Action\IndexAction */
    $action = $this->Crud->action();
}
```

### FriendsOfCake/crud Event Subject
Tell phpstan that if is an event inside a Controller, the subject will probably be a CrudSubject

You'll have to add to you `phpstan.neon`
```neon
parameters:
    universalObjectCratesClasses:
        - Crud\Event\Subject
```

Example:
```php
$this->Crud->on('beforePaginate', function (Event $event) {
    $query = $event->getSubject()->query;
    $query->where([ /** ... */]);
});
```

## License

MIT
