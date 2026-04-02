<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
  /**
   * Display the login view.
   */
  public function create(): View
  {
    return view('auth.login');
  }

  // В контроллере Auth\LoginController или Auth\RegisterController:
  protected function authenticated(Request $request, $user)
  {
    // Перенос гостевой корзины в БД после авторизации
    $guestCart = Session::get('guest_cart', []);

    if (!empty($guestCart)) {
      foreach ($guestCart as $notebookId => $count) {
        $user->baskets()->updateOrCreate(
          ['notebook_id' => $notebookId],
          ['count' => DB::raw("GREATEST(count, $count)")]
        );
      }

      // Очищаем гостевую корзину
      Session::forget('guest_cart');
    }

    return redirect()->intended($this->redirectPath());
  }

  /**
   * Handle an incoming authentication request.
   */
  public function store(LoginRequest $request): RedirectResponse
  {
    $request->authenticate();

    $request->session()->regenerate();

    return redirect()->route('products.index');
  }

  /**
   * Destroy an authenticated session.
   */
  public function destroy(Request $request): RedirectResponse
  {
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
  }
}
