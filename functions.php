<?php
// functions.phpの「phpのコード」は**絶対に閉じない**

// <title>タグの出力
add_theme_support('title-tag');

// アイキャッチの有効化
add_theme_support('post-thumbnails');

// 管理画面側からグローバルメニューの編集を有効化
add_theme_support('menus');

// <title>タグの区切り文字の変更
add_filter('document_title_separator','my_document_title_separator');
function my_document_title_separator($separator){
    // ''で囲われている文字列が区切り文字になるよ
    $separator = '|';
    return $separator;
}

/* ==========================================================================
 * クイック編集 カスタムフィールド追加テンプレート
 * ========================================================================== */

/**
 * 1. 一覧画面の表に「見出し（カラム）」を追加する
 * * 【解説】
 * WordPressの表に新しい列を追加するためのフックです。
 * 'manage_〇〇_posts_columns' の 〇〇 の部分をご自身のカスタム投稿スラッグに書き換えます。
 */
// 【ここを変更】 'food' の部分をご自身のカスタム投稿タイプ名（例: 'item', 'book'など）に変更してください。
add_filter( 'manage_food_posts_columns', 'my_custom_add_quickedit_columns' );
function my_custom_add_quickedit_columns( $columns ) {
    // 【ここを変更】 'カラムの内部ID' => '画面に表示される見出し' を設定します。
    // カラムの内部IDは、他のものと被らないように好きな名前をつけられます。
    $columns['my_col_price']     = '価格';
    $columns['my_col_calorie']   = 'カロリー';
    $columns['my_col_recommend'] = 'おすすめ';
    return $columns;
}

/**
 * 2. 各行にデータを表示し、JSで拾うための「隠しデータ」を出力する
 * * 【解説】
 * 先ほど作った列（カラム）の中に、実際のデータ（値）を出力します。
 * 同時に、クイック編集を開いたときにJavaScriptが値を読み取れるよう、
 * <div style="display:none;"> で見えないように値を出力しておくのがコツです。
 */
// 【ここを変更】 'food' の部分をご自身のカスタム投稿タイプ名に変更してください。
add_action( 'manage_food_posts_custom_column', 'my_custom_render_quickedit_columns', 10, 2 );
function my_custom_render_quickedit_columns( $column_name, $post_id ) {
  // 【解説】 $column_name には、手順1で設定した「カラムの内部ID」が入ってきます。
  switch ( $column_name ) {
      case 'my_col_price':
          // 【ここを変更】 'price' の部分を、ご自身のカスタムフィールド名（キー）に変更してください。
          $val = get_post_meta( $post_id, 'price', true );
          echo esc_html( $val ); // 一覧画面に表示される文字
          // 【解説】 JSで取得するための隠しID。post_id を混ぜることで、行ごとの固有IDにしています。
          echo '<div id="hidden_val_price_' . $post_id . '" style="display:none;">' . esc_html( $val ) . '</div>';
          break;

      case 'my_col_calorie':
          // 【ここを変更】 'calorie' の部分をご自身のカスタムフィールド名に変更。
          $val = get_post_meta( $post_id, 'calorie', true );
          echo esc_html( $val );
          echo '<div id="hidden_val_calorie_' . $post_id . '" style="display:none;">' . esc_html( $val ) . '</div>';
          break;

      case 'my_col_recommend':
          // 【ここを変更】 'recommend' の部分をご自身のカスタムフィールド名に変更。
          $val = get_post_meta( $post_id, 'recommend', true );
          echo $val ? '✓' : '-'; // 値があれば ✓ を表示
          echo '<div id="hidden_val_recommend_' . $post_id . '" style="display:none;">' . esc_html( $val ) . '</div>';
          break;
  }
}

/**
 * 3. クイック編集を開いた時の「入力フォーム（UI）」を追加する
 * * 【解説】
 * クイック編集パネルを開いたときに表示されるHTMLを記述します。
 */
