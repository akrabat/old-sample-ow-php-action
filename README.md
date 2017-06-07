# Simple OpenWhisk PHP action

This is an example of a simple OpenWhisk PHP action.

It creates a [docker action][1], which has a `router.php` to respond to the 
OpenWhisk invocation API calls and an `action.php` which contains the PHP function
to execute.

The `action.php` must have a `main()` function with the following signature:

```php
    function main(array $args) : array
```

The `$args` contains the parameters passed to the action as an associative array and you must return an associative array which is then returned to the caller.

## Install

* Ensure you have installed Docker and have logged in using `docker login`.
* Ensure you have set up OpenWhisk and have a working `wsk` command line client.
* Copy `local.env.dist` to `local.env` and change `DOCKER_USER` to your username.
* Run `./build.sh` to build the docker container, create the action and run it.

## Run

To just run the action:

    wsk action invoke -br hellophp --param name Everyone



[1]: https://github.com/apache/incubator-openwhisk/blob/master/docs/actions.md#creating-docker-actions
