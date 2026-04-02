<?php


namespace App\Services\Filters;

class FilterDefinitions
{
  public const ALLOWED_MANUFACTURERS = [
    'Acer', 'Apple', 'Asus', 'Blackview', 'Chuwi', 'Dell', 'DIGMA',
    'Gigabyte', 'HIPER', 'Honor', 'Horizont', 'HP', 'Huawei', 'Infinix',
    'KUU', 'Lenovo', 'Maibenben', 'MSI', 'Ninkear', 'Vision'
  ];

  public const SORT_OPTIONS = [
    'default' => ['text' => 'По популярности', 'column' => 'rating', 'direction' => 'desc'],
    'price_asc' => ['text' => 'По цене (сначала дешевле)', 'column' => 'price', 'direction' => 'asc'],
    'price_desc' => ['text' => 'По цене (сначала дороже)', 'column' => 'price', 'direction' => 'desc'],
  ];

  public const SCREEN_RESOLUTIONS = [
    '3456×2234', '3072×1920', '3024×1964', '2880×1920', '2880×1864',
    '2880×1800', '2880×1620', '2560×1664', '2560×1600', '2160×1440',
    '1920×1200', '1920×1080'
  ];

  public const RAM_OPTIONS = [
    'ram_4' => 4, 'ram_8' => 8, 'ram_12' => 12, 'ram_16' => 16,
    'ram_18' => 18, 'ram_24' => 24, 'ram_32' => 32, 'ram_36' => 36,
    'ram_48' => 48, 'ram_64' => 64,
  ];

  public const CORE_MAPPING = [
    'cores_2' => ['2'],
    'cores_4' => ['4'],
    'cores_5' => ['5'],
    'cores_6' => ['6'],
    'cores_8' => ['8'],
    'cores_10' => ['10', '10 (8+2)', '10 (2+8)'],
    'cores_11' => ['11'],
    'cores_12' => ['12', '12 (4+8)'],
    'cores_14' => ['14'],
    'cores_16' => ['16', '16 (6+10)'],
    'cores_20' => ['20'],
    'cores_24' => ['24'],
  ];

  public const STORAGE_MAPPING = [
    'storage_config_ssd' => ['SSD'],
    'storage_config_ssd_m2_pcie' => ['SSD (M.2 PCIe)'],
    'storage_config_ssd_m2_2280' => ['SSD (M.2 2280)'],
    'storage_config_ssd_m2_nvme_2242' => [
      'SSD (M.2 2242 PCIe 3.0 x4 NVMe)',
      'SSD (M.2 2242 PCIe 3.0x4 NVMe)'
    ],
    'storage_config_hdd' => ['HDD'],
    'storage_config_emmc' => ['eMMC'],
    'storage_config_ufs' => ['UFS'],
  ];

  public const SCREEN_TECH_MAPPING = [
    'screen_tech_ips' => 'IPS',
    'screen_tech_ips_truetone' => 'IPS (поддержка True Tone)',
    'screen_tech_ips_liquid_retina' => 'IPS (Liquid Retina XDR mini-LED)',
    'screen_tech_oled' => 'OLED',
    'screen_tech_tn' => 'TN+Film',
    'screen_tech_wva' => 'WVA',
    'screen_tech_sva' => 'SVA',
  ];

  public const CASE_MATERIAL_MAPPING = [
    'case_material_plastic' => 'пластик',
    'case_material_aluminum' => 'алюминий',
    'case_material_metal' => 'металл',
    'case_material_magnesium' => 'магниевый сплав',
    'case_material_carbon' => 'карбон',
    'case_material_plastic_metal' => 'пластик, металл',
    'case_material_plastic_aluminum' => 'пластик, алюминий',
    'case_material_metal_plastic' => 'металл, пластик',
    'case_material_magnesium_metal' => 'магниевый сплав, металл',
    'case_material_metal_magnesium' => 'металл, магниевый сплав',
    'case_material_plastic_carbon' => 'пластик (углепластик)',
    'case_material_carbon_fiber' => 'углепластик',
    'case_material_pc_cf' => 'пластик (PC + 20% CF)',
  ];