add_action( 'quick_edit_custom_box', 'my_custom_add_quickedit_ui', 10, 2 );
function my_custom_add_quickedit_ui( $column_name, $post_type ) {
    // 【ここを変更】 'food' の部分をご自身のカスタム投稿タイプ名に変更。
    if ( $post_type !== 'food' ) return;

    // 【解説】 このフックは列の数だけ何度も実行されてしまうため、最初の列の時だけ出力するように制限します。
    if ( $column_name !== 'my_col_price' ) return;

    // 【解説】 セキュリティ対策（不正アクセス防止）の暗号キーを発行します。
    wp_nonce_field( 'my_quick_edit_nonce_action', 'my_quick_edit_nonce' );
    ?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <div class="inline-edit-group wp-clearfix">
                <label class="inline-edit-custom">
                    <span class="title">価格</span>
                    <span class="input-text-wrap">
                        <input type="text" name="input_price" value="">
                    </span>
                </label>
                <label class="inline-edit-custom">
                    <span class="title">カロリー</span>
                    <span class="input-text-wrap">
                        <input type="text" name="input_calorie" value="">
                    </span>
                </label>
            </div>
            <div class="inline-edit-group wp-clearfix" style="margin-top: 10px;">
                <label class="alignleft">
                    <input type="checkbox" name="input_recommend" value="1">
                    <span class="checkbox-title">おすすめ商品にする</span>
                </label>
            </div>
        </div>
    </fieldset>
    <?php
}

/**
 * 4. JavaScriptで元のデータをフォームに自動入力する
 * * 【解説】
 * クイック編集を開いた瞬間に、手順2で作った「隠しデータ」を読み取り、
 * 手順3で作った「入力フォーム」に値をセットします。
 */
add_action( 'admin_footer-edit.php', 'my_custom_quickedit_javascript' );
function my_custom_quickedit_javascript() {
    global $current_screen;
    // 【ここを変更】 'food' の部分をご自身のカスタム投稿タイプ名に変更。
    if ( $current_screen->post_type !== 'food' ) return;
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        var $wp_inline_edit = inlineEditPost.edit;
        inlineEditPost.edit = function(id) {
            $wp_inline_edit.apply(this, arguments);
            var $post_id = 0;
            if (typeof(id) == 'object') {
                $post_id = parseInt(this.getId(id));
            }
            if ($post_id > 0) {
                var $editRow = $('#edit-' + $post_id);
                
                // 【ここを変更】 手順2の隠しIDから値を取得し、手順3のinputのname属性にセットします。
                
                // 価格のセット
                var price = $('#hidden_val_price_' + $post_id).text();
                $editRow.find('input[name="input_price"]').val(price);
                
                // カロリーのセット
                var calorie = $('#hidden_val_calorie_' + $post_id).text();
                $editRow.find('input[name="input_calorie"]').val(calorie);
                
                // おすすめ（チェックボックス）のセット
                var recommend = $('#hidden_val_recommend_' + $post_id).text();
                if (recommend === '1' || recommend === 'true') {
                    $editRow.find('input[name="input_recommend"]').prop('checked', true);
                } else {
                    $editRow.find('input[name="input_recommend"]').prop('checked', false);
                }
            }
        };
    });
    </script>
    <?php
}

/**
 * 5. 「更新」ボタンを押したときにデータを安全に保存する
 * * 【解説】
 * 入力されたデータを受け取り、データベースに保存します。
 */
// 【ここを変更】 'save_post_food' の 'food' の部分をご自身のカスタム投稿タイプ名に変更。
// 例：'item' なら 'save_post_item' となります。
add_action( 'save_post_food', 'my_custom_save_quickedit_data', 10, 2 );
function my_custom_save_quickedit_data( $post_id, $post ) {
    if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) return;
    if ( ! isset( $_POST['my_quick_edit_nonce'] ) || ! wp_verify_nonce( $_POST['my_quick_edit_nonce'], 'my_quick_edit_nonce_action' ) ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    // 【ここを変更】 $_POST['手順3で決めたname属性'] を受け取り、
    // update_post_metaの第2引数に「保存したいカスタムフィールド名（キー）」を指定します。
    
    // 価格の保存
    if ( isset( $_POST['input_price'] ) ) {
        update_post_meta( $post_id, 'price', sanitize_text_field( $_POST['input_price'] ) );
    }
    
    // カロリーの保存
    if ( isset( $_POST['input_calorie'] ) ) {
        update_post_meta( $post_id, 'calorie', sanitize_text_field( $_POST['input_calorie'] ) );
    }

    // おすすめの保存 (チェックがあれば1、なければ0)
    $recommend_val = isset( $_POST['input_recommend'] ) ? '1' : '0';
    update_post_meta( $post_id, 'recommend', $recommend_val );
}