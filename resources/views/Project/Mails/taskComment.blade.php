<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Email Template</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="robots" content="index,follow">
	<style type="text/css">
	.mail-content,.mail-content table,.mail-content table.footer-light td,.mail-content tbody,.mail-content td,.mail-content tr{display:block}.mail-content{background-color:#f5f5f5;width:100%;margin:auto;box-shadow:0 0 3px #ccc}.mail-content .navbar{margin-bottom:-15px}.mail-content .navbar-brand{padding:0;display:block}.mail-content .container{width:850px;margin:auto}.mail-content .container .content-wrap{background-color:#fff;padding:35px}.mail-content .container .content-wrap *{font-family:Helvetica,Arial,sans-serif;color:#000;margin:0}.edition-info p .mail-content .container .content-wrap a,.mail-content .container .content-wrap .edition-info p a,.mail-content .container .content-wrap p{font-size:12px;line-height:22.5px}.mail-content .footer-light{background-color:#bbb;padding:20px;margin:5px 0}.edition-info p .mail-content .footer-light a,.mail-content .footer-light .edition-info p a,.mail-content .footer-light p{font-size:12px;color:#eee;margin:0;line-height:18px}.mail-content .footer-light a{color:#fff}.text-center{text-align:center}.table-content{display:table}.table-content tr{display:table-row;width:478px}.table-content td{display:table-cell;width:239px;padding:5px 0}table.info-wrap tr:last-child{border-bottom:0}table.info-wrap{margin-top:30px!important}table.info-wrap tr{border-bottom:1px solid #ccc;padding:10px 0}table.info-wrap td{display:inline-block}
    </style>
</head>

<body>
    <table class="mail-content">
        <tbody>
            <tr>
                <td class="navbar">
                    <table class="container">
                        <tr class="pull-left">
                            <td>
                                <a class="navbar-brand">
				                	<img src="<?php echo $message->embed(public_path() . '/images/logo-dark.png'); ?>" class="img-responsive" width="150">
				                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
			<tr class="content">
				<td class="container">
					<table class="row">
					<tbody class="content-wrap">
						<tr>
							<td>
								<h4 style="margin-bottom:0;">{!! trans('ticketingtool.new_comment_posted_in', ['user' => $created_by, 'task' => $task_name]) !!}</h4>
							</td>
						</tr>
						<tr>
							<td>
								<b></b><br>
								<p>{!! $comment !!}</p>
							</td>
						</tr>
                        <tr>
							<td>
								<b></b><br>
								<a href="{{ env('APP_URL') }}projects/view/{{ $project_id }}?task={{ $task_id }}" style='text-decoration:underline;'>{{ env('APP_URL') }}projects/view/{{ $project_id }}?task={{ $task_id }}</a>
							</td>
						</tr>
						<tr>
							<td>
								<br>
								<p>Sincerely,</p>
								<p>The FAKTENHAUS team.</p>
							</td>
						</tr>
					</tbody>
					</table>
				</td>
			</tr>
            <tr>
                <td>
                    <table class="footer-light">
                        <tr class="container">
                            <td class="text-center">
                                    <p>{{__('ticketingtool.copyright_text')}}&nbsp;??&nbsp;<?php echo Date("Y"); ?>&nbsp;{{__('ticketingtool.faktenhaus_all_rights_reserved')}}</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
