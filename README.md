# store
PHP常用存储工具集，包含Session、Cookie、File、Redis、Cache等

## 安装

```bash
composer require mongdch/store
```
## 版本

> 1.1.1

* 优化注解


> 1.1.0

* 重写Cache类，支持对Redis缓存的使用
* 优化Rdb类，完善对redis扩展支持
* 优化Session类、Cookie类，支持原生单例调用

> 1.0.7

* 优化代码，增强File类

> 1.0.6

* 增强RDB类功能，增加读取超时时间配置及key前缀配置
* 优化代码

> 1.0.5

* 优化SESSION类功能，防止在PHP7.2以上的版本出现在session_start后定义session配置的错误

> 1.0.4

* 优化代码，加强file类

> 1.0.3

* 优化代码，加强session类
* 修复Redis类自定义链接配置无效的问题

> 1.0.2

* 优化代码结构
* 减低PHP版本要求，改为PHP5.6以上
* 修改开源协议为MIT

> 1.0.1

* 修复Redis类BUG
* 优化代码结构