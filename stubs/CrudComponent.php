<?php

declare(strict_types=1);

namespace Cake\Event {
    /**
     * @template TSubject
     */
    interface EventInterface {
        /**
         * Returns the subject of this event.
         *
         * @return TSubject
         */
        public function getSubject();
    }

    interface EventListenerInterface {}

    class Event implements EventInterface {}
}

namespace Cake\Controller {

    use Cake\Event\EventListenerInterface;

    class Component implements EventListenerInterface {}
}

namespace Crud\Controller\Component {
    use Cake\Controller\Component;

    class CrudComponent extends Component {
        /**
         * Attaches an event listener function to the controller for Crud Events.
         *
         * @param string|array $events Name of the Crud Event you want to attach to controller.
         * @param callable(\Cake\Event\EventInterface<\Crud\Event\Subject>):void $callback Callable method or closure to be executed on event.
         * @param array $options Used to set the `priority` and `passParams` flags to the listener.
         * @return void
         */
        public function on($events, callable $callback, array $options = []): void {}
    }
}

namespace Crud\Event {
    class Subject {}
}
