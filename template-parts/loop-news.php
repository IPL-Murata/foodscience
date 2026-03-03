<section id="<?php the_ID(); ?>" <?php post_class('cardList_item'); ?>>
  <a href="<?php the_permalink(); ?>" class="card">
  <?php
  // get_the_categoryでカテゴリーを配列で変数に代入
  $categories = get_the_category(); 
  ?>
  <?php if($categories): ?>  
    <div class="card_label">
      <?php foreach($categories as $category): ?>
      <span class="label label-black"><?php echo $category -> name; ?></span>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
    <div class="card_pic">
    <?php if( has_post_thumbnail() ): ?>
      <!-- アイキャッチ画像が投稿されている場合(true) -->
      <?php the_post_thumbnail(); ?>
    <?php else: ?>
      <!-- アイキャッチ画像が投稿されていない場合(false) -->
      <img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/noimage.png" alt="">
    <?php endif; ?>
    </div>
    <div class="card_body">
      <h2 class="card_title"><?php the_title(); ?></h2>
      <time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y年m月d日'); ?>更新</time>
    </div>
  </a>
</section>