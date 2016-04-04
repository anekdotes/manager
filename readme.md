# Anekdotes Manager

[![Latest Stable Version](https://poser.pugx.org/anekdotes/manager/v/stable)](https://packagist.org/packages/anekdotes/manager)
[![Build Status](https://travis-ci.org/anekdotes/manager.svg?branch=master)](https://travis-ci.org/anekdotes/manager)
[![codecov.io](https://codecov.io/github/anekdotes/manager/coverage.svg?branch=master)](https://codecov.io/github/anekdotes/manager?branch=master)
[![StyleCI](https://styleci.io/repos/54115628/shield?style=flat)](https://styleci.io/repos/54115628)
[![License](https://poser.pugx.org/anekdotes/manager/license)](https://packagist.org/packages/anekdotes/manager)
[![Total Downloads](https://poser.pugx.org/anekdotes/manager/downloads)](https://packagist.org/packages/anekdotes/manager)

A library that provides an easy way to upload files to the server simply by using configurations.

## Installation

Install via composer into your project:

```
composer require anekdotes/manager
```

## Usage

Use the class where ever you need it:

```
use Anekdotes\Manager\Manager;
```

**See configuration section below for more information**

With **Laravel**:
```
$manager = new Manager(array);
$manager->manage(Input::file('nameOfInput'));
```

Without **Laravel**:
```
$manager = new Manager(array);
$manager->manage($_FILES['nameOfInput']);
```

Catch errors/exceptions:
```
try {
    $manager = new Manager(array);
    $manager->manage($_FILES['nameOfInput']);
} catch (\Exception $e) {
    //do something
}
```

## Configurations

### Init

Must be of type array

```
    new Manager(array());
```

Available properties:

**prefix** : type _string_. Path's prefix to upload file **(default: `/public`)**
```
    'prefix' => '/public',
```
**path** : type _string_. Path to upload file **(default: `/uploads`)**
```
    'path' => 'uploads/',
```
**exts** : type _array_. Array of all supported file extensions **(default: `jpg, jpeg, png`)**
```   
    'exts' => array('jpeg', 'jpg', 'png),
```
**weight** : type _integer_. Maximum file size in bytes **(default: `3 mbs`)**
```
    'weight' => 3000000,
```
**size** : type _array_. Array containing as many sizes as needed  **(default: `null`)**
```
    'size' => array(
    ),
```

Put together:

```
    $manager = new Manager(array(
        'prefix' => '/public',
        'path' => 'uploads/',
        'exts' => array('jpeg', 'jpg', 'png),
        'weight' => 3000000
    ));
```

### Manage method's callback

You may pass a closure to the **manage** method to execute special code before uploading file such as creating an entry in the database or simply changing name.

```
    $manager->manage($_FILES['nameOfInput'], function($fi){
        //do something fancy
        return "potato.jpg";
    });
```

With the above example and default configurations, the new file will be located at **/public/uploads** as **potato.jpg**.
