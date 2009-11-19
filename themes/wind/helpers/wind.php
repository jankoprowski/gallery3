<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2009 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */

class wind {
  static function get_admin_form($action) {
    $form = new Forge($action, "", null, array("id" =>"g-wind-options-form"));
    $group = $form->group("edit_theme");
    $group->input("page_size")->label(t("Items per page"))->id("g-page-size")
      ->rules("required|valid_digit")
      ->value(module::get_var("wind", "page_size"));
    $group->input("thumb_size")->label(t("Thumbnail size (in pixels)"))->id("g-thumb-size")
      ->rules("required|valid_digit")
      ->value(module::get_var("wind", "thumb_size"));
    $group->input("resize_size")->label(t("Resized image size (in pixels)"))->id("g-resize-size")
      ->rules("required|valid_digit")
      ->value(module::get_var("wind", "resize_size"));
    $group->textarea("header_text")->label(t("Header text"))->id("g-header-text")
      ->value(module::get_var("wind", "header_text"));
    $group->textarea("footer_text")->label(t("Footer text"))->id("g-footer-text")
      ->value(module::get_var("wind", "footer_text"));
    $group->checkbox("show_credits")->label(t("Show site credits"))->id("g-footer-text")
      ->checked(module::get_var("wind", "show_credits"));
    $group->submit("")->value(t("Save"));
    return $form;
  }

  static function update_options($form) {
    module::set_var("wind", "page_size", $form->edit_theme->page_size->value);

    $thumb_size = $form->edit_theme->thumb_size->value;
    $thumb_dirty = false;
    if (module::get_var("wind", "thumb_size") != $thumb_size) {
      graphics::remove_rule("gallery", "thumb", "gallery_graphics::resize");
      graphics::add_rule(
        "gallery", "thumb", "gallery_graphics::resize",
        array("width" => $thumb_size, "height" => $thumb_size, "master" => Image::AUTO),
        100);
      module::set_var("wind", "thumb_size", $thumb_size);
    }

    $resize_size = $form->edit_theme->resize_size->value;
    $resize_dirty = false;
    if (module::get_var("wind", "resize_size") != $resize_size) {
      graphics::remove_rule("gallery", "resize", "gallery_graphics::resize");
      graphics::add_rule(
        "gallery", "resize", "gallery_graphics::resize",
        array("width" => $resize_size, "height" => $resize_size, "master" => Image::AUTO),
        100);
      module::set_var("wind", "resize_size", $resize_size);
    }

    module::set_var("wind", "header_text", $form->edit_theme->header_text->value);
    module::set_var("wind", "footer_text", $form->edit_theme->footer_text->value);
    module::set_var("wind", "show_credits", $form->edit_theme->show_credits->value);
  }
}
