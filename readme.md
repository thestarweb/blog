code charset utf-8
由于这个项目目录同时需要用于调试，所以大家使用时需要再做一些调整
首先需要找我们的Tools项目和myscript项目并下载（这两个项目是一些公共的类）
同时本程序还需要依赖sucs。
去掉ini文件后面的.d，并正确配置它，务必配置好succ路径（在sucs项目中，如果没找到，请耐心等更新）
导入数据库文件
我们建议把ini文件移动到外部不可访问的文件夹或使用其他方式进行保护（同时需保护ini同目录下生成的ini.temp文件），以免数据库密码泄露
修改index.php让它能找到system类（作为基础性的类，暂时把他放到Tools项目中了）和ini文件
确保重写规则可用应该就没有问题了

#nginx参考重写规则（把“{your root/}”改成自己实际的目录 务必加上后面的斜杠）：
if ( !-e $request_filename){
	rewrite ^/{your root/}.*$ /{your root/}index.php last;
}

#阿帕奇参考重写规则（把“{your root/}”改成自己实际的目录 务必加上后面的斜杠）：
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^{your root/}(.*) /{your root/}index.php/$1