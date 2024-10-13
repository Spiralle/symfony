<?php declare(strict_types = 1);

namespace Spiralle\Symfony\Common;

use InvalidArgumentException;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Core\Attribute\Singleton;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache as SymfonyCache;

#[Singleton]
final class SymfonyCacheAdapterFactory
{

	public function __construct(
		private DirectoriesInterface $directories,
	)
	{
	}

	public function create(string $type, string $namespace): AdapterInterface
	{
		return match ($type) {
			'file' => new SymfonyCache\Adapter\FilesystemAdapter('cache', directory: $this->directories->get('cache') . $namespace),
			'memory' => new SymfonyCache\Adapter\ArrayAdapter(),
			'none' => new SymfonyCache\Adapter\NullAdapter(),
			default => throw new InvalidArgumentException(sprintf('Unknown cache storage "%s"', $type)),
		};
	}

}
