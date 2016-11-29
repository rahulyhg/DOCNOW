<?php
	session_start();
	/*echo "<pre>";
	print_r($_SESSION);
	echo "</pre>";*/
?>

<?php if (isset($_SESSION['sessionMessage']) && !empty($_SESSION['sessionMessage'])) :?>
	<?php
		$sessionMessageClass = isset($_SESSION['sessionMessageClass']) ? ' ' . $_SESSION['sessionMessageClass'] : '';
		$sessionMessage = $_SESSION['sessionMessage'];
	?>
	<div class="alert<?= $sessionMessageClass ?>">
		<?php if (!isset($close) || $close !== false):?>
			<a class="close" data-dismiss="alert" href="#">×</a>
		<?php endif; ?>
		<?=$sessionMessage;?>
	</div>

	<?php $_SESSION['sessionMessage'] = null;?>
<?php endif; ?>

