DB
========
[![Total Downloads](https://poser.pugx.org/attwframework/db/downloads.png)](https://packagist.org/packages/attwframework/db) [![Latest Unstable Version](https://poser.pugx.org/attwframework/db/v/unstable.png)](https://packagist.org/packages/attwframework/db) [![License](https://poser.pugx.org/attwframework/db/license.png)](https://packagist.org/packages/attwframework/db)

Database component of [AttwFramework](https://github.com/attwframework/framework).

##Composer
###Download
```json
{
    "require": {
        "attwframework/db": "dev-master"
    }
}
```
##Support
###Database
* [MySQL](http://www.mysql.com/)

###Driver
* [PDO](http://www.php.net/manual/en/book.pdo.php)
* [MySQLi](https://php.net/manual/pt_BR/book.mysqli.php)

##How to use
###Connecting with a relational database
Create an object to be the connector, passing the instance of connector configurations in the constructor
```php
use Attw\Db\Connection\PDOConnector;

$connector = new PDOConnector('mysql:host=localhost;dbname=test', 'root', 'pass');
```
Connections collection
```php
use Attw\Db\Connection\Connector\Config\MySQLConnectionConfig;
use Attw\Db\Connection\PDOConnector;
use Attw\Db\Collection as DBCollection;

$connector = new PDOConnector('mysql:host=localhost;dbname=test', 'root', 'pass');

$connections = DBCollection::getInstance();
$connections->add('ConnectionName', $connector);
```
The default methods of a connector are:
* ```Attw\Db\Connection\ConnectorInterface::getConnection()``` Returns the connection
* ```Attw\Db\Connection\ConnectorInterface::getDriver()``` Returns the connected driver
* ```Attw\Db\Connection\ConnectorInterface::query($sql)``` Execute a SQL query
* ```Attw\Db\Connection\ConnectorInterface::prepare($sql)``` Prepares a statement for execution and returns a statement object
* ```Attw\Db\Connection\ConnectorInterface::exec($sql)``` Execute an SQL statement and return the number of affected rows
* ```Attw\Db\Connection\ConnectorInterface::lastInsertId([ string $name ])``` Returns the ID of the last inserted row or sequence value
* ```Attw\Db\Connection\ConnectorInterface::getStatement( $sql )``` Returns statement class

###Storage methods
Methods useds for interaction with database

Crud:
####Inserting something
```php
use Attw\Db\Connection\PDOConnector;
use Attw\Db\Storage\Storage;
use Attw\Db\Sql\MySQL;

$connector = new PDOConnector('mysql:host=localhost;dbname=test', 'root', 'pass');
$storage = new Storage($connector, new MySQL());
$storage->create('users', array(
    'name' => 'Gabriel Jacinto', 
    'email' => 'gamjj74@hotmail.com',
    'age' => 15,
    'gender' => 'male'
))->execute();
```
####Updating something
```php
$storage->update('users', array('name' => 'Gabriel Jacinto'), array('id' => 17))
    ->execute();
```
####Deleting something
```php
$storage->remove('users', array('id' => 17))
    ->execute();
```
####Selecting somethig
```php
$stmt = $storage->select('users')->where(array('id' => 17));
$stmt->execute();
print_r($stmt->fetch());
```
####Counting results
```php
$stmt = $storage->select('users')->where(array('id' => 17));
$stmt->execute();
$total = $stmt->rowCount();
```
####Executing a SQL query
```php
$connector->query("DELETE FROM `users` WHERE `id` = '20'");
```
####Prepared statements
```php
use Attw\Db\Connection\PDOConnector;
use Attw\Db\Storage\Storage;
use Attw\Db\Sql\MySQL;

$connector = new PDOConnector('mysql:host=localhost;dbname=test', 'root', 'pass');

$stmt = $connector->getStatement("SELECT * FROM `users` WHERE `id` = ?");
$stmt->execute(array(20));
$userData = $stmt->fetch();
```
###Entities
Entities are classes that represent tables.
To create an entity, create a class that extends ```Attw\Db\Storage\Entity\AbstractEntity```.
```php
use Attw\Db\Storage\Entity\AbstractEntity;

class User extends AbstractEntity
{
    protected $_table = 'users';
    
    /**
     * @key PRIMARY KEY
    */
    protected $id;
    
    protected $username;
    protected $email;
}
```
####Inserting and updataing
If you indicate the primary key and it exists, an update will be make.

**Insert**
```php
use Attw\Db\Connection\PDOConnector;
use Attw\Db\Storage\Storage;
use Attw\Db\Sql\MySQL;
use Attw\Db\Entity\EntityStorage;

$connector = new PDOConnector('mysql:host=localhost;dbname=test', 'root', 'pass');
$storage = new Storage($connector, new MySQL());
$entityStorage = new EntityStorage($storage);

$user = new User();
$user->username = 'Gabriel';
$user->email = 'gamjj74@hotmail.com';

$entityStorage->persist($user);
```
**Update**
```php
$user = new User();
$user->id = 17; //primary key indicates which registry
$user->email = 'other@email.com';

$entityStorage->persist($user);
```
####Removing
```php
$user = new User();
$user->id = 17;

$entityStorage->remove($user);
```
####Fetching
**Fetch one**
```php
$user = new User();
$user->id = 21;

$data = $entityStorage->fetch($user);
```
**Fetch all**
```php
$user = new User();

$data = $entityStorage->fetchAll($user);
```
