<div class="homepage-adds">
    <div class="left-con">
        <h2><?php echo nl2br($title); ?></h2>

        <?php if($repeater): ?>
            <div class="content">
                <?php foreach($repeater as $r1): ?>
                    <div>
                        <img src="image/<?php echo $r1['image1']; ?>" alt="image"/>
                        <h4><?php echo $r1['text1']; ?></h4>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>

    <div class="right-con">
        <?php echo html_entity_decode($text); ?>

        <?php if($repeater2): ?>
            <div class="content">
                <?php foreach($repeater2 as $r2): ?>
                    <div>
                        <img src="image/<?php echo $r2['image2']; ?>" alt="image"/>
                        <h3><?php echo nl2br($r2['text2']); ?></h3>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
    <img src="image/<?php echo $design2; ?>" class="design d1" alt="image"/>
    <img src="image/<?php echo $design2b; ?>" class="design d2" alt="image"/>

</div>