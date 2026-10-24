<?php
if (!defined('ABSPATH')) { exit; }
$brands=taxonomy_exists('product_brand') ? get_terms(['taxonomy'=>'product_brand','hide_empty'=>false,'orderby'=>'name','order'=>'ASC']) : [];
if (is_wp_error($brands)) { $brands=[]; }
?>
<section class="brand-directory" aria-label="Seznam značek">
<?php if (!$brands) : ?><div class="brands-empty"><h2>Značky brzy doplníme.</h2><p>Mezitím si můžete prohlédnout všechny produkty.</p><a class="primary" href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/')); ?>">Prohlédnout produkty</a></div>
<?php else : ?>
<label class="brand-search">Vyhledat značku<input type="search" data-brand-search placeholder="Název značky…"></label>
<p class="brand-result-count" role="status" aria-live="polite"><?php echo esc_html('Počet značek: '.count($brands)); ?></p>
<div class="brand-grid"><?php foreach ($brands as $brand) :
    $link=get_term_link($brand);
    if (is_wp_error($link)) { continue; }
    $thumbnail_id=absint(get_term_meta($brand->term_id,'thumbnail_id',true));
    $image=$thumbnail_id ? wp_get_attachment_image($thumbnail_id,'medium',false,['alt'=>$brand->name,'loading'=>'lazy']) : '';
    $initial=function_exists('mb_substr') ? mb_substr($brand->name,0,1) : substr(remove_accents($brand->name),0,1);
    $count=(int)$brand->count;
?><a class="brand-card" href="<?php echo esc_url($link); ?>" data-brand-name="<?php echo esc_attr($brand->name); ?>"><span class="brand-card-logo"><?php if ($image) { echo $image; } else { echo '<span class="brand-initial">'.esc_html($initial).'</span>'; } ?></span><strong><?php echo esc_html($brand->name); ?></strong><small><?php echo esc_html(number_format_i18n($count).' '.($count===1?'produkt':($count>=2 && $count<=4?'produkty':'produktů'))); ?></small><span class="brand-card-action">Zobrazit produkty →</span></a><?php endforeach; ?></div>
<p class="brand-no-results" hidden>Žádná značka neodpovídá hledání.</p>
<?php endif; ?></section>
