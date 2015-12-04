# Royal Mail PHP Shipping Method Library

![image](http://up.nicksays.co.uk/200k1j35411o2i0Y0N3S/RoyalMail.png)

This repository contains the source code for the Meanbee Royal Mail PHP Library. It takes the country code, package value, and package weight and then outputs an array of objects containing the available shipping methods.

## Using the Library

To use the library, call the **getMethods** method with your country code ([in the ISO 3166 format](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)), package value, and package weight. 

#### Example Usage
```PHP
$calculateMethodClass = new CalculateMethodClass();

$calculateMethodClass->getMethods('GB', 20, 0.050);
```

This will return an array of objects where each object contains the shipping method name, minimum weight, maximum weight, price of the method, maximum insurance value, and clean shipping method.

## Unit Testing

This program is automatically unit tested with phpunit and travis.