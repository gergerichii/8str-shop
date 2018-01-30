select n.nid as old_id, n.title as name, CONCAT (bod.body_value, bod.body_summary) as `desc`,
prod.sell_price as price, vsp.field__price_vigsec_value as vig_sec_price,
n.promote as show_on_home, n.sticky as on_list_top, REPLACE(f.uri, 'public://product/', '') as filename, 
m.field_market_value as market_upload, hit.field_hit_value as bestseller, a.field_action_value as is_promo, 
ne.field_new_value as is_new, man2.tid as old_brand_id, man2.name as brand_name, man2.description as brand_desc,
cat2.tid as old_rubric_id
from pdxnode as n
  left join pdxfield_data_uc_product_image as im
      on n.nid = im.entity_id
  left join pdxfile_managed as f
      on im.uc_product_image_fid = f.fid
  left join pdxuc_products as prod
      on prod.nid = n.nid
  left join pdxfield_data_field_market as m
      on m.entity_id = n.nid
  left join pdxfield_data_field_hit as hit
      on hit.entity_id = n.nid
  left join pdxfield_data_field_action as a
      on a.entity_id = n.nid
  left join pdxfield_data_field_new as ne
      on ne.entity_id = n.nid
  left join pdxfield_data_field__price_vigsec as vsp
      on vsp.entity_id = n.nid
  left join pdxfield_data_body as bod
      on bod.entity_id = n.nid
  left join pdxfield_data_field_manufacturer as man
      on man.entity_id = n.nid
  left join pdxtaxonomy_term_data as man2
      on man.field_manufacturer_tid = man2.tid
  left join pdxfield_data_taxonomy_catalog as cat
      on cat.entity_id = n.nid
  left join pdxtaxonomy_term_data as cat2
      on cat.taxonomy_catalog_tid = cat2.tid
where
  n.`type` = 'product' and n.`status` = 1 and f.uri is not null;