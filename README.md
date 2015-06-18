Contao Inherit Article
===================

Simple extension to replace the `\ArticleModel` in order to allow inheritable articles. In each article's settings, you have to option to let it inherit downwards in the page hierarchy of Contao. When enabled, the article will be visible on its parent page and all subpages of that parent. Since version 1.1.0 you can also optionally set a maximum inheritance level. e.g. a maximum inheritance of `2` means, that the article will only be inherited down two levels of the page hierarchy.

![Article settings](https://raw.githubusercontent.com/fritzmg/contao-inherit-article/master/inherit_article.png)

Note: this will not affect article teasers currently.