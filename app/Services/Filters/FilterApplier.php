<?php


namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Services\Filters\FilterDefinitions;

class FilterApplier
{
  private FilterDefinitions $definitions;

  public function __construct(FilterDefinitions $definitions)
  {
    $this->definitions = $definitions;
  }

  public function applyAll(Builder $query, Request $request): void
  {
    $this->applyPriceFilters($query, $request);
    $this->applyYearFilters($query, $request);
    $this->applyViewFilters($query, $request);
    $this->applyScreenFilters($query, $request);
    $this->applyPopularFeatures($query, $request);
    $this->applyRamFilters($query, $request);
    $this->applyProcessorFilters($query, $request);
    $this->applyGpuFilters($query, $request);
    $this->applyStorageFilters($query, $request);
    $this->applyOsFilters($query, $request);
    $this->applyLineFilters($query, $request);
    $this->applyColorFilters($query, $request);
    $this->applyCaseMaterialFilters($query, $request);
    $this->applyFrequencyFilters($query, $request);
    $this->applyBatteryFilters($query, $request);
    $this->applyEthernetFilters($query, $request);
    $this->applyUsbFilters($query, $request);
    $this->applyThunderboltFilters($query, $request);
    $this->applyCyrillicKeyboardFilters($query, $request);
    $this->applyTransformerFilters($query, $request);
  }

  private function applyPriceFilters(Builder $query, Request $request): void
  {
    if ($request->has('min_price') && is_numeric($request->min_price) && $request->min_price > 0) {
      $query->where('price', '>=', (float)$request->min_price);
    }

    if ($request->has('max_price') && is_numeric($request->max_price) && $request->max_price > 0) {
      $query->where('price', '<=', (float)$request->max_price);
    }
  }

  private function applyYearFilters(Builder $query, Request $request): void
  {
    if ($request->has('release_year_from') && is_numeric($request->release_year_from) && $request->release_year_from > 0) {
      $query->where('release_date', '>=', (int)$request->release_year_from);
    }

    if ($request->has('release_year_to') && is_numeric($request->release_year_to) && $request->release_year_to > 0) {
      $query->where('release_date', '<=', (int)$request->release_year_to);
    }
  }

  private function applyViewFilters(Builder $query, Request $request): void
  {
    $viewFilters = [];
    foreach (['worker', 'gaming', 'ultrabook', 'universal', 'domestic'] as $type) {
      if ($request->filled($type)) {
        $viewFilters[] = $request->$type;
      }
    }

    if (!empty($viewFilters)) {
      $query->whereIn('view_title', $viewFilters);
    }
  }

  private function applyScreenFilters(Builder $query, Request $request): void
  {
    // Диагональ
    if ($request->has('screen_diagonal_from') && is_numeric($request->screen_diagonal_from) && $request->screen_diagonal_from > 0) {
      $query->where('screen_diagonal', '>=', (float)$request->screen_diagonal_from);
    }

    if ($request->has('screen_diagonal_to') && is_numeric($request->screen_diagonal_to) && $request->screen_diagonal_to > 0) {
      $query->where('screen_diagonal', '<=', (float)$request->screen_diagonal_to);
    }

    // Разрешение
    $selectedResolutions = [];
    foreach (FilterDefinitions::SCREEN_RESOLUTIONS as $resolution) {
      if ($request->has('resolution_' . $resolution)) {
        $selectedResolutions[] = $resolution;
      }
    }

    if (!empty($selectedResolutions)) {
      $query->whereIn('screen_resolution', $selectedResolutions);
    }

    // Технология экрана
    $selectedScreenTechs = [];
    foreach (FilterDefinitions::SCREEN_TECH_MAPPING as $field => $technology) {
      if ($request->has($field)) {
        $selectedScreenTechs[] = $technology;
      }
    }

    if (!empty($selectedScreenTechs)) {
      $query->whereIn('screen_technology', $selectedScreenTechs);
    }

    // Частота обновления
    $refreshRateConditions = [];
    $rates = [
      'refresh_rate_60' => '60 Гц',
      'refresh_rate_100' => '100 Гц',
      'refresh_rate_120' => '120 Гц',
      'refresh_rate_120_promotion' => '120 Гц (Pro Motion)',
      'refresh_rate_144' => '144 Гц',
      'refresh_rate_165' => '165 Гц',
      'refresh_rate_180' => '180 Гц',
      'refresh_rate_240' => '240 Гц',
    ];

    foreach ($rates as $field => $value) {
      if ($request->has($field)) {
        $refreshRateConditions[] = ['refresh_rate', '=', $value];
      }
    }

    if (!empty($refreshRateConditions)) {
      $query->where(function ($q) use ($refreshRateConditions) {
        foreach ($refreshRateConditions as $condition) {
          $q->orWhere($condition[0], $condition[1], $condition[2]);
        }
      });
    }
  }

