<?php
if (!defined('ABSPATH')) { exit; }

function bella_banner_shade($value) {
    return max(0, min(80, absint($value)));
}

add_action('customize_register', function ($customizer) {
    $customizer->add_section('bella_hero', [
        'title'=>'Úvodní banner',
        'description'=>'Změňte fotografii akvabely, vlastní pozadí vody a texty na úvodní stránce. Změny uvidíte v náhledu; na web se uloží tlačítkem Publikovat.',
        'priority'=>30,
        'capability'=>'edit_theme_options',
    ]);
    $images = [
        'bella_hero_image'=>['Fotografie akvabely',get_template_directory_uri().'/assets/hero-swimmer.png','Doporučujeme široký obrázek s akvabelou v pravé části. Fotografii lze také odebrat.'],
        'bella_water_image'=>['Vlastní pozadí vody','','Volitelné pozadí pod fotografií. Bez obrázku zůstane modré vodní pozadí šablony.'],
    ];
    foreach ($images as $key=>$data) {
        $customizer->add_setting($key, ['default'=>$data[1],'sanitize_callback'=>'esc_url_raw','capability'=>'edit_theme_options','transport'=>'refresh']);
        $customizer->add_control(new WP_Customize_Image_Control($customizer,$key,['label'=>$data[0],'description'=>$data[2],'section'=>'bella_hero','settings'=>$key]));
    }
    $fields = [
        'bella_hero_line'=>['První řádek nadpisu','Vše pro','text','sanitize_text_field'],
        'bella_hero_title'=>['Zvýrazněný řádek nadpisu','akvabely','text','sanitize_text_field'],
        'bella_hero_description'=>['Text pod nadpisem',"Plavky, pomůcky, doplňky\na mnohem více.",'textarea','sanitize_textarea_field'],
        'bella_hero_button'=>['Text tlačítka','Nakupovat','text','sanitize_text_field'],
        'bella_hero_link'=>['Odkaz tlačítka','#kolekce','text','esc_url_raw'],
        'bella_hero_shade'=>['Ztmavení pod textem (%)',45,'range','bella_banner_shade'],
    ];
    foreach ($fields as $key=>$data) {
        $customizer->add_setting($key,['default'=>$data[1],'sanitize_callback'=>$data[3],'capability'=>'edit_theme_options','transport'=>'refresh']);
        $args=['label'=>$data[0],'section'=>'bella_hero','type'=>$data[2]];
        if ($data[2]==='range') { $args['input_attrs']=['min'=>0,'max'=>80,'step'=>5]; }
        $customizer->add_control($key,$args);
    }
});

add_action('customize_register', function ($customizer) {
    $customizer->add_section('bella_categories',[
        'title'=>'Kategorie obchodu',
        'description'=>'Nejprve vytvořte kategorie v Produkty → Kategorie a přiřaďte do nich produkty. Zde vyberte kategorii pro každou dlaždici a položku menu. Dlaždice i menu používají stejné propojení. Vybraná kategorie zůstane propojená i po přejmenování. Obrázek dlaždice se přebírá z náhledu produktové kategorie.',
        'priority'=>31,
        'capability'=>'edit_theme_options',
    ]);
    if (!taxonomy_exists('product_cat')) { return; }
    $choices=[0=>'Automaticky podle původního názvu'];
    $terms=get_terms(['taxonomy'=>'product_cat','hide_empty'=>false,'orderby'=>'name']);
    if (is_wp_error($terms)) { return; }
    foreach ($terms as $term) {
        $path=[];
        foreach (array_reverse(get_ancestors($term->term_id,'product_cat','taxonomy')) as $ancestor_id) {
            $ancestor=get_term($ancestor_id,'product_cat');
            if ($ancestor && !is_wp_error($ancestor)) { $path[]=$ancestor->name; }
        }
        $path[]=$term->name;
        $choices[$term->term_id]=implode(' / ',$path);
    }
    foreach (bella_category_slots() as $key=>$slot) {
        $id='bella_category_'.$key;
        $customizer->add_setting($id,['default'=>0,'capability'=>'edit_theme_options','sanitize_callback'=>'bella_sanitize_category_id','transport'=>'refresh']);
        $customizer->add_control($id,['label'=>$slot['name'].' — propojená kategorie','section'=>'bella_categories','type'=>'select','choices'=>$choices]);
        $label_id='bella_category_label_'.$key;
        $customizer->add_setting($label_id,['default'=>'','capability'=>'edit_theme_options','sanitize_callback'=>'sanitize_text_field','transport'=>'refresh']);
        $customizer->add_control($label_id,['label'=>$slot['name'].' — vlastní název','description'=>'Volitelné. Prázdné pole použije název vybrané produktové kategorie.','section'=>'bella_categories','type'=>'text']);
    }
});
