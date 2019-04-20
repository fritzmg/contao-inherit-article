[![](https://img.shields.io/maintenance/yes/2019.svg)](https://github.com/fritzmg/contao-inherit-article)
[![](https://img.shields.io/packagist/v/fritzmg/contao-inherit-article.svg)](https://packagist.org/packages/fritzmg/contao-inherit-article)
[![](https://img.shields.io/packagist/dt/fritzmg/contao-inherit-article.svg)](https://packagist.org/packages/fritzmg/contao-inherit-article)

Contao Inherit Article
===================

Simple extension to allow inheritable articles within Contao. In each article's settings, you have to option to let it inherit downwards in the page hierarchy of Contao. When enabled, the article will be visible on its parent page and all subpages of that parent.

Since version `1.1.0` you can also optionally set a maximum inheritance level. e.g. a maximum inheritance of `2` means that the article will only be inherited down two levels of the page hierarchy.

Since version `1.2.0` you can also let the inherited article to be added after the other articles. This has changed to a priority setting in version `1.3.0`. A negative priority means, that the inherited article is put after regular ones.

Since version `2.1.0` you are also able to inherit articles that are otherwise unpublished.

![Article settings](https://raw.githubusercontent.com/fritzmg/contao-inherit-article/master/inherit_article.png)

Note: this will not affect article teasers currently.