  private function applyPopularFeatures(Builder $query, Request $request): void
  {
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
  }

  private function applyRamFilters(Builder $query, Request $request): void
  {
    // Емкость RAM
    $selectedRam = [];
    foreach (FilterDefinitions::RAM_OPTIONS as $field => $value) {
      if ($request->has($field)) {
        $selectedRam[] = $value;
      }
    }

    if (!empty($selectedRam)) {
      $query->whereIn('ram_capacity', $selectedRam);
    }

    // Тип RAM
    $selectedRamTypes = [];
    $ramTypes = [
      'ram_type_ddr4' => 'DDR4',
      'ram_type_ddr5' => 'DDR5',
      'ram_type_lpddr4' => 'LPDDR4',
      'ram_type_lpddr4x' => 'LPDDR4X',
      'ram_type_lpddr5' => 'LPDDR5',
      'ram_type_lpddr5x' => 'LPDDR5X',
    ];

    foreach ($ramTypes as $field => $type) {
      if ($request->has($field)) {
        $selectedRamTypes[] = $type;
      }
    }

    if (!empty($selectedRamTypes)) {
      $query->whereIn('ram_type', $selectedRamTypes);
    }
  }

  private function applyProcessorFilters(Builder $query, Request $request): void
  {
    // Серия процессора
    $processorSeries = [];
    $seriesMap = [
      'processor_intel_core_i3' => 'Intel Core i3',
      'processor_intel_core_i5' => 'Intel Core i5',
      'processor_intel_core_i7' => 'Intel Core i7',
      'processor_intel_celeron' => 'Intel Celeron',
      'processor_intel_core_5' => 'Intel Core 5',
      'processor_intel_processor' => 'Intel Processor',
      'processor_intel_core_ultra_7' => 'Intel Core Ultra 7',
      'processor_intel_core_ultra_5' => 'Intel Core Ultra 5',
      'processor_intel_core_ultra_9' => 'Intel Core Ultra 9',
      'processor_intel_pentium' => 'Intel Pentium',
      'processor_amd_ryzen_5' => 'AMD Ryzen 5',
      'processor_amd_ryzen_7' => 'AMD Ryzen 7',
      'processor_amd_ryzen_3' => 'AMD Ryzen 3',
      'processor_amd_ryzen_9' => 'AMD Ryzen 9',
      'processor_amd_athlon' => 'AMD Athlon',
      'processor_amd_ryzen_ai' => 'AMD Ryzen AI',
      'processor_ryzen_ai_5' => 'Ryzen AI 5',
      'processor_amd_ryzen_ai_7' => 'AMD Ryzen AI 7',
      'processor_apple_m2' => 'Apple M2',
      'processor_apple_m4' => 'Apple M4',
      'processor_apple_m1' => 'Apple M1',
      'processor_apple_m3' => 'Apple M3',
      'processor_apple_m3_max' => 'Apple M3 Max',
      'processor_qualcomm' => 'Qualcomm',
    ];

    foreach ($seriesMap as $field => $series) {
      if ($request->has($field)) {
        $processorSeries[] = $series;
      }
    }

    if (!empty($processorSeries)) {
      $query->where(function ($q) use ($processorSeries) {
        foreach ($processorSeries as $series) {
          $q->orWhere('processor_series', 'LIKE', '%' . $this->escapeLike($series) . '%');
        }
      });
    }

    // Модель процессора
    $selectedModels = [];
    foreach (FilterDefinitions::PROCESSOR_MODELS as $field => $model) {
      if ($request->has($field) && $request->$field) {
        $selectedModels[] = $model;
      }
    }

    if (!empty($selectedModels)) {
      $query->whereIn('processor_model', $selectedModels);
    }

    // Количество ядер
    $selectedCoreValues = [];
    foreach (FilterDefinitions::CORE_MAPPING as $field => $values) {
      if ($request->has($field)) {
        $selectedCoreValues = array_merge($selectedCoreValues, $values);
      }
    }

    if (!empty($selectedCoreValues)) {
      $query->whereIn('cores_count', array_unique($selectedCoreValues));
    }
  }

