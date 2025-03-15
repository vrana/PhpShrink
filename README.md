# PhpShrink
Remove spaces and comments from PHP code.

Operations:
- remove unnecessary whitespace, change necessary whitespace to `\n`
- strip comments, preserve only the first doc-comment
- remove extra `{}`, e.g. in `if (true) { oneCommand(); }`
- minify variables, e.g. in `function f($long) { return $long; }`
- change `?>HTML<?php` to `echo 'HTML'` if it saves space
- join consecutive echo, e.g. in `echo 'a'; echo 'b';`
- strip public visibility or change it to `var`

The main user is [Adminer](https://www.adminer.org/).
