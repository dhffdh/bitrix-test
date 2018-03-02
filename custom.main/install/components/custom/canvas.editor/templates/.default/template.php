<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);

$saveMode = !empty($arResult['ID']);

?>
<div>

    <div class="b-canvas-wrap">
        <canvas id="js-canvas-container"
                width="600"
                height="400"
                <? if (!empty($arResult['IMAGE'])){ ?>data-image="<?= $arResult['IMAGE'] ?>"<? } ?>
        ></canvas>
    </div>

    <div>
        <form class="b-control" id="js-canvas-form" method="post">
            <input type="hidden" name="AJAX" value="Y">

            <input type="hidden" name="action" value="save">

            <?if($saveMode){
                ?><input type="hidden" name="id" value="<?=$arResult['ID']?>"><?
            }?>

            <div>
                <input type="password" name="pass" required="required" placeholder="Password" minlength="5">
                <button id="js-canvas-save" class="btn" type="submit" value="save"><?if($saveMode){?>Save<?}else{?>Add<?}?></button>

                <?if($saveMode){?>
                    <label>
                        <input type="checkbox" name="delete" value="Y"> - delete
                    </label>
                <?}?>

            </div>
            <div>
                <button id="js-canvas-clear" class="btn">Clear</button>
            </div>
        </form>
    </div>

</div>