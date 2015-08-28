<!DOCTYPE html>
<html lang="<?=$localize->getLanguage()?>">
	<head>
		<meta charset="UTF-8" />
		<meta name="robots" content="none" />
		<meta name="viewport" content="width=device-width"/>
		<title><?=$localize->translate('Messages') ?></title>
		<style type="text/css">
			*{padding:0; margin:0;}
			html{background: #f9f9f9;}
			body,input,button,select,textarea{font: medium/1.8em Tahoma,Helvetica,arial,sans-serif;color:#333;}
			body{background:#fff;width: 80%;max-width: 800px;margin: 10% auto 0 auto;border: 1px solid #dfdfdf;padding-bottom: 1.4em;}
			#title{font-size: larger;padding:.6em 1em;border-bottom: 1px solid #dfdfdf;font-weight: bold;}
			#messages{padding: 1.4em 1.4em 0 1.4em;}
			#messages .message{padding-left:1em;margin-bottom: .4em}
			#messages .message-type-1{border-left: 4px solid #7ad03a;}
			#messages .message-type-2{border-left: 4px solid #ffba00;}
			#messages .message-type-2{border-left: 4px solid #dd3d36;}
			#redirect{padding: .4em 1.4em 0 1.4em;}
			#redirect a{cursor: pointer;color: #369;}
			@media screen and (max-width: 782px) {
				body{width: 100%;margin:0;border:0;}
				html{background:#fff;}
			}
		</style>
	</head>
	<body>
		<?=$this->processing() ?>
	</body>
</html>