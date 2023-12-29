<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Context;

if (!check_bitrix_sessid()) return;

global $APPLICATION;

if ($ex = $APPLICATION->GetException()) {
    CAdminMessage::ShowMessage([
        "TYPE" => "ERROR",
        "MESSAGE" => Loc::getMessage("MOD_INST_ERR"),
        "DETAILS" => $ex->GetString(),
        "HTML" => 'HTML'
    ]);
} else {
    CAdminMessage::ShowNote(Loc::getMessage("MOD_INST_OK"));
}

$request = Context::getCurrent()->getRequest();
?>

<table class="adm-info-message filter-form" cellpadding="3" cellspacing="0" border="0" width="0%">
    <tbody>
        <tr>
            <td>
                <p><?=Loc::getMessage("GNVS_SLIDER_THANK") ?></p>
            </td>
        </tr>
        <tr>
            <td><?// Координаты решения, типа заглушки ?>
                <p><?=Loc::getMessage("GNVS_SLIDER_INSTRUCTION") ?> <a href="https://gnvs.ru/works/gnvs-slider/" target="_blank"><?=Loc::getMessage('GNVS_SLIDER_INSTRUCTION_LINK') ?></a></p>
                <p><?=Loc::getMessage("GNVS_SLIDER_SUPPORT_TEXT") ?> <a href="mailto:gnvsxx@gmail.com">gnvsxx@gmail.com</a></p>
                <?php if ($request->get('iblock') === 'Y') { ?>
                    <h1><?=Loc::getMessage("MODULE_SLIDER_CHECK_INSTALL_IBLOCK") ?></h1>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td>
                <p><?=Loc::getMessage("GNVS_SLIDER_GOODBYE") ?> <a href="https://gnvs.ru" target="_blank">https://gnvs.ru</a></p>
            </td>
        </tr>
    </tbody>
</table>

<form action="<?= $APPLICATION->GetCurPage(); ?>" name="blank-install">
    <?=bitrix_sessid_post()?>
    <input type="submit" name="" value="<?=Loc::getMessage("MODULE_SLIDER_IN_MENU") ?>">
</form>
