

[GridView]

Class GridView
@package kak\widgets\grid
@property $behaviors;

#### *public* sortArrow@var bool show direct sorted column up/down

#### *public* contentOptions@var array

#### *public* dataColumnClass@var string

#### *public* layout@var string

#### *public* caption@var string the caption of the grid table
@see captionOptions

#### *public* captionOptions@var array the HTML attributes for the caption element.
@see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
@see caption

#### *public* tableOptions@var array the HTML attributes for the grid table element.
@see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.

#### *public* options@var array the HTML attributes for the container tag of the grid view.
The "tag" element specifies the tag name of the container element and defaults to "div".
@see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.

#### *public* headerRowOptions@var array the HTML attributes for the table header row.
@see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.

#### *public* footerRowOptions@var array the HTML attributes for the table footer row.
@see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.

#### *public* rowOptions@var array|Closure the HTML attributes for the table body rows. This can be either an array
specifying the common HTML attributes for all body rows, or an anonymous function that
returns an array of the HTML attributes. The anonymous function will be called once for every
data model returned by [[dataProvider]]. It should have the following signature:

```php
function ($model, $key, $index, $grid)
```

- `$model`: the current data model being rendered
- `$key`: the key value associated with the current data model
- `$index`: the zero-based index of the data model in the model array returned by [[dataProvider]]
- `$grid`: the GridView object

@see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.

#### *public* beforeRow@var Closure an anonymous function that is called once BEFORE rendering each data model.
It should have the similar signature as [[rowOptions]]. The return result of the function
will be rendered directly.

#### *public* afterRow@var Closure an anonymous function that is called once AFTER rendering each data model.
It should have the similar signature as [[rowOptions]]. The return result of the function
will be rendered directly.

#### *public* showHeader@var bool whether to show the header section of the grid table.

#### *public* showFooter@var bool whether to show the footer section of the grid table.

#### *public* showOnEmpty@var bool whether to show the grid view if [[dataProvider]] returns no data.

#### *public* formatter@var array|Formatter the formatter used to format model attribute values into displayable texts.
This can be either an instance of [[Formatter]] or an configuration array for creating the [[Formatter]]
instance. If this property is not set, the "formatter" application component will be used.

#### *public* columns@var array grid column configuration. Each array element represents the configuration
for one particular grid column. For example,

```php
[
    ['class' => SerialColumn::className()],
    [
        'class' => DataColumn::className(), // this line is optional
        'attribute' => 'name',
        'format' => 'text',
        'label' => 'Name',
    ],
    ['class' => CheckboxColumn::className()],
]
```

If a column is of class [[DataColumn]], the "class" element can be omitted.

As a shortcut format, a string may be used to specify the configuration of a data column
which only contains [[DataColumn::attribute|attribute]], [[DataColumn::format|format]],
and/or [[DataColumn::label|label]] options: `"attribute:format:label"`.
For example, the above "name" column can also be specified as: `"name:text:Name"`.
Both "format" and "label" are optional. They will take default values if absent.

Using the shortcut format the configuration for columns in simple cases would look like this:

```php
[
    'id',
    'amount:currency:Total Amount',
    'created_at:datetime',
]
```

When using a [[dataProvider]] with active records, you can also display values from related records,
e.g. the `name` attribute of the `author` relation:

```php
// shortcut syntax
'author.name',
// full syntax
[
    'attribute' => 'author.name',
    // ...
]
```

#### *public* emptyCell@var string the HTML display when the content of a cell is empty.
This property is used to render cells that have no defined content,
e.g. empty footer or filter cells.

Note that this is not used by the [[DataColumn]] if a data item is `null`. In that case
the [[\yii\i18n\Formatter::nullDisplay|nullDisplay]] property of the [[formatter]] will
be used to indicate an empty data value.

#### *public* filterModel@var \yii\base\Model the model that keeps the user-entered filter data. When this property is set,
the grid view will enable column-based filtering. Each data column by default will display a text field
at the top that users can fill in to filter the data.

Note that in order to show an input field for filtering, a column must have its [[DataColumn::attribute]]
property set and the attribute should be active in the current scenario of $filterModel or have
[[DataColumn::filter]] set as the HTML code for the input field.

