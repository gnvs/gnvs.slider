<?php

use \Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) {
    return;
}

global $APPLICATION;

if ($ex = $APPLICATION->GetException()) {
    echo CAdminMessage::ShowMessage([
        "TYPE" => "ERROR",
        "MESSAGE" => Loc::getMessage("MOD_INST_ERR"),
        "DETAILS" => $ex->GetString(),
        "HTML" => 'HTML'
    ]);
} else {
    echo CAdminMessage::ShowNote(Loc::getMessage("MOD_UNINST_OK"));
}
?>
<form action="<?= $APPLICATION->GetCurPage(); ?>">
    <?=bitrix_sessid_post()?>
    <input type="submit" name="submit" value="<?=Loc::getMessage("MODULE_GNVS_SLIDER_IN_MENU") ?>">
</form>
