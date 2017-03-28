# Sohucs
## 中文
这是一个自制的畅言Mediawiki插件，写的比较简陋但是还是可以用<br>
用安装方法：将Sohucs整个文件夹放到Extensions目录下<br>
然后到LocalSettings.php页面添加如下语句：<br>

require_once("$IP/extensions/Sohucs/Sohucs.php");<br>
$wgSohucsAppid = 搜狐畅言生成的appid<br>
$wgSohucsConf = 搜狐畅言生成的conf<br>

关于搜狐畅言appid和conf查看的方法：http://changyan.kuaizhan.com/setting/common/isv-mgr

## English
This is a extension name Sohucs (http://changyan.kuaizhan.com/ , like Desqus) for Mediawiki's extension.<br>
Install:copy the Sohucs directory to your mediawiki\extensions

Then add:

require_once("$IP/extensions/Sohucs/Sohucs.php");<br>
$wgSohucsAppid = Sohucs appid<br>
$wgSohucsConf = Sohucs conf<br>

to your LocalSettings.php.

About sohucs appid and conf, please goto http://changyan.kuaizhan.com/setting/common/isv-mgr.
