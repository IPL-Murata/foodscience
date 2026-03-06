<footer class="footer">
  <?php get_template_part('template-parts/breadcrumb'); ?>
    <div class="footer_inner">
      <div class="footer_info">
        <div class="footer_logo">
          <h3 class="logo logo-white"><a href="">FOOD SCIENCE<span>メキシカン・レストラン</span></a></h3>
        </div>
        <div class="footer_text">
          <p>〒162-0846 東京都新宿区市谷左内町21-13</p>
        </div>
      </div>
      <section class="footer_sns">
        <h3>SHARE ON</h3>
        <?php
        $snslinks =[
          'menu' => 'footer-sns', //管理画面で設定したメニュー名
          'menu_class' => '', //ulタグにつけるclass名
          'container' => false, //ulの親にできるdivを「無し」にする
        ];
        wp_nav_menu($snslinks);
        ?>
      </section>
      <div class="footer_copyright">
        <small>&copy; FOOD SCIENCE All rights reserved.</small>
      </div>
    </div>
  </footer>

  <div class="pageTop js-toTop">
    <a href="#"><i class="fas fa-arrow-up"></i><span>TOP PAGE</span></a>
  </div>
  <?php if( is_home() ): ?>
    <?php wp_enqueue_style('slick-carousel','https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css'); ?>
    <?php wp_enqueue_script('slick-carousel','https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js'); ?>
    <?php
    wp_enqueue_script('food-science-home',get_template_directory_uri().'/assets/js/home.js');
    wp_footer(); 
    ?>
  <?php endif; ?>
</body>
</html>