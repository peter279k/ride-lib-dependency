# Ride: Dependency Injection Library

Dependency injection library of the PHP Ride framework.

This module can create objects and invoke callbacks with dynamic argument injection.

## Code Sample

Check this code sample to see the possibilities of this library:

    <?php
    
    use ride\library\dependency\Dependency;
    use ride\library\dependency\DependencyCall;
    use ride\library\dependency\DependencyCallArgument;
    use ride\library\dependency\DependencyContainer;
    use ride\library\dependency\DependencyInjector;

    // Your dependencies are stored in a dependency container. For the sake of
    // explaining this library, let's initialize it manually. This should be done
    // through configuration to get real benefit of this library
    $dependencyContainer = new DependencyContainer();
    
    // most generic definition of a dependency is a class, however this is 
    // obsulete since the dependency injector attempts to create undefined 
    // dependencies as well
    $dependency = new Dependency('some\Class');

    // give your dependency an id to retrieve a specific instance of an interface
    $dependency = new Dependency('some\Class', 'id1');

    // some\Class implements some interfaces
    $dependency->addInterface('some\Interface');
    $dependency->addInterface('some\OtherInterface');

    // now add it to the container
    $dependencyContainer->addDependency($dependency);
    
    // lets create another another, this time with a constructor and some action
    
    // define the constructor call
    $call = new DependencyCall('__construct');
    $call->addArgument(new DependencyCallArgument('name', 'dependency', array('interface' => 'some\Interface', 'id' => 'id1)));
    
    // define the dependency and add some calls
    $dependency = new Dependency('another\Class', 'id2');
    $dependency->addCall($call);
    $dependency->addCall(new DependencyCall('doSomething'));
    $dependency->addInterface('some\Interface');

    // add it to the container
    $dependencyContainer->addDependency($dependency);

    // define a factory for a dependency
    $constructCall = new DependencyConstructCall('some\Factory', 'methodOnFactory');
    
    $dependency = new Dependency($constructCall, 'id');
    $dependency->addCall(new DependencyCall('doSomething'));
    $dependency->addInterface('some\Interface');
    
    $dependencyContainer->addDependency($dependency);

    // Your dependency container gets filled up with this kind of definitions.
    // Once setup, you are ready to get your instances.

    // First we need to create the dependency injector itself.
    $dependencyInjector = new DependencyInjector($dependencyContainer);

    // Let's get an instance, the thing you are most likely to do...
    $instance = $dependencyInjector->get('some\Interface'); // another\Class since it's last defined
    $instance = $dependencyInjector->get('some\Interface', 'id1'); // some\Class
    try {
        $instance = $dependencyInjector->get('third\Class');
        // your instance if the third\Class can be created with the available dependencies
    } catch (Exception $e) {
        // when it's a class, is not constructable or when some required arguments could
        // not be injected
    }
    
    // You can also invoke callbacks
    $callback = 'function';
    $callback = array('some\Static', 'call');
    $callback = array(new some\Class(), 'call');
    $arguments = array('name' => $value); // arguments you know/want, the rest will be injected
    $returnValue = $dependencyInjector->invoke($callback, $arguments);
    