<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

if (!check_bitrix_sessid()) return;

global $APPLICATION;
?>

<table class="adm-info-message filter-form" cellpadding="3" cellspacing="0" border="0" width="0%">
    <tbody>
        <tr>
            <td>
                <p><?=Loc::getMessage('GNVS_SLIDER_STEP_1') ?></p>
                <p><?=Loc::getMessage('GNVS_SLIDER_STEP_2') ?></p>
                <p><?=Loc::getMessage('GNVS_SLIDER_SUPPORT') ?> <a href="mailto:gnvsxx@gmail.com">gnvsxx@gmail.com</a></p>
            </td>
        </tr>
    </tbody>
</table>

<form action="<?= $APPLICATION->GetCurPage(); ?>" name="blank-install">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID?>">
    <input type="hidden"  name="id" value="gnvs.slider">
    <input type="hidden" name="step" value="2">
        <input type="hidden" name="install" value="Y">
    <div style="display:flex; align-items: center">
        <label for="iblock"><?=Loc::getMessage('GNVS_SLIDER_IBLOCK_INSTALL') ?></label>
        <input id="iblock" type="checkbox" name="iblock" checked value="Y"><br><br>
    </div>


    <input type="submit" name="inst" value="<?=Loc::getMessage("GNVS_SLIDER_INSTALL") ?>">
</form>