  public const PROCESSOR_MODELS = [
    // Intel
    'processor_model_intel_core_i3_1115g4' => 'Intel Core i3-1115G4',
    'processor_model_intel_core_i5_12450hx' => 'Intel Core i5-12450HX',
    'processor_model_intel_celeron_n4020' => 'Intel Celeron N4020',
    'processor_model_intel_core_i3_n305' => 'Intel Core i3-N305',
    'processor_model_intel_core_5_210h' => 'Intel Core 5 210H',
    'processor_model_intel_core_i5_13420h' => 'Intel Core i5-13420H',
    'processor_model_intel_celeron_n4500' => 'Intel Celeron N4500',
    'processor_model_intel_celeron_n5100' => 'Intel Celeron N5100',
    'processor_model_intel_processor_n100' => 'Intel Processor N100',
    'processor_model_intel_core_ultra_7_155h' => 'Intel Core Ultra 7 155H',
    'processor_model_intel_core_i5_12450h' => 'Intel Core i5-12450H',
    'processor_model_intel_core_5_120u' => 'Intel Core 5 120U',
    'processor_model_intel_celeron_n100' => 'Intel Celeron N100',
    'processor_model_intel_processor_n95' => 'Intel Processor N95',
    'processor_model_intel_processor_n150' => 'Intel Processor N150',
    'processor_model_intel_core_i7_12700h' => 'Intel Core i7-12700H',
    'processor_model_intel_core_i3_1315u' => 'Intel Core i3-1315U',
    'processor_model_intel_core_ultra_5_125h' => 'Intel Core Ultra 5 125H',
    'processor_model_intel_core_i7_1355u' => 'Intel Core i7-1355U',
    'processor_model_intel_core_i3_1215u' => 'Intel Core i3-1215U',
    'processor_model_intel_core_i3_10110u' => 'Intel Core i3-10110U',
    'processor_model_intel_core_i5_11320h' => 'Intel Core i5-11320H',
    'processor_model_intel_core_i5_12500h' => 'Intel Core i5-12500H',
    'processor_model_intel_core_i5_1155g7' => 'Intel Core i5-1155G7',
    'processor_model_intel_core_i5_1235u' => 'Intel Core i5-1235U',
    'processor_model_intel_core_i5_1335u' => 'Intel Core i5-1335U',
    'processor_model_intel_core_i3_1000ng4' => 'Intel Core i3-1000NG4',
    'processor_model_intel_core_i7_13620h' => 'Intel Core i7-13620H',
    'processor_model_intel_core_i7_14650hx' => 'Intel Core i7-14650HX',
    'processor_model_intel_core_ultra_5_125u' => 'Intel Core Ultra 5 125U',
    'processor_model_intel_pentium_silver_n6000' => 'Intel Pentium Silver N6000',
    'processor_model_intel_core_i7_1195g7' => 'Intel Core i7-1195G7',
    'processor_model_intel_core_i5_1334u' => 'Intel Core i5-1334U',
    'processor_model_intel_core_i7_13650hx' => 'Intel Core i7-13650HX',
    'processor_model_intel_core_i3_1305u' => 'Intel Core i3-1305U',
    'processor_model_intel_core_i3_1220p' => 'Intel Core i3-1220P',
    'processor_model_intel_core_i7_14700hx' => 'Intel Core i7-14700HX',
    'processor_model_intel_core_i5_13450hx' => 'Intel Core i5-13450HX',
    'processor_model_intel_core_i5_14450hx' => 'Intel Core i5-14450HX',
    'processor_model_intel_core_ultra_7_255h' => 'Intel Core Ultra 7 255H',
    'processor_model_intel_core_i5_1135g7' => 'Intel Core i5-1135G7',
    'processor_model_intel_core_i7_1255u' => 'Intel Core i7-1255U',
    'processor_model_intel_core_i7_13700h' => 'Intel Core i7-13700H',
    'processor_model_intel_core_ultra_7_155u' => 'Intel Core Ultra 7 155U',
    'processor_model_intel_core_ultra_5_225h' => 'Intel Core Ultra 5 225H',
    'processor_model_intel_core_i7_13700hx' => 'Intel Core i7-13700HX',
    'processor_model_intel_core_i7_1360p' => 'Intel Core i7-1360P',
    'processor_model_intel_pentium_silver_n5030' => 'Intel Pentium Silver N5030',
    'processor_model_intel_core_ultra_7_255u' => 'Intel Core Ultra 7 255U',
    'processor_model_intel_core_ultra_9_285h' => 'Intel Core Ultra 9 285H',
    'processor_model_intel_core_ultra_9_275hx' => 'Intel Core Ultra 9 275HX',
    'processor_model_intel_core_ultra_5_225u' => 'Intel Core Ultra 5 225U',
    'processor_model_intel_core_i7_1185g7' => 'Intel Core i7-1185G7',
    'processor_model_intel_core_ultra_5_226v' => 'Intel Core Ultra 5 226V',
    'processor_model_intel_core_7_240h' => 'Intel Core 7 240H',
    'processor_model_intel_core_ultra_5_135h' => 'Intel Core Ultra 5 135H',
    'processor_model_intel_core_ultra_7_256v' => 'Intel Core Ultra 7 256V',
    'processor_model_intel_core_ultra_7_255hx' => 'Intel Core Ultra 7 255HX',
    'processor_model_intel_core_ultra_7_165u' => 'Intel Core Ultra 7 165U',
    'processor_model_intel_core_ultra_7_258v' => 'Intel Core Ultra 7 258V',
    'processor_model_intel_core_i7_1165g7' => 'Intel Core i7-1165G7',
    'processor_model_intel_core_i7_12650h' => 'Intel Core i7-12650H',
    'processor_model_intel_core_i5_11400h' => 'Intel Core i5-11400H',
    'processor_model_intel_processor_n97' => 'Intel Processor N97',
    'processor_model_intel_core_i5_1240p' => 'Intel Core i5-1240P',
    'processor_model_intel_core_i7_11390h' => 'Intel Core i7-11390H',
    'processor_model_intel_core_i5_10400h' => 'Intel Core i5-10400H',
    'processor_model_intel_core_i9_11900h' => 'Intel Core i9-11900H',
    'processor_model_intel_core_i7_10750h' => 'Intel Core i7-10750H',
    'processor_model_intel_celeron_n5095' => 'Intel Celeron N5095',
    'processor_model_intel_core_i5_1035g1' => 'Intel Core i5-1035G1',
    'processor_model_intel_core_i3_n300' => 'Intel Core i3-N300',
    'processor_model_intel_core_5_220h' => 'Intel Core 5 220H',
    'processor_model_intel_core_ultra_9_288v' => 'Intel Core Ultra 9 288V',
    'processor_model_intel_core_i5_13500h' => 'Intel Core i5-13500H',
    'processor_model_intel_core_i5_1030ng7' => 'Intel Core i5-1030NG7',
    'processor_model_intel_core_i9_13900hk' => 'Intel Core i9 13900HK',
    'processor_model_intel_core_3_n350' => 'Intel Core 3 N350',
    'processor_model_intel_core_7_150u' => 'Intel Core 7 150U',
    'processor_model_intel_core_i3_1025g1' => 'Intel Core i3-1025G1',
    'processor_model_intel_processor_n200' => 'Intel Processor N200',
    'processor_model_intel_core_i9_14900hx' => 'Intel Core i9-14900HX',
    'processor_model_intel_core_ultra_9_185h' => 'Intel Core Ultra 9 185H',

    // AMD
    'processor_model_amd_ryzen_5_7235hs' => 'AMD Ryzen 5 7235HS',
    'processor_model_amd_ryzen_5_7430u' => 'AMD Ryzen 5 7430U',
    'processor_model_amd_ryzen_5_8645hs' => 'AMD Ryzen 5 8645HS',
    'processor_model_amd_ryzen_7_7445hs' => 'AMD Ryzen 7 7445HS',
    'processor_model_amd_ryzen_7_7735hs' => 'AMD Ryzen 7 7735HS',
    'processor_model_amd_ryzen_5_7520u' => 'AMD Ryzen 5 7520U',
    'processor_model_amd_ryzen_7_5700u' => 'AMD Ryzen 7 5700U',
    'processor_model_amd_ryzen_5_7530u' => 'AMD Ryzen 5 7530U',
    'processor_model_amd_ryzen_7_7435hs' => 'AMD Ryzen 7 7435HS',
    'processor_model_amd_ryzen_3_7320u' => 'AMD Ryzen 3 7320U',
    'processor_model_amd_ryzen_5_7535hs' => 'AMD Ryzen 5 7535HS',
    'processor_model_amd_ryzen_9_8940hx' => 'AMD Ryzen 9 8940HX',
    'processor_model_amd_ryzen_7_5825u' => 'AMD Ryzen 7 5825U',
    'processor_model_amd_ryzen_7_7730u' => 'AMD Ryzen 7 7730U',
    'processor_model_amd_athlon_silver_7120u' => 'AMD Athlon Silver 7120U',
    'processor_model_amd_ryzen_5_220' => 'AMD Ryzen 5 220',
    'processor_model_amd_ryzen_ai_5_340' => 'AMD Ryzen AI 5 340',
    'processor_model_amd_ryzen_7_250' => 'AMD Ryzen 7 250',
    'processor_model_amd_ryzen_9_8945hs' => 'AMD Ryzen 9 8945HS',
    'processor_model_amd_ryzen_7_260' => 'AMD Ryzen 7 260',
    'processor_model_amd_ryzen_7_8845hs' => 'AMD Ryzen 7 8845HS',
    'processor_model_amd_ryzen_5_5600u' => 'AMD Ryzen 5 5600U',
    'processor_model_amd_ryzen_5_8540u' => 'AMD Ryzen 5 8540U',
    'processor_model_amd_ryzen_5_5500u' => 'AMD Ryzen 5 5500U',
    'processor_model_amd_ryzen_5_6600h' => 'AMD Ryzen 5 6600H',
    'processor_model_amd_ryzen_5_4600h' => 'AMD Ryzen 5 4600H',
    'processor_model_amd_ryzen_3_7330u' => 'AMD Ryzen 3 7330U',
    'processor_model_amd_ryzen_7_7745hx' => 'AMD Ryzen 7 7745HX',
    'processor_model_amd_ryzen_ai_max_395' => 'AMD Ryzen AI MAX+ 395',
    'processor_model_amd_ryzen_ai_7_350' => 'AMD Ryzen AI 7 350',
    'processor_model_amd_ryzen_5_240' => 'AMD Ryzen 5 240',
    'processor_model_amd_ryzen_7_8745hx' => 'AMD Ryzen 7 8745HX',
    'processor_model_amd_ryzen_5_8640hs' => 'AMD Ryzen 5 8640HS',
    'processor_model_amd_ryzen_7_8840hx' => 'AMD Ryzen 7 8840HX',
    'processor_model_amd_ryzen_ai_5_330' => 'AMD Ryzen AI 5 330',
    'processor_model_amd_ryzen_ai_5_pro_340' => 'AMD Ryzen AI 5 Pro 340',
    'processor_model_amd_athlon_gold_3150u' => 'AMD Athlon Gold 3150U',
    'processor_model_amd_ryzen_7_3700u' => 'AMD Ryzen 7 3700U',
    'processor_model_amd_athlon_silver_3050u' => 'AMD Athlon Silver 3050U',
    'processor_model_amd_ryzen_7_7840u' => 'AMD Ryzen 7 7840U',
    'processor_model_amd_ryzen_7_6800h' => 'AMD Ryzen 7 6800H',
    'processor_model_amd_ryzen_7_4800h' => 'AMD Ryzen 7 4800H',
    'processor_model_amd_ryzen_ai_9_hx_370' => 'AMD Ryzen AI 9 HX 370',
    'processor_model_amd_ryzen_3_4300u' => 'AMD Ryzen 3 4300U',
    'processor_model_amd_ryzen_7_7735u' => 'AMD Ryzen 7 7735U',
    'processor_model_amd_ryzen_5_7533hs' => 'AMD Ryzen 5 7533HS',
    'processor_model_amd_ryzen_7_8840hs' => 'AMD Ryzen 7 8840HS',
    'processor_model_amd_ryzen_5_5625u' => 'AMD Ryzen 5 5625U',
    'processor_model_amd_ryzen_5_7640hs' => 'AMD Ryzen 5 7640HS',
    'processor_model_amd_ryzen_7_7435h' => 'AMD Ryzen 7 7435H',
    'processor_model_amd_ryzen_9_9955hx' => 'AMD Ryzen 9 9955HX',

    // Apple
    'processor_model_apple_m2' => 'Apple M2',
    'processor_model_apple_m4' => 'Apple M4',
    'processor_model_apple_m1' => 'Apple M1',
    'processor_model_apple_m3' => 'Apple M3',
    'processor_model_apple_m4_pro' => 'Apple M4 Pro',
    'processor_model_apple_m2_pro' => 'Apple M2 Pro',
    'processor_model_apple_m3_pro' => 'Apple M3 Pro',
    'processor_model_apple_m3_max' => 'Apple M3 Max',
    'processor_model_apple_m4_max' => 'Apple M4 Max',
    'processor_model_apple_m2_max' => 'Apple M2 Max',
    'processor_model_apple_m5' => 'Apple M5',

    // Qualcomm/Snapdragon
    'processor_model_snapdragon_x_plus_x1p_42_100' => 'Snapdragon X Plus X1P-42-100',
    'processor_model_snapdragon_x_elite_x1e_78_100' => 'Snapdragon X Elite X1E-78-100',
    'processor_model_snapdragon_x_x1_26_100' => 'Snapdragon X X1-26-100',
  ];

