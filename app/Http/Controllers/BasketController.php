<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Notebook;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BasketController extends Controller
{
  public function index(): View
  {
    if (auth()->check()) {
      // Для авторизованных пользователей
      $products = auth()->user()->baskets()->with('notebook')->get();
    } else {
      // Для неавторизованных пользователей
      $cartItems = session()->get('cart', []);
      $products = collect();

      if (!empty($cartItems)) {
        $notebooks = Notebook::whereIn('id', array_keys($cartItems))->get();

        foreach ($notebooks as $notebook) {
          $products->push((object)[
            'id' => 'session_' . $notebook->id,
            'count' => $cartItems[$notebook->id],
            'notebook' => $notebook,
            'notebook_id' => $notebook->id
          ]);
        }
      }
    }

    // Единый источник данных о товарах в корзине

    $cartProducts = $products ?? collect();

    // Для неавторизованных пользователей
    if (!auth()->check() && empty($products)) {
      $cartItems = session('cart', []);
      $cartProducts = collect();

      if (!empty($cartItems)) {
        $notebooks = Notebook::whereIn('id', array_keys($cartItems))->get();

        foreach ($notebooks as $notebook) {
          $cartProducts->push((object)[
            'id' => 'session_' . $notebook->id,
            'count' => $cartItems[$notebook->id],
            'notebook' => $notebook,
            'notebook_id' => $notebook->id
          ]);
        }
      }
    }

    // Общие расчеты
    $totalItemsPrice = $cartProducts->sum(function ($item) {
      return is_object($item) ? $item->count * $item->notebook->price : $item['count'] * $item['notebook']['price'];
    });

    $totalItemsCount = $cartProducts->sum(function ($item) {
      return is_object($item) ? $item->count : $item['count'];
    });


    return view('basket.layout', [
      'products' => $products,
      'totalItemsPrice' => $totalItemsPrice,
      'totalItemsCount' => $totalItemsCount,
      'cartProducts' => $cartProducts,
    ]);
  }

  public function store(Request $request): JsonResponse|RedirectResponse
  {
    $request->validate([
      'notebook_id' => 'required|exists:notebooks,id',
      'count' => 'integer|min:1|max:10',
    ]);

    $notebookId = $request->notebook_id;
    $count = $request->count ?? 1;

    if (auth()->check()) {
      $user = auth()->user();
      $basket = $user->baskets()->where('notebook_id', $notebookId)->first();

      if ($basket) {
        $newCount = $basket->count + $count;
        $basket->count = min($newCount, 10);
        $basket->save();
      } else {
        $user->baskets()->create([
          'notebook_id' => $notebookId,
          'count' => $count
        ]);
      }

      $cartCount = $user->baskets()->sum('count');
    } else {
      $cart = session()->get('cart', []);

      if (isset($cart[$notebookId])) {
        $cart[$notebookId] += $count;
        if ($cart[$notebookId] > 10) {
          $cart[$notebookId] = 10;
        }
      } else {
        $cart[$notebookId] = $count;
      }

      session()->put('cart', $cart);
      $cartCount = array_sum($cart);
    }

    if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
      return response()->json([
        'success' => true,
        'message' => 'Товар добавлен в корзину',
        'cart_count' => $cartCount
      ]);
    }

    return back()->with('success', 'Товар добавлен в корзину');
  }

  public function increase($id): JsonResponse|RedirectResponse
  {
    if (str_starts_with($id, 'session_')) {
      // Для сессионной корзины (неавторизованный пользователь)
      $notebookId = str_replace('session_', '', $id);
      $cart = session()->get('cart', []);

      if (isset($cart[$notebookId])) {
        // Увеличиваем, но не более 10
        if ($cart[$notebookId] < 10) {
          $cart[$notebookId]++;
          session()->put('cart', $cart);

          $itemTotal = Notebook::find($notebookId)->price * $cart[$notebookId];
          $cartTotal = $this->getCartTotal();
          $totalItems = array_sum($cart); // Общее количество штук
          $cartCount = $totalItems; // Используем total_items для счетчика

          // Для AJAX запросов
          if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
              'success' => true,
              'message' => 'Количество товара увеличено',
              'item_total' => $itemTotal,
              'cart_total' => $cartTotal,
              'total_items' => $totalItems,
              'cart_count' => $cartCount, // Теперь это общее количество штук
              'new_quantity' => $cart[$notebookId]
            ]);
          }

          return back()->with('success', 'Количество товара увеличено');
        } else {
          if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
              'success' => false,
              'message' => 'Максимальное количество - 10 шт.'
            ], 400);
          }
          return back()->with('error', 'Максимальное количество - 10 шт.');
        }
      }

      // Если товар не найден
      if (request()->wantsJson() || request()->ajax()) {
        return response()->json([
          'success' => false,
          'message' => 'Товар не найден в корзине'
        ], 404);
      }

      return back()->with('error', 'Товар не найден в корзине');

    } else {
      // Для авторизованных пользователей
      $basket = Basket::findOrFail($id);

      if (auth()->id() !== $basket->user_id) {
        if (request()->wantsJson() || request()->ajax()) {
          return response()->json([
            'success' => false,
            'message' => 'Доступ запрещен'
          ], 403);
        }
        abort(403);
      }

      if ($basket->count < 10) {
        $basket->increment('count');

        $itemTotal = $basket->notebook->price * $basket->count;
        $cartTotal = $this->getCartTotal();
        $totalItems = auth()->user()->baskets()->sum('count');
        $cartCount = $totalItems; // Используем total_items для счетчика

        if (request()->wantsJson() || request()->ajax()) {
          return response()->json([
            'success' => true,
            'message' => 'Количество товара увеличено',
            'item_total' => $itemTotal,
            'cart_total' => $cartTotal,
            'total_items' => $totalItems,
            'cart_count' => $cartCount, // Теперь это общее количество штук
            'new_quantity' => $basket->count
          ]);
        }

        return back()->with('success', 'Количество товара увеличено');
      } else {
        if (request()->wantsJson() || request()->ajax()) {
          return response()->json([
            'success' => false,
            'message' => 'Максимальное количество - 10 шт.'
          ], 400);
        }
        return back()->with('error', 'Максимальное количество - 10 шт.');
      }
    }
  }

  public function decrease($id): JsonResponse|RedirectResponse
  {
    if (str_starts_with($id, 'session_')) {
      // Для сессионной корзины
      $notebookId = str_replace('session_', '', $id);
      $cart = session()->get('cart', []);

      if (isset($cart[$notebookId])) {
        if ($cart[$notebookId] > 1) {
          $cart[$notebookId]--;
          session()->put('cart', $cart);

          $itemTotal = Notebook::find($notebookId)->price * $cart[$notebookId];
          $cartTotal = $this->getCartTotal();
          $totalItems = array_sum($cart);
          $cartCount = $totalItems; // Используем total_items для счетчика

          if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
              'success' => true,
              'message' => 'Количество товара уменьшено',
              'item_total' => $itemTotal,
              'cart_total' => $cartTotal,
              'total_items' => $totalItems,
              'cart_count' => $cartCount, // Теперь это общее количество штук
              'new_quantity' => $cart[$notebookId]
            ]);
          }

          return back()->with('success', 'Количество товара уменьшено');
        } else {
          if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
              'success' => false,
              'message' => 'Нельзя уменьшить количество ниже 1'
            ], 400);
          }
          return back()->with('error', 'Нельзя уменьшить количество ниже 1');
        }
      }

      // Если товар не найден
      if (request()->wantsJson() || request()->ajax()) {
        return response()->json([
          'success' => false,
          'message' => 'Товар не найден в корзине'
        ], 404);
      }

      return back()->with('error', 'Товар не найден в корзине');

    } else {
      // Для авторизованных пользователей
      $basket = Basket::findOrFail($id);

      if (auth()->id() !== $basket->user_id) {
        if (request()->wantsJson() || request()->ajax()) {
          return response()->json([
            'success' => false,
            'message' => 'Доступ запрещен'
          ], 403);
        }
        abort(403);
      }

      if ($basket->count > 1) {
        $basket->decrement('count');

        $itemTotal = $basket->notebook->price * $basket->count;
        $cartTotal = $this->getCartTotal();
        $totalItems = auth()->user()->baskets()->sum('count');
        $cartCount = $totalItems; // Используем total_items для счетчика

        if (request()->wantsJson() || request()->ajax()) {
          return response()->json([
            'success' => true,
            'message' => 'Количество товара уменьшено',
            'item_total' => $itemTotal,
            'cart_total' => $cartTotal,
            'total_items' => $totalItems,
            'cart_count' => $cartCount, // Теперь это общее количество штук
            'new_quantity' => $basket->count
          ]);
        }

        return back()->with('success', 'Количество товара уменьшено');
      } else {
        if (request()->wantsJson() || request()->ajax()) {
          return response()->json([
            'success' => false,
            'message' => 'Нельзя уменьшить количество ниже 1'
          ], 400);
        }
        return back()->with('error', 'Нельзя уменьшить количество ниже 1');
      }
    }
  }

  public function destroy($id): JsonResponse|RedirectResponse
  {
    if (str_starts_with($id, 'session_')) {
      // Для сессионной корзины
      $notebookId = str_replace('session_', '', $id);
      $cart = session()->get('cart', []);

      if (isset($cart[$notebookId])) {
        // Сохраняем количество для расчета
        $removedCount = $cart[$notebookId];
        unset($cart[$notebookId]);
        session()->put('cart', $cart);

        $cartTotal = $this->getCartTotal();
        $totalItems = array_sum($cart);
        $cartCount = $totalItems; // Используем total_items для счетчика

        if (request()->wantsJson() || request()->ajax()) {
          return response()->json([
            'success' => true,
            'message' => 'Товар удален из корзины',
            'cart_total' => $cartTotal,
            'total_items' => $totalItems,
            'cart_count' => $cartCount, // Теперь это общее количество штук
            'removed_count' => $removedCount
          ]);
        }
      } else {
        if (request()->wantsJson() || request()->ajax()) {
          return response()->json([
            'success' => false,
            'message' => 'Товар не найден в корзине'
          ], 404);
        }
        return back()->with('error', 'Товар не найден в корзине');
      }

    } else {
      // Для авторизованных пользователей
      $basket = Basket::findOrFail($id);

      if (auth()->id() !== $basket->user_id) {
        if (request()->wantsJson() || request()->ajax()) {
          return response()->json([
            'success' => false,
            'message' => 'Доступ запрещен'
          ], 403);
        }
        abort(403);
      }

      // Сохраняем количество для расчета
      $removedCount = $basket->count;
      $basket->delete();

      $cartTotal = $this->getCartTotal();
      $totalItems = auth()->user()->baskets()->sum('count');
      $cartCount = $totalItems; // Используем total_items для счетчика

      if (request()->wantsJson() || request()->ajax()) {
        return response()->json([
          'success' => true,
          'message' => 'Товар удален из корзины',
          'cart_total' => $cartTotal,
          'total_items' => $totalItems,
          'cart_count' => $cartCount, // Теперь это общее количество штук
          'removed_count' => $removedCount
        ]);
      }
    }

    return back()->with('success', 'Товар удален из корзины');
  }

  public function clear(): JsonResponse|RedirectResponse
  {
    if (auth()->check()) {
      auth()->user()->baskets()->delete();
    } else {
      session()->forget('cart');
    }

    if (request()->wantsJson() || request()->ajax()) {
      return response()->json([
        'success' => true,
        'message' => 'Корзина очищена',
        'cart_count' => 0,
        'cart_total' => 0,
        'total_items' => 0
      ]);
    }

    return back()->with('success', 'Корзина очищена');
  }

  /**
   * Вспомогательные методы
   */
  private function getCartTotal(): float
  {
    if (auth()->check()) {
      return auth()->user()->baskets()->with('notebook')->get()->sum(function ($item) {
        return $item->notebook->price * $item->count;
      });
    }

    $cart = session()->get('cart', []);
    $total = 0;

    if (!empty($cart)) {
      $notebooks = Notebook::whereIn('id', array_keys($cart))->get();
      foreach ($notebooks as $notebook) {
        $total += $notebook->price * $cart[$notebook->id];
      }
    }

    return $total;
  }

  private function getTotalItemsCount(): int
  {
    if (auth()->check()) {
      return auth()->user()->baskets()->sum('count');
    }

    return array_sum(session()->get('cart', []));
  }

  private function getCartCount(): int
  {
    if (auth()->check()) {
      // Возвращаем общее количество штук, а не количество записей
      return auth()->user()->baskets()->sum('count');
    }

    // Для сессии возвращаем сумму всех количеств
    return array_sum(session()->get('cart', []));
  }

  public function getCartData(Request $request): JsonResponse
  {
    $cartItems = [];

    if (auth()->check()) {
      $cartItems = auth()->user()->baskets()
        ->with('notebook')
        ->get(['notebook_id', 'count'])
        ->map(function ($item) {
          return [
            'notebook_id' => $item->notebook_id,
            'count' => $item->count
          ];
        })
        ->toArray();
    } else {
      $cart = session()->get('cart', []);
      foreach ($cart as $notebookId => $count) {
        $cartItems[] = [
          'notebook_id' => $notebookId,
          'count' => $count
        ];
      }
    }

    return response()->json([
      'cart_items' => $cartItems,
      'cart_count' => $this->getCartCount()
    ]);
  }

  public function getState(Request $request): JsonResponse
  {
    $cartData = [];

    if (auth()->check()) {
      $user = auth()->user();
      $baskets = $user->baskets()->with('notebook')->get();

      foreach ($baskets as $item) {
        $cartData[$item->notebook_id] = [
          'id' => $item->notebook_id,
          'count' => $item->count,
          'name' => $item->notebook->name ?? 'Товар'
        ];
      }

      $cartCount = $user->baskets()->sum('count');
    } else {
      $cart = session()->get('cart', []);

      foreach ($cart as $notebookId => $count) {
        $notebook = Notebook::find($notebookId);
        $cartData[$notebookId] = [
          'id' => $notebookId,
          'count' => $count,
          'name' => $notebook->name ?? 'Товар'
        ];
      }

      $cartCount = array_sum($cart);
    }

    return response()->json([
      'items' => $cartData,
      'count' => $cartCount
    ]);
  }

  public function order(): View
  {

    $products = auth()->user()->baskets()->with('notebook')->get();

// Сохраняем и ноутбук, и количество
    $notebooks = $products->map(function ($product) {
      return (object)[
        'notebook' => $product->notebook,
        'title' => $product->notebook->title,
        'count' => $product->count,
        'slug' => $product->notebook->slug,
        'price' => $product->notebook->price,
        'total_price' => $product->notebook->price * $product->count
      ];
    });

    $totalItemsCount = $notebooks->sum('count');
    $totalAllPrice = $notebooks->sum('total_price');

    return view('basket.order', compact('notebooks', 'totalItemsCount', 'totalAllPrice'));

  }

}
