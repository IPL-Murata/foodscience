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