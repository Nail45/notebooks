<?php


namespace App\Services\Filters;

use App\Models\Notebook;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Services\Filters\FilterDefinitions;
use App\Services\Filters\FilterApplier;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class NotebookFilterService
{
  private FilterDefinitions $definitions;
  private FilterApplier $filterApplier;

  public function __construct(FilterDefinitions $definitions, FilterApplier $filterApplier)
  {
    $this->definitions = $definitions;
    $this->filterApplier = $filterApplier;
  }

  /**
   * Построить базовый запрос с учетом производителя
   */
  public function buildBaseQuery(?string $manufacturer): Builder
  {
    $query = Notebook::query();

    if ($manufacturer && $manufacturer !== 'all' && in_array($manufacturer, $this->definitions::ALLOWED_MANUFACTURERS)) {
      $query->where('manufacturer', $manufacturer);
    } else {
      $query->whereIn('manufacturer', $this->definitions::ALLOWED_MANUFACTURERS);
    }

    return $query;
  }

  /**
   * Применить все фильтры
   */
  public function applyFilters(Builder $query, Request $request): void
  {
    $this->filterApplier->applyAll($query, $request);
  }

  /**
   * Применить сортировку
   */
  public function applySort(Builder $query, string $sort): void
  {
    $sortOption = $this->definitions::SORT_OPTIONS[$sort] ?? $this->definitions::SORT_OPTIONS['default'];
    $query->orderBy($sortOption['column'], $sortOption['direction']);
  }

  /**
   * Получить активные фильтры из запроса
   */
  public function getActiveFilters(Request $request): array
  {
    $activeFilters = [];
    $exclude = ['page', '_token', '_method'];

    foreach ($request->all() as $key => $value) {
      if (in_array($key, $exclude)) {
        continue;
      }

      if ($value !== null && $value !== '') {
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

  /**
   * Получить определение сортировки
   */
  public function getSortOption(string $sort): array
  {
    return $this->definitions::SORT_OPTIONS[$sort] ?? $this->definitions::SORT_OPTIONS['default'];
  }

  /**
   * Получить все определения (для передачи в представление)
   */
  public function getDefinitions(): array
  {
    return [
      'allowedManufacturers' => $this->definitions::ALLOWED_MANUFACTURERS,
      'sortOptions' => $this->definitions::SORT_OPTIONS,
      'screenResolutions' => $this->definitions::SCREEN_RESOLUTIONS,
      'ramOptions' => $this->definitions::RAM_OPTIONS,
      'coreMapping' => $this->definitions::CORE_MAPPING,
      'storageMapping' => $this->definitions::STORAGE_MAPPING,
      'screenTechMapping' => $this->definitions::SCREEN_TECH_MAPPING,
      'caseMaterialMapping' => $this->definitions::CASE_MATERIAL_MAPPING,
      'processorModels' => $this->definitions::PROCESSOR_MODELS,
      'lineMapping' => $this->definitions::LINE_MAPPING,
      'colorMapping' => $this->definitions::COLOR_MAPPING,
      'gpuCategories' => $this->definitions::GPU_CATEGORIES,
      'perPage' => $this->definitions::PER_PAGE,
    ];
  }
}
