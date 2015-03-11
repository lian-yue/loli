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
		<?php if ($refresh >= 0 && is_string($redirect)): ?>
		<meta http-equiv="refresh" content="<?php echo $refresh; ?>;URL=<?php echo $redirect; ?>" />
		<?php endif; ?>
		<meta name="robots" content="none" />
		<meta name="viewport" content="width=device-width"/>
		<title><?php echo $title; ?></title>
		<style type="text/css">
			*{padding:0; margin:0;}
			html{background: #f9f9f9;}
			body,input,button,select,textarea{font: medium/1.8em Tahoma,Helvetica,arial,sans-serif;color:#333;}
			body{background:#fff;width: 80%;max-width: 800px;margin: 10% auto 0 auto;border: 1px solid #dfdfdf;padding-bottom: 1.4em;}
			#title{font-size: larger;padding:.6em 1em;border-bottom: 1px solid #dfdfdf;font-weight: bold;}
			#errors{padding: 1.4em 1.4em 0 1.4em;}
			#errors .error{padding-bottom: .4em;}
			#redirect{padding: .4em 1.4em 0 1.4em;}
			#redirect a{cursor: pointer;color: #369;}
			@media only screen and (max-width : 800px) {
				body{width: 100%;margin:0;border:0;}
				html{background:#fff;}
			}


		</style>
		<script type="text/javascript">
			var redirectFunction = function() {
				<?php echo $redirect === true ? 'history.back();' : ($redirect ? 'document.location="' . $redirect .'";' : '') ?>
			};
			<?php echo $refresh > 0 ? 'window.setTimeout(redirectFunction, '. ($refresh * 1000) .');' : ($refresh == 0 ? 'redirectFunction();' : '') ?>
		</script>
	</head>
	<body>
		<div id="title"><?php echo $title; ?></div>
		<div id="errors">
			<?php foreach($errors as $error): ?>
			<div class="error" code="<?php echo htmlspecialchars($error['code'], ENT_QUOTES) ?>"><?php echo $error['message']; ?></div>
			<?php endforeach; ?>
		</div>
		<?php if ($redirect): ?>
			<div id="redirect">
				<a href="<?php echo $redirect === true ? 'javascript:history.back()' : $redirect; ?>"><?php echo Error::lang(['Automatic return after $1 seconds', '<strong id="refresh">'. $refresh .'</strong>']) ?></a>
			</div>
		<?php endif; ?>
	</body>
</html>