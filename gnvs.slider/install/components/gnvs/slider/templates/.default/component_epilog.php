<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();}

$arJSParams = [
	'start' => 1,
	'autoscroll' => (int)$arParams['AUTOSCROLL'],
	'showDots' => (bool)$arParams['SHOW_DOTS'],
	'showArrows' => (bool)$arParams['SHOW_ARROWS']
];
?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        new Slider(
            '<?= md5(implode('', $arParams))?>',
			<?= CUtil::PhpToJSObject($arJSParams)?>
        )
    })
</script>