  public const LINE_MAPPING = [
    // Lenovo
    'line_loq_lenovo' => 'LOQ (Lenovo)',
    'line_v_lenovo' => 'V (Lenovo)',
    'line_ideapad_slim_lenovo' => 'IdeaPad Slim (Lenovo)',
    'line_ideapad_1_lenovo' => 'IdeaPad 1 (Lenovo)',
    'line_v15_lenovo' => 'V15 (Lenovo)',
    'line_thinkbook_lenovo' => 'ThinkBook (Lenovo)',
    'line_ideapad_3_lenovo' => 'IdeaPad 3 (Lenovo)',
    'line_thinkpad_t_lenovo' => 'ThinkPad T (Lenovo)',
    'line_thinkpad_e_lenovo' => 'ThinkPad E (Lenovo)',
    'line_thinkpad_lenovo' => 'ThinkPad (Lenovo)',
    'line_thinkpad_t16_lenovo' => 'ThinkPad T16 (Lenovo)',
    'line_thinkpad_x_lenovo' => 'ThinkPad X (Lenovo)',
    'line_thinkpad_l_lenovo' => 'ThinkPad L (Lenovo)',
    'line_ideapad_gaming_3_lenovo' => 'IdeaPad Gaming 3 (Lenovo)',
    'line_chromebook_lenovo' => 'Chromebook (Lenovo)',
    'line_yoga_lenovo' => 'Yoga (Lenovo)',
    'line_ideapad_l_lenovo' => 'IdeaPad L (Lenovo)',
    'line_legion_5_pro_lenovo' => 'Legion 5 Pro (Lenovo)',
    'line_legion_5_lenovo' => 'Legion 5 (Lenovo)',

    // Asus
    'line_vivobook_go_15_asus' => 'Vivobook Go 15 (Asus)',
    'line_tuf_gaming_asus' => 'TUF Gaming (Asus)',
    'line_vivobook_asus' => 'VivoBook (Asus)',
    'line_vivobook_15_asus' => 'VivoBook 15 (Asus)',
    'line_vivobook_s_16_asus' => 'VivoBook S 16 (Asus)',
    'line_zenbook_asus' => 'ZenBook (Asus)',
    'line_vivobook_s_asus' => 'VivoBook S (Asus)',
    'line_rog_strix_g16_asus' => 'ROG Strix G16 (Asus)',
    'line_rog_zephyrus_asus' => 'ROG Zephyrus (Asus)',
    'line_expertbook_asus' => 'ExpertBook (Asus)',
    'line_rog_flow_asus' => 'ROG Flow (Asus)',
    'line_rog_strix_asus' => 'ROG Strix (Asus)',
    'line_vivobook_s_14_asus' => 'Vivobook S 14 (Asus)',
    'line_v16_asus' => 'V16 (Asus)',
    'line_vivobook_16_asus' => 'Vivobook 16 (Asus)',
    'line_zenbook_pro_asus' => 'Zenbook Pro (Asus)',
    'line_vivobook_14_asus' => 'VivoBook 14 (Asus)',
    'line_vivobook_13_asus' => 'Vivobook 13 (Asus)',
    'line_expertbook_b5_flip_asus' => 'ExpertBook B5 Flip (Asus)',
    'line_zenbook_s_asus' => 'Zenbook S (Asus)',
    'line_vivobook_17_asus' => 'VivoBook 17 (Asus)',
    'line_proart_asus' => 'ProArt (Asus)',
    'line_rog_strix_g18_g814_asus' => 'ROG Strix G18 G814 (Asus)',
    'line_expertbook_b5_asus' => 'ExpertBook B5 (Asus)',
    'line_tuf_gaming_a15_asus' => 'TUF Gaming A15 (Asus)',
    'line_tuf_gaming_a17_asus' => 'TUF Gaming A17 (Asus)',

    // Acer
    'line_aspire_3_acer' => 'Aspire 3 (Acer)',
    'line_extensa_acer' => 'Extensa (Acer)',
    'line_aspire_lite_acer' => 'Aspire Lite (Acer)',
    'line_aspire_5_acer' => 'Aspire 5 (Acer)',
    'line_nitro_v_15_acer' => 'Nitro V 15 (Acer)',
    'line_aspire_go_acer' => 'Aspire Go (Acer)',
    'line_nitro_acer' => 'Nitro (Acer)',
    'line_aspire_15_acer' => 'Aspire 15 (Acer)',
    'line_travelmate_acer' => 'TravelMate (Acer)',
    'line_swift_ai_acer' => 'Swift AI (Acer)',
    'line_predator_helios_acer' => 'Predator Helios (Acer)',
    'line_swift_go_acer' => 'Swift Go (Acer)',
    'line_swift_3_acer' => 'Swift 3 (Acer)',
    'line_swift_x_acer' => 'Swift X (Acer)',
    'line_aspire_7_acer' => 'Aspire 7 (Acer)',
    'line_nitro_5_acer' => 'Nitro 5 (Acer)',
    'line_aspire_17_acer' => 'Aspire 17 (Acer)',
    'line_gadget_e10_etbook_acer' => 'Gadget E10 ETBook (Acer)',

    // HP
    'line_victus_hp' => 'Victus (HP)',
    'line_laptop_15_hp' => 'Laptop 15 (HP)',
    'line_pavilion_hp' => 'Pavilion (HP)',
    'line_elitebook_hp' => 'EliteBook (HP)',
    'line_probook_hp' => 'ProBook (HP)',
    'line_255_hp' => '255 (HP)',
    'line_250_hp' => '250 (HP)',
    'line_240_hp' => '240 (HP)',
    'line_zbook_hp' => 'ZBook (HP)',
    'line_250_g9_hp' => '250 G9 (HP)',
    'line_probook_440_g9_hp' => 'Probook 440 G9 (HP)',

    // Dell
    'line_vostro_dell' => 'Vostro (Dell)',
    'line_xps_dell' => 'XPS (Dell)',
    'line_latitude_dell' => 'Latitude (Dell)',

    // Apple
    'line_macbook_air_m2_2022_apple' => 'MacBook Air M2 2022 (Apple)',
    'line_macbook_air_apple' => 'Macbook Air (Apple)',
    'line_macbook_air_m1_2020_apple' => 'MacBook Air M1 2020 (Apple)',
    'line_macbook_pro_apple' => 'MacBook Pro (Apple)',
    'line_macbook_pro_m1_2020_apple' => 'MacBook Pro M1 2020 (Apple)',

    // MSI
    'line_modern_msi' => 'Modern (MSI)',
    'line_thin_a15_msi' => 'Thin A15 (MSI)',
    'line_crosshair_msi' => 'Crosshair (MSI)',
    'line_katana_msi' => 'Katana (MSI)',
    'line_sword_msi' => 'Sword (MSI)',
    'line_prestige_msi' => 'Prestige (MSI)',
    'line_vector_msi' => 'Vector (MSI)',
    'line_venture_msi' => 'Venture (MSI)',
    'line_cyborg_15_msi' => 'Cyborg 15 (MSI)',
    'line_pulse_msi' => 'Pulse (MSI)',
    'line_stealth_16_msi' => 'Stealth 16 (MSI)',
    'line_creatorpro_msi' => 'CreatorPro (MSI)',
    'line_cyborg_msi' => 'Cyborg (MSI)',
    'line_thin_15_msi' => 'Thin 15 (MSI)',

    // Huawei/Honor
    'line_matebook_d_16_huawei' => 'MateBook D 16 (Huawei)',
    'line_matebook_huawei' => 'MateBook (Huawei)',
    'line_magicbook_honor' => 'MagicBook (Honor)',
    'line_magicbook_pro_honor' => 'MagicBook Pro (Honor)',
    'line_art_14_honor' => 'Art 14 (Honor)',
    'line_matebook_13_huawei' => 'MateBook 13 (Huawei)',
    'line_matebook_d_15_huawei' => 'MateBook D 15 (Huawei)',
    'line_matebook_x_pro_huawei' => 'MateBook X Pro (Huawei)',

    // Chuwi
    'line_herobook_pro_chuwi' => 'HeroBook Pro (Chuwi)',
    'line_corebook_x_chuwi' => 'CoreBook X (Chuwi)',
    'line_gemibook_xpro_chuwi' => 'GemiBook XPro (Chuwi)',
    'line_gemibook_plus_chuwi' => 'GemiBook Plus (Chuwi)',
    'line_herobook_plus_chuwi' => 'HeroBook Plus (Chuwi)',
    'line_corebook_max_chuwi' => 'CoreBook Max (Chuwi)',
    'line_corebook_xpro_chuwi' => 'CoreBook XPro (Chuwi)',
    'line_freebook_chuwi' => 'FreeBook (Chuwi)',

    // Horizont
    'line_hbook_15_horizont' => 'H-book 15 (Horizont)',
    'line_hbook_14_horizont' => 'H-book 14 (Horizont)',
    'line_hbook_16_horizont' => 'H-book 16 (Horizont)',

    // KUU
    'line_a5_kuu' => 'А5 (KUU)',
    'line_yepbook_2_kuu' => 'Yepbook 2 (KUU)',
    'line_a6_kuu' => 'А6 (KUU)',
    'line_g3_pro_kuu' => 'G3 Pro (KUU)',
    'line_g5_kuu' => 'G5 (KUU)',
    'line_xbook_kuu' => 'XBook (KUU)',
    'line_g3_kuu' => 'G3 (KUU)',

    // Maibenben
    'line_m657_maibenben' => 'M657 (Maibenben)',
    'line_m645_maibenben' => 'M645 (Maibenben)',
    'line_medio_maibenben' => 'Medio (Maibenben)',
    'line_m557_maibenben' => 'M557 (Maibenben)',
    'line_xtreme_typhoon_maibenben' => 'X-Treme Typhoon (Maibenben)',
    'line_perfectum_maibenben' => 'Perfectum (Maibenben)',

    // Ninkear
    'line_n16_air_ninkear' => 'N16 Air (Ninkear)',
    'line_n15_pro_ninkear' => 'N15 Pro (Ninkear)',

    // Infinix
    'line_inbook_y3_plus_infinix' => 'Inbook Y3 Plus (Infinix)',
    'line_inbook_infinix' => 'Inbook (Infinix)',
    'line_inbook_y3_max_infinix' => 'Inbook Y3 Max (Infinix)',
    'line_inbook_y2_plus_infinix' => 'Inbook Y2 Plus (Infinix)',
    'line_inbook_air_infinix' => 'Inbook Air (Infinix)',

    // Gigabyte
    'line_gaming_gigabyte' => 'Gaming (Gigabyte)',
    'line_g6_gigabyte' => 'G6 (Gigabyte)',

    // HIPER
    'line_workbook_hiper' => 'WorkBook (HIPER)',
    'line_dzen_hiper' => 'Dzen (HIPER)',

    // Blackview
    'line_acebook_blackview' => 'Acebook (Blackview)',

    // Vision
    'line_l16_se_vision' => 'L16 SE (Vision)',
    'line_l16_origin_vision' => 'L16 Origin (Vision)',

    // DIGMA
    'line_eve_digma' => 'EVE (DIGMA)',

    'line_null' => 'NULL',
  ];

