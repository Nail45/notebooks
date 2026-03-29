<?php

// app/Helpers/CartHelper.php
namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartHelper
{
  public static function getCart()
  {
    if (Auth::check()) {
      return Auth::user()->baskets()->with('notebook')->get();
    }

    $guestCart = Session::get('guest_cart', []);
    $products = collect();

    if (!empty($guestCart)) {
      $notebookIds = array_keys($guestCart);
      $notebooks = \App\Models\Notebook::whereIn('id', $notebookIds)->get();

      foreach ($notebooks as $notebook) {
        $products->push((object)[
          'id' => 'guest_' . $notebook->id,
          'notebook_id' => $notebook->id,
          'count' => $guestCart[$notebook->id],
          'notebook' => $notebook,
        ]);
      }
    }

    return $products;
  }

  public static function addToCart($notebookId, $count = 1)
  {
    if (Auth::check()) {
      $user = Auth::user();
      $basket = $user->baskets()->updateOrCreate(
        ['notebook_id' => $notebookId],
        ['count' => \DB::raw("count + $count")]
      );
      return $basket;
    }

    $guestCart = Session::get('guest_cart', []);

    if (isset($guestCart[$notebookId])) {
      $guestCart[$notebookId] += $count;
    } else {
      $guestCart[$notebookId] = $count;
    }

    Session::put('guest_cart', $guestCart);

    return true;
  }

  public static function getCartCount()
  {
    if (Auth::check()) {
      return Auth::user()->baskets()->sum('count');
    }

    $guestCart = Session::get('guest_cart', []);
    return array_sum($guestCart);
  }
}
