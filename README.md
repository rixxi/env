ENV variables in Nette DI configuration (`nette`, `php` and `parameters` sections excluded).

# Install

`composer require rixxi/env:~1.0` or `@dev` if you are mad.

Put extension at least before other extensions that use ENV variables in configuration.

# Configure

By default only defined parameters are registered.
If you want all environment variables be available then turn `whitelist: off`..


## Example of kdyby/doctrine configuration via ENV variables

```neon
env:
	parameters:
		DB_HOST: 127.0.0.1
		DB_NAME:
		DB_DRIVER: pgsql
		DB_USERNAME:
		DB_PASSWORD:

doctrine:
	host: %env.DB_HOST%
	dbname: %env.DB_NAME%
	driver: pdo_%env.DB_DRIVER%
	username: %env.DB_USERNAME%
	password: %env.DB_PASSWORD%
	// ... other stuff

extensions:
	env: Rixxi\Env\DI\EnvExtension
```

When running application at least DB_NAME ENV must be set. You can do that directly via shell

```sh
DB_NAME=test php www/index.php
```

or in config of apache or fpm pool.

# Limitations

Only other 3rd party extension configuration is supported that means no variables in `nette` or `php`.
Sections `parameters` and `services` are not supported. It is not possible at this time due to limitations of [nette/di](https://github.com/nette/di).
Only **string names** and **scalar and null** values are supported. It is on purpose.
