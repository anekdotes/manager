# Anekdotes Manager

[![Latest Stable Version](http://poser.pugx.org/anekdotes/manager/v)](https://packagist.org/packages/anekdotes/manager)
[![Total Downloads](http://poser.pugx.org/anekdotes/manager/downloads)](https://packagist.org/packages/anekdotes/manager)
[![License](http://poser.pugx.org/anekdotes/manager/license)](https://packagist.org/packages/anekdotes/manager)
[![PHP Version Require](http://poser.pugx.org/anekdotes/manager/require/php)](https://packagist.org/packages/anekdotes/manager)

A library that provides an easy way to upload files to the server simply by using configurations.

## Installation

Install via composer into your project:

```
composer require anekdotes/manager
```

## Usage

Use the class where ever you need it:

```php
use Anekdotes\Manager\Manager;
```

**See configuration section below for more information**

With **Laravel**:
```php
$manager = new Manager(array);
$manager->manage(Input::file('nameOfInput'));
```

Without **Laravel**:
```php
$manager = new Manager(array);
$manager->manage($_FILES['nameOfInput']);
```

Catch errors/exceptions:
```php
try {
    $manager = new Manager(array);
    $manager->manage($_FILES['nameOfInput']);
} catch (\Exception $e) {
    //do something
}
```

## Configurations

### Instantiation

You can pass a config array to the Manager's constructor. 

```php
    new Manager(array());
```

Available properties:

**prefix** : type _string_. Path's prefix to upload file **(default: `/public`)**
```php
    'prefix' => '/public',
```
**path** : type _string_. Path to upload file **(default: `/uploads`)**
```php
    'path' => 'uploads/',
```
**exts** : type _array_. Array of all supported file extensions **(default: `jpg, jpeg, png`)**
```php
    'exts' => array('jpeg', 'jpg', 'png),
```
**weight** : type _integer_. Maximum file size in bytes **(default: `3 mbs`)**
```php
    'weight' => 3000000,
```
**size** : type _array_. Array containing as many sizes as needed  **(default: `null`)**
```php
    'size' => array(
    ),
```

Put together:

```php
    $manager = new Manager(array(
        'prefix' => '/public',
        'path' => 'uploads/',
        'exts' => array('jpeg', 'jpg', 'png),
        'weight' => 3000000
    ));
```

### Manage method's callback

You may pass a closure to the **manage** method to execute special code before uploading file such as creating an entry in the database or simply changing name.

```php
    $manager->manage($_FILES['nameOfInput'], function($fi){
        //do something fancy
        return "potato.jpg";
    });
```

With the above example and default configurations, the new file will be located at **/public/uploads** as **potato.jpg**.
