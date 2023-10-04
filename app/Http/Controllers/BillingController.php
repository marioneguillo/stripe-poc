<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class BillingController extends Controller
{

    private StripeClient $stripe;
    private User $user;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('cashier.secret'));
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();

            return $next($request);
        });

    }

    //formulario de pago
    public function paymentMethodForm(): Renderable
    {
        $countries = Country::all();

        return view('front.billing.payment_method_form', [
            'intent' => $this->user->createSetupIntent(),
            'countries' => $countries,
            'currentMethod' => $this->getPaymentMethods(),
        ]);
    }

    /**
     * Usuario autenticado da sus datos a stripe (nombre, email, número de tarjeta y país)
     */
    public function processPaymentMethod(): RedirectResponse
    {

        $this->validate(request(), [
            'pm' => 'required|string|starts_with:pm_|max:50',
            'card_holder_name' => 'required|max:150|string',
            'country_id' => 'required|exists:countries,id',
        ]);

        if (!$this->user->hasStripeId()) {

            $this->user->createAsStripeCustomer([
                'address' => [
                    'country' => Country::find(request('country_id'))->code,
                ]
            ]);
        }

        $this->user->updateDefaultPaymentMethod(request('pm'));

        return back()
            ->with('notification', [
                'title' => __('Método de pago actualizado!'),
                'message' => __('Su metodo de pago ha sido actualizado correctamente.')
            ]);
    }


    /**
     * @throws ApiErrorException
     */
    public function plans(): Renderable|RedirectResponse
    {
        // Solo puede ver los planes si el usuario tiene un metodo de pago creado.
        /*
        if (!$this->user->hasdefaultPaymentMethod()) {
            return back();
        }
        */

        //obtener planes del propietario de la cuenta de Stripe
        $plans = $this->stripe->plans->all();
        $plans = $plans->data;
        $plans = array_reverse($plans);

        return view('front.billing.plans', compact('plans'));
    }

    public function plansEmbebed(): Renderable|RedirectResponse
    {

        $plans = $this->stripe->plans->all();
        $plans = $plans->data;
        $plans = array_reverse($plans);

        return view('front.billing.plansEmbebed', compact('plans'));
    }


    /**
     * Usuario se suscribe a un plan
     * 
     * @throws ApiErrorException
     */
    public function processSubscription(): RedirectResponse
    {

        $this->validate(request(), [
            'price_id' => 'required|string|starts_with:price_',
        ]);

        $plan = $this->stripe->plans->retrieve(request('price_id'));

        try {
            $this->user->newSubscription('default', request('price_id'))
                ->create($this->user->defaultPaymentMethod()->id);

            return redirect(route('billing.my_subscription'))
                ->with(
                    'notification',
                    [
                        'title' => __('Gracias por comprar,'),
                        'message' => __('Hemos asignado el plan:  ' . getPlanNameByStripePlan($plan) . ' correctamente')
                    ]
                );

        } catch (IncompletePayment $exception) {

            return redirect()->route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route('billing.my_subscription')]
            );

        } catch (\Exception $exception) {

            return back()->with('notification', [
                'title' => __('Error'),
                'message' => $exception->getMessage()
            ]);
        }
    }


    public function mySubscription(): Renderable
    {
        $subscription = getSubscriptionNameForUser();

        return view('front.billing.my_subscription', compact('subscription'));
    }

    public function invoices(): Renderable
    {

        $invoices = $this->user->invoices();

        return view('front.billing.invoices', compact('invoices'));
    }

    public function savePaymentMethod(Request $request)
    {
        $paymentMethodID = $request->input('payment_method');

        if ($this->user->stripe_id == null) {
            $this->user->createAsStripeCustomer();
        }

        $this->user->addPaymentMethod($paymentMethodID);
        $this->user->updateDefaultPaymentMethod($paymentMethodID);

        return;
    }


    public function getPaymentMethods(): array
    {
        $methods = [];

        if ($this->user->hasPaymentMethod()) {
            foreach ($this->user->paymentMethods() as $method) {
                $methods[] = [
                    'id' => $method->id,
                    'brand' => $method->card->brand,
                    'last_four' => $method->card->last4,
                    'exp_month' => $method->card->exp_month,
                    'exp_year' => $method->card->exp_year,
                ];
            }
        }

        return $methods;
    }

    public function processExtraSubscription()
    {
        $this->validate(request(), [
            'price_id' => 'required|string|starts_with:price_',
            'quantity' => 'required'
        ]);

        return $this->user->checkout([request('price_id') => request('quantity')], []);
    }
}