When this property is not set (null) the filtering feature is disabled.

#### *public* filterUrl@var string|array the URL for returning the filtering result. [[Url::to()]] will be called to
normalize the URL. If not set, the current controller action will be used.
When the user makes change to any filter input, the current filtering inputs will be appended
as GET parameters to this URL.

#### *public* filterSelector@var string additional jQuery selector for selecting filter input fields

#### *public* filterPosition@var string whether the filters should be displayed in the grid view. Valid values include:

- [[FILTER_POS_HEADER]]: the filters will be displayed on top of each column's header cell.
- [[FILTER_POS_BODY]]: the filters will be displayed right below each column's header cell.
- [[FILTER_POS_FOOTER]]: the filters will be displayed below each column's footer cell.

#### *public* filterRowOptions@var array the HTML attributes for the filter row element.
@see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.

#### *public* filterErrorSummaryOptions@var array the options for rendering the filter error summary.
Please refer to [[Html::errorSummary()]] for more details about how to specify the options.
@see renderErrors()

#### *public* filterErrorOptions@var array the options for rendering every filter error message.
This is mainly used by [[Html::error()]] when rendering an error message next to every filter input field.

#### *public* dataProvider@var \yii\data\DataProviderInterface the data provider for the view. This property is required.

#### *public* pager@var array the configuration for the pager widget. By default, [[LinkPager]] will be
used to render the pager. You can use a different widget class by configuring the "class" element.
Note that the widget must support the `pagination` property which will be populated with the
[[\yii\data\BaseDataProvider::pagination|pagination]] value of the [[dataProvider]].

#### *public* sorter@var array the configuration for the sorter widget. By default, [[LinkSorter]] will be
used to render the sorter. You can use a different widget class by configuring the "class" element.
Note that the widget must support the `sort` property which will be populated with the
[[\yii\data\BaseDataProvider::sort|sort]] value of the [[dataProvider]].

#### *public* summary@var string the HTML content to be displayed as the summary of the list view.
If you do not want to show the summary, you may set it with an empty string.

The following tokens will be replaced with the corresponding values:

- `{begin}`: the starting row number (1-based) currently being displayed
- `{end}`: the ending row number (1-based) currently being displayed
- `{count}`: the number of rows currently being displayed
- `{totalCount}`: the total number of rows available
- `{page}`: the page number (1-based) current being displayed
- `{pageCount}`: the number of pages available

#### *public* summaryOptions@var array the HTML attributes for the summary of the list view.
The "tag" element specifies the tag name of the summary element and defaults to "div".
@see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.

#### *public* emptyText@var string|false the HTML content to be displayed when [[dataProvider]] does not have any data.
When this is set to `false` no extra HTML content will be generated.
The default value is the text "No results found." which will be translated to the current application language.
@see showOnEmpty
@see emptyTextOptions

#### *public* emptyTextOptions@var array the HTML attributes for the emptyText of the list view.
The "tag" element specifies the tag name of the emptyText element and defaults to "div".
@see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.

#### *public static* counter@var int a counter used to generate [[id]] for widgets.
@internal

#### *public static* autoIdPrefix@var string the prefix to the automatically generated widget IDs.
@see getId()

#### *public static* stack@var Widget[] the widgets that are currently being rendered (not ended). This property
is maintained by [[begin()]] and [[end()]] methods.
@internal
#### setBehaviors() 
Provide the option to be able to set behaviors on GridView configuration.
 * `param array` $behaviors

#### behaviors() 
get behaviors
@return array

#### init() 
Initializes the object.
This method is invoked at the end of the constructor after the object is initialized with the
given configuration.

#### initContainerOptions() 
#### run() 
Executes the widget.
@return string the result of widget execution to be outputted.

#### renderSection() 
@inheritdoc

#### footerSummary() 
 * `param` $data
 * `param` $attribute
 * `param` $type
@return number|string
@deprecated remove next version and remove constant SUMMARY_*

#### renderErrors() 
Renders validator errors of filter model.
@return string the rendering result.

