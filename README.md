# Omnipay: Dummy

**Sogenactif 1.0 driver for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Sogenactif 1.0 support for Omnipay.

[Sogenactif](https://documentation.sogenactif.com/) is a payment gateway created and maintained
by the Société Générale since 1995.
This driver is meant to use with the version 1.0 of their service, which has been superseded
since then by version 2, based on Worldline Sips. You only want to use the present driver if
you're currently stuck with Sogenactif 1.0.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply require `league/omnipay` and `omnipay/dummy` with Composer:

```
composer require league/omnipay meteoconcept/omnipay-sogenactif1
```

## Basic Usage

The following gateways are provided by this package:

* Sogenactif1

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you have any problem with this specific driver, please contact us at contact@meteo-concept.fr.
