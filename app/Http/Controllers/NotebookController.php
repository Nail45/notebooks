<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\View\Factory;
use App\Models\Notebook;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class MainController extends Controller
{
  private const ALLOWED_MANUFACTURERS = [
    'Acer',
    'Apple',
    'Asus',
    'Blackview',
    'Chuwi',
    'Dell',
    'DIGMA',
    'Gigabyte',
    'HIPER',
    'Honor',
    'Horizont',
    'HP',
    'Huawei',
    'Infinix',
    'KUU',
    'Lenovo',
    'Maibenben',
    'MSI',
    'Ninkear',
    'Vision'
  ];

  private const SORT_OPTIONS = [
    'default' => [
      'text' => 'По популярности',
      'column' => 'rating',
      'direction' => 'desc'
    ],
    'price_asc' => [
      'text' => 'По цене (сначала дешевле)',
      'column' => 'price',
      'direction' => 'asc'
    ],
    'price_desc' => [
      'text' => 'По цене (сначала дороже)',
      'column' => 'price',
      'direction' => 'desc'
    ],
  ];

  private const SCREEN_RESOLUTIONS = [
    '3456×2234', '3072×1920', '3024×1964', '2880×1920', '2880×1864',
    '2880×1800', '2880×1620', '2560×1664', '2560×1600', '2160×1440',
    '1920×1200', '1920×1080'
  ];

  private const RAM_OPTIONS = [
    'ram_4' => 4, 'ram_8' => 8, 'ram_12' => 12, 'ram_16' => 16,
    'ram_18' => 18, 'ram_24' => 24, 'ram_32' => 32, 'ram_36' => 36,
    'ram_48' => 48, 'ram_64' => 64,
  ];

  private const CORE_MAPPING = [
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

  private const STORAGE_MAPPING = [
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

  private const SCREEN_TECH_MAPPING = [
    'screen_tech_ips' => 'IPS',
    'screen_tech_ips_truetone' => 'IPS (поддержка True Tone)',
    'screen_tech_ips_liquid_retina' => 'IPS (Liquid Retina XDR mini-LED)',
    'screen_tech_oled' => 'OLED',
    'screen_tech_tn' => 'TN+Film',
    'screen_tech_wva' => 'WVA',
    'screen_tech_sva' => 'SVA',
  ];

  private const CASE_MATERIAL_MAPPING = [
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

  private const PER_PAGE = 40;

  public function index(Request $request): Factory|View|JsonResponse
  {
    try {
      // Получаем параметры из запроса
      $sort = $request->get('sort', 'default');
      $manufacturer = $request->get('manufacturer', 'all');
      $currentSort = self::SORT_OPTIONS[$sort] ?? self::SORT_OPTIONS['default'];

      // Построение основного запроса
      $query = $this->buildBaseQuery($manufacturer);

      // Применение фильтров
      $this->applyFilters($query, $request);

      // Применение сортировки
      $query->orderBy($currentSort['column'], $currentSort['direction']);

      // Пагинация с сохранением ВСЕХ параметров
      $result = $this->paginateResults($query, $request, $manufacturer, $sort);

      // Подсчет активных фильтров (все параметры кроме служебных)
      $activeFilters = $this->getActiveFilters($request);

      // Ответ в зависимости от типа запроса
      return $this->buildResponse($result, $activeFilters, $request, $manufacturer, $sort);

    } catch (\Exception $e) {
      return $this->handleError($e, $request);
    }
  }

  private function buildBaseQuery(string $manufacturer): Builder
  {
    $query = Notebook::query();

    if ($manufacturer && $manufacturer !== 'all' && in_array($manufacturer, self::ALLOWED_MANUFACTURERS)) {
      $query->where('manufacturer', $manufacturer);
    } else {
      $query->whereIn('manufacturer', self::ALLOWED_MANUFACTURERS);
    }

    return $query;
  }

  private function applyFilters(Builder $query, Request $request): void
  {
    // Фильтр по цене
    if ($request->has('min_price') && is_numeric($request->min_price) && $request->min_price > 0) {
      $query->where('price', '>=', (float)$request->min_price);
    }

    if ($request->has('max_price') && is_numeric($request->max_price) && $request->max_price > 0) {
      $query->where('price', '<=', (float)$request->max_price);
    }

    // Фильтр по году выпуска
    if ($request->has('release_year_from') && is_numeric($request->release_year_from) && $request->release_year_from > 0) {
      $query->where('release_date', '>=', (int)$request->release_year_from);
    }

    if ($request->has('release_year_to') && is_numeric($request->release_year_to) && $request->release_year_to > 0) {
      $query->where('release_date', '<=', (int)$request->release_year_to);
    }

    // Фильтр по виду
    $viewFilters = [];
    foreach (['worker', 'gaming', 'ultrabook', 'universal', 'domestic'] as $type) {
      if ($request->filled($type)) {
        $viewFilters[] = $request->$type;
      }
    }

    if (!empty($viewFilters)) {
      $query->whereIn('view_title', $viewFilters);
    }

    // Фильтр по диагонали экрана
    if ($request->has('screen_diagonal_from') && is_numeric($request->screen_diagonal_from) && $request->screen_diagonal_from > 0) {
      $query->where('screen_diagonal', '>=', (float)$request->screen_diagonal_from);
    }

    if ($request->has('screen_diagonal_to') && is_numeric($request->screen_diagonal_to) && $request->screen_diagonal_to > 0) {
      $query->where('screen_diagonal', '<=', (float)$request->screen_diagonal_to);
    }

    // Фильтр по разрешению экрана
    $selectedResolutions = [];
    foreach (self::SCREEN_RESOLUTIONS as $resolution) {
      if ($request->has('resolution_' . $resolution)) {
        $selectedResolutions[] = $resolution;
      }
    }

    if (!empty($selectedResolutions)) {
      $query->whereIn('screen_resolution', $selectedResolutions);
    }

    // Фильтрация по популярным параметрам
    if ($request->has('keyboard_backlight')) {
      $query->where(function ($q) {
        $q->where('keyboard_backlight', 'LIKE', 'есть%')
          ->orWhere('keyboard_backlight', 'LIKE', 'есть (%');
      });
    }

    if ($request->has('numeric_keypad')) {
      $query->where(function ($q) {
        $q->where('numeric_keypad', 'LIKE', 'есть%')
          ->orWhere('numeric_keypad', 'LIKE', 'есть (%');
      });
    }

    if ($request->has('touch_screen')) {
      $query->where('touch_screen', '=', 'есть');
    }

    if ($request->has('hdmi_port')) {
      $query->where(function ($q) {
        $q->where('hdmi_port', 'LIKE', 'есть%')
          ->orWhere('hdmi_port', 'LIKE', 'есть (%')
          ->orWhere('hdmi_port', 'LIKE', '%HDMI%')
          ->orWhere('hdmi_port', 'LIKE', '%mini%');
      });
    }

    if ($request->has('displayport')) {
      $query->where(function ($q) {
        $q->where('displayport', 'LIKE', 'есть%')
          ->orWhere('displayport', 'LIKE', 'есть (%')
          ->orWhere('displayport', 'LIKE', '%DisplayPort%')
          ->orWhere('displayport', 'LIKE', '%USB Type-C%')
          ->orWhere('displayport', 'LIKE', '%Type-C%');
      });
    }

    // Фильтр по оперативной памяти
    $selectedRam = [];
    foreach (self::RAM_OPTIONS as $field => $value) {
      if ($request->has($field)) {
        $selectedRam[] = $value;
      }
    }

    if (!empty($selectedRam)) {
      $query->whereIn('ram_capacity', $selectedRam);
    }

    // Фильтр по типу оперативной памяти
    $selectedRamTypes = [];
    if ($request->has('ram_type_ddr4')) {
      $selectedRamTypes[] = 'DDR4';
    }
    if ($request->has('ram_type_ddr5')) {
      $selectedRamTypes[] = 'DDR5';
    }
    if ($request->has('ram_type_lpddr4')) {
      $selectedRamTypes[] = 'LPDDR4';
    }
    if ($request->has('ram_type_lpddr4x')) {
      $selectedRamTypes[] = 'LPDDR4X';
    }
    if ($request->has('ram_type_lpddr5')) {
      $selectedRamTypes[] = 'LPDDR5';
    }
    if ($request->has('ram_type_lpddr5x')) {
      $selectedRamTypes[] = 'LPDDR5X';
    }

    if (!empty($selectedRamTypes)) {
      $query->whereIn('ram_type', $selectedRamTypes);
    }

    // Фильтр по серии процессора
    $selectedProcessors = [];
    // Intel
    if ($request->has('processor_intel_core_i3')) {
      $selectedProcessors[] = 'Intel Core i3';
    }
    if ($request->has('processor_intel_core_i5')) {
      $selectedProcessors[] = 'Intel Core i5';
    }
    if ($request->has('processor_intel_core_i7')) {
      $selectedProcessors[] = 'Intel Core i7';
    }
    if ($request->has('processor_intel_celeron')) {
      $selectedProcessors[] = 'Intel Celeron';
    }
    if ($request->has('processor_intel_core_5')) {
      $selectedProcessors[] = 'Intel Core 5';
    }
    if ($request->has('processor_intel_processor')) {
      $selectedProcessors[] = 'Intel Processor';
    }
    if ($request->has('processor_intel_core_ultra_7')) {
      $selectedProcessors[] = 'Intel Core Ultra 7';
    }
    if ($request->has('processor_intel_core_ultra_5')) {
      $selectedProcessors[] = 'Intel Core Ultra 5';
    }
    if ($request->has('processor_intel_core_ultra_9')) {
      $selectedProcessors[] = 'Intel Core Ultra 9';
    }
    if ($request->has('processor_intel_pentium')) {
      $selectedProcessors[] = 'Intel Pentium';
    }

    // AMD
    if ($request->has('processor_amd_ryzen_5')) {
      $selectedProcessors[] = 'AMD Ryzen 5';
    }
    if ($request->has('processor_amd_ryzen_7')) {
      $selectedProcessors[] = 'AMD Ryzen 7';
    }
    if ($request->has('processor_amd_ryzen_3')) {
      $selectedProcessors[] = 'AMD Ryzen 3';
    }
    if ($request->has('processor_amd_ryzen_9')) {
      $selectedProcessors[] = 'AMD Ryzen 9';
    }
    if ($request->has('processor_amd_athlon')) {
      $selectedProcessors[] = 'AMD Athlon';
    }
    if ($request->has('processor_amd_ryzen_ai')) {
      $selectedProcessors[] = 'AMD Ryzen AI';
    }
    if ($request->has('processor_ryzen_ai_5')) {
      $selectedProcessors[] = 'Ryzen AI 5';
    }
    if ($request->has('processor_amd_ryzen_ai_7')) {
      $selectedProcessors[] = 'AMD Ryzen AI 7';
    }

    // Apple
    if ($request->has('processor_apple_m2')) {
      $selectedProcessors[] = 'Apple M2';
    }
    if ($request->has('processor_apple_m4')) {
      $selectedProcessors[] = 'Apple M4';
    }
    if ($request->has('processor_apple_m1')) {
      $selectedProcessors[] = 'Apple M1';
    }
    if ($request->has('processor_apple_m3')) {
      $selectedProcessors[] = 'Apple M3';
    }
    if ($request->has('processor_apple_m3_max')) {
      $selectedProcessors[] = 'Apple M3 Max';
    }

    if ($request->has('processor_qualcomm')) {
      $selectedProcessors[] = 'Qualcomm';
    }

    if (!empty($selectedProcessors)) {
      $query->where(function ($q) use ($selectedProcessors) {
        foreach ($selectedProcessors as $processor) {
          $q->orWhere('processor_series', 'LIKE', '%' . $this->escapeLike($processor) . '%');
        }
      });
    }

    // Фильтр по модели процессора
    $processorModelOptions = [
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

    $selectedModels = [];
    foreach ($processorModelOptions as $field => $model) {
      if ($request->has($field) && $request->$field) {
        $selectedModels[] = $model;
      }
    }

    if (!empty($selectedModels)) {
      $query->whereIn('processor_model', $selectedModels);
    }

    // Фильтр по количеству ядер
    $selectedCoreValues = [];
    foreach (self::CORE_MAPPING as $field => $values) {
      if ($request->has($field)) {
        $selectedCoreValues = array_merge($selectedCoreValues, $values);
      }
    }

    if (!empty($selectedCoreValues)) {
      $query->whereIn('cores_count', array_unique($selectedCoreValues));
    }

    // Фильтр по типу видеокарты
    $gpuConditions = [];
    if ($request->has('gpu_type_integrated')) {
      $gpuConditions[] = ['gpu_type', '=', 'встроенная'];
    }
    if ($request->has('gpu_type_discrete')) {
      $gpuConditions[] = ['gpu_type', '=', 'дискретная'];
    }

    if (!empty($gpuConditions)) {
      $query->where(function ($q) use ($gpuConditions) {
        foreach ($gpuConditions as $condition) {
          $q->orWhere($condition[0], $condition[1], $condition[2]);
        }
      });
    }

    // Фильтр по модели видеокарты
    $gpuCategories = [
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

    $selectedCategories = [];
    foreach ($gpuCategories as $category => $data) {
      if ($request->has('gpu_category_' . $category)) {
        $selectedCategories[] = $data;
      }
    }

    $selectedModels = [];
    foreach ($request->all() as $key => $value) {
      if (str_starts_with($key, 'gpu_model_') && $value) {
        $modelSlug = substr($key, 10);
        $model = $this->slugToGpuModel($modelSlug);
        $selectedModels[] = $model;
      }
    }

    if (!empty($selectedCategories) || !empty($selectedModels)) {
      $query->where(function ($q) use ($selectedCategories, $selectedModels) {
        foreach ($selectedCategories as $category) {
          foreach ($category['search_terms'] as $term) {
            $q->orWhere('gpu_model', 'LIKE', '%' . $this->escapeLike($term) . '%');
          }
        }

        foreach ($selectedModels as $model) {
          $q->orWhere('gpu_model', 'LIKE', '%' . $this->escapeLike($model) . '%');
        }
      });
    }

    // Фильтр по конфигурации дисков
    $selectedStorageConfigs = [];
    foreach (self::STORAGE_MAPPING as $field => $values) {
      if ($request->has($field)) {
        $selectedStorageConfigs = array_merge($selectedStorageConfigs, $values);
      }
    }

    if (!empty($selectedStorageConfigs)) {
      $query->whereIn('storage_config', array_unique($selectedStorageConfigs));
    }

    // Фильтр по емкости SSD
    $ssdConditions = [];
    if ($request->has('ssd_capacity_256')) {
      $ssdConditions[] = ['ssd_capacity', '=', '256 Гб'];
    }
    if ($request->has('ssd_capacity_512')) {
      $ssdConditions[] = ['ssd_capacity', '=', '512 Гб'];
    }
    if ($request->has('ssd_capacity_1024')) {
      $ssdConditions[] = ['ssd_capacity', '=', '1024 Гб'];
    }
    if ($request->has('ssd_capacity_2048')) {
      $ssdConditions[] = ['ssd_capacity', '=', '2048 Гб'];
    }

    if (!empty($ssdConditions)) {
      $query->where(function ($q) use ($ssdConditions) {
        foreach ($ssdConditions as $condition) {
          $q->orWhere($condition[0], $condition[1], $condition[2]);
        }
      });
    }

    // Фильтр по операционной системе
    $osConditions = [];
    // Windows
    if ($request->has('os_windows_11_home')) {
      $osConditions[] = ['operating_system', '=', 'Windows 11 Home'];
    }
    if ($request->has('os_windows_11_pro')) {
      $osConditions[] = ['operating_system', '=', 'Windows 11 Pro'];
    }
    if ($request->has('os_windows_11')) {
      $osConditions[] = ['operating_system', '=', 'Windows 11'];
    }
    if ($request->has('os_windows_10_home')) {
      $osConditions[] = ['operating_system', '=', 'Windows 10 Home'];
    }
    if ($request->has('os_windows_10_pro')) {
      $osConditions[] = ['operating_system', '=', 'Windows 10 Pro'];
    }
    if ($request->has('os_windows_10')) {
      $osConditions[] = ['operating_system', '=', 'Windows 10'];
    }

    // macOS
    if ($request->has('os_macos')) {
      $osConditions[] = ['operating_system', 'LIKE', 'macOS%'];
    }
    if ($request->has('os_macos_big_sur')) {
      $osConditions[] = ['operating_system', '=', 'macOS (Big Sur)'];
    }

    // Linux
    if ($request->has('os_linux')) {
      $osConditions[] = ['operating_system', 'LIKE', '%Linux%'];
    }
    if ($request->has('os_ubuntu')) {
      $osConditions[] = ['operating_system', 'LIKE', '%Ubuntu%'];
    }

    // Без ОС
    if ($request->has('os_dos')) {
      $osConditions[] = ['operating_system', 'LIKE', 'DOS%'];
    }

    // Chrome OS
    if ($request->has('os_chrome_os')) {
      $osConditions[] = ['operating_system', '=', 'Google Chrome OS'];
    }

    if (!empty($osConditions)) {
      $query->where(function ($q) use ($osConditions) {
        foreach ($osConditions as $condition) {
          $q->orWhere($condition[0], $condition[1], $condition[2]);
        }
      });
    }

    // Конфигурация технологий экрана
    $selectedScreenTechs = [];
    foreach (self::SCREEN_TECH_MAPPING as $field => $technology) {
      if ($request->has($field)) {
        $selectedScreenTechs[] = $technology;
      }
    }

    if (!empty($selectedScreenTechs)) {
      $query->whereIn('screen_technology', $selectedScreenTechs);
    }

    // Фильтрация по частоте обновления
    $refreshRateConditions = [];
    if ($request->has('refresh_rate_60')) {
      $refreshRateConditions[] = ['refresh_rate', '=', '60 Гц'];
    }
    if ($request->has('refresh_rate_100')) {
      $refreshRateConditions[] = ['refresh_rate', '=', '100 Гц'];
    }
    if ($request->has('refresh_rate_120')) {
      $refreshRateConditions[] = ['refresh_rate', '=', '120 Гц'];
    }
    if ($request->has('refresh_rate_120_promotion')) {
      $refreshRateConditions[] = ['refresh_rate', '=', '120 Гц (Pro Motion)'];
    }
    if ($request->has('refresh_rate_144')) {
      $refreshRateConditions[] = ['refresh_rate', '=', '144 Гц'];
    }
    if ($request->has('refresh_rate_165')) {
      $refreshRateConditions[] = ['refresh_rate', '=', '165 Гц'];
    }
    if ($request->has('refresh_rate_180')) {
      $refreshRateConditions[] = ['refresh_rate', '=', '180 Гц'];
    }
    if ($request->has('refresh_rate_240')) {
      $refreshRateConditions[] = ['refresh_rate', '=', '240 Гц'];
    }

    if (!empty($refreshRateConditions)) {
      $query->where(function ($q) use ($refreshRateConditions) {
        foreach ($refreshRateConditions as $condition) {
          $q->orWhere($condition[0], $condition[1], $condition[2]);
        }
      });
    }

    // Маппинг линеек
    $lineMapping = [
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

      // NULL значение
      'line_null' => 'NULL',
    ];

    $selectedLines = [];
    foreach ($lineMapping as $field => $line) {
      if ($request->has($field) && $request->$field) {
        $selectedLines[] = $line;
      }
    }

    if (!empty($selectedLines)) {
      $query->whereIn('line', $selectedLines);
    }

    // Маппинг цветов
    $colorMapping = [
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

    $selectedColors = [];
    foreach ($colorMapping as $field => $color) {
      if ($request->has($field)) {
        $selectedColors[] = $color;
      }
    }

    if (!empty($selectedColors)) {
      $query->whereIn('case_color', $selectedColors);
    }

    // Фильтр по материалу корпуса
    $selectedMaterials = [];
    foreach (self::CASE_MATERIAL_MAPPING as $field => $material) {
      if ($request->has($field)) {
        $selectedMaterials[] = $material;
      }
    }

    if (!empty($selectedMaterials)) {
      $query->where(function ($q) use ($selectedMaterials) {
        foreach ($selectedMaterials as $material) {
          $q->orWhere('case_material', 'LIKE', '%' . $this->escapeLike($material) . '%');
        }
      });
    }

    // Фильтр по видеопамяти
    if ($request->filled('vram')) {
      $selectedVrams = (array)$request->input('vram');
      if (!empty($selectedVrams)) {
        $query->where(function ($q) use ($selectedVrams) {
          foreach ($selectedVrams as $vram) {
            $v = $this->escapeLike((string)$vram);
            $q->orWhere('gpu_memory', 'LIKE', '%' . $v . '%');
            $q->orWhere('gpu_memory', 'LIKE', $v . '%');
            $q->orWhere('gpu_memory', 'LIKE', '%' . $v . 'Гб%');
            $q->orWhere('gpu_memory', 'LIKE', '%' . $v . 'GB%');
          }
        });
      }
    }

    // Фильтр по частоте процессора
    if ($request->has('cpu_freq_range') && $request->cpu_freq_range == 'any') {
      // ничего не делаем
    } elseif ($request->filled('cpu_freq_min_slider') || $request->filled('cpu_freq_max_slider')) {
      $min = $request->filled('cpu_freq_min_slider') ? (float)$request->cpu_freq_min_slider * 1000 : 900;
      $max = $request->filled('cpu_freq_max_slider') ? (float)$request->cpu_freq_max_slider * 1000 : 4500;
      $this->applyFrequencyRange($query, $min, $max);
    } elseif ($request->filled('cpu_freq_custom_min') || $request->filled('cpu_freq_custom_max')) {
      $min = $request->filled('cpu_freq_custom_min') ? (float)$request->cpu_freq_custom_min * 1000 : null;
      $max = $request->filled('cpu_freq_custom_max') ? (float)$request->cpu_freq_custom_max * 1000 : null;
      $this->applyFrequencyRange($query, $min, $max);
    } elseif ($request->has('cpu_freq_range')) {
      $range = $request->input('cpu_freq_range');
      switch ($range) {
        case 'under_2':
        case 'low':
          $this->applyFrequencyRange($query, 900, 1999);
          break;
        case '2_3':
        case 'medium':
          $this->applyFrequencyRange($query, 2000, 2999);
          break;
        case '3_4':
        case 'high':
          $this->applyFrequencyRange($query, 3000, 3999);
          break;
        case 'over_4':
        case 'max':
          $this->applyFrequencyRange($query, 4000, 5000);
          break;
      }
    }

    // Фильтр по трансформеру
    $value = $request->input('transformer');
    if ($value === 'yes') {
      $query->where(function ($q) {
        $q->where('transformer', 'да')
          ->orWhere('transformer', 'like', '%да%')
          ->orWhere('transformer', 'yes')
          ->orWhere('transformer', '=', 1);
      });
    } elseif ($value === 'no') {
      $query->where(function ($q) {
        $q->where('transformer', 'нет')
          ->orWhere('transformer', 'like', '%нет%')
          ->orWhere('transformer', 'no')
          ->orWhere('transformer', '=', 0)
          ->orWhereNull('transformer');
      });
    }

    // Фильтр по емкости аккумулятора
    if ($request->filled('battery_capacities') && is_array($request->battery_capacities)) {
      $selectedCapacities = array_map('intval', $request->battery_capacities);
      $query->where(function ($q) use ($selectedCapacities) {
        foreach ($selectedCapacities as $capacity) {
          $q->orWhere('energy_reserve', 'LIKE', $capacity . ' Вт·ч')
            ->orWhere('energy_reserve', 'LIKE', $capacity . '%')
            ->orWhereRaw("CAST(REGEXP_REPLACE(energy_reserve, '[^0-9.]', '') AS DECIMAL(10,2)) = ?", [$capacity]);
        }
      });
    }

    if ($request->filled('battery_range') && is_array($request->battery_range)) {
      $selectedRanges = $request->battery_range;
      $query->where(function ($q) use ($selectedRanges) {
        foreach ($selectedRanges as $range) {
          switch ($range) {
            case 'under_50':
              $q->orWhere(function ($subQ) {
                $subQ->whereNotNull('energy_reserve')
                  ->whereRaw("CAST(REGEXP_REPLACE(energy_reserve, '[^0-9.]', '') AS DECIMAL(10,2)) < 50");
              });
              break;
            case '50_70':
              $q->orWhere(function ($subQ) {
                $subQ->whereNotNull('energy_reserve')
                  ->whereRaw("CAST(REGEXP_REPLACE(energy_reserve, '[^0-9.]', '') AS DECIMAL(10,2)) BETWEEN 50 AND 70");
              });
              break;
            case '70_90':
              $q->orWhere(function ($subQ) {
                $subQ->whereNotNull('energy_reserve')
                  ->whereRaw("CAST(REGEXP_REPLACE(energy_reserve, '[^0-9.]', '') AS DECIMAL(10,2)) BETWEEN 70 AND 90");
              });
              break;
            case 'over_90':
              $q->orWhere(function ($subQ) {
                $subQ->whereNotNull('energy_reserve')
                  ->whereRaw("CAST(REGEXP_REPLACE(energy_reserve, '[^0-9.]', '') AS DECIMAL(10,2)) > 90");
              });
              break;
          }
        }
      });
    }

    // Применяем фильтр Ethernet если есть параметр
    if ($request->filled('ethernet')) {
      $value = $request->input('ethernet');
      if ($value === 'yes') {
        $query->where(function ($q) {
          $q->where('ethernet_lan', 'like', '%есть%')
            ->orWhere('ethernet_lan', 'like', 'есть%')
            ->orWhere('ethernet_lan', 'like', '%есть')
            ->orWhere('ethernet_lan', '1 Gbit, есть')
            ->orWhere('ethernet_lan', 'есть, 1 Gbit')
            ->orWhere('ethernet_lan', '100 Mbit, есть')
            ->orWhere('ethernet_lan', 'есть, 100 Mbit')
            ->orWhere('ethernet_lan', '2.5 Gbit, есть')
            ->orWhere('ethernet_lan', 'есть, 2.5 Gbit')
            ->orWhere('ethernet_lan', '1 Gbit, 100 Mbit, есть')
            ->orWhere('ethernet_lan', '=', 'есть');
        });
      } elseif ($value === 'no') {
        $query->where(function ($q) {
          $q->where('ethernet_lan', 'нет')
            ->orWhere('ethernet_lan', 'like', 'нет%')
            ->orWhere('ethernet_lan', 'like', '%нет')
            ->orWhere('ethernet_lan', 'like', '%нет%')
            ->orWhereNull('ethernet_lan');
        });
      }
    }

    // Применяем фильтр USB портов
    if ($request->filled('usb_ports') && is_array($request->usb_ports)) {
      $selectedPorts = $request->input('usb_ports');
      $query->where(function ($q) use ($selectedPorts) {
        foreach ($selectedPorts as $portValue) {
          if ($portValue === '5_6') {
            $q->orWhere(function ($subQ) {
              $subQ->whereNotNull('total_usb_ports')
                ->whereRaw('CAST(total_usb_ports AS UNSIGNED) BETWEEN 5 AND 6');
            });
          } else {
            $q->orWhere('total_usb_ports', (int)$portValue);
          }
        }
      });
    }

    // Применяем фильтр Thunderbolt
    if ($request->filled('thunderbolt')) {
      $value = $request->input('thunderbolt');
      if ($value === 'yes') {
        $query->where(function ($q) {
          $q->where('thunderbolt', 'like', '%есть%')
            ->orWhere('thunderbolt', 'like', 'есть%')
            ->orWhere('thunderbolt', 'like', '%есть')
            ->orWhere('thunderbolt', 'есть, в интерфейсе USB Type-C')
            ->orWhere('thunderbolt', 'LIKE', '%thunderbolt%')
            ->orWhere('thunderbolt', 'LIKE', '%Thunderbolt%');
        });
      } elseif ($value === 'no') {
        $query->where(function ($q) {
          $q->where('thunderbolt', 'нет')
            ->orWhere('thunderbolt', 'like', 'нет%')
            ->orWhere('thunderbolt', 'like', '%нет')
            ->orWhere('thunderbolt', 'like', '%нет%')
            ->orWhereNull('thunderbolt');
        });
      }
    }

    // Применяем фильтр кириллической клавиатуры
    if ($request->filled('cyrillic_keyboard')) {
      $value = $request->input('cyrillic_keyboard');
      if ($value === 'yes') {
        $query->where(function ($q) {
          $q->where('cyrillic_on_keyboard', 'like', '%есть%')
            ->orWhere('cyrillic_on_keyboard', 'like', 'есть%')
            ->orWhere('cyrillic_on_keyboard', 'like', '%есть')
            ->orWhere('cyrillic_on_keyboard', 'есть')
            ->orWhere('cyrillic_on_keyboard', 'есть (сенсорная площадка)')
            ->orWhere('cyrillic_on_keyboard', 'есть (не заводская)')
            ->orWhere('cyrillic_on_keyboard', 'есть (заводская)');
        });
      } elseif ($value === 'no') {
        $query->where('cyrillic_on_keyboard', 'нет');
      }
    }
  }

  private function applyFrequencyRange(Builder $query, $min, $max): void
  {
    $query->where(function ($q) use ($min, $max) {
      $q->whereNotNull('clock_speed');
      if ($min !== null && $max !== null) {
        $q->whereRaw("CAST(REGEXP_REPLACE(clock_speed, '[^0-9.]', '') AS DECIMAL(10,2)) BETWEEN ? AND ?",
          [$min, $max]);
      } elseif ($min !== null) {
        $q->whereRaw("CAST(REGEXP_REPLACE(clock_speed, '[^0-9.]', '') AS DECIMAL(10,2)) >= ?", [$min]);
      } elseif ($max !== null) {
        $q->whereRaw("CAST(REGEXP_REPLACE(clock_speed, '[^0-9.]', '') AS DECIMAL(10,2)) <= ?", [$max]);
      }
    });
  }

  private function paginateResults(Builder $query, Request $request, string $manufacturer, string $sort): array
  {
    $currentPage = max(1, (int)$request->get('page', 1));
    $totalItems = $query->count();
    $totalPages = max(1, ceil($totalItems / self::PER_PAGE));

    if ($currentPage > $totalPages) {
      $currentPage = 1;
    }

    $offset = ($currentPage - 1) * self::PER_PAGE;
    $notebooks = $query->skip($offset)->take(self::PER_PAGE)->get();

    // Создаем пагинатор с автоматическим сохранением всех параметров
    $paginator = new LengthAwarePaginator(
      $notebooks,
      $totalItems,
      self::PER_PAGE,
      $currentPage,
      ['path' => Paginator::resolveCurrentPath(), 'pageName' => 'page']
    );

    // Автоматически сохраняет все параметры из текущего запроса
    $paginator->withQueryString();

    return [
      'notebooks' => $notebooks,
      'paginator' => $paginator,
      'totalItems' => $totalItems,
      'currentPage' => $currentPage,
      'totalPages' => $totalPages,
      'from' => $offset + 1,
      'to' => min($offset + self::PER_PAGE, $totalItems)
    ];
  }

  private function getActiveFilters(Request $request): array
  {
    $activeFilters = [];
    $exclude = ['page', '_token', '_method'];

    foreach ($request->all() as $key => $value) {
      // Пропускаем служебные параметры
      if (in_array($key, $exclude)) {
        continue;
      }

      // Проверяем, что значение не пустое
      if ($value !== null && $value !== '') {
        // Если это массив, проверяем, есть ли в нем непустые значения
        if (is_array($value)) {
          $filteredArray = array_filter($value, function ($item) {
            return $item !== null && $item !== '';
          });

          if (!empty($filteredArray)) {
            $activeFilters[$key] = $filteredArray;
          }
        } else {
          $activeFilters[$key] = $value;
        }
      }
    }

    return $activeFilters;
  }

  private function buildResponse(array $result, array $activeFilters, Request $request, string $manufacturer, string $sort): Factory|View|JsonResponse
  {
    $isAjaxRequest = $request->ajax() ||
      $request->wantsJson() ||
      $request->hasHeader('X-Requested-With') ||
      ($request->hasHeader('Accept') && str_contains($request->header('Accept'), 'application/json'));

    if ($isAjaxRequest) {
      try {
        $productsHtml = view('main.products-list', [
          'notebooks' => $result['notebooks'],
          'activeFilters' => $activeFilters,
          'totalItems' => $result['totalItems'],
          'currentPage' => $result['currentPage'],
          'from' => $result['from'],
          'to' => $result['to']
        ])->render();

        $paginationHtml = $result['totalPages'] > 1
          ? $result['paginator']->links('vendor.pagination.tailwind')->toHtml()
          : '';

      } catch (\Exception $e) {
        $productsHtml = '<div class="text-center py-8"><p>Ошибка загрузки товаров</p></div>';
        $paginationHtml = '';
      }

      return response()->json([
        'success' => true,
        'products_html' => $productsHtml,
        'pagination_html' => $paginationHtml,
        'total' => $result['totalItems'],
        'active_manufacturer' => $manufacturer,
        'active_sort' => $sort,
        'active_filters' => $activeFilters,
        'filter_count' => count($activeFilters),
        'current_page' => $result['currentPage'],
        'last_page' => $result['totalPages'],
        'per_page' => self::PER_PAGE,
        'from' => $result['from'],
        'to' => $result['to'],
      ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    // Обычный запрос
    return view('main.layout', [
      'notebooks' => $result['notebooks'],
      'paginator' => $result['paginator'],
      'sortOptions' => self::SORT_OPTIONS,
      'sort' => $sort,
      'allowedManufacturers' => self::ALLOWED_MANUFACTURERS,
      'totalItems' => $result['totalItems'],
      'currentPage' => $result['currentPage'],
      'totalPages' => $result['totalPages'],
      'activeFilters' => $activeFilters,
      'filterCount' => count($activeFilters)
    ]);
  }

  private function handleError(\Exception $e, Request $request): JsonResponse
  {
    $isAjaxRequest = $request->ajax() ||
      $request->wantsJson() ||
      $request->hasHeader('X-Requested-With');

    if ($isAjaxRequest) {
      return response()->json([
        'success' => false,
        'error' => 'Ошибка загрузки данных',
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ], 500);
    }

    throw $e;
  }

  /**
   * Экранирует символы % и _ для безопасного использования в LIKE.
   */
  private function escapeLike(string $value): string
  {
    return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
  }

  private function slugToGpuModel(string $slug): string
  {
    $slug = str_replace('_', ' ', $slug);

    // Восстанавливаем производителя
    if (str_starts_with($slug, 'intel ')) {
      $slug = 'Intel ' . substr($slug, 6);
    } elseif (str_starts_with($slug, 'nvidia ')) {
      $slug = 'NVIDIA ' . substr($slug, 7);
    } elseif (str_starts_with($slug, 'amd ')) {
      $slug = 'AMD ' . substr($slug, 4);
    } elseif (str_starts_with($slug, 'apple ')) {
      $slug = 'Apple ' . substr($slug, 6);
    } elseif (str_starts_with($slug, 'qualcomm ')) {
      $slug = 'Qualcomm ' . substr($slug, 9);
    }

    // Восстанавливаем специальные обозначения
    $slug = str_replace('eu', 'EU', $slug);
    $slug = str_replace('xe', 'Xe', $slug);
    $slug = str_replace('rtx', 'RTX', $slug);
    $slug = str_replace('gtx', 'GTX', $slug);
    $slug = str_replace('mx', 'MX', $slug);

    return $slug;
  }
}