  private function applyGpuFilters(Builder $query, Request $request): void
  {
    // Тип GPU
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

    // Категории GPU
    $selectedCategories = [];
    foreach (FilterDefinitions::GPU_CATEGORIES as $category => $data) {
      if ($request->has('gpu_category_' . $category)) {
        $selectedCategories[] = $data;
      }
    }

    // Модели GPU
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

    // Видеопамять
    if ($request->filled('vram')) {
      $selectedVrams = (array)$request->input('vram');
      if (!empty($selectedVrams)) {
        $query->where(function ($q) use ($selectedVrams) {
          foreach ($selectedVrams as $vram) {
            $v = $this->escapeLike((string)$vram);
            $q->orWhere('gpu_memory', 'LIKE', '%' . $v . '%')
              ->orWhere('gpu_memory', 'LIKE', $v . '%')
              ->orWhere('gpu_memory', 'LIKE', '%' . $v . 'Гб%')
              ->orWhere('gpu_memory', 'LIKE', '%' . $v . 'GB%');
          }
        });
      }
    }
  }

  private function applyStorageFilters(Builder $query, Request $request): void
  {
    // Конфигурация дисков
    $selectedStorageConfigs = [];
    foreach (FilterDefinitions::STORAGE_MAPPING as $field => $values) {
      if ($request->has($field)) {
        $selectedStorageConfigs = array_merge($selectedStorageConfigs, $values);
      }
    }

    if (!empty($selectedStorageConfigs)) {
      $query->whereIn('storage_config', array_unique($selectedStorageConfigs));
    }

    // Емкость SSD
    $ssdConditions = [];
    $ssdCapacities = [
      'ssd_capacity_256' => '256 Гб',
      'ssd_capacity_512' => '512 Гб',
      'ssd_capacity_1024' => '1024 Гб',
      'ssd_capacity_2048' => '2048 Гб',
    ];

    foreach ($ssdCapacities as $field => $value) {
      if ($request->has($field)) {
        $ssdConditions[] = ['ssd_capacity', '=', $value];
      }
    }

    if (!empty($ssdConditions)) {
      $query->where(function ($q) use ($ssdConditions) {
        foreach ($ssdConditions as $condition) {
          $q->orWhere($condition[0], $condition[1], $condition[2]);
        }
      });
    }
  }

  private function applyOsFilters(Builder $query, Request $request): void
  {
    $osConditions = [];

    // Windows
    $windowsMap = [
      'os_windows_11_home' => 'Windows 11 Home',
      'os_windows_11_pro' => 'Windows 11 Pro',
      'os_windows_11' => 'Windows 11',
      'os_windows_10_home' => 'Windows 10 Home',
      'os_windows_10_pro' => 'Windows 10 Pro',
      'os_windows_10' => 'Windows 10',
    ];

    foreach ($windowsMap as $field => $os) {
      if ($request->has($field)) {
        $osConditions[] = ['operating_system', '=', $os];
      }
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
  }

  private function applyLineFilters(Builder $query, Request $request): void
  {
    $selectedLines = [];
    foreach (FilterDefinitions::LINE_MAPPING as $field => $line) {
      if ($request->has($field) && $request->$field) {
        $selectedLines[] = $line;
      }
    }

    if (!empty($selectedLines)) {
      $query->whereIn('line', $selectedLines);
    }
  }

  private function applyColorFilters(Builder $query, Request $request): void
  {
    $selectedColors = [];
    foreach (FilterDefinitions::COLOR_MAPPING as $field => $color) {
      if ($request->has($field)) {
        $selectedColors[] = $color;
      }
    }

    if (!empty($selectedColors)) {
      $query->whereIn('case_color', $selectedColors);
    }
  }

  private function applyCaseMaterialFilters(Builder $query, Request $request): void
  {
    $selectedMaterials = [];
    foreach (FilterDefinitions::CASE_MATERIAL_MAPPING as $field => $material) {
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
  }

  private function applyFrequencyFilters(Builder $query, Request $request): void
  {
    if ($request->has('cpu_freq_range') && $request->cpu_freq_range == 'any') {
      return;
    }

    if ($request->filled('cpu_freq_min_slider') || $request->filled('cpu_freq_max_slider')) {
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
  }

  private function applyBatteryFilters(Builder $query, Request $request): void
  {
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
  }

  private function applyEthernetFilters(Builder $query, Request $request): void
  {
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
  }

  private function applyUsbFilters(Builder $query, Request $request): void
  {
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
  }

  private function applyThunderboltFilters(Builder $query, Request $request): void
  {
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
  }

  private function applyCyrillicKeyboardFilters(Builder $query, Request $request): void
  {
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

  private function applyTransformerFilters(Builder $query, Request $request): void
  {
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

  private function escapeLike(string $value): string
  {
    return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
  }

  private function slugToGpuModel(string $slug): string
  {
    $slug = str_replace('_', ' ', $slug);

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

    $slug = str_replace('eu', 'EU', $slug);
    $slug = str_replace('xe', 'Xe', $slug);
    $slug = str_replace('rtx', 'RTX', $slug);
    $slug = str_replace('gtx', 'GTX', $slug);
    $slug = str_replace('mx', 'MX', $slug);

    return $slug;
  }
}
