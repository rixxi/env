<?php

namespace Rixxi\Env\DI;

use Nette;
use Nette\Utils\Validators;


class EnvExtension extends Nette\DI\CompilerExtension
{

	const PARAMETER_CONTAINER = 'env';

	public $defaults = array(
		'parameters' => array(),
		'whitelist' => TRUE,
	);


	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		Validators::assert($config['parameters'], 'array');
		$parameters = array();
		foreach ($config['parameters'] as $name => $value) {
			self::assertEnvName($name);
			self::assertEnvValue($value, $name);
			$parameters[$name] = $value;
		}
		Validators::assert($config['whitelist'], 'bool');

		if (strpos(ini_get('variables_order'), 'E') === TRUE) {
			foreach ($_ENV as $name => $value) {
				if ($config['whitelist'] && !isset($parameters[$name])) {
					continue;
				}
				self::assertEnvName($name);
				self::assertEnvValue($value, $name);
				$parameters[$name] = $value;
			}

		} else {
			foreach (array_keys($config['parameters']) as $name) {
				if (($value = getenv($name)) !== FALSE) {
					$parameters[$name] = $value;
				}
			}
		}

		$this->getContainerBuilder()->parameters += array(self::PARAMETER_CONTAINER => $parameters);
	}


	private static function assertEnvName($key)
	{
		if (!is_string($key)) {
			throw new \Exception("Only string ENV names are supported.");
		}
	}


	private static function assertEnvValue($value, $name)
	{
		if (!is_scalar($value) && $value !== NULL) {
			throw new \Exception("Only scalar and null values are supported (ENV $name).");
		}
	}

}
