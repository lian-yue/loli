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


'redirect' => '重定向ing...',
'success' => '成功',



'exception' => '系统异常 {value} ({code})',
'auth_login' => '你尚未登录请登录后访问',
'rate_limit' => '请求次数过于频繁请在{diff}访问 ({name})',

'permission_denied' => '无权限访问 ({code})',
'controller_exists' => '控制器不存在',
'paginator_exists' => '页面不存在',

'user_exists' => '用户不存在',
'folder_exists' => '文件不存在',



'validator' => '{title}不正确 ({name})',
'validator_required' => '{title}不能为空 ({name})',


'validator_exists' => '{title}不存在 ({name})', // exists
'validator_unique' => '{title}已存在 ({name})', // unique

'validator_min_numeric' => '{title}不能小于{rule} ({name})', // numeric
'validator_max_numeric' => '{title}不能大于{rule} ({name})', // numeric

'validator_min_string' => '{title}长度不能小于{rule}字节 ({name})', // string
'validator_max_string' => '{title}长度不能大于{rule}字节 ({name})', // string

'validator_min_size' => '{title}不能小于{rule} ({name})', // size
'validator_max_size' => '{title}不能大于{rule} ({name})', // size


'validator_min_count' => '{title}不能少余{rule}个 ({name})', // count
'validator_max_count' => '{title}不能多余{rule}个 ({name})', // count




'validator_again' => '重复输入的{title}不相同 ({name})',             // again

'validator_step_numeric' => '{title}必须是{rule}的倍数 ({name})', // step  numeric
'validator_step_count' => '{title}数量必须是{rule}的倍数 ({name})', // step count

'validator_accept' => '{title}不允许上传该类型文件 ({name})',
'validator_accept_image' => '{title}必须是图片 ({name})',
'validator_accept_audio' => '{title}必须是音频 ({name})',
'validator_accept_video' => '{title}必须是视频 ({name})',


// 上传文件错误
'validator_file_1' => '上传的文件大小超过系统限制',
'validator_file_2' => '上传文件的大小超过表单限制',
'validator_file_3' => '你的文件未上传完成',
'validator_file_4' => '没有文件被上传',
'validator_file_6' => '缺少一个临时文件夹',
'validator_file_7' => '无法写入文件到磁盘',
'validator_file_8' => '不允许上传该扩展名的文件',




// 80
'oauth2_unique' => '你绑定的{name}账号已经被其他用户绑定了',
'oauth2_exists' => '你绑定的{name}账号最大数量超过限制',
'oauth2_remove' => '绑定的的账号不允许删除 设置密码后才允许删除',


// 绑定数量
'profile_bind_max_count' => '绑定的{title}数量已满',
'profile_bind_unique' => '{title}已被人绑定过了',
'profile_bind_auth' => '绑定的{title}数量已满',
];
