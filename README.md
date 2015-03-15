# Qrz_Indexer

Simple Magento module that adds an Admin Panel option in the System menu, just below Index Management, to trigger reindexes.

The functionality mimics the ```shell/indexer.php``` script.
![indexer grid](https://cloud.githubusercontent.com/assets/3646206/6541766/a6069de4-c4da-11e4-8533-1463a0d6e999.png)


# How to get

This module is available through the Firegento Magento Module Composer Repository. To install, add http://packages.firegento.com/ to your composer.json repositories and require this module. I also recommend installing the [Magento composer installer](https://github.com/magento-hackathon/magento-composer-installer).

Add the following to your composer.json:


```
{
    ...
    "repositories": [
        {
            "type": "composer",
            "url": "http://packages.firegento.com"
        }
    ],
    "require" : {
        ...
        "qrz-io/indexer":                                 "~1.0",
        "magento-hackathon/magento-composer-installer":   "~2.0"
    },
    "extra": {
        "magento-root-dir": "./"
    }
    ...
}
```

#License
The MIT License (MIT)

Copyright (c) 2015 Cristian Quiroz

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.