#### renderItems() 
Renders the data models for the grid view.

#### renderCaption() 
Renders the caption element.
@return bool|string the rendered caption element or `false` if no caption element should be rendered.

#### renderColumnGroup() 
Renders the column group HTML.
@return bool|string the column group HTML or `false` if no column group should be rendered.

#### renderTableHeader() 
Renders the table header.
@return string the rendering result.

#### renderTableFooter() 
Renders the table footer.
@return string the rendering result.

#### renderFilters() 
Renders the filter.
@return string the rendering result.

#### renderTableBody() 
Renders the table body.
@return string the rendering result.

#### renderTableRow() 
Renders a table row with the given data model and key.
 * `param mixed` $model the data model to be rendered
 * `param mixed` $key the key associated with the data model
 * `param int` $index the zero-based index of the data model among the model array returned by [[dataProvider]].
@return string the rendering result

#### renderEmpty() 
Renders the HTML content indicating that the list view has no data.
@return string the rendering result
@see emptyText

#### renderSummary() 
Renders the summary text.

#### renderPager() 
Renders the pager.
@return string the rendering result

#### renderSorter() 
Renders the sorter.
@return string the rendering result

#### begin() 
Begins a widget.
This method creates an instance of the calling class. It will apply the configuration
to the created instance. A matching [[end()]] call should be called later.
As some widgets may use output buffering, the [[end()]] call should be made in the same view
to avoid breaking the nesting of output buffers.
 * `param array` $config name-value pairs that will be used to initialize the object properties
@return static the newly created widget instance
@see end()

#### end() 
Ends a widget.
Note that the rendering result of the widget is directly echoed out.
@return static the widget instance that is ended.
@throws InvalidCallException if [[begin()]] and [[end()]] calls are not properly nested
@see begin()

#### widget() 
Creates a widget instance and runs it.
The widget rendering result is returned by this method.
 * `param array` $config name-value pairs that will be used to initialize the object properties
@return string the rendering result of the widget.
@throws \Exception

#### getId() 
Returns the ID of the widget.
 * `param bool` $autoGenerate whether to generate an ID if it is not set previously
@return string ID of the widget.

#### setId() 
Sets the ID of the widget.
 * `param string` $value id of the widget.

#### getView() 
Returns the view object that can be used to render views or view files.
The [[render()]] and [[renderFile()]] methods will use
this view object to implement the actual view rendering.
If not set, it will default to the "view" application component.
@return \yii\web\View the view object that can be used to render views or view files.

#### setView() 
Sets the view object to be used by this widget.
 * `param View` $view the view object that can be used to render views or view files.

#### render() 
Renders a view.

The view to be rendered can be specified in one of the following formats:

- [path alias](guide:concept-aliases) (e.g. "@app/views/site/index");
- absolute path within application (e.g. "//site/index"): the view name starts with double slashes.
  The actual view file will be looked for under the [[Application::viewPath|view path]] of the application.
- absolute path within module (e.g. "/site/index"): the view name starts with a single slash.
  The actual view file will be looked for under the [[Module::viewPath|view path]] of the currently
  active module.
- relative path (e.g. "index"): the actual view file will be looked for under [[viewPath]].

If the view name does not contain a file extension, it will use the default one `.php`.

 * `param string` $view the view name.
 * `param array` $params the parameters (name-value pairs) that should be made available in the view.
@return string the rendering result.
@throws InvalidParamException if the view file does not exist.

#### renderFile() 
Renders a view file.
 * `param string` $file the view file to be rendered. This can be either a file path or a [path alias](guide:concept-aliases).
 * `param array` $params the parameters (name-value pairs) that should be made available in the view.
@return string the rendering result.
@throws InvalidParamException if the view file does not exist.

#### getViewPath() 
Returns the directory containing the view files for this widget.
The default implementation returns the 'views' subdirectory under the directory containing the widget class file.
@return string the directory containing the view files for this widget.

#### beforeRun() 
This method is invoked right before the widget is executed.

The method will trigger the [[EVENT_BEFORE_RUN]] event. The return value of the method
will determine whether the widget should continue to run.

