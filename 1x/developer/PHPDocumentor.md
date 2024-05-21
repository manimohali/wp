Yes, there are Composer packages and tools available that can help you automatically generate or manage PHPDoc comments, including adding missing tags like `@package`. One such tool is the PHPDoc generator tool, which can help with generating documentation blocks.

### PHPDocumentor

One popular tool for generating PHP documentation is [PHPDocumentor](https://www.phpdoc.org/). It can generate detailed documentation for your PHP projects and ensure that your code follows proper documentation standards.

To use PHPDocumentor with Composer:

1. **Install PHPDocumentor**:

   Run the following command to install PHPDocumentor as a dev dependency in your project:

   ```bash
   composer require --dev phpdocumentor/phpdocumentor
   ```

2. **Generate Documentation**:

   You can run PHPDocumentor to generate documentation, which includes parsing your code and generating PHPDoc blocks:

   ```bash
   vendor/bin/phpdoc
   ```

### PHP_CodeSniffer with PHPCompatibility

Another useful tool is PHP_CodeSniffer along with PHPCompatibility. While PHP_CodeSniffer itself does not automatically add missing PHPDoc blocks, it helps enforce coding standards.

1. **Install PHP_CodeSniffer and PHPCompatibility**:

   Run the following commands to install PHP_CodeSniffer and PHPCompatibility:

   ```bash
   composer require --dev squizlabs/php_codesniffer
   composer require --dev wimg/php-compatibility
   ```

2. **Configure PHP_CodeSniffer**:

   Create a `phpcs.xml` file to define your coding standards, including WordPress or custom standards.

   ```xml
   <?xml version="1.0"?>
   <ruleset name="MyProject">
       <description>PHP CodeSniffer configuration for MyProject</description>
       <rule ref="WordPress"/>
       <rule ref="PHPCompatibility"/>
   </ruleset>
   ```

3. **Run PHP_CodeSniffer**:

   Execute PHP_CodeSniffer to check your code against the defined standards:

   ```bash
   vendor/bin/phpcs
   ```

### PHP CS Fixer

[PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) is another tool that can automatically fix PHP coding standards issues, including some documentation issues.

1. **Install PHP CS Fixer**:

   Run the following command to install PHP CS Fixer:

   ```bash
   composer require --dev friendsofphp/php-cs-fixer
   ```

2. **Create a Configuration File**:

   Create a `.php_cs.dist` or `.php-cs-fixer.php` configuration file to define the rules and fixers.

   ```php
   <?php

   $finder = PhpCsFixer\Finder::create()
       ->in(__DIR__);

   return PhpCsFixer\Config::create()
       ->setRules([
           '@PSR2' => true,
           'phpdoc_add_missing_param_annotation' => true,
           'phpdoc_order' => true,
       ])
       ->setFinder($finder);
   ```

3. **Run PHP CS Fixer**:

   Execute PHP CS Fixer to automatically fix your code:

   ```bash
   vendor/bin/php-cs-fixer fix
   ```

### Summary

While PHP_CodeSniffer and related tools like PHPCompatibility and PHP CS Fixer help enforce coding standards and fix some issues, they might not automatically add specific PHPDoc tags like `@package`. For generating or managing PHPDoc comments, PHPDocumentor can be very helpful.

If none of these tools fully meet your needs for automatically adding `@package` tags, you might still need to use a custom script to handle this specific requirement. However, combining these tools can significantly improve the quality and consistency of your code documentation.