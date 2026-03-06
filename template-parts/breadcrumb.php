<?php if(function_exists('bcn_display')): ?>
  <!-- 上のifの条件はbcn_displayという関数があるかどうかを判別している -->
  <div class="breadcrumb">
    <div class="breadcrub_inner">
    <!-- breadcrumb navXTの独自関数（プラグインを起動するための関数） -->
    <?php bcn_display(); ?>
    </div>
  </div>
<?php endif; ?>