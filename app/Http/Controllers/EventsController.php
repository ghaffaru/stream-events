<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Follower;
use App\Models\MerchSale;
use App\Models\Subscriber;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function index()
    {
        $user_id = 1;

        $user = User::with(['followers', 'subscribers', 'donations','merch_sales'])->findOrFail($user_id);

        $data = [];

        foreach ($user->followers as $follower) {
            $data[] = [
                'name' => $follower->name . ' followed you',
                'read' => $follower->read,
                'date' => $follower->created_at,
                'type' => 'followers',
            ];
        }

        foreach ($user->subscribers as $subscriber) {
            $data[] = [
                'name' => $subscriber->name . '(' . $subscriber->subscription_tier . ') subscribed to you!',
                'read' => $subscriber->read,
                'date' => $subscriber->created_at,
                'type' => 'subscribers',
            ];
        }

        foreach ($user->donations as $donation) {
            $data[] = [
                'name' => $donation->name . ' donated ' . $donation->amount . ' ' . $donation->currency . ' to you! "' . $donation->message . '"' ,
                'read' => $donation->read,
                'date' => $donation->created_at,
                'type' => 'donations',
            ];
        }

        foreach ($user->merch_sales as $merch_sale) {
            $data[] = [
                'name' => $merch_sale->name . ' bought some ' . $merch_sale->item_name . ' from you for ' . $merch_sale->price . 'USD!',
                'read' => $merch_sale->read,
                'amount' => $merch_sale->amount,
                'price' => $merch_sale->price,
                'date' => $merch_sale->created_at,
                'type' => 'merch_sales',
            ];
        }


        // Sort the data by date
        usort($data, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        return $data;
    }

    public function statistics()
    {
        $user_id = 1;
        $total_followers = Follower::where('user_id', $user_id)
            ->whereDate('created_at', '<=', Carbon::today())
            ->whereDate('created_at', '>=', Carbon::today()->subDays(30))
            ->count();

        // last 30 days
        $subscriptions = Subscriber::where('user_id', $user_id)
            ->whereDate('created_at', '<=', Carbon::today())
            ->whereDate('created_at', '>=', Carbon::today()->subDays(30))
            ->get();

        $subscription_total = 0;
        foreach ($subscriptions as $subscription) {
            switch ($subscription->subscription_tier) {
                case 'Tier1':
                    $subscription_total += 5;
                case 'Tier2':
                    $subscription_total += 10;
                case 'Tier3':
                    $subscription_total += 15;
            }
        }

        $donations_total = Donation::where('user_id', $user_id)
            ->whereDate('created_at', '<=', Carbon::today())
            ->whereDate('created_at', '>=', Carbon::today()->subDays(30))
            ->sum('amount');

        $merch_total = MerchSale::where('user_id', $user_id)
            ->whereDate('created_at', '<=', Carbon::today())
            ->whereDate('created_at', '>=', Carbon::today()->subDays(30))
            ->sum('price');

        $top3 =  MerchSale::where('user_id', $user_id)
            ->whereDate('created_at', '<=', Carbon::today())
            ->whereDate('created_at', '>=', Carbon::today()->subDays(30))
            ->orderBy('price', 'DESC')
            ->limit(3)
            ->get();
        return [
            'total_followers' => $total_followers,
            'total_revenue' => $subscription_total + $donations_total + $merch_total,
            'top3_merch' => $top3
        ];
    }
}
