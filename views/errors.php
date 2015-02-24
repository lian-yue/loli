<?php
use Loli\HMVC\Error;
/* ************************************************************************** */
/*
/*	Lian Yue
/*
/*	Url: www.lianyue.org
/*	Email: admin@lianyue.org
/*	Author: Moon
/*
/*	Created: UTC 2015-02-24 04:25:11
/*	Updated: UTC 2015-02-24 06:06:17
/*
/* ************************************************************************** */
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<?php if ($refresh >= 0 && $redirect && $redirect != true): ?>
		<meta http-equiv="refresh" content="{$refresh};URL={$redirect}" />
		<?php endif; ?>
		<meta http-equiv="pragma" content="no-cache">
		<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="expires" content="0">   
		<meta name="robots" content="none" />
		<meta name="viewport" content="width=device-width" />
		<title><?php echo $title; ?></title>
		<style type="text/css">

		</style>
		<script type="text/javascript">
			var redirectFunction = function() {
				<?php echo $redirect === true ? 'history.back();' : ($redirect ? 'document.location="' . $redirect .'";' : '') ?>
			};
			<?php echo $refresh > 0 ? 'window.setTimeout(redirectFunction, '. ($refresh * 1000) .');' : ($refresh == 0 ? 'redirectFunction();' : '') ?>
		</script>
	</head>
	<body>
		<div id="errors">
			<?php foreach($errors as $error): ?>
			<div clas="error" code="<?php echo htmlspecialchars($error['code'], ENT_QUOTES) ?>"><?php echo $error['message']; ?></div>
			<?php endforeach; ?>
		</div>
		<?php if ($redirect): ?>
			<div id="redirect" onclick="redirectFunction()"><?php echo Error::lang('Return') ?></div>
		<?php endif; ?>
	</body>
</html>