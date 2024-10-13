<?php declare(strict_types = 1);

namespace Spiralle\Symfony\Bridge\Serializer\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\EnvironmentInterface;
use Spiralle\Symfony\Common\SymfonyCacheAdapterFactory;
use Symfony\Component\Serializer\Mapping\Factory\CacheClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;

final class SerializerBootloader extends Bootloader
{

	public function defineSingletons(): array
	{
		return [
			ClassMetadataFactoryInterface::class => [self::class, 'initClassMetadataFactory'],
		];
	}

	private function initClassMetadataFactory(
		LoaderInterface $loader,
		SymfonyCacheAdapterFactory $cacheFactory,
		EnvironmentInterface $env,
	): CacheClassMetadataFactory
	{
		$cache = $cacheFactory->create(
			$env->get('SYMFONY_SERIALIZER_CACHE_STORAGE', 'memory'),
			'symfony/serializer.classMetadata'
		);

		return new CacheClassMetadataFactory(
			new ClassMetadataFactory($loader),
			$cache,
		);
	}

}