When overriding this method, make sure you call the parent implementation like the following:

```php
public function beforeRun()
{
    if (!parent::beforeRun()) {
        return false;
    }

    // your custom code here

    return true; // or false to not run the widget
}
```

@return bool whether the widget should continue to be executed.
@since 2.0.11

#### afterRun() 
This method is invoked right after a widget is executed.

The method will trigger the [[EVENT_AFTER_RUN]] event. The return value of the method
will be used as the widget return value.

If you override this method, your code should look like the following:

```php
public function afterRun($result)
{
    $result = parent::afterRun($result);
    // your custom code here
    return $result;
}
```

 * `param mixed` $result the widget return result.
@return mixed the processed widget result.
@since 2.0.11

#### __get() 
Returns the value of a component property.

This method will check in the following order and act accordingly:

 - a property defined by a getter: return the getter result
 - a property of a behavior: return the behavior property value

Do not call this method directly as it is a PHP magic method that
will be implicitly called when executing `$value = $component->property;`.
 * `param string` $name the property name
@return mixed the property value or the value of a behavior's property
@throws UnknownPropertyException if the property is not defined
@throws InvalidCallException if the property is write-only.
@see __set()

#### __set() 
Sets the value of a component property.

This method will check in the following order and act accordingly:

 - a property defined by a setter: set the property value
 - an event in the format of "on xyz": attach the handler to the event "xyz"
 - a behavior in the format of "as xyz": attach the behavior named as "xyz"
 - a property of a behavior: set the behavior property value

Do not call this method directly as it is a PHP magic method that
will be implicitly called when executing `$component->property = $value;`.
 * `param string` $name the property name or the event name
 * `param mixed` $value the property value
@throws UnknownPropertyException if the property is not defined
@throws InvalidCallException if the property is read-only.
@see __get()

#### __isset() 
Checks if a property is set, i.e. defined and not null.

This method will check in the following order and act accordingly:

 - a property defined by a setter: return whether the property is set
 - a property of a behavior: return whether the property is set
 - return `false` for non existing properties

Do not call this method directly as it is a PHP magic method that
will be implicitly called when executing `isset($component->property)`.
 * `param string` $name the property name or the event name
@return bool whether the named property is set
@see http://php.net/manual/en/function.isset.php

#### __unset() 
Sets a component property to be null.

This method will check in the following order and act accordingly:

 - a property defined by a setter: set the property value to be null
 - a property of a behavior: set the property value to be null

Do not call this method directly as it is a PHP magic method that
will be implicitly called when executing `unset($component->property)`.
 * `param string` $name the property name
@throws InvalidCallException if the property is read only.
@see http://php.net/manual/en/function.unset.php

#### __call() 
Calls the named method which is not a class method.

This method will check if any attached behavior has
the named method and will execute it if available.

Do not call this method directly as it is a PHP magic method that
will be implicitly called when an unknown method is being invoked.
 * `param string` $name the method name
 * `param array` $params method parameters
@return mixed the method return value
@throws UnknownMethodException when calling unknown method

#### __clone() 
This method is called after the object is created by cloning an existing one.
It removes all behaviors because they are attached to the old object.

#### hasProperty() 
Returns a value indicating whether a property is defined for this component.

A property is defined if:

- the class has a getter or setter method associated with the specified name
  (in this case, property name is case-insensitive);
- the class has a member variable with the specified name (when `$checkVars` is true);
- an attached behavior has a property of the given name (when `$checkBehaviors` is true).

 * `param string` $name the property name
 * `param bool` $checkVars whether to treat member variables as properties
 * `param bool` $checkBehaviors whether to treat behaviors' properties as properties of this component
@return bool whether the property is defined
@see canGetProperty()
@see canSetProperty()

#### canGetProperty() 
Returns a value indicating whether a property can be read.

A property can be read if:

- the class has a getter method associated with the specified name
  (in this case, property name is case-insensitive);
- the class has a member variable with the specified name (when `$checkVars` is true);
- an attached behavior has a readable property of the given name (when `$checkBehaviors` is true).

 * `param string` $name the property name
 * `param bool` $checkVars whether to treat member variables as properties
 * `param bool` $checkBehaviors whether to treat behaviors' properties as properties of this component
