<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED === false) {
    return;
}

if (!$arResult['ERRORS']->isEmpty()) { ?>
    <div class="slider">
        <?php foreach ($arResult['ERRORS']->getValues() as $error) { ?>
            <? ShowError($error->getMessage()) ?>
        <?php } ?>
    </div>
    <?php return;
}

switch ($arParams['POSITION_TEXT']) {
    case 'up':
        $positionText = 'top: 0';
        break;
    case 'down':
        $positionText = 'bottom: 0';
        break;
    default:
        $positionText = 'top: 50%';
}
?>

<div class="slider" id="<?= md5(implode('', $arParams)) ?>"
     style="width: <?= $arParams['IMAGE_WIDTH'] ?>px">

    <div class="slider-track">

        <?php foreach ($arResult['ELEMENTS'] as $element) { ?>

            <?php $this->AddEditAction($element['ID'], $element['EDIT_LINK'], CIBlock::GetArrayByID($element["IBLOCK_ID"], "ELEMENT_EDIT")); ?>

            <div class="item" id="<?= $this->GetEditAreaId($element['ID']); ?>">

                <?php if ($arParams['SLIDER_TYPE'] === 'image') { ?>

                    <img data-src="<?= $element['IMG'] ?>" alt="<?= $element['NAME'] ?>">

                    <div class="slideText" style="<?=$positionText ?>">

                        <?php if ($arParams['SHOW_TEXT']) { ?>
                            <div class="item-content"><?= $element[$arParams['TEXT_SOURCE']] ?></div>
                        <?php } ?>
                        <?php
                        if (!empty($element['PROPERTIES'][$arParams['LINK_SOURCE']])
                            && !empty($element['PROPERTIES'][$arParams['LINK_TEXT_SOURCE']])
                        ) { ?>
                            <p class="item-link"><a
                                        class="slider-link"
                                        href="<?= $element['PROPERTIES'][$arParams['LINK_SOURCE']] ?>"
                                ><?= $element['PROPERTIES'][$arParams['LINK_TEXT_SOURCE']] ?></a></p>
                        <?php } ?>

                    </div>

                <?php } ?>

                <?php if ($arParams['SLIDER_TYPE'] === 'text') { ?>
                    <div class="slider-text-wrapper">
                        <?php
                        if ($arParams['TEXT_IMAGE_POSITION'] === 'left') {
                            ?>
                            <div class="slider-text"
                                 style="width: <?= $arParams['TEXT_WIDTH'] ?>px;"
                            >
                                <?= $element[$arParams['TEXT_SOURCE']] ?>
                            </div>
                            <?php
                        }
                        if (isset($element['IMG'])) {
                            ?>
                            <div class="slider-image">
                                <img style="width:<?= $arParams['TEXT_IMAGE_WIDTH'] ?>px;height: <?= $arParams['TEXT_IMAGE_HEIGHT'] ?>px"
                                     data-src="<?= $element['IMG'] ?>"
                                     alt="<?= $element['NAME'] ?>">
                            </div>
                            <?php
                        }
                        if ($arParams['TEXT_IMAGE_POSITION'] === 'right') {
                            ?>
                            <div class="slider-text"
                                 style="width: <?= $arParams['TEXT_WIDTH'] ?>px;"
                            >
                                <?= $element[$arParams['TEXT_SOURCE']] ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <?php if ($arParams['SHOW_ARROWS']) { ?>
        <a class="prev">&#10094;</a>
        <a class="next">&#10095;</a>
    <?php } ?>

    <?php if ($arParams['SHOW_DOTS']) { ?>
        <div class="slider-dots">
            <?php
            $counter = 1;
            foreach ($arResult['ELEMENTS'] as $element) { ?>
                <span class="slider-dots_item" data-slider-counter="<?= $counter ?>"></span>
                <?php
                $counter++;
            }
            ?>
        </div>
    <?php } ?>
</div>
