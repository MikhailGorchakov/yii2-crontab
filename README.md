# Yii2-crontab
Extension for Yii2, which is helpful, when you don't want to edit your server cron configuration every time you add or delete a new cron task. Also can help with task distribution on multi-server systems.

### Installing

Composer installation

```
composer require djiney/yii2-crontab dev-master
```

### Configuration

At first, run migration using custom path

```
php yii migrate/up --migrationPath=@vendor/djiney/yii2-crontab/src/migrations/
```

Then, add controller class for your console application config:

```
'controllerMap' => [
   ...
   'task-manager' => 'djiney\crontab\controllers\TaskManagerController'
   ...
],
```

And specify configuration component:
```
'components' => [
   ...
   'cron' => [
      'class' => 'djiney\crontab\components\Configuration',
      'tasks' => require(__DIR__ . '/cron.php')
   ],
   ...
],
```

Each task has it's own configuration template:
```
'cron/balance' => [
   'command' => '/var/www/html/yii cron/balance',
   'log' => '/var/www/html/balance.log',
   'interval' => [
      'minute'   => '*/8',
      'hour'     => '11',
      'day'      => '12-14',
      'month'    => '1,3',
      'week_day' => '5'
    ],
],
```

And finally, don't forget to add cron-task to your server (you can add this command to all of your servers, tasks won't be duplicated)
```
* * * * * sudo -u nginx /var/www/html/yii task-manager/create-tasks >> /var/www/html/console/runtime/logs/create-tasks.log
* * * * * sudo -u nginx /var/www/html/yii task-manager/start-tasks >> /var/www/html/console/runtime/logs/start-tasks.log

```
!! Space in the end of cron file is important !!