@return bool whether the property can be read
@see canSetProperty()

#### canSetProperty() 
Returns a value indicating whether a property can be set.

A property can be written if:

- the class has a setter method associated with the specified name
  (in this case, property name is case-insensitive);
- the class has a member variable with the specified name (when `$checkVars` is true);
- an attached behavior has a writable property of the given name (when `$checkBehaviors` is true).

 * `param string` $name the property name
 * `param bool` $checkVars whether to treat member variables as properties
 * `param bool` $checkBehaviors whether to treat behaviors' properties as properties of this component
@return bool whether the property can be written
@see canGetProperty()

#### hasMethod() 
Returns a value indicating whether a method is defined.

A method is defined if:

- the class has a method with the specified name
- an attached behavior has a method with the given name (when `$checkBehaviors` is true).

 * `param string` $name the property name
 * `param bool` $checkBehaviors whether to treat behaviors' methods as methods of this component
@return bool whether the method is defined

#### hasEventHandlers() 
Returns a value indicating whether there is any handler attached to the named event.
 * `param string` $name the event name
@return bool whether there is any handler attached to the event.

#### on() 
Attaches an event handler to an event.

The event handler must be a valid PHP callback. The following are
some examples:

```
function ($event) { ... }         // anonymous function
[$object, 'handleClick']          // $object->handleClick()
['Page', 'handleClick']           // Page::handleClick()
'handleClick'                     // global function handleClick()
```

The event handler must be defined with the following signature,

```
function ($event)
```

where `$event` is an [[Event]] object which includes parameters associated with the event.

 * `param string` $name the event name
 * `param callable` $handler the event handler
 * `param mixed` $data the data to be passed to the event handler when the event is triggered.
When the event handler is invoked, this data can be accessed via [[Event::data]].
 * `param bool` $append whether to append new event handler to the end of the existing
handler list. If false, the new handler will be inserted at the beginning of the existing
handler list.
@see off()

#### off() 
Detaches an existing event handler from this component.
This method is the opposite of [[on()]].
 * `param string` $name event name
 * `param callable` $handler the event handler to be removed.
If it is null, all handlers attached to the named event will be removed.
@return bool if a handler is found and detached
@see on()

#### trigger() 
Triggers an event.
This method represents the happening of an event. It invokes
all attached handlers for the event including class-level handlers.
 * `param string` $name the event name
 * `param Event` $event the event parameter. If not set, a default [[Event]] object will be created.

#### getBehavior() 
Returns the named behavior object.
 * `param string` $name the behavior name
@return null|Behavior the behavior object, or null if the behavior does not exist

#### getBehaviors() 
Returns all behaviors attached to this component.
@return Behavior[] list of behaviors attached to this component

#### attachBehavior() 
Attaches a behavior to this component.
This method will create the behavior object based on the given
configuration. After that, the behavior object will be attached to
this component by calling the [[Behavior::attach()]] method.
 * `param string` $name the name of the behavior.
 * `param string|array|Behavior` $behavior the behavior configuration. This can be one of the following:

 - a [[Behavior]] object
 - a string specifying the behavior class
 - an object configuration array that will be passed to [[Yii::createObject()]] to create the behavior object.

@return Behavior the behavior object
@see detachBehavior()

#### attachBehaviors() 
Attaches a list of behaviors to the component.
Each behavior is indexed by its name and should be a [[Behavior]] object,
a string specifying the behavior class, or an configuration array for creating the behavior.
 * `param array` $behaviors list of behaviors to be attached to the component
@see attachBehavior()

#### detachBehavior() 
Detaches a behavior from the component.
The behavior's [[Behavior::detach()]] method will be invoked.
 * `param string` $name the behavior's name.
@return null|Behavior the detached behavior. Null if the behavior does not exist.

#### detachBehaviors() 
Detaches all behaviors from the component.

#### ensureBehaviors() 
Makes sure that the behaviors declared in [[behaviors()]] are attached to this component.

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


