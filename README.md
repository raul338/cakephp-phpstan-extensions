# CakePHP phpstan extensions

Services to aid phpstan analysis on CakePHP projects

| Version | CakePHP Version | phpstan version |
| ------- | --------------- | --------------- |
| 2.x | 3.x | 0.12 |
| 1.x | 3.x | 0.11 |

## Install
```sh
composer require --dev raul338/cakephp-phpstan-extensions
```

This extensions load automatically if you install [phpstan/extension-installer](https://github.com/phpstan/extension-installer)
```sh
composer require --dev phpstan/extension-installer
```

or if you don't use phpstan/extension-installer, include in your phpstan.neon

```
includes:
	- vendor/raul338/cakephp-phpstan-extensions/src/extension.neon
```

## License

MIT
