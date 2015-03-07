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

