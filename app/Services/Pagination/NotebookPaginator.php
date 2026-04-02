<?php


namespace App\Services\Pagination;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class NotebookPaginator
{
  public int $perPage;

  public function __construct(int $perPage = 40)
  {
    $this->perPage = $perPage;
  }

  /**
   * Пагинация с результатами
   */
  public function paginate(Builder $query, Request $request): array
  {
    $currentPage = max(1, (int)$request->get('page', 1));
    $totalItems = $query->count();
    $totalPages = max(1, ceil($totalItems / $this->perPage));

    if ($currentPage > $totalPages) {
      $currentPage = 1;
    }

    $offset = ($currentPage - 1) * $this->perPage;
    $notebooks = $query->skip($offset)->take($this->perPage)->get();

    $paginator = new LengthAwarePaginator(
      $notebooks,
      $totalItems,
      $this->perPage,
      $currentPage,
      ['path' => Paginator::resolveCurrentPath(), 'pageName' => 'page']
    );

    $paginator->withQueryString();

    return [
      'notebooks' => $notebooks,
      'paginator' => $paginator,
      'totalItems' => $totalItems,
      'currentPage' => $currentPage,
      'totalPages' => $totalPages,
      'from' => $offset + 1,
      'to' => min($offset + $this->perPage, $totalItems)
    ];
  }
}
