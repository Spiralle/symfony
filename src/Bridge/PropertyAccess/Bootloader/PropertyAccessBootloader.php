<?php declare(strict_types = 1);

namespace Spiralle\Symfony\Bridge\PropertyAccess\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\EnvironmentInterface;
use Spiralle\Symfony\Common\SymfonyCacheAdapterFactory;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class PropertyAccessBootloader extends Bootloader
{

	public function defineSingletons(): array
	{
		return [
			PropertyAccessorInterface::class => [self::class, 'initPropertyAccessor'],
		];
	}

	private function initPropertyAccessor(
		SymfonyCacheAdapterFactory $cacheFactory,
		EnvironmentInterface $env,
	): PropertyAccessorInterface
	{
		$cache = $cacheFactory->create(
			$env->get('PROPERTY_ACCESS_CACHE_STORAGE', 'memory'),
			'symfony/propertyAccess',
		);

		return new PropertyAccessor(
			PropertyAccessor::DISALLOW_MAGIC_METHODS,
			PropertyAccessor::THROW_ON_INVALID_INDEX | PropertyAccessor::THROW_ON_INVALID_PROPERTY_PATH,
			$cache,
		);
	}

}