  public const COLOR_MAPPING = [
    'case_color_black' => 'черный',
    'case_color_white' => 'белый',
    'case_color_silver' => 'серебристый',
    'case_color_gray' => 'серый',
    'case_color_dark_gray' => 'темно-серый',
    'case_color_blue' => 'синий',
    'case_color_sapphire_blue' => 'синий (сапфировый)',
    'case_color_dark_blue' => 'темно-синий',
    'case_color_light_blue' => 'голубой',
    'case_color_green' => 'зеленый',
    'case_color_gold' => 'золотистый',
    'case_color_lilac' => 'сиреневый',
    'case_color_beige' => 'бежевый',
    'case_color_white_gray' => 'белый , серый',
    'case_color_gray_blue' => 'серый, синий',
    'case_color_black_silver' => 'черный, серебристый',
  ];

  public const GPU_CATEGORIES = [
    'integrated_intel' => [
      'models' => ['Intel Iris Xe', 'Intel UHD Graphics', 'Intel HD Graphics', 'Intel Arc Graphics'],
      'search_terms' => ['Intel', 'Iris', 'UHD', 'Arc', 'Graphics']
    ],
    'discrete_nvidia' => [
      'models' => ['NVIDIA GeForce', 'RTX', 'GTX', 'MX', 'Quadro'],
      'search_terms' => ['NVIDIA', 'GeForce', 'RTX', 'GTX', 'MX']
    ],
    'discrete_amd' => [
      'models' => ['AMD Radeon', 'Radeon RX', 'Radeon'],
      'search_terms' => ['AMD', 'Radeon', 'RX']
    ],
    'integrated_amd' => [
      'models' => ['AMD Radeon Graphics', 'Vega'],
      'search_terms' => ['AMD Graphics', 'Vega']
    ],
    'apple_gpu' => [
      'models' => ['Apple M', 'GPU'],
      'search_terms' => ['Apple', 'M1', 'M2', 'M3', 'M4', 'M5']
    ],
    'qualcomm' => [
      'models' => ['Qualcomm', 'Adreno'],
      'search_terms' => ['Qualcomm', 'Adreno']
    ]
  ];

  public const PER_PAGE = 40;
}
