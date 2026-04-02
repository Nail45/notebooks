<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotebookFilterRequest;
use App\Services\Filters\NotebookFilterService;
use App\Services\Pagination\NotebookPaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Notebook;

class NotebookSearchController extends Controller
{
  private NotebookFilterService $filterService;
  private NotebookPaginator $paginator;

  public function __construct(NotebookFilterService $filterService, NotebookPaginator $paginator)
  {
    $this->filterService = $filterService;
    $this->paginator = $paginator;
  }

  /**
   * Поиск ноутбуков по названию (AJAX для живого поиска)
   */
  public function search(Request $request): JsonResponse
  {
    $query = $request->get('q', '');

    if (empty($query) || strlen($query) < 2) {
      return response()->json([
        'success' => false,
        'message' => 'Минимум 2 символа',
        'results' => [],
        'html' => view('main.search-results', [
          'notebooks' => [],
          'query' => $query
        ])->render()
      ]);
    }

    $notebooks = Notebook::where('title', 'LIKE', "%{$query}%")
      ->orWhere('line', 'LIKE', "%{$query}%")
      ->orWhere('manufacturer', 'LIKE', "%{$query}%")
      ->select('id', 'title', 'line', 'manufacturer', 'price', 'slug')
      ->limit(10)
      ->get();

    $formattedResults = $notebooks->map(function ($notebook) {
      return [
        'id' => $notebook->id,
        'title' => $notebook->title,
        'slug' => $notebook->slug,
        'line' => $notebook->line,
        'manufacturer' => $notebook->manufacturer,
        'price' => number_format($notebook->price, 0, '.', ' '),
      ];
    });

    return response()->json([
      'success' => true,
      'count' => $formattedResults->count(),
      'results' => $formattedResults,
      'html' => view('main.search-results', [
        'notebooks' => $formattedResults,
        'query' => $query
      ])->render()
    ]);
  }

  /**
   * Результаты поиска с сортировкой и фильтрацией
   */
  public function index(NotebookFilterRequest $request, ?string $line = null): View|JsonResponse
  {
    try {
      // Получаем параметры
      $sort = $request->get('sort', 'default');
      $manufacturer = $request->get('manufacturer', 'all');
      $searchQuery = $request->get('q', '');

      // Построение базового запроса
      $query = $this->filterService->buildBaseQuery($manufacturer);

      // Применяем поиск по тексту
      if (!empty($searchQuery) && strlen($searchQuery) >= 2) {
        $query->where(function($q) use ($searchQuery) {
          $q->where('title', 'LIKE', "%{$searchQuery}%")
            ->orWhere('line', 'LIKE', "%{$searchQuery}%")
            ->orWhere('manufacturer', 'LIKE', "%{$searchQuery}%");
        });
      }

      // Фильтр по линейке (из URL)
      if ($line) {
        $query->where('line', 'LIKE', "%{$line}%");
      }

      // Применение всех остальных фильтров
      $this->filterService->applyFilters($query, $request);

      // Применение сортировки
      $this->filterService->applySort($query, $sort);

      // Пагинация
      $result = $this->paginator->paginate($query, $request);

      // Активные фильтры
      $activeFilters = $this->filterService->getActiveFilters($request);

      // Добавляем поисковый запрос в активные фильтры для отображения
      if (!empty($searchQuery)) {
        $activeFilters['q'] = $searchQuery;
      }
      if ($line) {
        $activeFilters['line'] = $line;
      }

      // Ответ в зависимости от типа запроса
      return $this->buildResponse($result, $activeFilters, $request, $manufacturer, $sort, $searchQuery, $line);

    } catch (\Exception $e) {
      return $this->handleError($e, $request);
    }
  }

  /**
   * Построение ответа (копия из NotebookController)
   */
  private function buildResponse(array $result, array $activeFilters, NotebookFilterRequest $request, string $manufacturer, string $sort, string $searchQuery = '', ?string $line = null): View|JsonResponse
  {
    $isAjaxRequest = $request->ajax() || $request->wantsJson() || $request->hasHeader('X-Requested-With');

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
        'per_page' => $this->paginator->perPage,
        'from' => $result['from'],
        'to' => $result['to'],
        'search_query' => $searchQuery,
      ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    // Обычный запрос
    $definitions = $this->filterService->getDefinitions();

    return view('search.results', array_merge([
      'notebooks' => $result['notebooks'],
      'paginator' => $result['paginator'],
      'sort' => $sort,
      'totalItems' => $result['totalItems'],
      'currentPage' => $result['currentPage'],
      'totalPages' => $result['totalPages'],
      'activeFilters' => $activeFilters,
      'filterCount' => count($activeFilters),
      'searchQuery' => $searchQuery,
      'line' => $line
    ], $definitions));
  }

  /**
   * Обработка ошибок (копия из NotebookController)
   */
  private function handleError(\Exception $e, NotebookFilterRequest $request): JsonResponse
  {
    $isAjaxRequest = $request->ajax() || $request->wantsJson() || $request->hasHeader('X-Requested-With');

    if ($isAjaxRequest) {
      return response()->json([
        'success' => false,
        'error' => 'Ошибка загрузки данных',
        'message' => $e->getMessage(),
      ], 500);
    }

    throw $e;
  }
}
