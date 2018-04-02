<?php
/** @var array $items */
$countItems = 0;
foreach($items as $item) {
    $countItems++;
    if (!empty($item['items'])) {
        $countItems += count($item['items']);
    }
}
$cols = 4;
$rows = ceil($countItems/$cols);
?>

<div class="mega-menu clearfix pre-scrollable jscrollpane" style="overflow-x: hidden">
    <div class="row">
        <?php $line = 0; $i = 0?>
        <?php foreach($items as $item): ?>
            <?php if ($line === 0):?>
                <div class="col-<?=$cols?>" style="float:left">
            <?php endif; ?>
            <a href="<?= $item['url'] ?? '#'; ?>" class="mega-menu-title"><?= $item['label']; ?></a>
            <?php $line = ++$i % $rows ?>
            
            <?php if (!empty($item['items'])): ?>
                <?php if ($line === 0):?>
                    </div>
                    <div class="col-<?=$cols?>" style="float:left">
                <?php endif; ?>
                <ul class="mega-menu-list clearfix">
                    
                    <?php $item['items'] = array_values($item['items']); ?>
                    <?php foreach ($item['items'] as $si => $subitem): ?>
                        <?php if ($si != 0 && $line === 0):?>
                                </ul>
                            </div>
                            <div class="col-<?=$cols?>" style="float:left">
                                <ul class="mega-menu-list clearfix">
                        <?php endif; ?>
                        <li><a href="<?= $subitem['url']; ?>"><?= $subitem['label']; ?></a></li>
                        <?php $line = ++$i % $rows ?>
                    <?php endforeach; ?>
                    
                </ul>
            <?php endif; ?>
            
            <?php if ($line === 0): ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<?php common\helpers\ViewHelper::startRegisterScript($this); ?>
<script>
// var mmenu = $('.mega-menu.pre-scrollable.jscrollpane');
// mmenu.jScrollPane({
//     showArrows: false
// });
</script>
<?php common\helpers\ViewHelper::endRegisterScript(); ?>
