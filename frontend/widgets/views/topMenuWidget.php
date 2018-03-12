<?php
/** @var array $items */
?>

<div class="mega-menu clearfix pre-scrollable">
    <?php while ($item = current($items)) { ?>
        <?php 
        $index = 0;
        if(!$item) break; 
        ?>

        <div class="container">
            <?php do { ?>
                <div class="col-5">
                    <a href="<?= $item['url'] ?? '#'; ?>" class="mega-menu-title"><?= $item['label']; ?></a>
                    <?php if (!empty($item['items'])) { ?>
                        <ul class="mega-menu-list clearfix">
                            <?php foreach ($subitems = $item['items'] as $subitem) { ?>
                                <li><a href="<?= $subitem['url']; ?>"><?= $subitem['label']; ?></a></li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>

                <?php
                next($items);
                $index++;
                $item = current($items)
                ?>
            <?php } while ($item && $index % 5 != 0); ?>
        </div>
    <?php } ?>
</div>