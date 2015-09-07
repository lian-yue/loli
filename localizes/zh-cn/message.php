<?php
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-08-25 09:39:26
/*
/* ************************************************************************** */

return [


/*
消息模块
1000 以前是系统预留的

1	- 99 系统错误代码(%s)

1 ＝ 基本错误(%s)
2 ＝ HTTP错误(%s)
3 ＝ 权限错误(文件权限什么的)(%s)
4 ＝ 缓存错误(%s)
5 ＝ 数据库错误(%s)
6 ＝ 储存错误(%s)
7 ＝ 通讯错误(%s)
90 ＝ 无权限(%s)
99 ＝ Exception错误(%s)


200 － 399 执行成功 并且要设置http状态码的
400 － 599 ＝ 执行失败 并且 要设置 http 状态码的

*/

1 => '系统异常 ($1)',
2 => 'HTTP异常 ($1)',
3 => '系统权限异常 ($1)',
4 => '系统缓存异常 ($1)',
5 => '缓存服务异常 $1',
6 => '储存服务异常 ($1)',
7 => '通讯服务异常 ($1)',
8 => '数据库服务异常 ($1)',
90 => '你没有访问权限访问该页面 ($1)',
99 => '($1) 异常',

200 => '请求已成功',
201 => '请求已处理',
202 => '已接受请求',

301 => '链接已永久重定向',
302 => '链接已重定向',
307 => '链接已暂时重定向',

400 => '无法处理该请求',
401 => '需要登录',
403 => '拒绝访问',
404 => '链接不存在',
405 => '不允许的方法',
406 => 'Not Acceptable',
407 => '请求超时',
410 => '链接不存在',
411 => 'Length Required',
412 => 'Precondition Failed',
413 => 'Request Entity Too Large',
414 => 'Request-URI Too Long',
415 => 'Unsupported Media Type',
416 => 'Requested Range Not Satisfiable',
417 => 'Expectation Failed',
421 => 'There are too many connections from your internet address',
422 => 'Unprocessable Entity',
423 => 'Locked',
424 => 'Failed Dependency',
426 => 'Upgrade Required',


500 => 'Internal Server Error',
501 => 'Not Implemented',
502 => 'Bad Gateway',
503 => 'Service Unavailable',
504 => 'Gateway Timeout',
505 => 'HTTP Version Not Supported',
506 => 'Variant Also Negotiates',
507 => 'Insufficient Storage',
509 => 'Bandwidth Limit Exceeded',
510 => 'Not Extended',

1000 => '你输入的$2有误 ($1)',
1001 => '$2不能为空 ($1)',
1002 => '$2数据过长 ($1)',
1003 => '$2范围不正确 ($1)',
1004 => '$2输入的不是指定的值 ($1)',
];