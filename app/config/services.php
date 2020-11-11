<?php
use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher;


$di = new FactoryDefault();
/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di['db'] = function (){

    $config = $this->getConfig();
    try {
        $connection = new DbAdapter(array(
            "host" => $config->database->host,
            "username" => $config->database->username,
            "password" => $config->database->password,
            "dbname" => $config->database->dbname,
            "charset" => $config->database->charset,
            "port" => $config->database->port,
            "persistent" => true,
            "options"    => [\PDO::ATTR_PERSISTENT => 1]
        ));

        $connection->connect();
        return $connection;
    } catch (\PDOException $e) {
        throw $e;
    }
};


$di->setShared(
    'response',
    function () {
        $response = new \Phalcon\Http\Response();
        $response->setContentType('application/json', 'utf-8');
  
        return $response;
    }
  );


