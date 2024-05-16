
# Coding Standard 
##### Follow the following code  
File: .gitignore
```bash
    touch .gitignore
```

```text
    .DS_Store
    .idea/
    _test.php
    vendor/
```
###### Install `phpcodesniffer` 
```bash
composer require --dev  dealerdirect/phpcodesniffer-composer-installer

```

######  List all coding Standered
```bash 
    ./vendor/bin/phpcs -i
```

###### Install WordPress Coding Standards
```bash
composer require --dev wp-coding-standards/wpcs
```

######  Verify your `phpcs.xml` file 
```bash
    ./vendor/bin/phpcs -vvv
```
