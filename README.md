### Hexlet tests and linter status:
[![Actions Status](https://github.com/maxheong54/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/maxheong54/php-project-48/actions)
[![Maintainability](https://api.codeclimate.com/v1/badges/8b725cbaf6ba34030eb1/maintainability)](https://codeclimate.com/github/maxheong54/php-project-48/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/8b725cbaf6ba34030eb1/test_coverage)](https://codeclimate.com/github/maxheong54/php-project-48/test_coverage)
[![my-chek](https://github.com/maxheong54/php-project-48/actions/workflows/my-chek.yml/badge.svg)](https://github.com/maxheong54/php-project-48/actions/workflows/my-chek.yml)

### Prerequisites

* Linux, Macos, WSL
* PHP >=8.4
* Xdebug
* Make
* Git
  
### Install
```bash
git clone https://github.com/maxheong54/php-project-48.git
cd php-package-48
make install
```

### Project Description

This project is a command-line program designed to determine the differences between two data structures.

Features:
- Supports various input file formats: YAML and JSON.
- Generates reports in multiple formats: plain text, stylish, json.

### Example Usage:
```bash
# format plain
gendiff --format plain path/to/file.yml another/path/file.json

Property 'common.follow' was added with value: false
Property 'group1.baz' was updated. From 'bas' to 'bars'
Property 'group2' was removed

# format stylish
gendiff filepath1.json filepath2.json

{
  + follow: false
    setting1: Value 1
  - setting2: 200
  - setting3: true
  + setting3: {
        key: value
    }
  + setting4: blah blah
  + setting5: {
        key5: value5
    }
}

```

Demonstration of gendiff work: [Watch demo](https://asciinema.org/a/R0OfENOrV3kmPiYtJaPaDTcZc)