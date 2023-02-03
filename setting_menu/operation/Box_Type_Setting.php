<?php

class Box_Type_Setting {
  public function __construct () {}

  public function box_echo ( $slug, $value, $type ) {
    $value_checked = $value ? "checked" : "";
    echo <<<HTML
    <h2>$slug</h2>
    <div>
      <input id="type_$slug" name="type_$slug" type="checkbox" value="$slug" $value_checked>
      <label for="type_$slug">検索に表示する</label>
    </div>
    HTML;
  }
}