

[MenuColumnsBehavior]

Class MenuColumnsBehavior
@package kak\widgets\grid\behaviors
  * @property $owner \kak\widgets\grid\GridView

```php
 'behaviors' => [
     [
         'class' => \kak\widgets\grid\behaviors\ToolBarBehavior::className(),
         'toolbar' => [
             [
             '   content' => '{MenuColumns}' // attach behavior MenuColumnsBehavior
             ]
         ]
     ],[
     'class' => \kak\widgets\grid\behaviors\MenuColumnsBehavior::className(),
     ]
 ],
```

#### *public* label
#### *public* owner@var Component|null the owner of this behavior
#### renderMenuColumns() 
render the output

#### run() 
#### events() 
Declares event handlers for the [[owner]]'s events.

Child classes may override this method to declare what PHP callbacks should
be attached to the events of the [[owner]] component.

The callbacks will be attached to the [[owner]]'s events when the behavior is
attached to the owner; and they will be detached from the events when
the behavior is detached from the component.

The callbacks can be any of the following:

- method in this behavior: `'handleClick'`, equivalent to `[$this, 'handleClick']`
- object method: `[$object, 'handleClick']`
- static method: `['Page', 'handleClick']`
- anonymous function: `function ($event) { ... }`

The following is an example:

```php
[
    Model::EVENT_BEFORE_VALIDATE => 'myBeforeValidate',
    Model::EVENT_AFTER_VALIDATE => 'myAfterValidate',
]
```

@return array events (array keys) and the corresponding event handler methods (array values).

#### attach() 
Attaches the behavior object to the component.
The default implementation will set the [[owner]] property
and attach event handlers as declared in [[events]].
Make sure you call the parent implementation if you override this method.
 * `param Component` $owner the component that this behavior is to be attached to.

#### detach() 
Detaches the behavior object from the component.
The default implementation will unset the [[owner]] property
and detach event handlers declared in [[events]].
Make sure you call the parent implementation if you override this method.

#### className() 
Returns the fully qualified name of this class.
@return string the fully qualified name of this class.

#### __construct() 
Constructor.

The default implementation does two things:

- Initializes the object with the given configuration `$config`.
- Call [[init()]].

If this method is overridden in a child class, it is recommended that

- the last parameter of the constructor is a configuration array, like `$config` here.
- call the parent implementation at the end of the constructor.

 * `param array` $config name-value pairs that will be used to initialize the object properties

#### init() 
Initializes the object.
This method is invoked at the end of the constructor after the object is initialized with the
given configuration.

#### __get() 
Returns the value of an object property.

Do not call this method directly as it is a PHP magic method that
will be implicitly called when executing `$value = $object->property;`.
 * `param string` $name the property name
@return mixed the property value
@throws UnknownPropertyException if the property is not defined
@throws InvalidCallException if the property is write-only
@see __set()

#### __set() 
Sets value of an object property.

Do not call this method directly as it is a PHP magic method that
will be implicitly called when executing `$object->property = $value;`.
 * `param string` $name the property name or the event name
 * `param mixed` $value the property value
@throws UnknownPropertyException if the property is not defined
@throws InvalidCallException if the property is read-only
@see __get()

#### __isset() 
Checks if a property is set, i.e. defined and not null.

Do not call this method directly as it is a PHP magic method that
will be implicitly called when executing `isset($object->property)`.

Note that if the property is not defined, false will be returned.
 * `param string` $name the property name or the event name
@return bool whether the named property is set (not null).
@see http://php.net/manual/en/function.isset.php

#### __unset() 
Sets an object property to null.

Do not call this method directly as it is a PHP magic method that
will be implicitly called when executing `unset($object->property)`.

Note that if the property is not defined, this method will do nothing.
If the property is read-only, it will throw an exception.
 * `param string` $name the property name
@throws InvalidCallException if the property is read only.
@see http://php.net/manual/en/function.unset.php

#### __call() 
Calls the named method which is not a class method.

Do not call this method directly as it is a PHP magic method that
will be implicitly called when an unknown method is being invoked.
 * `param string` $name the method name
 * `param array` $params method parameters
@throws UnknownMethodException when calling unknown method
@return mixed the method return value

#### hasProperty() 
Returns a value indicating whether a property is defined.

A property is defined if:

- the class has a getter or setter method associated with the specified name
  (in this case, property name is case-insensitive);
- the class has a member variable with the specified name (when `$checkVars` is true);

 * `param string` $name the property name
 * `param bool` $checkVars whether to treat member variables as properties
@return bool whether the property is defined
@see canGetProperty()
@see canSetProperty()

#### canGetProperty() 
Returns a value indicating whether a property can be read.

A property is readable if:

- the class has a getter method associated with the specified name
  (in this case, property name is case-insensitive);
- the class has a member variable with the specified name (when `$checkVars` is true);

 * `param string` $name the property name
 * `param bool` $checkVars whether to treat member variables as properties
@return bool whether the property can be read
@see canSetProperty()

#### canSetProperty() 
Returns a value indicating whether a property can be set.

A property is writable if:

- the class has a setter method associated with the specified name
  (in this case, property name is case-insensitive);
- the class has a member variable with the specified name (when `$checkVars` is true);

 * `param string` $name the property name
 * `param bool` $checkVars whether to treat member variables as properties
@return bool whether the property can be written
@see canGetProperty()

#### hasMethod() 
Returns a value indicating whether a method is defined.

The default implementation is a call to php function `method_exists()`.
You may override this method when you implemented the php magic method `__call()`.
 * `param string` $name the method name
@return bool whether the method is defined


